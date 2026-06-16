<?php
include "if_isset.php";

$nome = $marca = $preco = $quant = $foto = '';

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $nome = isset($_POST['nome']) ? trim($_POST['nome']) : '';
    $marca= isset($_POST['marca']) ? trim($_POST['marca']) : '';
    $preco= isset($_POST['preco']) ? trim($_POST['preco']) : '';
    $quant= isset($_POST['quant_estoque']) ? trim($_POST['quant_estoque']) : '';
    $foto= isset($_POST['imagem']) ? trim($_POST['imagem']) : '';
    
    // CORREÇÃO AQUI: Mudamos !empty($quant) para $quant !== '' para aceitar o número 0
    if (!empty($nome) && !empty($marca) && !empty($preco) && $quant !== '' && !empty($foto)) {
        
        if ($preco < 0 || $quant < 0) {
            $mensagem_erro = "Preço e Quantidade de estoque não podem ser valores negativos.";
        } else {
            $sql = "INSERT INTO produtos (nome, marca, preco, quant_estoque, imagem) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conexao->prepare($sql);
            
            if ($stmt->execute([$nome, $marca, $preco, $quant, $foto])) {
                $_SESSION['sucesso'] = "Produto cadastrado com sucesso na rede Aromas da Lari!";
                header("Location: produto_read.php");
                exit;
            } else {
                $mensagem_erro = "Erro ao salvar no banco de dados.";
            }
        }
    } else {
        $mensagem_erro = "Por favor, preencha todos os campos obrigatórios.";
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
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Cadastrar Novo Produto | Aromas da Lari</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&amp;family=DM+Sans:wght@400;500;700&amp;display=swap" rel="stylesheet">
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
            "fontFamily": {
                "headline-sm": ["Playfair Display"],
                "label-sm": ["DM Sans"],
                "body-md": ["DM Sans"]
            }
          },
        },
      }
    </script>
    <style>
      body { background-color: #fbf9f8; color: #1b1c1c; -webkit-font-smoothing: antialiased; }
      .glass-card { background: rgba(255, 255, 255, 0.4); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.5); }
      .soft-glow-primary { box-shadow: 0 10px 30px -10px rgba(12, 103, 128, 0.1); }
      .input-transition:focus { border-color: #0c6780; box-shadow: 0 0 0 4px rgba(135, 206, 235, 0.2); }
    </style>
</head>
<body class="min-h-screen flex flex-col overflow-x-hidden">

<header class="w-full h-20 px-6 md:px-16 flex items-center justify-between sticky top-0 z-50 bg-surface/80 backdrop-blur-md">
    <div class="flex items-center gap-6">
      <a href="produto_read.php">
        <button aria-label="Voltar" class="flex items-center justify-center w-10 h-10 rounded-full hover:bg-primary-container/20 transition-all text-primary">
            <span class="material-symbols-outlined">arrow_back</span>
        </button>
      </a>
      <h1 class="font-headline-sm text-2xl text-primary tracking-tight">Cadastrar Novo Produto</h1>
    </div>
</header>

<main class="flex-grow flex flex-col items-center justify-center py-12 px-6">
    <div class="w-full max-w-[800px] glass-card rounded-[32px] p-8 md:p-12 soft-glow-primary">
        
        <?php if(!empty($mensagem_erro)): ?>
            <div class="mb-6 p-4 bg-red-100 border border-red-300 text-red-700 rounded-xl text-sm font-medium flex items-center gap-2">
                <span class="material-symbols-outlined text-red-500">error</span>
                <?php echo $mensagem_erro; ?>
            </div>
        <?php endif; ?>

        <form class="space-y-8" id="productForm" method="POST" action="">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-y-8 gap-x-6">
                
                <div class="md:col-span-2 space-y-2">
                    <label class="font-label-sm text-xs text-primary uppercase tracking-[0.1em] ml-1" for="imagem">Link da Foto</label>
                    <input class="w-full bg-surface-container-lowest/50 border border-outline-variant rounded-xl px-4 py-4 font-body-md text-base input-transition outline-none transition-all placeholder:text-on-surface-variant/40" 
                           id="imagem" name="imagem" type="url" required value="<?php echo htmlspecialchars($foto); ?>">
                </div>
                
                <div class="md:col-span-2 space-y-2">
                    <label class="font-label-sm text-xs text-primary uppercase tracking-[0.1em] ml-1" for="nome">Nome do Produto</label>
                    <input class="w-full bg-surface-container-lowest/50 border border-outline-variant rounded-xl px-4 py-4 font-body-md text-base input-transition outline-none transition-all placeholder:text-on-surface-variant/40" 
                           id="nome" name="nome" required type="text" value="<?php echo htmlspecialchars($nome); ?>">
                </div>
                
                <div class="space-y-2">
                    <label class="font-label-sm text-xs text-primary uppercase tracking-[0.1em] ml-1" for="marca">Marca</label>
                    <input class="w-full bg-surface-container-lowest/50 border border-outline-variant rounded-xl px-4 py-4 font-body-md text-base input-transition outline-none transition-all placeholder:text-on-surface-variant/40" 
                           id="marca" name="marca" required type="text" value="<?php echo htmlspecialchars($marca); ?>">
                </div>
                
                <div class="space-y-2">
                    <label class="font-label-sm text-xs text-primary uppercase tracking-[0.1em] ml-1" for="preco">Preço (R$)</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 font-label-md text-on-surface-variant">R$</span>
                        <input class="w-full bg-surface-container-lowest/50 border border-outline-variant rounded-xl pl-12 pr-4 py-4 font-body-md text-base input-transition outline-none transition-all placeholder:text-on-surface-variant/40" 
                               id="preco" name="preco" placeholder="0.00" required min="0" step="0.01" type="number" value="<?php echo htmlspecialchars($preco); ?>">
                    </div>
                </div>
                
                <div class="md:col-span-2 space-y-2">
                    <label class="font-label-sm text-xs text-primary uppercase tracking-[0.1em] ml-1" for="quant_estoque">Quantidade no estoque</label>
                    <input class="w-full bg-surface-container-lowest/50 border border-outline-variant rounded-xl px-4 py-4 font-body-md text-base input-transition outline-none transition-all placeholder:text-on-surface-variant/40" 
                           id="quant_estoque" name="quant_estoque" required min="0" type="number" value="<?php echo htmlspecialchars($quant); ?>">
                </div>
            </div>

            <div class="pt-8 flex justify-center">
                <button class="group relative px-12 py-4 bg-primary text-white rounded-full font-label-md text-sm overflow-hidden transition-all hover:pr-14 active:scale-95 shadow-xl" type="submit">
                    <span class="relative z-10 flex items-center gap-2" id="btnText">Adicionar Produto</span>
                    <span class="material-symbols-outlined absolute right-6 top-1/2 -translate-y-1/2 opacity-0 group-hover:opacity-100 transition-all duration-300">check</span>
                </button>
            </div>
        </form>
    </div>
</main>

<script>
    document.getElementById('productForm').addEventListener('submit', function(e) {
        if(this.checkValidity()) {
            const btnText = document.getElementById('btnText');
            btnText.innerHTML = '<span class="material-symbols-outlined animate-spin">progress_activity</span> Cadastrando...';
        }
    });
</script>
</body>
</html>