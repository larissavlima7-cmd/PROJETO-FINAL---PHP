<?php
session_start(); //isso vai permitir a navegação em outras páginas

 // para não dar erro antes de colocar as informações
if(isset($_POST["nome"], $_POST["senha"])){
    $nome = $_POST["nome"];
    $senha = $_POST["senha"];
    
    //validando para confirmar se existe esse nome no bd
    require_once "conexao_bd.php";
    $sql="SELECT * FROM usuarios WHERE nome = :nome";
    $stmt = $conexao->prepare($sql);
    $stmt->bindValue(':nome', $nome);
    $stmt-> execute();//resultado

    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);//traz o resultado da busca para o php
    // Vai confirmar se o que digitamos tem no banco de dados
    if($usuario && $usuario ['senha'] == $senha){
        //se tiver ele vai "guardar" e direcionar pra página principal
        $_SESSION['id_usuario']=$usuario['id'];
        $_SESSION['nome_usuario']=$usuario['nome'];

        header("Location: index.php");                
    }else{
        //se não ele vai mostrar a mensagem de erro:
        $erro = "Usuário ou senha inválidos!";
        
    }
}
?>
<!DOCTYPE html>

<html lang="pt-BR"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Aromas da Lari - Login</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&amp;family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400;1,600&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "tertiary": "#5e5e5f",
                        "on-primary": "#ffffff",
                        "secondary-fixed-dim": "#ecbaba",
                        "on-tertiary-fixed-variant": "#464747",
                        "on-secondary-fixed-variant": "#613d3e",
                        "on-primary-fixed": "#001f29",
                        "surface-bright": "#fbf9f8",
                        "outline": "#6f787d",
                        "on-secondary-fixed": "#2f1314",
                        "on-tertiary-fixed": "#1b1c1c",
                        "background": "#fbf9f8",
                        "tertiary-fixed-dim": "#c7c6c6",
                        "on-surface": "#1b1c1c",
                        "surface-container-lowest": "#ffffff",
                        "inverse-surface": "#303030",
                        "on-background": "#1b1c1c",
                        "on-tertiary": "#ffffff",
                        "surface-variant": "#e4e2e2",
                        "surface-container-low": "#f5f3f3",
                        "surface-container": "#efeded",
                        "on-surface-variant": "#3f484c",
                        "outline-variant": "#bfc8cd",
                        "secondary-container": "#fecbcb",
                        "tertiary-fixed": "#e4e2e2",
                        "error-container": "#ffdad6",
                        "secondary": "#7b5455",
                        "surface-dim": "#dbd9d9",
                        "error": "#ba1a1a",
                        "on-error": "#ffffff",
                        "surface-container-high": "#eae8e7",
                        "on-secondary": "#ffffff",
                        "tertiary-container": "#c5c4c4",
                        "inverse-on-surface": "#f2f0f0",
                        "on-primary-container": "#005870",
                        "inverse-primary": "#89d0ed",
                        "on-error-container": "#93000a",
                        "primary-fixed": "#baeaff",
                        "secondary-fixed": "#ffdad9",
                        "surface-tint": "#0c6780",
                        "on-primary-fixed-variant": "#004d62",
                        "primary-fixed-dim": "#89d0ed",
                        "on-secondary-container": "#7a5354",
                        "surface": "#fbf9f8",
                        "primary": "#0c6780",
                        "on-tertiary-container": "#515151",
                        "primary-container": "#87ceeb",
                        "surface-container-highest": "#e4e2e2"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                    "spacing": {
                        "margin-mobile": "20px",
                        "margin-desktop": "64px",
                        "gutter": "24px",
                        "unit": "8px",
                        "container-max": "1200px"
                    },
                    "fontFamily": {
                        "body-lg": ["DM Sans"],
                        "label-md": ["DM Sans"],
                        "body-md": ["DM Sans"],
                        "headline-lg-mobile": ["Playfair Display"],
                        "label-sm": ["DM Sans"],
                        "headline-md": ["Playfair Display"],
                        "headline-lg": ["Playfair Display"],
                        "headline-sm": ["Playfair Display"]
                    },
                    "fontSize": {
                        "body-lg": ["18px", {"lineHeight": "1.6", "fontWeight": "400"}],
                        "label-md": ["14px", {"lineHeight": "1.4", "letterSpacing": "0.05em", "fontWeight": "500"}],
                        "body-md": ["16px", {"lineHeight": "1.5", "fontWeight": "400"}],
                        "headline-lg-mobile": ["32px", {"lineHeight": "1.2", "fontWeight": "700"}],
                        "label-sm": ["12px", {"lineHeight": "1.4", "fontWeight": "500"}],
                        "headline-md": ["32px", {"lineHeight": "1.2", "fontWeight": "600"}],
                        "headline-lg": ["48px", {"lineHeight": "1.1", "letterSpacing": "-0.02em", "fontWeight": "700"}],
                        "headline-sm": ["24px", {"lineHeight": "1.3", "fontWeight": "600"}]
                    }
                    
                },
            },
        }
    </script>
