<?php
include "if_isset.php";

// Captura a ID do pedido a ser editado vindo da URL
$id_pedido_grupo = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id_pedido_grupo === 0) {
    header("Location: pedido_read.php");
    exit;
}

// 1. Buscar os dados das tabelas auxiliares para preencher os <select>
$sql_clientes = "SELECT id, nome FROM clientes ORDER BY nome ASC";
$stmt_cli = $conexao->prepare($sql_clientes);
$stmt_cli->execute();
$clientes = $stmt_cli->fetchAll(PDO::FETCH_ASSOC);

$sql_usuarios = "SELECT id, nome FROM usuarios ORDER BY nome ASC";
$stmt_usu = $conexao->prepare($sql_usuarios);
$stmt_usu->execute();
$usuarios = $stmt_usu->fetchAll(PDO::FETCH_ASSOC);

$sql_status = "SELECT id, descricao FROM status_pedido ORDER BY id ASC";
$stmt_st = $conexao->prepare($sql_status);
$stmt_st->execute();
$status_list = $stmt_st->fetchAll(PDO::FETCH_ASSOC);


// 2. BUSCAR DADOS ATUAIS DO PEDIDO (Para preencher o formulário)
$sql_pedido_atual = "SELECT * FROM pedidos WHERE id = ? LIMIT 1"; 
$stmt_ped = $conexao->prepare($sql_pedido_atual);
$stmt_ped->execute([$id_pedido_grupo]);
$dados_pedido = $stmt_ped->fetch(PDO::FETCH_ASSOC);

if (!$dados_pedido) {
    header("Location: pedido_read.php");
    exit;
}

