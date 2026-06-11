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
        // Se o usuário digitou uma nova senha, atualiza nome e senha
        if (!empty($senha)) {
            $sql_update = "UPDATE usuarios SET nome = ?, senha = ? WHERE id = ?";
            $params = [$nome, $senha, $id];
        } else {
            // Se deixou a senha em branco, atualiza apenas o nome (mantém a senha antiga)
            $sql_update = "UPDATE usuarios SET nome = ? WHERE id = ?";
            $params = [$nome, $id];
        }
        
        $stmt_update = $conexao->prepare($sql_update);
        
        if ($stmt_update->execute($params)) {
            $_SESSION['sucesso'] = "Usuário atualizado com sucesso!";
            header("Location: usuario_read.php");
            exit;
        } else {
            $mensagem_erro = "Erro ao atualizar no banco de dados.";
        }
    } else {
        $mensagem_erro = "Por favor, preencha o campo Nome.";
    }
}

?>
<!DOCTYPE html>
<html class="light" lang="pt-BR"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Editar Usuário - Aura Management</title>
<!-- Fonts -->
<link href="https://fonts.googleapis.com" rel="preconnect"/>
<link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&amp;family=Playfair+Display:ital,wght@0,400..900;1,400..900&amp;display=swap" rel="stylesheet"/>
<!-- Material Icons -->
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<!-- Tailwind CSS -->
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<script id="tailwind-config">
      tailwind.config = {
        darkMode: "class",
        theme: {
          extend: {
            "colors": {
                "on-surface-variant": "#3f484c",
                "on-secondary-fixed": "#2f1314",
                "secondary": "#7b5455",
                "on-primary-fixed": "#001f29",
                "background": "#fbf9f8",
                "tertiary": "#5e5e5f",
                "on-secondary-container": "#7a5354",
                "inverse-primary": "#89d0ed",
                "inverse-surface": "#303030",
                "secondary-fixed-dim": "#ecbaba",
                "outline": "#6f787d",
                "on-secondary": "#ffffff",
                "tertiary-fixed-dim": "#c7c6c6",
                "inverse-on-surface": "#f2f0f0",
                "surface-container-low": "#f5f3f3",
                "surface-container-lowest": "#ffffff",
                "on-tertiary": "#ffffff",
                "tertiary-fixed": "#e4e2e2",
                "tertiary-container": "#c5c4c4",
                "outline-variant": "#bfc8cd",
                "on-error-container": "#93000a",
                "surface-variant": "#e4e2e2",
                "surface-tint": "#0c6780",
                "primary-container": "#87ceeb",
                "on-error": "#ffffff",
                "error-container": "#ffdad6",
                "surface-dim": "#dbd9d9",
                "secondary-container": "#fecbcb",
                "secondary-fixed": "#ffdad9",
                "primary-fixed": "#baeaff",
                "primary": "#0c6780",
                "on-tertiary-fixed-variant": "#464747",
                "surface-container-high": "#eae8e7",
                "surface-container": "#efeded",
                "on-secondary-fixed-variant": "#613d3e",
                "on-surface": "#1b1c1c",
                "on-primary": "#ffffff",
                "surface-container-highest": "#e4e2e2",
                "primary-fixed-dim": "#89d0ed",
                "on-primary-fixed-variant": "#004d62",
                "on-background": "#1b1c1c",
                "on-tertiary-container": "#515151",
                "surface": "#fbf9f8",
                "surface-bright": "#fbf9f8",
                "on-tertiary-fixed": "#1b1c1c",
                "error": "#ba1a1a",
                "on-primary-container": "#005870"
            },
            "borderRadius": {
                "DEFAULT": "0.25rem",
                "lg": "0.5rem",
                "xl": "0.75rem",
                "full": "9999px"
            },
            "spacing": {
                "margin-desktop": "64px",
                "container-max": "1200px",
                "unit": "8px",
                "margin-mobile": "20px",
                "gutter": "24px"
            },
            "fontFamily": {
                "headline-lg": ["Playfair Display"],
                "body-md": ["DM Sans"],
                "label-sm": ["DM Sans"],
                "headline-sm": ["Playfair Display"],
                "headline-md": ["Playfair Display"],
                "body-lg": ["DM Sans"],
                "label-md": ["DM Sans"]
            },
            "fontSize": {
                "headline-lg": ["48px", { "lineHeight": "1.1", "letterSpacing": "-0.02em", "fontWeight": "700" }],
                "body-md": ["16px", { "lineHeight": "1.5", "fontWeight": "400" }],
                "label-sm": ["12px", { "lineHeight": "1.4", "fontWeight": "500" }],
                "headline-sm": ["24px", { "lineHeight": "1.3", "fontWeight": "600" }],
                "headline-md": ["32px", { "lineHeight": "1.2", "fontWeight": "600" }],
                "body-lg": ["18px", { "lineHeight": "1.6", "fontWeight": "400" }],
                "label-md": ["14px", { "lineHeight": "1.4", "letterSpacing": "0.05em", "fontWeight": "500" }]
            }
          },
        },
      }
    </script>