<style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .glass-effect {
            backdrop-filter: blur(12px);
            background: rgba(255, 255, 255, 0.7);
            border: 0.5px solid rgba(255, 255, 255, 0.5);
        }
        .soft-glow-primary:focus-within {
            box-shadow: 0 0 30px 2px rgba(12, 103, 128, 0.1);
        }
        .bloom-button:hover {
            background-color: #7b5455; /* Secondary Rose */
            box-shadow: 0 0 40px 5px rgba(123, 84, 85, 0.15);
        }
        .floating-scent {
            animation: float 6s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        #resp{
            margin-top: 25px;
            padding: 10px;
            border-radius: 15px;
            background-color: #e7a7cea1;
            width: 100%;
            max-width: 500px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body class="bg-surface font-body-md text-on-surface min-h-screen overflow-x-hidden">
<main class="min-h-screen flex items-center justify-center relative overflow-hidden">
<div class="absolute top-[-10%] right-[-5%] w-[400px] h-[400px] bg-primary-container/20 rounded-full blur-[100px] pointer-events-none"></div>
<div class="absolute bottom-[-10%] left-[-5%] w-[400px] h-[400px] bg-secondary-container/20 rounded-full blur-[100px] pointer-events-none"></div>
<div class="w-full max-w-[1100px] flex flex-col md:flex-row bg-white rounded-[32px] overflow-hidden shadow-sm shadow-primary/5 mx-margin-mobile md:mx-margin-desktop min-h-[700px]">
<div class="hidden md:flex w-1/2 relative overflow-hidden group">
<img alt="Ethereal imagery" class="absolute inset-0 w-full h-full object-cover transition-transform duration-1000 group-hover:scale-105" data-alt="A serene, ethereal close-up of soft pink rose petals covered in delicate morning dew, set against a backdrop of a clear, pale sky-blue morning light. The composition is artistic and minimalist, evoking a sense of fresh artisanal fragrance and weightless luxury. The lighting is high-key and natural, creating a soft, dreamlike atmosphere that aligns with the premium aromatherapy brand aesthetic." src="https://lh3.googleusercontent.com/aida-public/AB6AXuDbMDEk93m7DUkdHflxjBvRVhkJbsxZcIR8ahs7qjjWZ_EpXxMeqObTBmmrA5zu1B2fGi9ZXPy6d2bsH_tVCAGX9rBIgvuGPeRQ77jJl4_7ZTY8vKE5565jnZaUo5BLioFRXADfbSSBd5nTzKiPgi9a3_2QX7K5RDustW7L_AWGCS7WxvbbURgHGA5PYipvad4rkU19643vy_TvEXQJAHYA1wt377zV3k26HGlbAC54MBCfrPcLrUSnHHtBt6ybUXzo4NXOYylpX14"/>
<div class="absolute inset-0 bg-gradient-to-t from-primary/30 to-transparent"></div>
<div class="relative z-10 flex flex-col justify-end p-12 text-white h-full">
<h2 class="font-headline-lg text-headline-lg mb-4 italic leading-tight">A essência da <br/>serenidade.</h2>
<p class="font-body-lg text-body-lg text-white/90 max-w-[320px]">Sinta a leveza de um novo amanhecer em cada detalhe de sua jornada conosco.</p>
</div>
</div>
<div class="w-full md:w-1/2 flex flex-col p-8 md:p-16 justify-center bg-white">
<div class="max-w-md mx-auto w-full">
<div class="mb-12 flex flex-col items-center md:items-start">
<span class="text-primary font-headline-sm text-headline-sm italic mb-2 tracking-tight">Aromas da Lari</span>
<h1 class="font-headline-md text-headline-md text-on-surface mb-2">Bem-vindo de volta</h1>
<p class="font-label-md text-label-md text-tertiary">Acesse seu portal de gestão</p>
</div>
<form action="#" class="space-y-6" method="POST">
<div class="space-y-2 group">
<label class="block font-label-sm text-label-sm uppercase tracking-widest text-on-surface-variant px-1" for="user">Usuário</label>
<div class="relative flex items-center soft-glow-primary transition-all duration-300">
<span class="material-symbols-outlined absolute left-4 text-outline-variant">person</span>
<input class="w-full bg-surface-container-low border border-outline-variant/30 rounded-xl py-4 pl-12 pr-4 font-body-md text-on-surface focus:ring-2 focus:ring-primary-container focus:border-primary-container transition-all outline-none" id="nome" name="nome" placeholder="Seu nome" type="text"/>
</div>
</div>
<div class="space-y-2 group">
<label class="block font-label-sm text-label-sm uppercase tracking-widest text-on-surface-variant px-1" for="password">Senha</label>
<div class="relative flex items-center soft-glow-primary transition-all duration-300">
<span class="material-symbols-outlined absolute left-4 text-outline-variant">lock</span>
<input class="w-full bg-surface-container-low border border-outline-variant/30 rounded-xl py-4 pl-12 pr-4 font-body-md text-on-surface focus:ring-2 focus:ring-primary-container focus:border-primary-container transition-all outline-none" id="senha" name="senha" placeholder="••••••••" type="password"/>
<button class="absolute right-4 text-outline-variant hover:text-primary transition-colors" type="button">
<span class="material-symbols-outlined">visibility</span>
</button>
</div>
</div>

<div class="flex items-center justify-between py-2">
<button class="w-full bg-primary-container text-on-primary-container font-label-md text-label-md py-5 rounded-xl flex items-center justify-center gap-3 bloom-button transition-all duration-500 transform active:scale-95 shadow-lg shadow-primary/10" type="submit">
<span>Entrar no Sistema</span>
<span class="material-symbols-outlined">arrow_forward</span>
</button>
</div>

    <?php
    if (isset($erro)) {
        echo "<div id='resp'>$erro</div>";
    }
    ?>
    
</form>
</div>
<div class="mt-auto pt-12 flex justify-between items-center opacity-60">
<span class="font-label-sm text-label-sm text-outline">©2026 Aromas da Lari</span>
</div>
</div>
</div>
</main>
<script>
        document.addEventListener('DOMContentLoaded', () => {
            const inputs = document.querySelectorAll('input');
            inputs.forEach(input => {
                input.addEventListener('focus', () => {
                    input.parentElement.parentElement.classList.add('scale-[1.01]');
                });
                input.addEventListener('blur', () => {
                    input.parentElement.parentElement.classList.remove('scale-[1.01]');
                });
            });
        });
    </script>
</body></html>