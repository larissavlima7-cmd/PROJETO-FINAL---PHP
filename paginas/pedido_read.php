<?php
include "if_isset.php";
$sql="SELECT * FROM pedidos ORDER BY id ASC";

//stmt = statement refere-se a um objeto PDO Statement no contexto do PDO
$stmt=$conexao->prepare($sql);
$stmt->execute();

$pedido= $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html><html class="light" lang="pt-br" style=""><head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<title>Pedidos | Aromas da Lari</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&amp;family=DM+Sans:wght@400;500;700&amp;display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet">
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
        body {
            background-color: #fbf9f8;
            -webkit-font-smoothing: antialiased;
        }
        .glass-header {
            backdrop-filter: blur(12px);
            border-bottom: 0.5px solid rgba(12, 103, 128, 0.1);
        }
        .hover-glow:hover {
            box-shadow: 0 0 30px rgba(135, 206, 235, 0.15);
        }
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #e4e2e2;
            border-radius: 10px;
        }
        .fade-in {
            animation: fadeIn 0.8s ease-out forwards;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="font-body-md text-on-surface">
<?php include "header.php"?>
<!-- Page Content Container -->
<main class="max-w-container-max mx-auto w-full px-6 md:px-margin-desktop pt-12 pb-8 fade-in h-auto">
<a href="pedido_create.php">
    <button class="w-full md:w-auto px-8 py-3 bg-primary text-on-primary rounded-full font-label-md text-label-md hover:bg-on-primary-container transition-all duration-300 flex items-center justify-center gap-2 hover-glow active:scale-95">
    <span class="material-symbols-outlined text-[20px]">add</span>
                    Cadastrar Pedido
    </button>
</a>
</div>
<!-- Orders Table Card -->
<div class="bg-white rounded-xl overflow-hidden border border-outline-variant/20 shadow-sm">
<div class="custom-scrollbar">
<table class="w-full text-left border-collapse">
<thead>
<tr class="bg-surface-container-low/50">
<th class="px-8 py-5 font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">ID</th>
<th class="px-8 py-5 font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Cliente</th>
<th class="px-8 py-5 font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Usuário</th>
<th class="px-8 py-5 font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Produto</th>
<th class="px-8 py-5 font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Quantidade</th>
<th class="px-8 py-5 font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Status</th>
<th class="px-8 py-5 font-label-md text-label-md text-on-surface-variant uppercase tracking-wider text-right">Ações</th>
</tr>
</thead>
<tbody class="divide-y divide-outline-variant/10">

<?php foreach ($pedido as $ped): ?>
<tr class="group hover:bg-primary-container/5 transition-colors">
    
    <td class="px-8 py-6 text-on-surface-variant font-medium">
        <?php echo $ped['id']; ?>
    </td>
    
    <td class="px-8 py-6">
        <div class="flex items-center gap-3">
            <div>
                <p class="font-bold text-on-surface">
                    <?php echo $ped['idclientes']; ?>
                </p>
            </div>
        </div>
    </td>
    <td class="px-8 py-6">
        <div class="flex items-center gap-3">
            <div>
                <p class="font-bold text-on-surface">
                    <?php echo $ped['idusuarios']; ?>
                </p>
            </div>
        </div>
    </td>
    <td class="px-8 py-6">
        <div class="flex items-center gap-3">
            <div>
                <p class="font-bold text-on-surface">
                    <?php echo $ped['idprodutos']; ?>
                </p>
            </div>
        </div>
    </td>
    <td class="px-8 py-6">
        <div class="flex items-center gap-3">
            <div>
                <p class="font-bold text-on-surface">
                    <?php echo $ped['quantidade']; ?>
                </p>
            </div>
        </div>
    </td>
    <td class="px-8 py-6">
        <div class="flex items-center gap-3">
            <div>
                <p class="font-bold text-on-surface">
                    <?php echo $ped['idstatus']; ?>
                </p>
            </div>
        </div>
    </td>
    
    <td class="px-8 py-6 text-right">
        <div class="flex items-center justify-end gap-2">
            <a href="pedido_update.php?id=<?php echo $ped['id']; ?>" class="p-2 text-on-surface-variant hover:text-primary hover:bg-primary-container/20 rounded-lg transition-all material-symbols-outlined">
                edit
            </a>
            <button data-id="<?php echo $ped['id']; ?>" data-idclientes="<?php echo $ped['idclientes']; ?>" data-isusuarios="<?php echo $ped['idusuarios'];?>"
             data-idprodutos="<?php echo $ped['idprodutos'];?>" data-quantidade="<?php echo $ped['quantidade'];?>" data-status="<?php echo $ped['idstatus'];?>" class="delete-trigger p-2 text-on-surface-variant hover:text-error hover:bg-error-container/20 rounded-lg transition-all material-symbols-outlined">
                delete
            </button>
        </div>
    </td>

</tr>
<?php endforeach; ?>
</tbody>
</table>
</div>
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
<!-- Footer Component -->
<footer class="w-full bg-surface-container-low border-t border-secondary-container/50">
<div class="max-w-container-max mx-auto px-6 md:px-margin-desktop py-12 flex flex-col md:flex-row justify-between items-center gap-gutter">
<div class="flex flex-col items-center md:items-start gap-2">
<div class="font-headline-sm text-headline-sm text-secondary">Aromas da Lari</div>
<p class="font-body-md text-body-md text-on-secondary-fixed-variant/70 text-center md:text-left">© 2024 Aromas da Lari. Crafted for the senses.</p>
</div>
<div class="flex gap-8">
<a class="font-body-md text-body-md text-on-secondary-fixed-variant/70 hover:text-secondary transition-colors duration-200" href="#">Scent Care</a>
<a class="font-body-md text-body-md text-on-secondary-fixed-variant/70 hover:text-secondary transition-colors duration-200" href="#">Privacy Policy</a>
<a class="font-body-md text-body-md text-on-secondary-fixed-variant/70 hover:text-secondary transition-colors duration-200" href="#">Terms of Service</a>
</div>
</div>
</footer>
<!-- Background Decorative Gradients -->


<script>
         document.addEventListener('DOMContentLoaded', () => {
        const deleteModal = document.getElementById('delete-modal');
        const deleteTriggers = document.querySelectorAll('.delete-trigger');
        const closeModalBtn = document.getElementById('close-modal-btn');
        const modalOverlay = document.getElementById('modal-overlay');
        const modalProductName = document.getElementById('modal-product-name');
        const confirmDeleteBtn = document.getElementById('confirm-delete-btn');

        const showModal = (id, idclientes) => {
            modalProductName.textContent = idclientes;
            // Aponta para o arquivo de exclusão de produtos
            confirmDeleteBtn.href = `pedido_delete.php?id=${id}`;
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
                const idclientes= trigger.getAttribute('data-iclientes');
                showModal(id, idclientes);
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