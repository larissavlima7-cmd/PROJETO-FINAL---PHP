<?php
include "if_isset.php";

$sql= "SELECT * FROM produtos ORDER BY id ASC";

$stmt=$conexao->prepare($sql);
$stmt->execute();

$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html><html class="light" lang="pt-br"><head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<title>Gerenciamento de Produtos</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&amp;family=DM+Sans:wght@400;500;700&amp;display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet">
<script id="tailwind-config">
      tailwind.config = {
        darkMode: "class",
        theme: {
          extend: {
            "colors": {
                    "on-primary-fixed-variant": "#004d62",
                    "tertiary-container": "#c5c4c4",
                    "surface-container-high": "#eae8e7",
                    "tertiary-fixed": "#e4e2e2",
                    "on-tertiary": "#ffffff",
                    "on-error": "#ffffff",
                    "on-secondary-fixed-variant": "#613d3e",
                    "error-container": "#ffdad6",
                    "primary-container": "#87ceeb",
                    "on-secondary-container": "#7a5354",
                    "secondary-fixed": "#ffdad9",
                    "outline-variant": "#bfc8cd",
                    "outline": "#6f787d",
                    "on-primary-container": "#005870",
                    "tertiary-fixed-dim": "#c7c6c6",
                    "surface-tint": "#0c6780",
                    "surface-container-low": "#f5f3f3",
                    "secondary-container": "#fecbcb",
                    "on-surface-variant": "#3f484c",
                    "on-primary": "#ffffff",
                    "surface-variant": "#e4e2e2",
                    "on-surface": "#1b1c1c",
                    "on-secondary": "#ffffff",
                    "tertiary": "#5e5e5f",
                    "surface-bright": "#fbf9f8",
                    "primary-fixed": "#baeaff",
                    "primary": "#0c6780",
                    "surface-container-highest": "#e4e2e2",
                    "surface-container": "#efeded",
                    "secondary": "#7b5455",
                    "on-tertiary-fixed-variant": "#464747",
                    "inverse-surface": "#303030",
                    "inverse-on-surface": "#f2f0f0",
                    "surface": "#fbf9f8",
                    "secondary-fixed-dim": "#ecbaba",
                    "on-secondary-fixed": "#2f1314",
                    "surface-dim": "#dbd9d9",
                    "on-tertiary-container": "#515151",
                    "on-error-container": "#93000a",
                    "on-background": "#1b1c1c",
                    "error": "#ba1a1a",
                    "on-primary-fixed": "#001f29",
                    "background": "#fbf9f8",
                    "inverse-primary": "#89d0ed",
                    "surface-container-lowest": "#ffffff",
                    "on-tertiary-fixed": "#1b1c1c",
                    "primary-fixed-dim": "#89d0ed"
            },
            "borderRadius": {
                    "DEFAULT": "0.25rem",
                    "lg": "0.5rem",
                    "xl": "0.75rem",
                    "full": "9999px"
            },
            "spacing": {
                    "unit": "8px",
                    "margin-mobile": "20px",
                    "margin-desktop": "64px",
                    "container-max": "1200px",
                    "gutter": "24px"
            },
            "fontFamily": {
                    "label-sm": ["DM Sans"],
                    "headline-sm": ["Playfair Display"],
                    "body-lg": ["DM Sans"],
                    "label-md": ["DM Sans"],
                    "headline-md": ["Playfair Display"],
                    "headline-lg-mobile": ["Playfair Display"],
                    "headline-lg": ["Playfair Display"],
                    "body-md": ["DM Sans"]
            },
            "fontSize": {
                    "label-sm": ["12px", {"lineHeight": "1.4", "fontWeight": "500"}],
                    "headline-sm": ["24px", {"lineHeight": "1.3", "fontWeight": "600"}],
                    "body-lg": ["18px", {"lineHeight": "1.6", "fontWeight": "400"}],
                    "label-md": ["14px", {"lineHeight": "1.4", "letterSpacing": "0.05em", "fontWeight": "500"}],
                    "headline-md": ["32px", {"lineHeight": "1.2", "fontWeight": "600"}],
                    "headline-lg-mobile": ["32px", {"lineHeight": "1.2", "fontWeight": "700"}],
                    "headline-lg": ["48px", {"lineHeight": "1.1", "letterSpacing": "-0.02em", "fontWeight": "700"}],
                    "body-md": ["16px", {"lineHeight": "1.5", "fontWeight": "400"}]
            }
          },
        },
      }
    </script>
