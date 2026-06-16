<?php
include "if_isset.php";
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: produto_read.php");
    exit;
}

$id = $_GET['id'];

// 2. BUSCA OS DADOS ATUAIS DOs produtos NO BANCO DE DADOS
$sql_select = "SELECT * FROM produtos WHERE id = ?";
$stmt_select = $conexao->prepare($sql_select);
$stmt_select->execute([$id]);
$pro = $stmt_select->fetch(PDO::FETCH_ASSOC);

// Se não encontrar o cliente, volta para a lista
if (!$pro) {
    header("Location: produto_read.php");
    exit;
}

// 3. PROCESSA O FORMULÁRIO QUANDO FOR ENVIADO (MÉTODO POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = isset($_POST['nome']) ? trim($_POST['nome']) : '';
    $marca = isset($_POST['marca']) ? trim($_POST['marca']) : '';
    $preco = isset($_POST['preco']) ? trim($_POST['preco']) : '';
    $quant= isset($_POST['quant_estoque']) ? trim($_POST['quant_estoque']) : '';
    $foto= isset($_POST['imagem']) ? trim($_POST['imagem']) : '';
    
    if (!empty($nome)) {
        // Se o usuário digitou uma nova senha, atualiza nome e senha
        $sql_update = "UPDATE produtos SET nome = ?, marca = ?, preco = ?, quant_estoque = ?, imagem = ? WHERE id = ?";
        $params = [$nome, $marca, $preco, $quant, $foto, $id];
    
        $stmt_update = $conexao->prepare($sql_update);
        
        if ($stmt_update->execute($params)) {
            $_SESSION['sucesso'] = "Produto atualizado com sucesso!";
            header("Location: produto_read.php");
            exit;
        } else {
            $mensagem_erro = "Erro ao atualizar no banco de dados.";
        }
    } else {
        $mensagem_erro = "Por favor, preencha o campo Nome.";
    }
}
?>

<!DOCTYPE html><html class="light" lang="pt-BR"><head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<title>Editando Produto | Aromas da Lari</title>
<!-- Tailwind CSS -->
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<!-- Google Fonts: Playfair Display & DM Sans -->
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&amp;family=DM+Sans:wght@400;500;700&amp;display=swap" rel="stylesheet">
<!-- Material Symbols -->
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet">
<script id="tailwind-config">
      tailwind.config = {
        darkMode: "class",
        theme: {
          extend: {
            "colors": {
                "surface-bright": "#fbf9f8",
                "on-tertiary-container": "#515151",
                "on-secondary-fixed-variant": "#613d3e",
                "secondary-fixed-dim": "#ecbaba",
                "inverse-surface": "#303030",
                "on-error-container": "#93000a",
                "surface-container-high": "#eae8e7",
                "secondary-container": "#fecbcb",
                "on-primary": "#ffffff",
                "surface-variant": "#e4e2e2",
                "on-primary-container": "#005870",
                "on-secondary-fixed": "#2f1314",
                "on-background": "#1b1c1c",
                "inverse-on-surface": "#f2f0f0",
                "primary-fixed": "#baeaff",
                "on-tertiary-fixed-variant": "#464747",
                "surface-container": "#efeded",
                "surface-container-highest": "#e4e2e2",
                "tertiary-fixed": "#e4e2e2",
                "tertiary-container": "#c5c4c4",
                "on-surface-variant": "#3f484c",
                "tertiary": "#5e5e5f",
                "primary-fixed-dim": "#89d0ed",
                "on-secondary-container": "#7a5354",
                "on-secondary": "#ffffff",
                "secondary": "#7b5455",
                "on-primary-fixed": "#001f29",
                "surface-container-low": "#f5f3f3",
                "on-primary-fixed-variant": "#004d62",
                "on-error": "#ffffff",
                "primary-container": "#87ceeb",
                "surface-tint": "#0c6780",
                "surface": "#fbf9f8",
                "outline-variant": "#bfc8cd",
                "background": "#fbf9f8",
                "surface-container-lowest": "#ffffff",
                "error-container": "#ffdad6",
                "inverse-primary": "#89d0ed",
                "error": "#ba1a1a",
                "secondary-fixed": "#ffdad9",
                "tertiary-fixed-dim": "#c7c6c6",
                "primary": "#0c6780",
                "surface-dim": "#dbd9d9",
                "outline": "#6f787d",
                "on-surface": "#1b1c1c",
                "on-tertiary-fixed": "#1b1c1c",
                "on-tertiary": "#ffffff"
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
                "margin-mobile": "20px",
                "gutter": "24px",
                "unit": "8px"
            },
            "fontFamily": {
                "headline-lg": ["Playfair Display"],
                "headline-sm": ["Playfair Display"],
                "headline-md": ["Playfair Display"],
                "label-md": ["DM Sans"],
                "body-md": ["DM Sans"],
                "label-sm": ["DM Sans"],
                "body-lg": ["DM Sans"]
            },
            "fontSize": {
                "headline-lg": ["48px", {"lineHeight": "1.1", "letterSpacing": "-0.02em", "fontWeight": "700"}],
                "headline-sm": ["24px", {"lineHeight": "1.3", "fontWeight": "600"}],
                "headline-md": ["32px", {"lineHeight": "1.2", "fontWeight": "600"}],
                "label-md": ["14px", {"lineHeight": "1.4", "letterSpacing": "0.05em", "fontWeight": "500"}],
                "body-md": ["16px", {"lineHeight": "1.5", "fontWeight": "400"}],
                "label-sm": ["12px", {"lineHeight": "1.4", "fontWeight": "500"}],
                "body-lg": ["18px", {"lineHeight": "1.6", "fontWeight": "400"}]
            }
          },
        },
      }
    </script>
