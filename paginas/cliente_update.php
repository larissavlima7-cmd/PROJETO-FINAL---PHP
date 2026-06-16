<?php
include "if_isset.php";//inclui o arquivo if_isset
if (!isset($_GET['id']) || empty($_GET['id'])) {// vai impedir que acessa a página sem saber qual cliente vamos editar
    header("Location: cliente_read.php");
    exit;
}

$id = $_GET['id'];

// 2. BUSCA OS DADOS ATUAIS DO Cliente NO BANCO DE DADOS
$sql_select = "SELECT * FROM clientes WHERE id = ?";
$stmt_select = $conexao->prepare($sql_select);
$stmt_select->execute([$id]);
$cli = $stmt_select->fetch(PDO::FETCH_ASSOC);

// Se não encontrar o cliente, volta para a lista
if (!$cli) {
    header("Location: cliente_read.php");
    exit;
}

// vai processar os dados para pegar as informações do cliente
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = isset($_POST['nome']) ? trim($_POST['nome']) : '';
    $telefone = isset($_POST['telefone']) ? trim($_POST['telefone']) : '';
    $cep = isset($_POST['cep']) ? trim($_POST['cep']) : '';
    $numerocasa = isset($_POST['numerocasa']) ? trim($_POST['numerocasa']) : '';
    
    if (!empty($nome) && !empty($telefone) && !empty($cep) && !empty($numerocasa)) {
        
        // para validar o nome, permitindo apenas letras e espaços, ignorando os acentos
        if (!preg_match("/^[a-zA-ZÀ-ÿ\s]+$/", $nome)) {
            $mensagem_erro = "O campo Nome deve conter apenas letras e espaços. Remova números ou símbolos.";
        } 
       // para validar o telefone, permitindo apenas os 11 números para o telefone
        elseif (!ctype_digit($telefone)) {
            $mensagem_erro = "O campo Telefone deve conter apenas números (ex: 19999999999). Não utilize espaços, parênteses ou traços.";
        }
       //para validar o cep, ele só pode ter números
        elseif (!ctype_digit($cep)) {
            $mensagem_erro = "O campo CEP deve conter apenas números (ex: 13460000). Não utilize letras ou traços.";
        } 
        //validando para que o campo numero da casa não recebe letras, nem simbolos e sim números
        elseif (!ctype_digit($numerocasa)) {
            $mensagem_erro = "O campo Número deve ser um valor numérico válido. Se não houver número, coloque 0.";
        } 
        else {
            // Se passar em todas as validações, executa o UPDATE no bd
            $sql_update = "UPDATE clientes SET nome = ?, telefone = ?, cep = ?, numerocasa = ? WHERE id = ?";
            $stmt_update = $conexao->prepare($sql_update);
            
            if ($stmt_update->execute([$nome, $telefone, $cep, $numerocasa, $id])) {
                $_SESSION['sucesso'] = "Dados do cliente atualizados com sucesso!";
                header("Location: cliente_read.php");
                exit;
            } else {
                $mensagem_erro = "Erro interno ao atualizar os dados no banco de dados.";
            }
        }
    } else {
        $mensagem_erro = "Por favor, preencha todos os campos obrigatórios.";
    }
}