<style>
        .glass-card {
            background: rgba(255, 255, 255, 0.4);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            transition: all 0.3s ease-in-out;
        }
        .glass-card:hover {
            box-shadow: 0 20px 40px rgba(135, 206, 235, 0.15);
            transform: translateY(-2px);
        }
        .card-image-container::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(0,0,0,0.05), transparent);
            pointer-events: none;
        }
    </style>
</head>
<body class="bg-background text-on-surface font-body-md selection:bg-primary-container selection:text-on-primary-container">
    <?php include "header.php"?>
<!-- <main class="pt-20 pb-24 px-margin-desktop max-w-container-max mx-auto min-h-screen">
<div class="mb-12 flex items-center justify-between">
<button class="group flex items-center gap-3 text-on-surface-variant hover:text-primary transition-colors">
<div class="w-10 h-10 rounded-full glass-card flex items-center justify-center group-hover:bg-primary-container group-hover:text-on-primary-container">
<span class="material-symbols-outlined text-[20px]">arrow_back</span>
</div>
<a href="home.php">
    <span class="font-label-md text-label-md tracking-wider">VOLTAR</span>
</a>
</button> -->
<a href="produto_create.php">
    <button class="bg-primary text-on-primary px-8 py-3 rounded-full font-label-md text-label-md hover:opacity-90 shadow-lg shadow-primary/20 transition-all flex items-center gap-2">
        <span class="material-symbols-outlined text-[20px]">add</span>
                ADICIONAR PRODUTO
    </button>
</a>
</div>
<!-- Page Header -->
<div class="mb-16">
<h1 class="font-headline-lg text-headline-lg text-on-surface mb-4">Gerenciamento de Produtos</h1>
<p class="text-body-lg font-body-lg text-on-surface-variant max-w-2xl">
            Controle o catálogo de essências e aromas. Edite preços, gerencie marcas e organize seu estoque com a delicadeza de uma brisa matinal.
        </p>
</div>
<!-- Content Area: Grid Layout -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-gutter">
<?php if (empty($produtos)): ?>
    <div class="col-span-full text-center py-12 text-on-surface-variant">
        Nenhum produto cadastrado no momento.
    </div>
<?php else: ?>
    <?php foreach ($produtos as $produto): ?>
        <div class="glass-card rounded-3xl overflow-hidden group">
            <div class="aspect-square relative overflow-hidden card-image-container">
                <img src="<?php echo $produto['imagem']; ?>"> 
                <div class="absolute top-4 right-4 flex flex-col gap-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    <a href="produto_update.php?id=<?php echo $produto['id']; ?>" class="w-9 h-9 rounded-full bg-white/90 text-primary flex items-center justify-center hover:bg-primary hover:text-white shadow-sm transition-colors">
                        <span class="material-symbols-outlined text-[18px]">edit</span>
                    </a>
                    <button data-id="<?php echo $produto['id']; ?>" data-nome="<?php echo $produto['nome']; ?>" data-marca="<?php echo $produto['marca'];?>"
                        data-preco="<?php echo $produto['preco'];?>" data-quant="<?php echo $produto['quant_estoque'];?>" data-foto="<?php echo $produto['imagem'];?>" class="delete-trigger p-2 text-on-surface-variant hover:text-error hover:bg-error-container/20 rounded-lg transition-all material-symbols-outlined">
                        delete
                    </button>
                </div>
            </div>
            <div class="p-6">
                <h3 class="font-headline-sm text-[20px] text-on-surface mb-1 truncate">
                    <?php echo htmlspecialchars($produto['nome']); ?>
                </h3>
                <p class="text-label-md text-on-surface-variant mb-4">
                    <?php echo htmlspecialchars($produto['marca'] ?? 'Marca Própria'); ?>
                </p>
                <p class="text-label-md text-on-surface-variant mb-4">
                    <?php echo ($produto['quant_estoque'] <= 0) ? 'Indisponível' : $produto['quant_estoque'] . ' no Estoque.'; ?>
                </p>
                <div class="flex items-center justify-between">
                    <span class="text-body-lg font-bold text-primary">
                        R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?>
                    </span>
                    <span class="text-label-sm text-outline">
                        <?php echo str_pad($produto['id'], 2, '0', STR_PAD_LEFT); ?>
                    </span>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
