<?php
include "if_isset.php";

// Buscar os dados dinâmicos para carregar nos selects do formulário
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


// PROCESSAMENTO DO FORMULÁRIO
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $cliente = isset($_POST['idclientes']) ? trim($_POST['idclientes']) : '';
    $usuario = isset($_POST['idusuarios']) ? trim($_POST['idusuarios']) : '';
    $status  = isset($_POST['idstatus']) ? trim($_POST['idstatus']) : '';
    
    // Agora recebemos arrays de produtos e quantidades
    $produtos    = isset($_POST['idprodutos']) ? $_POST['idprodutos'] : [];
    $quantidades = isset($_POST['quantidade']) ? $_POST['quantidade'] : [];

    if (!empty($cliente) && !empty($usuario) && !empty($status) && !empty($produtos)) {
        
        // Iniciamos uma transação para garantir que ou salva tudo, ou não salva nada em caso de erro
        $conexao->beginTransaction();
        
        try {
    // 1. Query para inserir o item no pedido
    $sql = "INSERT INTO pedidos (idclientes, idusuarios, idprodutos, quantidade, idstatus) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conexao->prepare($sql);
    
    // 2. Query para subtrair a quantidade do estoque do produto
    $sql_estoque = "UPDATE produtos SET quant_estoque = quant_estoque - ? WHERE id = ?";
    $stmt_estoque = $conexao->prepare($sql_estoque);
    
    // NEW: Query para verificar o estoque atual do produto antes da venda
    $sql_check_estoque = "SELECT nome, quant_estoque FROM produtos WHERE id = ?";
    $stmt_check = $conexao->prepare($sql_check_estoque);
    
    // Loop para percorrer cada produto enviado no formulário
    foreach ($produtos as $index => $id_produto) {
        $qtd = isset($quantidades[$index]) ? (int)trim($quantidades[$index]) : 1;
        $id_produto = trim($id_produto);
        
        // Só insere se o ID do produto não estiver em branco
        if (!empty($id_produto)) {
            
            // --- VALIDAÇÃO DE ESTOQUE ---
            $stmt_check->execute([$id_produto]);
            $produto_info = $stmt_check->fetch(PDO::FETCH_ASSOC);
            
            if (!$produto_info) {
                // Se o produto digitado sequer existir no banco
                throw new Exception("O produto com ID '{$id_produto}' não foi encontrado no sistema.");
            }
            
            if ($produto_info['quant_estoque'] < $qtd) {
                // Se a quantidade solicitada for maior que o estoque atual, interrompe o processo
                throw new Exception("Estoque insuficiente para o produto '{$produto_info['nome']}' (ID: {$id_produto}). Estoque atual: {$produto_info['quant_estoque']}, Solicitado: {$qtd}.");
            }
            // -----------------------------

            // Se passou na validação, insere o item do pedido
            $stmt->execute([$cliente, $usuario, $id_produto, $qtd, $status]);
            
            // Subtrai do estoque de produtos
            $stmt_estoque->execute([$qtd, $id_produto]);
        }
    }
    
    // Confirma as inserções e atualizações no banco de dados se tudo deu certo
    $conexao->commit();
    
    $_SESSION['sucesso'] = "Pedido realizado e estoque atualizado com sucesso!";
    header("Location: pedido_read.php");
    exit;
    
} catch (Exception $e) {
    // Desfaz TUDO (inclusive qualquer inserção ou alteração prévia desse loop) se houver falta de estoque ou erro
    $conexao->rollBack();
    $mensagem_erro = "Erro ao processar pedido: " . $e->getMessage();
}
}
}
?>
<!DOCTYPE html>
<html class="light" lang="pt-br">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Cadastrar Pedido - Aromas da Lari</title>
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
                    "borderRadius": {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                    "spacing": {
                        "unit": "8px",
                        "container-max": "1200px",
                        "gutter": "24px",
                        "margin-mobile": "20px",
                        "margin-desktop": "64px"
                    },
                    "fontFamily": {
                        "label-sm": ["DM Sans"],
                        "headline-md": ["Playfair Display"],
                        "body-md": ["DM Sans"],
                        "headline-sm": ["Playfair Display"],
                        "body-lg": ["DM Sans"],
                        "label-md": ["DM Sans"],
                        "headline-lg-mobile": ["Playfair Display"],
                        "headline-lg": ["Playfair Display"]
                    },
                    "fontSize": {
                        "label-sm": ["12px", {"lineHeight": "1.4", "fontWeight": "500"}],
                        "headline-md": ["32px", {"lineHeight": "1.2", "fontWeight": "600"}],
                        "body-md": ["16px", {"lineHeight": "1.5", "fontWeight": "400"}],
                        "headline-sm": ["24px", {"lineHeight": "1.3", "fontWeight": "600"}],
                        "body-lg": ["18px", {"lineHeight": "1.6", "fontWeight": "400"}],
                        "label-md": ["14px", {"lineHeight": "1.4", "letterSpacing": "0.05em", "fontWeight": "500"}],
                        "headline-lg-mobile": ["32px", {"lineHeight": "1.2", "fontWeight": "700"}],
                        "headline-lg": ["48px", {"lineHeight": "1.1", "letterSpacing": "-0.02em", "fontWeight": "700"}]
                    }
                },
            },
        }
    </script>
    <style>
        .glass-panel {
            background: rgba(255, 255, 255, 0.4);
            backdrop-filter: blur(12px);
            border: 0.5px solid rgba(255, 255, 255, 0.6);
        }
        input:focus, select:focus {
            outline: none;
            border-color: #0c6780 !important;
            box-shadow: 0 0 0 2px rgba(12, 103, 128, 0.1);
        }
    </style>