<style>
      body {
        background-color: #fbf9f8; /* surface-bright */
        color: #1b1c1c; /* on-surface */
        -webkit-font-smoothing: antialiased;
      }
      .glass-card {
        background: rgba(255, 255, 255, 0.4);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.5);
      }
      .soft-glow-primary {
        box-shadow: 0 10px 30px -10px rgba(12, 103, 128, 0.1);
      }
      .material-symbols-outlined {
        font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
      }
      .input-transition:focus {
        border-color: #0c6780;
        box-shadow: 0 0 0 4px rgba(135, 206, 235, 0.2);
      }
    </style>
</head>
<body class="min-h-screen flex flex-col overflow-x-hidden">
<!-- Header Navigation -->
<header class="w-full h-20 px-margin-mobile md:px-margin-desktop flex items-center justify-between sticky top-0 z-50 bg-surface/80 backdrop-blur-md">
<div class="flex items-center gap-6">
  <a href="produto_read.php">
    <button aria-label="Voltar" class="flex items-center justify-center w-10 h-10 rounded-full hover:bg-primary-container/20 transition-all text-primary">
    <span class="material-symbols-outlined">arrow_back</span>
    </button>
  </a>
<h1 class="font-headline-sm text-headline-sm text-primary tracking-tight">Editar um Produto</h1>
</div>

</header>
<!-- Main Content -->
<main class="flex-grow flex items-center justify-center py-12 px-margin-mobile">
<!-- Form Container -->
<div class="w-full max-w-[800px] glass-card rounded-[32px] p-8 md:p-12 soft-glow-primary animate-in fade-in slide-in-from-bottom-4 duration-700">
<form class="space-y-8" id="productForm" method="POST">
<!-- Visual Feedback Section (Image Placeholder) -->

