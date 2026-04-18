<?= $this->extend('frontend/layouts/base') ?>

<?= $this->section('content') ?>
<main class="flex-1 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-12">
    <div class="lg:grid lg:grid-cols-3 lg:gap-8 xl:gap-12">
        <!-- Main Content -->
        <article class="lg:col-span-2">
            <!-- Breadcrumbs -->
            <?php if (isset($breadcrumb) && !empty($breadcrumb)): ?>
                <div class="flex flex-wrap gap-2 mb-4">
                    <?php foreach ($breadcrumb as $index => $crumb): ?>
                        <?php if ($index > 0): ?>
                            <span class="text-gray-400 dark:text-gray-500 text-sm font-medium">/</span>
                        <?php endif; ?>
                        
                        <?php if (!empty($crumb['url'])): ?>
                            <a class="text-primary/80 dark:text-accent/80 hover:text-primary dark:hover:text-accent text-sm font-medium" 
                               href="<?= $crumb['url'] ?>"><?= esc($crumb['title']) ?></a>
                        <?php else: ?>
                            <span class="text-text-light dark:text-text-dark text-sm font-medium truncate max-w-xs">
                                <?= esc($crumb['title']) ?>
                            </span>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- Article Header -->
            <div class="mb-6">
                <h1 class="text-text-light dark:text-text-dark text-3xl md:text-4xl font-black leading-tight tracking-tight">
                    <?= esc($berita['judul']) ?>
                </h1>
                <p class="text-gray-500 dark:text-gray-400 text-sm font-normal mt-3">
                    Dipublikasikan pada <?= date('d M Y', strtotime($berita['tanggal_publikasi'])) ?> 
                    oleh <?= esc($berita['penulis'] ?? 'Admin Desa') ?> • 
                    <span class="inline-block bg-accent/20 text-accent-800 dark:text-accent dark:bg-accent/10 px-2 py-0.5 rounded-full text-xs font-semibold ml-1">
                        <?= ucfirst($berita['kategori']) ?>
                    </span>
                </p>
            </div>

            <!-- Featured Image -->
            <?php if (!empty($berita['gambar'])): ?>
                <div class="mb-8">
                    <div class="w-full bg-center bg-no-repeat bg-cover flex flex-col justify-end overflow-hidden bg-gray-200 dark:bg-gray-700 rounded-xl min-h-60 md:min-h-80 shadow-lg" 
                         style="background-image: url('<?= base_url('uploads/berita/' . $berita['gambar']) ?>');">
                    </div>
                </div>
            <?php endif; ?>

            <!-- Article Content -->
            <div class="prose prose-lg dark:prose-invert max-w-none text-text-light dark:text-text-dark text-justify">
                <?= $berita['konten'] ?>
            </div>

            <hr class="my-8 border-border-light dark:border-border-dark"/>

            <!-- Social Share Buttons -->
            <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                <h3 class="text-base font-semibold text-text-light dark:text-text-dark">Bagikan artikel ini:</h3>
                <div class="flex items-center gap-2">
                    <a class="flex items-center justify-center size-10 rounded-full bg-card-light dark:bg-card-dark hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-300 transition-colors border border-border-light dark:border-border-dark" 
                       href="#" onclick="shareOnFacebook()">
                        <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path clip-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12Z" fill-rule="evenodd"/>
                        </svg>
                    </a>
                    <a class="flex items-center justify-center size-10 rounded-full bg-card-light dark:bg-card-dark hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-300 transition-colors border border-border-light dark:border-border-dark" 
                       href="#" onclick="shareOnTwitter()">
                        <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0 0 22 5.92a8.19 8.19 0 0 1-2.357.646 4.118 4.118 0 0 0 1.804-2.27 8.224 8.224 0 0 1-2.605.996 4.107 4.107 0 0 0-6.993 3.743 11.65 11.65 0 0 1-8.457-4.287 4.106 4.106 0 0 0 1.27 5.477A4.072 4.072 0 0 1 2.8 9.71v.052a4.105 4.105 0 0 0 3.292 4.022 4.095 4.095 0 0 1-1.853.07 4.108 4.108 0 0 0 3.834 2.85A8.233 8.233 0 0 1 2 18.407a11.616 11.616 0 0 0 6.29 1.84"/>
                        </svg>
                    </a>
                    <a class="flex items-center justify-center size-10 rounded-full bg-card-light dark:bg-card-dark hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-300 transition-colors border border-border-light dark:border-border-dark" 
                       href="#" onclick="shareOnWhatsApp()">
                        <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.570-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.465 3.488"/>
                        </svg>
                    </a>
                    <button class="flex items-center justify-center size-10 rounded-full bg-card-light dark:bg-card-dark hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-300 transition-colors border border-border-light dark:border-border-dark" 
                            onclick="copyToClipboard()">
                        <span class="material-symbols-outlined text-xl">link</span>
                    </button>
                </div>
            </div>

            <!-- Navigation to Previous/Next Articles -->
            <nav class="flex flex-col sm:flex-row justify-between gap-4 mt-8 pt-8 border-t border-border-light dark:border-border-dark">
                <div class="flex-1">
                    <!-- Previous article link would go here -->
                </div>
                <div class="flex-1 text-right">
                    <!-- Next article link would go here -->
                </div>
            </nav>
        </article>

        <!-- Sidebar -->
        <aside class="lg:col-span-1 mt-12 lg:mt-0">
            <div class="sticky top-24">
                <h3 class="text-xl font-bold text-text-light dark:text-text-dark mb-4 pb-2 border-b-2 border-primary">
                    Berita Terkait
                </h3>
                
                <?php if (!empty($beritaTerkait)): ?>
                    <div class="space-y-6">
                        <?php foreach ($beritaTerkait as $item): ?>
                            <a class="group flex gap-4" href="<?= base_url('berita/' . $item['slug']) ?>">
                                <img class="w-24 h-24 object-cover rounded-lg flex-shrink-0 bg-gray-200 dark:bg-gray-700" 
                                     src="<?= $item['gambar'] ? base_url('uploads/berita/' . $item['gambar']) : 'https://images.unsplash.com/photo-1586339949216-35c2747c0851?ixlib=rb-4.0.3&auto=format&fit=crop&w=200&q=80' ?>"
                                     alt="<?= esc($item['judul']) ?>" />
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        <?= date('d M Y', strtotime($item['tanggal_publikasi'])) ?>
                                    </p>
                                    <h4 class="font-semibold text-text-light dark:text-text-dark group-hover:text-primary dark:group-hover:text-accent mt-1 leading-tight">
                                        <?= esc($item['judul']) ?>
                                    </h4>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Tidak ada berita terkait.</p>
                <?php endif; ?>

                <!-- Back to Berita List -->
                <div class="mt-8">
                    <a class="inline-flex items-center gap-2 px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 transition-colors" 
                       href="<?= base_url('berita') ?>">
                        <span class="material-symbols-outlined">arrow_back</span>
                        Kembali ke Daftar Berita
                    </a>
                </div>
            </div>
        </aside>
    </div>