</head>
<body class="bg-background text-on-background font-body-md min-h-screen flex flex-col">

    <nav class="w-full sticky top-0 z-50 bg-surface/80 backdrop-blur-md border-b border-outline-variant/30 shadow-sm shadow-primary/5">
        <div class="max-w-container-max mx-auto px-margin-desktop flex justify-between items-center h-20">
            <?php include "header.php"; ?>
        </div>
    </nav>

    <main class="flex-grow max-w-container-max mx-auto w-full px-margin-desktop py-12 md:py-24">
        <header class="mb-16">
            <h1 class="font-headline-lg text-headline-lg text-primary mb-4">Novo Pedido</h1>
            <p class="text-on-surface-variant max-w-2xl font-body-lg">Preencha as informações abaixo para criar um novo registro de venda.</p>
            
            <?php if(!empty($mensagem_erro)): ?>
                <div class="mt-4 p-4 bg-error-container text-on-error-container rounded-lg font-medium">
                    <?php echo $mensagem_erro; ?>
                </div>
            <?php endif; ?>
        </header>

        <form method="POST" action="">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-gutter">
                
                <div class="lg:col-span-5 space-y-10">
                    <section class="glass-panel p-8 rounded-xl space-y-8">
                        <div>
                            <label class="block font-label-md text-label-md text-primary mb-2 uppercase tracking-widest">Funcionário Responsável</label>
                            <select name="idusuarios" required class="w-full bg-surface-container-low border-outline-variant rounded-lg p-4 font-body-md text-on-surface transition-all">
                                <option disabled selected value="">Selecione o atendente...</option>
                                <?php foreach($usuarios as $u): ?>
                                    <option value="<?php echo $u['id']; ?>"><?php echo htmlspecialchars($u['nome']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div>
                            <label class="block font-label-md text-label-md text-primary mb-2 uppercase tracking-widest">Cliente</label>
                            <select name="idclientes" required class="w-full bg-surface-container-low border-outline-variant rounded-lg p-4 font-body-md text-on-surface transition-all">
                                <option disabled selected value="">Vincular um cliente...</option>
                                <?php foreach($clientes as $c): ?>
                                    <option value="<?php echo $c['id']; ?>"><?php echo htmlspecialchars($c['nome']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div>
                            <label class="block font-label-md text-label-md text-primary mb-2 uppercase tracking-widest">Status Inicial</label>
                            <input type="hidden" name="idstatus" id="idstatus" value="" required>
                            <div class="flex flex-wrap gap-3">
                                <?php foreach($status_list as $st): ?>
                                    <button type="button" data-status-id="<?php echo $st['id']; ?>" class="status-btn px-6 py-2 rounded-full border border-outline-variant text-on-surface-variant font-label-md hover:border-primary transition-all">
                                        <?php echo htmlspecialchars($st['descricao']); ?>
                                    </button>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </section>
                </div>

                <div class="lg:col-span-7">
                    <div class="glass-panel rounded-xl overflow-hidden flex flex-col h-full">
                        <div class="p-8 border-b border-outline-variant/30 flex justify-between items-center">
                            <h2 class="font-headline-sm text-headline-sm text-secondary">Itens do Pedido</h2>
                            <button type="button" onclick="addProdutoRow()" class="flex items-center gap-2 px-4 py-2 bg-secondary text-white rounded-lg font-label-md hover:bg-opacity-90 transition-all">
                                <span class="material-symbols-outlined text-sm">add</span> Adicionar Item
                            </button>
                        </div>
                        <div class="flex-grow overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-surface-container-low/50">
                                        <th class="px-8 py-4 font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">ID do Produto</th>
                                        <th class="px-8 py-4 font-label-md text-label-md text-on-surface-variant uppercase tracking-wider w-32">Quantidade</th>
                                        <th class="px-8 py-4 w-16"></th>
                                    </tr>
                                </thead>
                                <tbody id="productsTableBody" class="divide-y divide-outline-variant/20">
                                    <tr class="hover:bg-primary/5 transition-colors">
                                        <td class="px-8 py-6">
                                            <input name="idprodutos[]" required class="w-full bg-transparent border-none p-0 focus:ring-0 text-on-surface placeholder:text-outline-variant" placeholder="Digite o ID do produto" type="number"/>
                                        </td>
                                        <td class="px-8 py-6">
                                            <input name="quantidade[]" required class="w-24 bg-transparent border-none p-0 focus:ring-0 text-on-surface" min="1" type="number" value="1"/>
                                        </td>
                                        <td class="px-8 py-6 text-right">
                                            <button type="button" onclick="removeRow(this)" class="material-symbols-outlined text-outline hover:text-error transition-colors">delete</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="p-8 bg-surface-container-low/30 mt-auto">
                            <button type="submit" class="w-full py-5 bg-primary text-white rounded-lg font-headline-sm hover:bg-on-primary-container transition-all shadow-lg shadow-primary/20">
                                Salvar Pedido
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </main>

    <script>
        // Função para Adicionar nova linha de produto na tabela
        function addProdutoRow() {
            const tbody = document.getElementById('productsTableBody');
            const newRow = document.createElement('tr');
            newRow.className = 'hover:bg-primary/5 transition-colors';
            newRow.innerHTML = `
                <td class="px-8 py-6">
                    <input name="idprodutos[]" required class="w-full bg-transparent border-none p-0 focus:ring-0 text-on-surface placeholder:text-outline-variant" placeholder="Digite o ID do produto" type="number"/>
                </td>
                <td class="px-8 py-6">
                    <input name="quantidade[]" required class="w-24 bg-transparent border-none p-0 focus:ring-0 text-on-surface" min="1" type="number" value="1"/>
                </td>
                <td class="px-8 py-6 text-right">
                    <button type="button" onclick="removeRow(this)" class="material-symbols-outlined text-outline hover:text-error transition-colors">delete</button>
                </td>
            `;
            tbody.appendChild(newRow);
        }

        // Função para remover uma linha de produto
        function removeRow(button) {
            const tbody = document.getElementById('productsTableBody');
            // Só permite remover se houver mais de uma linha
            if (tbody.rows.length > 1) {
                button.closest('tr').remove();
            } else {
                alert('O pedido precisa ter pelo menos um produto!');
            }
        }

        // Gerenciador de cliques nos botões de status
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