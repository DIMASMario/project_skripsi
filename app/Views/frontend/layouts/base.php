<!DOCTYPE html>
<html class="light" lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title><?= $title ?? 'Website Desa Blanakan' ?></title>
    <meta name="description" content="<?= $meta_description ?? 'Portal digital Desa Blanakan - Layanan online, informasi terkini, dan transparansi pemerintahan desa.' ?>"/>
    <meta name="keywords" content="desa blanakan, pelayanan digital, surat online, pemerintah desa"/>
    <meta name="robots" content="index, follow"/>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="32x32" href="<?= base_url('favicon.png') ?>">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= base_url('favicon.png') ?>">
    <link rel="shortcut icon" href="<?= base_url('favicon.ico') ?>">
    <link rel="apple-touch-icon" sizes="180x180" href="<?= base_url('images/carousel/logo.png') ?>">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
        }
    </script>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"/>
    
    <!-- Main CSS -->
    <link href="<?= base_url('css/style.css') ?>" rel="stylesheet">
    <link href="<?= base_url('css/components/frontend.css') ?>" rel="stylesheet">
    
    <!-- Layout specific styles moved to external CSS -->
    
    <?= $this->renderSection('styles') ?>
</head>
<body class="bg-background-light dark:bg-background-dark font-display text-text-light dark:text-text-dark">
    
    <div x-data @keydown.escape.window="$store.modal && $store.modal.closeLogin()" class="relative w-full overflow-x-hidden">
        
        <!-- Main Navbar (Always Present) -->
        <?= $this->include('frontend/components/navbar') ?>
        
        <!-- Optional Header Extra Section -->
        <?= $this->renderSection('header-extra') ?>
        
        <!-- Main Content -->
        <main>
            <?= $this->renderSection('content') ?>
        </main>
        
        <!-- Footer -->
        <?= $this->include('frontend/components/footer') ?>
        
        <!-- Login Modal -->
        <?= $this->include('frontend/components/login-modal') ?>
    </div>

    <!-- Set base URL for JavaScript -->
    <script>
        window.BASE_URL = '<?= base_url() ?>';
    </script>

    <!-- User role data for JavaScript -->
    <?php if (session()->get('logged_in')): ?>
    <div style="display: none;" data-user-role="<?= session()->get('role') ?>"></div>
    <?php endif; ?>

    <!-- Alpine.js - LOAD FIRST before other scripts -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    
    <!-- Initialize Alpine Store IMMEDIATELY after Alpine -->
    <script>
        // Wait for Alpine to be available
        document.addEventListener('alpine:init', () => {
            console.log('🔵 Alpine:init event fired');
            
            // Create modal store
            Alpine.store('modal', {
                loginModalOpen: false,
                
                openLogin() {
                    console.log('🟢 Opening login modal');
                    this.loginModalOpen = true;
                },
                
                closeLogin() {
                    console.log('🟢 Closing login modal');
                    this.loginModalOpen = false;
                }
            });
            
            console.log('🔵 Modal store created:', Alpine.store('modal'));
        });
        
        // Confirm Alpine is fully ready
        document.addEventListener('alpine:initialized', () => {
            console.log('✅ Alpine FULLY READY');
            console.log('✅ Store available:', typeof Alpine !== 'undefined' && Alpine.store('modal'));
        });
    </script>
    
    <!-- Remove Outline Script -->
    <script src="<?= base_url('js/remove-outline.js') ?>"></script>
    
    <!-- Main JavaScript - Load AFTER Alpine -->
    <script src="<?= base_url('js/app.js') ?>" defer></script>
    <script src="<?= base_url('js/components/frontend.js') ?>" defer></script>
    
    <?= $this->renderSection('scripts') ?>
</body>
</html>