</div>
<div class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4" id="delete-modal">
<div class="absolute inset-0 bg-on-surface/20 backdrop-blur-md" id="modal-overlay"></div>
<div class="glass-card rounded-3xl p-8 max-w-md w-full relative z-10 shadow-xl border border-outline-variant/30 bg-white/90">
<h3 class="font-headline-sm text-headline-sm text-primary mb-4">Confirmação de Exclusão</h3>
<p class="text-on-surface-variant mb-8 opacity-80">Você tem certeza que deseja excluir o produto <strong id="modal-product-name" class="text-on-surface"></strong>? Esta ação não poderá ser desfeita.</p>
<div class="flex gap-4 justify-end">
<button class="px-6 py-3 rounded-full font-medium text-on-surface-variant hover:bg-surface-container-high transition-all" id="close-modal-btn">Cancelar</button>
<a id="confirm-delete-btn" href="#" class="px-6 py-3 bg-error text-white rounded-full font-bold shadow-lg shadow-error/10 hover:scale-[1.02] active:scale-95 transition-all text-center">Excluir</a>
</div>
</div>
</div>

</main>
<footer class="bg-background w-full py-12 border-t border-outline-variant/10">
<div class="flex flex-col items-center justify-center gap-4 max-w-container-max mx-auto">
<span class="font-headline-sm text-headline-sm text-primary">Aromas da Lari</span>
<div class="flex gap-8 text-on-surface-variant font-label-md text-label-md">
<a class="hover:text-primary transition-colors" href="#">Support</a>
<a class="hover:text-primary transition-colors" href="#">Privacy</a>
<a class="hover:text-primary transition-colors" href="#">System Status</a>
</div>
<p class="text-on-surface-variant font-label-md text-label-md opacity-60">© 2026 Aura Perfumery. All rights reserved.</p>
</div>
</footer>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const deleteModal = document.getElementById('delete-modal');
        const deleteTriggers = document.querySelectorAll('.delete-trigger');
        const closeModalBtn = document.getElementById('close-modal-btn');
        const modalOverlay = document.getElementById('modal-overlay');
        const modalProductName = document.getElementById('modal-product-name');
        const confirmDeleteBtn = document.getElementById('confirm-delete-btn');

        const showModal = (id, nome) => {
            modalProductName.textContent = nome;
            // Aponta para o arquivo de exclusão de produtos
            confirmDeleteBtn.href = `produto_delete.php?id=${id}`;
            deleteModal.classList.remove('hidden');
            document.body.style.overflow = 'hidden'; 
        };

        const hideModal = () => {
            deleteModal.classList.add('hidden');
            document.body.style.overflow = 'auto'; 
        };

        deleteTriggers.forEach(trigger => {
            trigger.addEventListener('click', (e) => {
                e.preventDefault();
                const id = trigger.getAttribute('data-id');
                const nome = trigger.getAttribute('data-nome');
                showModal(id, nome);
            });
        });

        if (closeModalBtn) closeModalBtn.addEventListener('click', hideModal);
        if (modalOverlay) modalOverlay.addEventListener('click', hideModal);

        // Micro-interactions back button
        const backBtn = document.querySelector('.group');
        if (backBtn) {
            backBtn.addEventListener('click', () => {
                document.body.style.opacity = '0';
                document.body.style.transition = 'opacity 0.3s ease';
                setTimeout(() => {
                    window.history.back();
                }, 300);
            });
        }
    });
</script>


</body></html>