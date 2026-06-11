<?php
include "if_isset.php";
?>
<!DOCTYPE html><html class="light" lang="pt-br"><head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<title>Aromas da Lari - Dashboard</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&amp;family=Playfair+Display:wght@600;700&amp;display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet">
<style>
        .glass-panel {
            background: rgba(255, 255, 255, 0.4);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 0.5px solid rgba(255, 255, 255, 0.5);
        }
        .glow-sky {
            box-shadow: 0 10px 40px -10px rgba(12, 103, 128, 0.12);
        }
        .glow-rose {
            box-shadow: 0 10px 40px -10px rgba(123, 84, 85, 0.12);
        }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 300, 'GRAD' 0, 'opsz' 24;
        }
        body {
            background: radial-gradient(circle at top right, #fdfbfb 0%, #ebf8ff 40%, #fbf9f8 100%);
            min-height: 100vh;
        }
        
    </style>
<script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "on-secondary-container": "#7a5354",
                        "secondary-fixed-dim": "#ecbaba",
                        "primary": "#0c6780",
                        "on-background": "#1b1c1c",
                        "surface-bright": "#fbf9f8",
                        "primary-container": "#87ceeb",
                        "tertiary-container": "#c5c4c4",
                        "on-surface": "#1b1c1c",
                        "on-secondary-fixed": "#2f1314",
                        "inverse-primary": "#89d0ed",
                        "secondary-container": "#fecbcb",
                        "tertiary-fixed-dim": "#c7c6c6",
                        "secondary": "#7b5455",
                        "error": "#ba1a1a",
                        "surface-container-low": "#f5f3f3",
                        "on-primary": "#ffffff",
                        "error-container": "#ffdad6",
                        "tertiary": "#5e5e5f",
                        "surface-container-highest": "#e4e2e2",
                        "surface-container-high": "#eae8e7",
                        "outline-variant": "#bfc8cd",
                        "on-primary-fixed-variant": "#004d62",
                        "inverse-on-surface": "#f2f0f0",
                        "background": "#fbf9f8",
                        "primary-fixed-dim": "#89d0ed",
                        "on-tertiary": "#ffffff",
                        "outline": "#6f787d",
                        "surface-variant": "#e4e2e2",
                        "surface-dim": "#dbd9d9",
                        "surface-container": "#efeded",
                        "inverse-surface": "#303030",
                        "on-tertiary-fixed-variant": "#464747",
                        "on-tertiary-fixed": "#1b1c1c",
                        "on-secondary-fixed-variant": "#613d3e",
                        "surface-tint": "#0c6780",
                        "on-secondary": "#ffffff",
                        "on-surface-variant": "#3f484c",
                        "on-error-container": "#93000a",
                        "primary-fixed": "#baeaff",
                        "on-primary-container": "#005870",
                        "surface-container-lowest": "#ffffff",
                        "on-error": "#ffffff",
                        "tertiary-fixed": "#e4e2e2",
                        "surface": "#fbf9f8",
                        "secondary-fixed": "#ffdad9",
                        "on-primary-fixed": "#001f29",
                        "on-tertiary-container": "#515151"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                    "spacing": {
                        "margin-mobile": "20px",
                        "unit": "8px",
                        "margin-desktop": "64px",
                        "container-max": "1200px",
                        "gutter": "24px"
                    },
                    "fontFamily": {
                        "body-lg": ["DM Sans"],
                        "label-md": ["DM Sans"],
                        "headline-lg": ["Playfair Display"],
                        "label-sm": ["DM Sans"],
                        "headline-lg-mobile": ["Playfair Display"],
                        "headline-md": ["Playfair Display"],
                        "headline-sm": ["Playfair Display"],
                        "body-md": ["DM Sans"]
                    },
                    "fontSize": {
                        "body-lg": ["18px", {"lineHeight": "1.6", "fontWeight": "400"}],
                        "label-md": ["14px", {"lineHeight": "1.4", "letterSpacing": "0.05em", "fontWeight": "500"}],
                        "headline-lg": ["48px", {"lineHeight": "1.1", "letterSpacing": "-0.02em", "fontWeight": "700"}],
                        "label-sm": ["12px", {"lineHeight": "1.4", "fontWeight": "500"}],
                        "headline-lg-mobile": ["32px", {"lineHeight": "1.2", "fontWeight": "700"}],
                        "headline-md": ["32px", {"lineHeight": "1.2", "fontWeight": "600"}],
                        "headline-sm": ["24px", {"lineHeight": "1.3", "fontWeight": "600"}],
                        "body-md": ["16px", {"lineHeight": "1.5", "fontWeight": "400"}]
                    }
                },
            },
        }
    </script>
</head>
<body class="text-on-background font-body-md overflow-x-hidden">
<!-- Side Navigation Shell -->

<!-- Main Content Area -->
<main class="min-h-screen flex flex-col">
<!-- Top Navigation Bar -->