</main>

<script>
function shareOnFacebook() {
    const url = encodeURIComponent(window.location.href);
    const title = encodeURIComponent(document.title);
    window.open(`https://www.facebook.com/sharer/sharer.php?u=${url}&t=${title}`, '_blank', 'width=600,height=400');
}

function shareOnTwitter() {
    const url = encodeURIComponent(window.location.href);
    const title = encodeURIComponent(document.title);
    window.open(`https://twitter.com/intent/tweet?url=${url}&text=${title}`, '_blank', 'width=600,height=400');
}

function shareOnWhatsApp() {
    const url = encodeURIComponent(window.location.href);
    const title = encodeURIComponent(document.title);
    window.open(`https://wa.me/?text=${title} ${url}`, '_blank');
}

function copyToClipboard() {
    navigator.clipboard.writeText(window.location.href).then(function() {
        // Show a temporary notification
        const button = event.target.closest('button');
        const icon = button.querySelector('.material-symbols-outlined');
        const originalText = icon.textContent;
        
        icon.textContent = 'check';
        button.classList.add('bg-green-100', 'text-green-600');
        
        setTimeout(() => {
            icon.textContent = originalText;
            button.classList.remove('bg-green-100', 'text-green-600');
        }, 2000);
    });
}
</script>
<?= $this->endSection() ?>