<style>
      .glass-card {
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.5);
      }
      
      .btn-bloom:hover {
        box-shadow: 0 0 30px rgba(12, 103, 128, 0.15);
      }

      body {
        background: radial-gradient(circle at 0% 0%, #fbf9f8 0%, #f0f7f9 100%);
        min-height: 100vh;
      }
      
      .material-symbols-outlined {
        font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
      }
    </style>
</head>
<body class="font-body-md text-on-surface flex items-center justify-center p-6">
<!-- Atmospheric Background Decoration -->
<div class="fixed top-[-10%] right-[-5%] w-[40%] h-[40%] bg-primary/5 rounded-full blur-[100px] pointer-events-none"></div>
<div class="fixed bottom-[-10%] left-[-5%] w-[35%] h-[35%] bg-secondary/5 rounded-full blur-[80px] pointer-events-none"></div>
<div class="w-full max-w-[600px] relative">
<!-- Back Navigation -->
<div class="mb-12 flex items-center gap-4 group">
<button class="flex items-center justify-center w-12 h-12 rounded-full border border-outline-variant text-primary hover:bg-primary-container hover:text-on-primary-container transition-all duration-300" onclick="window.history.back()">
<span class="material-symbols-outlined">arrow_back</span>
</button>
<span class="font-label-md text-on-surface-variant opacity-0 group-hover:opacity-100 transition-opacity duration-300">Voltar para lista</span>
</div>
<!-- Main Form Container -->
<main class="glass-card rounded-lg p-10 shadow-sm border border-white/40">
<div class="mb-10 text-center md:text-left">
<h1 class="font-headline-md text-headline-md text-primary mb-3">Editar Usuário</h1>
<p class="font-body-md text-on-surface-variant max-w-[420px]">
                    Edite os dados abaixo para atualizar as informações do usuário.
                </p>
</div>
<form class="space-y-8" id="addUserForm" method="POST">
<!-- Nome Field -->
<div class="space-y-2">
<label class="block font-label-md text-label-md text-on-surface-variant uppercase tracking-wider ml-1" for="nome">
                        Nome
                    </label>
<input class="w-full bg-surface-container-lowest border-outline-variant/30 rounded-lg px-4 py-4 focus:ring-primary focus:border-primary transition-all placeholder:text-outline-variant/60" id="nome" name="nome" value="<?php echo htmlspecialchars($usuario['nome']); ?>" required="" type="text"/>
</div>
<!-- Senha Field -->
<div class="space-y-2">
<label class="block font-label-md text-label-md text-on-surface-variant uppercase tracking-wider ml-1" for="senha">
                        Senha
                    </label>
<div class="relative">
<input class="w-full bg-surface-container-lowest border-outline-variant/30 rounded-lg px-4 py-4 focus:ring-primary focus:border-primary transition-all placeholder:text-outline-variant/60" id="senha" name="senha" value="<?php echo htmlspecialchars($usuario['senha']); ?>" required="" type="password"/>
<button class="absolute right-4 top-1/2 -translate-y-1/2 text-outline hover:text-primary transition-colors" onclick="togglePassword()" type="button">
<span class="material-symbols-outlined" id="password_toggle_icon">visibility</span>
</button>
</div>
</div>
<!-- Image Contextual Decoration (Subtle) -->
<div class="pt-4 flex justify-center">
<div class="relative w-full h-24 overflow-hidden rounded-lg group">
<img class="w-full h-full object-cover opacity-20 group-hover:opacity-40 transition-opacity duration-700" data-alt="A soft and airy macro photograph of essential oil droplets on a marble surface, with soft morning sunlight creating a serene spa-like atmosphere. The color palette features ethereal whites, sky blues, and muted rose tones, reflecting a premium boutique management identity. The mood is peaceful, clean, and professional, aligning with a high-end wellness brand aesthetic." src="https://lh3.googleusercontent.com/aida-public/AB6AXuAzUr4X0HXXjhVZhtcqjc22bHxDSPr9rM0wQDvNh0ClX6ihV-2t-qMiLhFqbvNmTS0NQdUi2gG9ICl906lnGdPf0mkSPcKzRZfTEL8fQ7trYUxNgPE_ZjTOWlOmBcZzAhDooWsXaRBmMIrRq32mxR3mABVboWxlRLBLMAw7Lee5WbZlN7Inbqj1mk5BebB9y4sL0XAb0o93tNdayiJmVsq7_G95cCmbIlUJfn3M3G8M0AyiqQ1QFfC7l7wXCjomI0VfuU4AuGaMkII"/>
<div class="absolute inset-0 bg-gradient-to-t from-background to-transparent"></div>
</div>
</div>
<!-- Action Button -->
<div class="pt-6">
<button class="w-full bg-primary text-on-primary font-label-md py-4 rounded-lg flex items-center justify-center gap-2 btn-bloom transition-all duration-300 hover:scale-[1.01] active:scale-[0.98]" type="submit">
<span class="material-symbols-outlined">person_edit</span>
                        Editar
                    </button>
</div>
</form>
</main>
<!-- Footer Branding (Minimalist) -->
<footer class="mt-12 text-center">
<p class="font-headline-sm text-primary/40 tracking-tight">Aura Management</p>
<p class="font-label-sm text-on-surface-variant/50 mt-2">© 2024 Aromas da Lari. All rights reserved.</p>
</footer>
</div>
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
           
            const btn = e.target.querySelector('button[type="submit"]');
            btn.innerHTML = '<span class="material-symbols-outlined animate-spin">progress_activity</span> Editando...';
            btn.classList.add('opacity-80', 'cursor-not-allowed');
        });
    </script>
</body></html>