?>
<!DOCTYPE html>
<html class="light" lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Editar Cliente - Aromas da Lari</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,600&family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <style>
        .glass-panel { background: rgba(255, 255, 255, 0.4); backdrop-filter: blur(12px); border: 0.5px solid rgba(255, 255, 255, 0.6); }
        .btn-bloom { background: linear-gradient(135deg, #0c6780 0%, #004d62 100%); box-shadow: 0 4px 15px rgba(12, 103, 128, 0.2); }
        .btn-bloom:hover { box-shadow: 0 6px 20px rgba(12, 103, 128, 0.3); }
        input:focus { outline: none; border-color: #0c6780 !important; box-shadow: 0 0 0 2px rgba(12, 103, 128, 0.1); }
    </style>
</head>
<body class="bg-[#fbf9f8] text-[#1b1c1c] font-['DM_Sans'] min-h-screen flex flex-col justify-between p-6">

    <nav class="max-w-6xl mx-auto w-full flex justify-between items-center mb-12">
        <button onclick="window.location.href='cliente_read.php'" class="group flex items-center gap-2 text-[#0c6780] font-medium transition-all hover:opacity-80">
            <span class="material-symbols-outlined transition-transform group-hover:-translate-x-1">arrow_back</span>
            Voltar para Clientes
        </button>
        <span class="font-['Playfair_Display'] italic text-lg text-[#7b5455]">Aromas da Lari</span>
    </nav>

    <main class="max-w-xl mx-auto w-full flex-grow flex flex-col justify-center">
        
        <header class="text-center mb-8">
            <h1 class="font-['Playfair_Display'] text-4xl font-bold text-[#0c6780] mb-2">Editar Cliente</h1>
            <p class="text-[#3f484c]">Modifique os campos abaixo para atualizar as informações do cliente.</p>
            
            <?php if(!empty($mensagem_erro)): ?>
                <!-- Caso de erro (salvar no banco/campos sem preencher/erro de validação) vai aparecer o erro na tela -->
                <div class="mt-4 p-4 bg-red-100 border border-red-300 text-red-700 rounded-lg text-sm font-medium text-left flex items-center gap-2 animate-fade-in">
                    <span class="material-symbols-outlined text-red-500">error</span>
                    <?php echo $mensagem_erro; ?>
                </div>
                 <!-- Para fechar o if, usa ele para facilitar ao invés de as chaves q podem deixar bagunça -->
            <?php endif; ?>
        </header>

        <form id="addUserForm" method="POST" action="" class="glass-panel p-8 rounded-2xl space-y-6 shadow-sm">
            
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-[#0c6780] mb-2" for="nome">Nome Completo</label>
                <input class="w-full bg-[#f5f3f3]/50 border-[#bfc8cd] rounded-lg p-3.5 text-sm transition-all placeholder:text-[#6f787d]/50" 
                       id="nome" name="nome" placeholder="Ex: Maria Silva" required 
                       pattern="[a-zA-ZÀ-ÿ\s]+" title="O nome deve conter apenas letras e espaços."
                       type="text" value="<?php echo htmlspecialchars($nome ?? $cli['nome']); ?>"/>
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-[#0c6780] mb-2" for="telefone">Telefone / Telemóvel (Apenas números)</label>
                <input class="w-full bg-[#f5f3f3]/50 border-[#bfc8cd] rounded-lg p-3.5 text-sm transition-all placeholder:text-[#6f787d]/50" 
                       id="telefone" name="telefone" placeholder="Ex: 19999999999" required 
                       inputmode="numeric" pattern="[0-9]+" title="O telefone deve conter apenas números, sem espaços ou símbolos."
                       type="tel" value="<?php echo htmlspecialchars($telefone ?? $cli['telefone']); ?>"/>
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-[#0c6780] mb-2" for="cep">CEP (Apenas números)</label>
                <input class="w-full bg-[#f5f3f3]/50 border-[#bfc8cd] rounded-lg p-3.5 text-sm transition-all placeholder:text-[#6f787d]/50" 
                       id="cep" name="cep" placeholder="Ex: 13460000" required 
                       inputmode="numeric" pattern="[0-9]{8}" title="O CEP deve conter exatamente 8 números, sem letras ou hifens."
                       type="text" value="<?php echo htmlspecialchars($cep ?? $cli['cep']); ?>"/>
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-[#0c6780] mb-2" for="numerocasa">Número da Residência</label>
                <input class="w-full bg-[#f5f3f3]/50 border-[#bfc8cd] rounded-lg p-3.5 text-sm transition-all placeholder:text-[#6f787d]/50" 
                       id="numerocasa" name="numerocasa" placeholder="Ex: 123" required 
                       inputmode="numeric" pattern="[0-9]+" title="Insira apenas números neste campo."
                       type="text" value="<?php echo htmlspecialchars($numerocasa ?? $cli['numerocasa']); ?>"/>
            </div>

            <div class="pt-2">
                <button class="w-full bg-[#0c6780] text-white font-medium py-4 rounded-lg flex items-center justify-center gap-2 btn-bloom transition-all duration-300 hover:scale-[1.01] active:scale-[0.98]" type="submit">
                    <span class="material-symbols-outlined">person_edit</span>
                    Salvar Alterações
                </button>
            </div>
        </form>
    </main>

    <footer class="mt-12 text-center">
        <p class="text-xs text-[#3f484c]/50 mt-2">©2026 Aromas da Lari. Todos os direitos reservados.</p>
    </footer>

    <script>
        document.getElementById('addUserForm').addEventListener('submit', function(e) {
            // Se o formulário for válido para as regras do HTML5, altera o texto do botão para feedback visual
            if(this.checkValidity()) {
                const btn = this.querySelector('button[type="submit"]');
                btn.innerHTML = '<span class="material-symbols-outlined animate-spin">progress_activity</span> Gravando dados...';
            }
        });
    </script>
    <footer class="mt-12 text-center">
        <p class="text-xs text-[#3f484c]/50 mt-2">©2026 Aromas da Lari. Todos os direitos reservados.</p>
    </footer>
</body>
</html>