<!-- Canvas / Dashboard Content -->
<section class="flex-1 p-6 md:p-12 max-w-container-max mx-auto w-full space-y-12 flex flex-col justify-center">
<!-- Welcome Header -->
<div class="space-y-2 animate-fade-in">
<h2 class="font-headline-lg text-headline-lg text-primary">Bem-vinda de volta, <?php echo $_SESSION['nome_usuario'];?>!</h2>
<p class="text-secondary font-body-lg max-w-2xl opacity-80">Seu painel administrativo para gestão. Veja o que mudou desde seu último acesso.</p>
</div>
<!-- Bento Quick Access Grid (Sketch inspired) -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-gutter">
<!-- USUARIOS -->
<div class="glass-panel p-8 rounded-3xl glow-sky group hover:-translate-y-1 transition-all duration-300 flex flex-col gap-4 cursor-pointer" style="opacity: 1; transform: translateY(0px); transition: 0.6s cubic-bezier(0.16, 1, 0.3, 1);">
<div class="h-14 w-14 rounded-2xl bg-primary/10 flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-white transition-colors duration-300">
<span class="material-symbols-outlined text-3xl" data-icon="badge">badge</span>
</div>
<div>
<a href="usuario_read.php" class="font-headline-sm text-headline-sm text-on-surface">Usuários</a>
<p class="font-label-sm text-label-sm text-secondary uppercase tracking-wider mt-1">2 Colaboradores</p>
</div>
<div class="mt-auto pt-4 flex items-center gap-2 text-primary font-bold">
<a href="usuario_read.php" class="">Gerenciar</a>
<span class="material-symbols-outlined" data-icon="arrow_forward">arrow_forward</span>
</div>
</div>
<!-- CLIENTES-->
<div class="glass-panel p-8 rounded-3xl glow-rose group hover:-translate-y-1 transition-all duration-300 flex flex-col gap-4 cursor-pointer" style="opacity: 1; transform: translateY(0px); transition: 0.6s cubic-bezier(0.16, 1, 0.3, 1);">
<div class="h-14 w-14 rounded-2xl bg-secondary/10 flex items-center justify-center text-secondary group-hover:bg-secondary group-hover:text-white transition-colors duration-300">
<span class="material-symbols-outlined text-3xl" data-icon="group">group</span>
</div>
<div>
<a href="cliente_read.php" class="font-headline-sm text-headline-sm text-on-surface">Clientes</a>
<p class="font-label-sm text-label-sm text-secondary uppercase tracking-wider mt-1">10 Clientes Ativos</p>
</div>
<div class="mt-auto pt-4 flex items-center gap-2 text-secondary font-bold">
<a href="cliente_read.php" class="">Ver Base</a>
<span class="material-symbols-outlined" data-icon="arrow_forward">arrow_forward</span>
</div>
</div>
<!-- PRODUTOS-->
<div class="glass-panel p-8 rounded-3xl glow-sky group hover:-translate-y-1 transition-all duration-300 flex flex-col gap-4 cursor-pointer" style="opacity: 1; transform: translateY(0px); transition: 0.6s cubic-bezier(0.16, 1, 0.3, 1);">
<div class="h-14 w-14 rounded-2xl bg-primary/10 flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-white transition-colors duration-300">
<span class="material-symbols-outlined text-3xl" data-icon="label_important">label_important</span>
</div>
<div>
<a href="produto_read.php" class="font-headline-sm text-headline-sm text-on-surface">Produtos</a>
<p class="font-label-sm text-label-sm text-secondary uppercase tracking-wider mt-1">30 Essências</p>
</div>
<div class="mt-auto pt-4 flex items-center gap-2 text-primary font-bold">
<a  href="produto_read.php" class="">Inventário</a>
<span class="material-symbols-outlined" data-icon="arrow_forward">arrow_forward</span>
</div>
</div>
<!-- PEDIDOS-->
<div class="glass-panel p-8 rounded-3xl glow-rose group hover:-translate-y-1 transition-all duration-300 flex flex-col gap-4 cursor-pointer" style="opacity: 1; transform: translateY(0px); transition: 0.6s cubic-bezier(0.16, 1, 0.3, 1);">
<div class="h-14 w-14 rounded-2xl bg-secondary/10 flex items-center justify-center text-secondary group-hover:bg-secondary group-hover:text-white transition-colors duration-300">
<span class="material-symbols-outlined text-3xl" data-icon="shopping_bag">shopping_bag</span>
</div>
<div>
<a href="pedido_read.php" class="font-headline-sm text-headline-sm text-on-surface">Pedidos</a>
<p class="font-label-sm text-label-sm text-secondary uppercase tracking-wider mt-1">5 Pendentes</p>
</div>
<div class="mt-auto pt-4 flex items-center gap-2 text-secondary font-bold">
<a href="pedido_read.php" class="">Expedição</a>
<span class="material-symbols-outlined" data-icon="arrow_forward">arrow_forward</span>
</div>
</div>
</div>
<!-- Secondary Layout: Quick Stats & Activity -->
<!-- Sketch Reference Section (As requested) -->
</section>
<!-- Footer Shell -->

</main>
<!-- Floating Action Button (FAB) - For Home/Dashboard Context -->

<script>
        // Simple entrance animations for cards
        document.addEventListener('DOMContentLoaded', () => {
            const cards = document.querySelectorAll('.glass-panel');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    card.style.transition = 'all 0.6s cubic-bezier(0.16, 1, 0.3, 1)';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, 100 * index);
            });
        });
    </script>


</body></html>