// Buscar todos os produtos vinculados a esse pedido atualmente
$sql_itens = "SELECT idprodutos, quantidade FROM pedidos WHERE id = ?";
$stmt_itens = $conexao->prepare($sql_itens);
$stmt_itens->execute([$id_pedido_grupo]);
$itens_atuais = $stmt_itens->fetchAll(PDO::FETCH_ASSOC);


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cliente = isset($_POST['idclientes']) ? trim($_POST['idclientes']) : '';
    $usuario = isset($_POST['idusuarios']) ? trim($_POST['idusuarios']) : '';
    $status_novo = isset($_POST['idstatus']) ? trim($_POST['idstatus']) : '';
    
    $produtos    = isset($_POST['idprodutos']) ? $_POST['idprodutos'] : [];
    $quantidades = isset($_POST['quantidade']) ? $_POST['quantidade'] : [];

    if (!empty($cliente) && !empty($usuario) && !empty($status_novo)) {
        
        $conexao->beginTransaction();
        
        try {
            // ID do status que representa "Cancelado" no seu banco (Ajuste o ID se no seu banco for outro, ex: 3 ou 4)
            $ID_STATUS_CANCELADO = 9; 

            // 1. BUSCAR O ESTADO ANTERIOR DO PEDIDO (Antes de aplicar a atualização)
            $sql_antigo = "SELECT idprodutos, quantidade, idstatus FROM pedidos WHERE id = ?";
            $stmt_antigo = $conexao->prepare($sql_antigo);
            $stmt_antigo->execute([$id_pedido_grupo]);
            $itens_antigos = $stmt_antigo->fetchAll(PDO::FETCH_ASSOC);
            
            // Descobre qual era o status antigo (como os itens compartilham o mesmo status, pegamos do primeiro)
            $status_antigo = !empty($itens_antigos) ? $itens_antigos[0]['idstatus'] : null;

            // Criar um mapa (dicionário) dos produtos antigos para facilitar a comparação de quantidades
            $mapa_antigo = [];
            foreach ($itens_antigos as $item) {
                $mapa_antigo[$item['idprodutos']] = (int)$item['quantidade'];
            }

            // Preparar as queries que usaremos no laço
            $stmt_check_est = $conexao->prepare("SELECT nome, quant_estoque FROM produtos WHERE id = ?");
            $stmt_up_est = $conexao->prepare("UPDATE produtos SET quant_estoque = quant_estoque + ? WHERE id = ?");

            // 2. SE O NOVO STATUS FOR CANCELADO (E antes não era cancelado): Devolve tudo ao estoque
            if ($status_novo == $ID_STATUS_CANCELADO && $status_antigo != $ID_STATUS_CANCELADO) {
                foreach ($mapa_antigo as $id_prod_antigo => $qtd_antiga) {
                    $stmt_up_est->execute([$qtd_antiga, $id_prod_antigo]);
                }
            }
            // 3. SE O STATUS ERA CANCELADO E AGORA VOLTOU A SER ATIVO: Precisamos subtrair tudo de novo (com validação)
            unset($item);
            if ($status_antigo == $ID_STATUS_CANCELADO && $status_novo != $ID_STATUS_CANCELADO) {
                foreach ($produtos as $index => $id_produto) {
                    $qtd_nova = isset($quantidades[$index]) ? (int)trim($quantidades[$index]) : 1;
                    $id_produto = trim($id_produto);
                    
                    if (!empty($id_produto)) {
                        // Valida estoque
                        $stmt_check_est->execute([$id_produto]);
                        $p_info = $stmt_check_est->fetch(PDO::FETCH_ASSOC);
                        if ($p_info['quant_estoque'] < $qtd_nova) {
                            throw new Exception("Estoque insuficiente para reativar o produto '{$p_info['nome']}'. Disponível: {$p_info['quant_estoque']}, Solicitado: {$qtd_nova}");
                        }
                        // Subtrai do estoque (passando valor negativo para somar subtraindo)
                        $stmt_up_est->execute([-$qtd_nova, $id_produto]);
                    }
                }
            }
            // 4. SE O PEDIDO CONTINUA ATIVO (Não foi cancelado nem reativado agora), MAS AS QUANTIDADES PODEM TER MUDADO
            if ($status_novo != $ID_STATUS_CANCELADO && $status_antigo != $ID_STATUS_CANCELADO) {
                foreach ($produtos as $index => $id_produto) {
                    $qtd_nova = isset($quantidades[$index]) ? (int)trim($quantidades[$index]) : 1;
                    $id_produto = trim($id_produto);
                    
                    if (!empty($id_produto)) {
                        // Verifica se esse produto já estava no pedido antes
                        $qtd_antiga = isset($mapa_antigo[$id_produto]) ? $mapa_antigo[$id_produto] : 0;
                        
                        // Diferença = Quantidade Antiga - Quantidade Nova
                        // Se Nova (5) > Antiga (2) -> Diferença = -3 (precisa retirar 3 do estoque)
                        // Se Nova (1) > Antiga (3) -> Diferença = +2 (precisa devolver 2 para o estoque)
                        $diferenca = $qtd_antiga - $qtd_nova;
                        
                        if ($diferenca < 0) { 
                            // O usuário aumentou a quantidade no pedido! Precisamos validar se o estoque aguenta a diferença
                            $qtd_a_subtrair = abs($diferenca); // Transforma em positivo para checar
                            
                            $stmt_check_est->execute([$id_produto]);
                            $p_info = $stmt_check_est->fetch(PDO::FETCH_ASSOC);
                            
                            if ($p_info['quant_estoque'] < $qtd_a_subtrair) {
                                throw new Exception("Estoque insuficiente para aumentar a quantidade de '{$p_info['nome']}'. Você precisa de mais {$qtd_a_subtrair} unidades, mas só há {$p_info['quant_estoque']} em estoque.");
                            }
                        }
                        
                        // Atualiza o estoque aplicando a diferença (se positivo soma, se negativo subtrai)
                        $stmt_up_est->execute([$diferenca, $id_produto]);
                    }
                }
            }

            // 5. ATUALIZAR OS DADOS DO PEDIDO
            // Para não causar duplicidade ou problemas com itens removidos/adicionados na tela, 
            // a forma mais limpa é deletar os registros antigos deste ID de grupo e reinserir os atuais
            $sql_deleta_itens = "DELETE FROM pedidos WHERE id = ?";
            $stmt_del = $conexao->prepare($sql_deleta_itens);
            $stmt_del->execute([$id_pedido_grupo]);

            // Insere os itens atualizados vindo do formulário
            $sql_insere = "INSERT INTO pedidos (id, idclientes, idusuarios, idprodutos, quantidade, idstatus) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt_ins = $conexao->prepare($sql_insere);

            foreach ($produtos as $index => $id_produto) {
                $qtd_nova = isset($quantidades[$index]) ? (int)trim($quantidades[$index]) : 1;
                $id_produto = trim($id_produto);
                
                if (!empty($id_produto)) {
                    $stmt_ins->execute([$id_pedido_grupo, $cliente, $usuario, $id_produto, $qtd_nova, $status_novo]);
                }
            }

            // Se tudo correu perfeitamente, grava no banco de dados
            $conexao->commit();
            
            $_SESSION['sucesso'] = "Pedido e estoque atualizados com sucesso!";
            header("Location: pedido_read.php");
            exit;

        } catch (Exception $e) {
            // Se faltou estoque ou deu qualquer erro, desfaz tudo e não altera nada no banco
            $conexao->rollBack();
            $mensagem_erro = "Erro ao atualizar o pedido: " . $e->getMessage();
        }
    } else {
        $mensagem_erro = "Por favor, preencha todos os campos obrigatórios.";
    }
}
?>
<!DOCTYPE html>
<html class="light" lang="pt-br">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Editar Pedido - Aromas da Lari</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,600&family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "on-primary-fixed-variant": "#004d62",
                        "on-secondary-fixed": "#2f1314",
                        "surface": "#fbf9f8",
                        "on-tertiary": "#ffffff",
                        "background": "#fbf9f8",
                        "on-error-container": "#93000a",
                        "surface-tint": "#0c6780",
                        "on-error": "#ffffff",
                        "surface-bright": "#fbf9f8",
                        "surface-variant": "#e4e2e2",
                        "on-primary": "#ffffff",
                        "secondary-container": "#fecbcb",
                        "surface-container-lowest": "#ffffff",
                        "error": "#ba1a1a",
                        "on-primary-container": "#005870",
                        "surface-container-high": "#eae8e7",
                        "tertiary": "#5e5e5f",
                        "primary-fixed": "#baeaff",
                        "on-surface-variant": "#3f484c",
                        "secondary": "#7b5455",
                        "on-surface": "#1b1c1c",
                        "error-container": "#ffdad6",
                        "surface-dim": "#dbd9d9",
                        "inverse-surface": "#303030",
                        "on-tertiary-fixed": "#1b1c1c",
                        "on-secondary-fixed-variant": "#613d3e",
                        "primary-container": "#87ceeb",
                        "on-secondary-container": "#7a5354",
                        "tertiary-fixed": "#e4e2e2",
                        "secondary-fixed": "#ffdad9",
                        "on-secondary": "#ffffff",
                        "surface-container-highest": "#e4e2e2",
                        "surface-container": "#efeded",
                        "primary": "#0c6780",
                        "outline-variant": "#bfc8cd",
                        "surface-container-low": "#f5f3f3",
                        "on-background": "#1b1c1c",
                        "inverse-on-surface": "#f2f0f0",
                        "on-primary-fixed": "#001f29",
                        "tertiary-container": "#c5c4c4",
                        "inverse-primary": "#89d0ed",
                        "primary-fixed-dim": "#89d0ed",
                        "outline": "#6f787d",
                        "secondary-fixed-dim": "#ecbaba",
                        "on-tertiary-container": "#515151",
                        "tertiary-fixed-dim": "#c7c6c6",
                        "on-tertiary-fixed-variant": "#464747"
                    },
                    "borderRadius": { "DEFAULT": "0.25rem", "lg": "0.5rem", "xl": "0.75rem", "full": "9999px" },
                    "spacing": { "unit": "8px", "container-max": "1200px", "gutter": "24px", "margin-mobile": "20px", "margin-desktop": "64px" },
                    "fontFamily": { "label-sm": ["DM Sans"], "headline-md": ["Playfair Display"], "body-md": ["DM Sans"], "headline-sm": ["Playfair Display"], "body-lg": ["DM Sans"], "label-md": ["DM Sans"], "headline-lg-mobile": ["Playfair Display"], "headline-lg": ["Playfair Display"] },
                    "fontSize": { "label-sm": ["12px", {"lineHeight": "1.4", "fontWeight": "500"}], "headline-md": ["32px", {"lineHeight": "1.2", "fontWeight": "600"}], "body-md": ["16px", {"lineHeight": "1.5", "fontWeight": "400"}], "headline-sm": ["24px", {"lineHeight": "1.3", "fontWeight": "600"}], "body-lg": ["18px", {"lineHeight": "1.6", "fontWeight": "400"}], "label-md": ["14px", {"lineHeight": "1.4", "letterSpacing": "0.05em", "fontWeight": "500"}], "headline-lg-mobile": ["32px", {"lineHeight": "1.2", "fontWeight": "700"}], "headline-lg": ["48px", {"lineHeight": "1.1", "letterSpacing": "-0.02em", "fontWeight": "700"}] }
                },
            },
        }
    </script>
    <style>
        .glass-panel { background: rgba(255, 255, 255, 0.4); backdrop-filter: blur(12px); border: 0.5px solid rgba(255, 255, 255, 0.6); }
        input:focus, select:focus { outline: none; border-color: #0c6780 !important; box-shadow: 0 0 0 2px rgba(12, 103, 128, 0.1); }
        @keyframes fade-in { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .animate-fade-in { animation: fade-in 0.3s ease-out forwards; }
    </style>
</head>
<body class="bg-background text-on-background font-body-md min-h-screen flex flex-col">

    <!-- TopNavBar -->
    <nav class="w-full sticky top-0 z-50 bg-surface/80 backdrop-blur-md border-b border-outline-variant/30 shadow-sm shadow-primary/5">
        <div class="max-w-container-max mx-auto px-margin-desktop flex justify-between items-center h-20">
            <?php include "header.php"; ?>
        </div>
    </nav>

    <main class="flex-grow max-w-container-max mx-auto w-full px-margin-desktop py-12 md:py-24">
        
        <!-- Cabeçalho Centralizado -->
        <header class="mb-16 text-center">
            <h1 class="font-headline-lg text-headline-lg text-primary mb-4">Editar Pedido- n°<?php echo $id_pedido_grupo; ?></h1>
            <p class="text-on-surface-variant max-w-2xl mx-auto font-body-lg">Atualize as informações do pedido artesanal abaixo.</p>
            
            <?php if(!empty($mensagem_erro)): ?>
                <div class="mt-4 p-4 max-w-xl mx-auto bg-error-container text-on-error-container rounded-lg font-medium">
                    <?php echo $mensagem_erro; ?>
                </div>
            <?php endif; ?>
        </header>

        <!-- Início do Formulário -->
        <form method="POST" action="">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-gutter mb-12">
                
                <!-- Coluna Esquerda: Dados do Pedido -->
                <div class="lg:col-span-5">
                    <section class="glass-panel p-8 rounded-xl space-y-8 h-full">
                        
                        <!-- Funcionário Responsável -->
                        <div>
                            <label class="block font-label-md text-label-md text-primary mb-2 uppercase tracking-widest">Funcionário Responsável</label>
                            <select name="idusuarios" required class="w-full bg-surface-container-low border-outline-variant rounded-lg p-4 font-body-md text-on-surface transition-all">
                                <option disabled value="">Selecione o atendente...</option>
                                <?php foreach($usuarios as $u): ?>
                                    <option value="<?php echo $u['id']; ?>" <?php echo ($u['id'] == $dados_pedido['idusuarios']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($u['nome']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Cliente -->
                        <div>
                            <label class="block font-label-md text-label-md text-primary mb-2 uppercase tracking-widest">Cliente</label>
                            <select name="idclientes" required class="w-full bg-surface-container-low border-outline-variant rounded-lg p-4 font-body-md text-on-surface transition-all">
                                <option disabled value="">Vincular um cliente...</option>
                                <?php foreach($clientes as $c): ?>
                                    <option value="<?php echo $c['id']; ?>" <?php echo ($c['id'] == $dados_pedido['idclientes']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($c['nome']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Status do Pedido -->
                        <div>
                            <label class="block font-label-md text-label-md text-primary mb-2 uppercase tracking-widest">Status do Pedido</label>
                            <input type="hidden" name="idstatus" id="idstatus" value="<?php echo htmlspecialchars($dados_pedido['idstatus']); ?>" required>
                            <div class="flex flex-wrap gap-3">
                                <?php foreach($status_list as $st): 
                                    $is_active = ($st['id'] == $dados_pedido['idstatus']);
                                    $btn_class = $is_active ? 'bg-primary text-white' : 'border-outline-variant text-on-surface-variant';
                                ?>
                                    <button type="button" data-status-id="<?php echo $st['id']; ?>" class="status-btn px-6 py-2 rounded-full border <?php echo $btn_class; ?> font-label-md hover:border-primary transition-all">
                                        <?php echo htmlspecialchars($st['descricao']); ?>
                                    </button>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </section>
                </div>

                <!-- Coluna Direita: Tabela de Itens -->
                <div class="lg:col-span-7">
                    <div class="glass-panel rounded-xl overflow-hidden flex flex-col h-full">
                        <div class="p-8 border-b border-outline-variant/30 flex justify-between items-center">
                            <h2 class="font-headline-sm text-headline-sm text-secondary">Itens do Pedido</h2>
                            <button type="button" class="flex items-center gap-2 text-primary font-label-md hover:opacity-70 transition-all active:scale-95" onclick="addRow()">
                                <span class="material-symbols-outlined">add_circle</span> ADICIONAR PRODUTO
                            </button>
                        </div>
                        
                        <div class="flex-grow overflow-x-auto">
                            <table class="w-full text-left border-collapse" id="productsTable">
                                <thead>
                                    <tr class="bg-surface-container-low/50">
                                        <th class="px-8 py-4 font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Identificador (ID do Produto)</th>
                                        <th class="px-8 py-4 font-label-md text-label-md text-on-surface-variant uppercase tracking-wider w-32">Quantidade</th>
                                        <th class="px-8 py-4 w-20"></th>
                                    </tr>
                                </thead>
                                <tbody id="productsTableBody" class="divide-y divide-outline-variant/20">
                                    <?php foreach($itens_atuais as $item): ?>
                                        <tr class="hover:bg-primary/5 transition-colors">
                                            <td class="px-8 py-6">
                                                <input name="idprodutos[]" required value="<?php echo htmlspecialchars($item['idprodutos']); ?>" class="w-full bg-transparent border-none p-0 focus:ring-0 text-on-surface placeholder:text-outline-variant" placeholder="Ex: 1" type="number"/>
                                            </td>
                                            <td class="px-8 py-6">
                                                <input name="whitespace" type="hidden">
                                                <input name="whitespace_two" type="hidden">
                                                <input name="quantidade[]" required value="<?php echo htmlspecialchars($item['whitespace_three'] ?? $item['quantidade']); ?>" class="w-24 bg-transparent border-none p-0 focus:ring-0 text-on-surface" min="1" type="number"/>
                                            </td>
                                            <td class="px-8 py-6 text-right">
                                                <button type="button" class="material-symbols-outlined text-outline hover:text-error transition-colors" onclick="removeRow(this)">delete</button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Botão de Atualizar Centralizado no final da página -->
            <div class="max-w-md mx-auto text-center">
                <button type="submit" class="w-full py-5 bg-primary text-white rounded-lg font-headline-sm hover:bg-on-primary-container transition-all shadow-lg shadow-primary/20 active:scale-[0.98]">
                    Atualizar Pedido
                </button>
            </div>
        </form>
    </main>

    <footer class="w-full bg-surface-container-low border-t border-secondary-container/50 mt-auto">
        <div class="max-w-container-max mx-auto px-margin-desktop py-16 flex flex-col md:flex-row justify-between items-center gap-gutter">
            <div class="flex flex-col items-center md:items-start gap-4">
                <span class="font-headline-sm text-headline-sm text-secondary">Aromas da Lari</span>
                <p class="font-body-md text-body-md text-on-secondary-fixed-variant/70 text-center md:text-left">© 2026 Aromas da Lari. Crafted for the senses.</p>
            </div>
        </div>
    </footer>

    <script>
        function addRow() {
            const tableBody = document.getElementById('productsTableBody');
            const newRow = document.createElement('tr');
            newRow.className = 'hover:bg-primary/5 transition-colors animate-fade-in';
            newRow.innerHTML = `
                <td class="px-8 py-6">
                    <input type="number" name="idprodutos[]" required placeholder="ID do Produto" class="w-full bg-transparent border-none p-0 focus:ring-0 text-on-surface placeholder:text-outline-variant">
                </td>
                <td class="px-8 py-6">
                    <input type="number" name="quantidade[]" required value="1" min="1" class="w-24 bg-transparent border-none p-0 focus:ring-0 text-on-surface">
                </td>
                <td class="px-8 py-6 text-right">
                    <button type="button" class="material-symbols-outlined text-outline hover:text-error transition-colors" onclick="removeRow(this)">delete</button>
                </td>
            `;
            tableBody.appendChild(newRow);
        }

        function removeRow(button) {
            const tableBody = document.getElementById('productsTableBody');
            if (tableBody.rows.length > 1) {
                button.closest('tr').remove();
            } else {
                alert('O pedido precisa ter pelo menos um produto!');
            }
        }

        const statusButtons = document.querySelectorAll('.status-btn');
        const idStatusInput = document.getElementById('idstatus');

        statusButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                statusButtons.forEach(b => {
                    b.classList.remove('bg-primary', 'text-white');
                    b.classList.add('border-outline-variant', 'text-on-surface-variant');
                });
                this.classList.remove('border-outline-variant', 'text-on-surface-variant');
                this.classList.add('bg-primary', 'text-white');
                idStatusInput.value = this.getAttribute('data-status-id');
            });
        });
    </script>
</body>
</html>