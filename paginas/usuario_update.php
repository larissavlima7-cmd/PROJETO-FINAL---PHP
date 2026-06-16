<?php
include "if_isset.php";
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: usuario_read.php");
    exit;
}

$id = $_GET['id'];

// 2. BUSCA OS DADOS ATUAIS DO USUÁRIO NO BANCO DE DADOS
$sql_select = "SELECT * FROM usuarios WHERE id = ?";
$stmt_select = $conexao->prepare($sql_select);
$stmt_select->execute([$id]);
$usuario = $stmt_select->fetch(PDO::FETCH_ASSOC);

// Se não encontrar o usuário, volta para a lista
if (!$usuario) {
    header("Location: usuario_read.php");
    exit;
}

// 3. PROCESSA O FORMULÁRIO QUANDO FOR ENVIADO (MÉTODO POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = isset($_POST['nome']) ? trim($_POST['nome']) : '';
    $senha = isset($_POST['senha']) ? trim($_POST['senha']) : '';

    if (!empty($nome)) {
        
        // VALIDAÇÃO DO NOME: Permite apenas letras e espaços (incluindo acentos)
        if (!preg_match("/^[a-zA-ZÀ-ÿ\s]+$/", $nome)) {
            $mensagem_erro = "O campo Nome deve conter apenas letras e espaços. Remova números ou símbolos.";
        } 
        else {
            // Se o usuário digitou uma nova senha, atualiza nome e senha
            if (!empty($senha)) {
                $sql_update = "UPDATE usuarios SET nome = ?, senha = ? WHERE id = ?";
                $params = [$nome, $senha, $id];
            } else {
                // Se deixou a senha em branco, atualiza apenas o nome
                $sql_update = "UPDATE usuarios SET nome = ? WHERE id = ?";
                $params = [$nome, $id];
            }

            $stmt_update = $conexao->prepare($sql_update);
            
            if ($stmt_update->execute($params)) {
                $_SESSION['sucesso'] = "Dados do usuário atualizados com sucesso!";
                header("Location: usuario_read.php");
                exit;
            } else {
                $mensagem_erro = "Erro interno ao atualizar os dados no banco de dados.";
            }
        }
    } else {
        $mensagem_erro = "Por favor, preencha o campo Nome.";
    }
}

if (isset($_SESSION['sucesso'])) {
    $mensagem_sucesso = $_SESSION['sucesso'];
    unset($_SESSION['sucesso']);
}
?>
<!DOCTYPE html>
<html class="light" lang="pt-BR">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Editar Usuário - Aromas da Lari</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,600&family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <style>
        .glass-panel { background: rgba(255, 255, 255, 0.4); backdrop-filter: blur(12px); border: 0.5px solid rgba(255, 255, 255, 0.6); }
        .btn-bloom { background: linear-gradient(135deg, #0c6780 0%, #004d62 100%); box-shadow: 0 4px 15px rgba(12, 103, 128, 0.2); }
        .btn-bloom:hover { box-shadow: 0 6px 20px rgba(12, 103, 128, 0.3); }
        input:focus { outline: none; border-color: #0c6780 !important; box-shadow: 0 0 0 2px rgba(12, 103, 128, 0.1); }
    </style>
</head>
<body class="bg-[#fbf9f8] text-[#1b1c1c] font-['DM_Sans'] min-h-screen flex flex-col justify-between p-6">

    <nav class="max-w-6xl mx-auto w-full flex justify-between items-center mb-12">
        <button onclick="window.location.href='usuario_read.php'" class="group flex items-center gap-2 text-[#0c6780] font-medium transition-all hover:opacity-80">
            <span class="material-symbols-outlined transition-transform group-hover:-translate-x-1">arrow_back</span>
            Voltar para Usuários
        </button>
        <span class="font-['Playfair_Display'] italic text-lg text-[#7b5455]">Aromas da Lari</span>
    </nav>

    <main class="max-w-xl mx-auto w-full flex-grow flex flex-col justify-center">
        
        <header class="text-center mb-8">
            <h1 class="font-['Playfair_Display'] text-4xl font-bold text-[#0c6780] mb-2">Editar Usuário</h1>
            <p class="text-[#3f484c]">Modifique os campos abaixo para atualizar o perfil do usuário.</p>
            
            <?php if(!empty($mensagem_erro)): ?>
                <div class="mt-4 p-4 bg-red-100 border border-red-300 text-red-700 rounded-lg text-sm font-medium text-left flex items-center gap-2 animate-fade-in">
                    <span class="material-symbols-outlined text-red-500">error</span>
                    <?php echo $mensagem_erro; ?>
                </div>
            <?php endif; ?>
        </header>

        <form id="addUserForm" method="POST" action="" class="glass-panel p-8 rounded-2xl space-y-6 shadow-sm">
            
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-[#0c6780] mb-2" for="nome">Nome Completo</label>
                <input class="w-full bg-[#f5f3f3]/50 border-[#bfc8cd] rounded-lg p-3.5 text-sm transition-all placeholder:text-[#6f787d]/50" 
                       id="nome" name="nome" placeholder="Ex: Larissa Silva" required 
                       pattern="[a-zA-ZÀ-ÿ\s]+" title="O nome deve conter apenas letras e espaços."
                       type="text" value="<?php echo htmlspecialchars($nome ?? $usuario['nome']); ?>"/>
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-[#0c6780] mb-2" for="senha">Nova Senha (Deixe em branco para não alterar)</label>
                <div class="relative">
                    <input class="w-full bg-[#f5f3f3]/50 border-[#bfc8cd] rounded-lg p-3.5 pr-12 text-sm transition-all placeholder:text-[#6f787d]/50" 
                           id="senha" name="senha" placeholder="Preencha apenas se quiser mudar" type="password"/>
                    <button class="absolute right-4 top-1/2 -translate-y-1/2 text-[#6f787d] hover:text-[#0c6780] transition-colors" onclick="togglePassword()" type="button">
                        <span class="material-symbols-outlined !text-xl" id="password_toggle_icon">visibility</span>
                    </button>
                </div>
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
        <p class="font-['Playfair_Display'] text-primary/40 tracking-tight text-[#7b5455]/40 italic">Aura Management</p>
        <p class="text-xs text-[#3f484c]/50 mt-2">© 2026 Aromas da Lari. Todos os direitos reservados.</p>
    </footer>

    <script>
        function togglePassword() {
            const input = document.getElementById('senha');
            const icon = document.getElementById('password_toggle_icon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.innerText = 'visibility_off';
            } else {
                input.type = 'password';
                icon.innerText = 'visibility';
            }
        }

        document.getElementById('addUserForm').addEventListener('submit', function(e) {
            if(this.checkValidity()) {
                const btn = this.querySelector('button[type="submit"]');
                btn.innerHTML = '<span class="material-symbols-outlined animate-spin">progress_activity</span> Salvando dados...';
            }
        });
    </script>
</body>
</html>