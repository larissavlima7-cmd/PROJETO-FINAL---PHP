<?php
session_start();
if (!isset($_SESSION['id_usuario'])) { //vai confirmar se a pessoa realmete fez o login, e não acessou direto pelo endereço
    header("Location: login.php");
    exit;
}
require_once "conexao_bd.php";
$sql="SELECT * FROM usuarios";

//stmt = statement refere-se a um objeto PDO Statement no contexto do PDO
$stmt=$conexao->prepare($sql);
$stmt->execute();

$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html><html class="light" lang="pt-br" style=""><head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<title>Gerenciamento de Usuários | USU Perfumery</title>
<!-- Fonts -->
<link href="https://fonts.googleapis.com" rel="preconnect">
<link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect">
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&amp;family=Playfair+Display:ital,wght@0,400..900;1,400..900&amp;display=swap" rel="stylesheet">
<!-- Icons -->
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet">
<!-- Tailwind -->
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "surface-container-low": "#f5f3f3",
                        "on-secondary-fixed-variant": "#613d3e",
                        "on-surface-variant": "#3f484c",
                        "outline": "#6f787d",
                        "on-error-container": "#93000a",
                        "inverse-primary": "#89d0ed",
                        "surface-dim": "#dbd9d9",
                        "surface-container-high": "#eae8e7",
                        "background": "#fbf9f8",
                        "on-tertiary-fixed": "#1b1c1c",
                        "on-secondary-fixed": "#2f1314",
                        "on-secondary-container": "#7a5354",
                        "on-background": "#1b1c1c",
                        "on-surface": "#1b1c1c",
                        "surface-container-highest": "#e4e2e2",
                        "on-tertiary-container": "#515151",
                        "error": "#ba1a1a",
                        "secondary": "#7b5455",
                        "on-primary-fixed-variant": "#004d62",
                        "on-primary-fixed": "#001f29",
                        "primary-fixed": "#baeaff",
                        "tertiary-container": "#c5c4c4",
                        "tertiary": "#5e5e5f",
                        "outline-variant": "#bfc8cd",
                        "secondary-container": "#fecbcb",
                        "inverse-surface": "#303030",
                        "secondary-fixed": "#ffdad9",
                        "on-tertiary": "#ffffff",
                        "on-secondary": "#ffffff",
                        "primary-fixed-dim": "#89d0ed",
                        "primary-container": "#87ceeb",
                        "surface-container": "#efeded",
                        "on-error": "#ffffff",
                        "surface-bright": "#fbf9f8",
                        "surface-tint": "#0c6780",
                        "tertiary-fixed-dim": "#c7c6c6",
                        "surface-container-lowest": "#ffffff",
                        "surface-variant": "#e4e2e2",
                        "secondary-fixed-dim": "#ecbaba",
                        "on-primary": "#ffffff",
                        "primary": "#0c6780",
                        "tertiary-fixed": "#e4e2e2",
                        "surface": "#fbf9f8",
                        "on-primary-container": "#005870",
                        "inverse-on-surface": "#f2f0f0",
                        "error-container": "#ffdad6",
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
                        "margin-desktop": "64px",
                        "container-max": "1200px",
                        "margin-mobile": "20px",
                        "gutter": "24px"
                    },
                    "fontFamily": {
                        "body-md": ["DM Sans"],
                        "label-md": ["DM Sans"],
                        "body-lg": ["DM Sans"],
                        "label-sm": ["DM Sans"],
                        "headline-md": ["Playfair Display"],
                        "headline-lg": ["Playfair Display"],
                        "headline-lg-mobile": ["Playfair Display"],
                        "headline-sm": ["Playfair Display"]
                    },
                    "fontSize": {
                        "body-md": ["16px", {"lineHeight": "1.5", "fontWeight": "400"}],
                        "label-md": ["14px", {"lineHeight": "1.4", "letterSpacing": "0.05em", "fontWeight": "500"}],
                        "body-lg": ["18px", {"lineHeight": "1.6", "fontWeight": "400"}],
                        "label-sm": ["12px", {"lineHeight": "1.4", "fontWeight": "500"}],
                        "headline-md": ["32px", {"lineHeight": "1.2", "fontWeight": "600"}],
                        "headline-lg": ["48px", {"lineHeight": "1.1", "letterSpacing": "-0.02em", "fontWeight": "700"}],
                        "headline-lg-mobile": ["32px", {"lineHeight": "1.2", "fontWeight": "700"}],
                        "headline-sm": ["24px", {"lineHeight": "1.3", "fontWeight": "600"}]
                    }
                }
            }
        }
    </script>