<!-- Form Fields Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-y-8 gap-x-gutter"><div class="md:col-span-2 space-y-2">
<label class="font-label-sm text-label-sm text-primary uppercase tracking-[0.1em] ml-1" for="imagem">Link da Foto</label>
<input class="w-full bg-surface-container-lowest/50 border border-outline-variant rounded-xl px-4 py-4 font-body-md text-body-md input-transition outline-none transition-all placeholder:text-on-surface-variant/40" value="<?php echo htmlspecialchars($pro['imagem'] ?? ''); ?>" id="imagem" name="imagem" type="url">
</div>
<!-- Product Name (Nome) - Full Width -->
<div class="md:col-span-2 space-y-2">
<label class="font-label-sm text-label-sm text-primary uppercase tracking-[0.1em] ml-1" for="nome">Nome do Produto</label>
<input class="w-full bg-surface-container-lowest/50 border border-outline-variant rounded-xl px-4 py-4 font-body-md text-body-md input-transition outline-none transition-all placeholder:text-on-surface-variant/40" value="<?php echo htmlspecialchars($pro['nome'] ?? ''); ?>" id="nome" name="nome" required="" type="text">
</div>
<!-- Brand (Marca) -->
<div class="space-y-2">
<label class="font-label-sm text-label-sm text-primary uppercase tracking-[0.1em] ml-1" for="marca">Marca</label>
<input class="w-full bg-surface-container-lowest/50 border border-outline-variant rounded-xl px-4 py-4 font-body-md text-body-md input-transition outline-none transition-all placeholder:text-on-surface-variant/40" value="<?php echo htmlspecialchars($pro['marca'] ?? ''); ?>" id="marca" name="marca" type="text">
</div>
<!-- Price (Preço) -->
<div class="space-y-2">
<label class="font-label-sm text-label-sm text-primary uppercase tracking-[0.1em] ml-1" for="preco">Preço (R$)</label>
<div class="relative">
<span class="absolute left-4 top-1/2 -translate-y-1/2 font-label-md text-on-surface-variant">R$</span>
<input class="w-full bg-surface-container-lowest/50 border border-outline-variant rounded-xl pl-12 pr-4 py-4 font-body-md text-body-md input-transition outline-none transition-all placeholder:text-on-surface-variant/40" value="<?php echo htmlspecialchars($pro['preco'] ?? ''); ?>" id="preco" name="preco" placeholder="0,00" required="" step="0.01" type="number">
</div>
</div>
<!-- Description or Notes (Optional additions to fill space gracefully) -->
<div class="md:col-span-2 space-y-2">
<label class="font-label-sm text-label-sm text-primary uppercase tracking-[0.1em] ml-1" for="quant_estoque">Quantidade no estoque</label>
<input class="w-full bg-surface-container-lowest/50 border border-outline-variant rounded-xl px-4 py-4 font-body-md text-body-md input-transition outline-none transition-all resize-none placeholder:text-on-surface-variant/40" value="<?php echo htmlspecialchars($pro['quant_estoque'] ?? ''); ?>" id="quant_estoque" name="quant_estoque" type="number">
</div>
</div>
<!-- Action Button -->
<div class="pt-8 flex justify-center">
<button class="group relative px-12 py-4 bg-primary text-white rounded-full font-label-md text-label-md overflow-hidden transition-all hover:pr-14 active:scale-95 shadow-xl hover:shadow-primary/20" type="submit">
<span class="relative z-10">Editar Produto</span>
<span class="material-symbols-outlined absolute right-6 top-1/2 -translate-y-1/2 opacity-0 group-hover:opacity-100 transition-all duration-300">check</span>
<div class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-500 ease-out"></div>
</button>
</div>
</form>
</div>
</main>
<!-- Visual Atmosphere: Subtle background texture/blob -->
<div class="fixed top-[-10%] right-[-5%] w-[40vw] h-[40vw] bg-primary-container/20 rounded-full blur-[120px] pointer-events-none -z-10 animate-pulse" style="animation-duration: 8s;"></div>
<div class="fixed bottom-[-10%] left-[-5%] w-[35vw] h-[35vw] bg-secondary-container/20 rounded-full blur-[100px] pointer-events-none -z-10 animate-pulse" style="animation-duration: 10s;"></div>
<script>
        // Micro-interaction for Image Preview
        const imageInput = document.getElementById('imageInput');
        const imagePreview = document.getElementById('imagePreview');
        const uploadPlaceholder = document.getElementById('uploadPlaceholder');

        imageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    imagePreview.src = event.target.result;
                    imagePreview.classList.remove('hidden');
                    uploadPlaceholder.classList.add('hidden');
                }
                reader.readAsDataURL(file);
            }
        });

        // Form Submit Interaction
        document.getElementById('productForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const btn = e.target.querySelector('button[type="submit"]');
            const originalText = btn.innerHTML;
            
            // Visual feedback
            btn.disabled = true;
            btn.innerHTML = '<span class="material-symbols-outlined animate-spin">progress_activity</span>';
            
            setTimeout(() => {
                btn.classList.add('bg-green-600');
                btn.innerHTML = 'Sucesso!';
                
                setTimeout(() => {
                    // Reset or Redirect logic here
                    btn.disabled = false;
                    btn.classList.remove('bg-green-600');
                    btn.innerHTML = originalText;
                }, 2000);
            }, 1000);
        });
    </script>


</body></html>