<style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 300, 'GRAD' 0, 'opsz' 24;
            display: inline-block;
            vertical-align: middle;
        }
        .glass-card {
            background: rgba(251, 249, 248, 0.6);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.4);
        }
        .soft-glow {
            box-shadow: 0 10px 40px -15px rgba(12, 103, 128, 0.12);
        }
        .hover-bloom:hover {
            background-color: #87ceeb;
            color: white;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        ::-webkit-scrollbar-thumb {
            background: #e4e2e2;
            border-radius: 10px;
        }
    </style>
</head>
<body class="bg-background font-body-md text-on-background min-h-screen"><a href="/" class="left-margin-mobile md:left-margin-desktop z-50 group flex items-center gap-2 py-2 bg-surface/60 backdrop-blur-md border border-outline-variant/30 rounded-full text-on-surface-variant hover:text-primary hover:border-primary/40 hover:scale-105 transition-all soft-glow py-3 px-8 relative mt-8 w-fit">
    <span class="material-symbols-outlined text-[20px]">arrow_back</span>
    <span class="font-label-md text-label-md">Voltar</span>
</a>
<!-- Main Content Area -->
<main class="min-h-screen px-margin-mobile md:px-margin-desktop">
<div class="max-w-container-max mx-auto py-12 pt-16">
<!-- Page Header & Action Bar -->
<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-16">
<div>
<h2 class="font-headline-lg text-headline-lg text-primary tracking-tight mb-2">Gerenciamento de Usuários</h2>
<p class="text-on-surface-variant max-w-xl opacity-80">Gerencie sua equipe de artesãos e administradores da Aromas da Lari nesta interface etérea e funcional.</p>
</div>
<button class="group flex items-center gap-2 bg-primary text-on-primary px-8 py-4 rounded-full font-bold shadow-lg shadow-primary/10 hover:scale-[1.02] active:scale-95 transition-all">
<span class="material-symbols-outlined" data-weight="fill">add_circle</span>
<span class="">Adicionar Usuário</span>
</button>
</div>

<!-- Table Section -->
<div class="glass-card rounded-3xl overflow-hidden soft-glow border border-outline-variant/20">
<div class="overflow-x-auto">
<table class="w-full text-left border-collapse">
<thead>
<tr class="bg-surface-container-low/50">
<th class="px-8 py-5 font-headline-sm text-[18px] text-primary">ID</th>
<th class="px-8 py-5 font-headline-sm text-[18px] text-primary">Nome</th>
<th class="px-8 py-5 font-headline-sm text-[18px] text-primary text-right">Ações</th>
</tr>
</thead>
<tbody class="divide-y divide-outline-variant/10">

<?php foreach ($usuarios as $usu): ?>
<tr class="group hover:bg-primary-container/5 transition-colors">
    
    <td class="px-8 py-6 text-on-surface-variant font-medium">
        <?php echo $usu['id']; ?>
    </td>
    
    <td class="px-8 py-6">
        <div class="flex items-center gap-3">
            <div>
                <p class="font-bold text-on-surface">
                    <?php echo $usu['nome']; ?>
                </p>
            </div>
        </div>
    </td>
    
    <td class="px-8 py-6 text-right">
        <div class="flex items-center justify-end gap-2">
            <a href="usuario_update.php?id=<?php echo $usu['id']; ?>" class="p-2 text-on-surface-variant hover:text-primary hover:bg-primary-container/20 rounded-lg transition-all material-symbols-outlined">
                edit
            </a>
            <a href="usuario_delete.php?id=<?php echo $usu['id']; ?>" class="p-2 text-on-surface-variant hover:text-error hover:bg-error-container/20 rounded-lg transition-all material-symbols-outlined">
                delete
            </a>
        </div>
    </td>

</tr>
<?php endforeach; ?> </tbody>
</table>
</div>
</div>
</div>
</main>
<script>
        // Micro-interactions and Atmospheric Effects
        document.addEventListener('DOMContentLoaded', () => {
            // Subtle parallax effect on glass cards
            const cards = document.querySelectorAll('.glass-card');
            
            document.addEventListener('mousemove', (e) => {
                const { clientX, clientY } = e;
                
                cards.forEach(card => {
                    const rect = card.getBoundingClientRect();
                    const cardCenterX = rect.left + rect.width / 2;
                    const cardCenterY = rect.top + rect.height / 2;
                    
                    const moveX = (clientX - cardCenterX) / 100;
                    const moveY = (clientY - cardCenterY) / 100;
                    
                    card.style.transform = `translate(${moveX}px, ${moveY}px)`;
                });
            });

            // Smooth search focus interaction
            const searchInput = document.querySelector('input[type="text"]');
            if (searchInput) {
                searchInput.addEventListener('focus', () => {
                    searchInput.parentElement.classList.add('scale-[1.02]');
                });
                searchInput.addEventListener('blur', () => {
                    searchInput.parentElement.classList.remove('scale-[1.02]');
                });
            }
        });
    </script>


</body></html>