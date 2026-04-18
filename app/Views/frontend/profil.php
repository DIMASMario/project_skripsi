<?= $this->extend('frontend/layouts/base') ?>

<?= $this->section('content') ?>
<div class="container mx-auto max-w-4xl px-4 py-8 sm:py-12">
<!-- Breadcrumbs -->
<div class="flex flex-wrap gap-2 mb-4">
<a class="text-subtle-text-light dark:text-subtle-text-dark hover:text-primary dark:hover:text-accent text-sm font-medium" href="<?= base_url() ?>">Beranda</a>
<span class="text-subtle-text-light dark:text-subtle-text-dark text-sm font-medium">/</span>
<span class="text-text-light dark:text-text-dark text-sm font-medium">Profil Desa</span>
</div>
<!-- PageHeading -->
<div class="mb-10">
<h1 class="text-primary dark:text-accent text-4xl font-black tracking-tight sm:text-5xl">Profil Desa Blanakan</h1>
<p class="mt-3 text-lg text-subtle-text-light dark:text-subtle-text-dark">Kenali lebih dalam tentang sejarah, visi &amp; misi, serta struktur pemerintahan Desa Blanakan.</p>
</div>
<div class="space-y-12">
<!-- Section: Sejarah Desa -->
<section class="rounded-lg bg-card-light dark:bg-card-dark shadow-md overflow-hidden" id="sejarah">
<div class="p-6">
<h2 class="text-2xl font-bold text-text-light dark:text-text-dark mb-4">Sejarah Desa</h2>
<p class="text-subtle-text-light dark:text-subtle-text-dark text-base leading-relaxed">
Desa Blanakan memiliki sejarah panjang yang berakar dari tradisi pesisir dan agraris. Berawal dari sebuah perkampungan nelayan kecil, desa ini berkembang pesat seiring dengan pembukaan lahan pertanian yang subur. Nama "Blanakan" diyakini berasal dari kata lokal yang berarti muara pertemuan air tawar dan air laut, mencerminkan letak geografisnya yang unik. Dari masa ke masa, Desa Blanakan terus bertransformasi menjadi pusat kegiatan ekonomi dan sosial yang dinamis bagi warganya, sambil tetap menjaga kearifan lokal yang diwariskan turun-temurun.
</p>
</div>
</section>
<!-- Section: Visi & Misi -->
<section class="rounded-lg bg-card-light dark:bg-card-dark shadow-md overflow-hidden" id="visi-misi">
<div class="p-6">
<h2 class="text-2xl font-bold text-text-light dark:text-text-dark mb-6">Visi &amp; Misi</h2>
<div class="space-y-6">
<div>
<h3 class="text-xl font-semibold text-primary dark:text-accent mb-2">Visi</h3>
<p class="text-subtle-text-light dark:text-subtle-text-dark text-base italic">"Terwujudnya Desa Blanakan yang Maju, Mandiri, Sejahtera, dan Berbudaya Berlandaskan Iman dan Taqwa."</p>
</div>
<div>
<h3 class="text-xl font-semibold text-primary dark:text-accent mb-3">Misi</h3>
<ul class="space-y-2 list-disc list-inside text-subtle-text-light dark:text-subtle-text-dark">
<?php 
$misi_list = $profil_data['misi'] ?? [
    'Meningkatkan kualitas sumber daya manusia melalui pendidikan dan kesehatan.',
    'Mengembangkan potensi ekonomi desa berbasis agraris dan kelautan.',
    'Meningkatkan kualitas infrastruktur dan pelayanan publik yang prima.',
    'Menciptakan tata kelola pemerintahan desa yang bersih, transparan, dan akuntabel.',
    'Melestarikan dan mengembangkan nilai-nilai budaya dan kearifan lokal.'
];
foreach($misi_list as $misi_item): 
?>
<li><?= esc($misi_item) ?></li>
<?php endforeach; ?>
</ul>
</div>
</div>
</div>
</section>
<!-- Section: Struktur Organisasi -->
<section class="rounded-lg bg-card-light dark:bg-card-dark shadow-md overflow-hidden" id="struktur-organisasi">
<div class="p-6">
<h2 class="text-2xl font-bold text-text-light dark:text-text-dark mb-4">Struktur Organisasi</h2>
<p class="text-subtle-text-light dark:text-subtle-text-dark mb-6">Struktur Organisasi Pemerintahan Desa Blanakan periode 2024-2029.</p>
<div class="bg-background-light dark:bg-background-dark p-4 rounded-lg border border-gray-200 dark:border-gray-700">
<a class="block cursor-pointer" href="#">
<img alt="Diagram alur struktur organisasi pemerintahan Desa Blanakan" class="w-full h-auto object-cover rounded" data-alt="Diagram alur struktur organisasi pemerintahan Desa Blanakan" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAOAX2rGlZTZz82BPxdSzlwk5ggcj_LLBSbzLlqZUEWlvB7kKpQcd6IBDXMBvyX1ZThp77kvyxcbqZpbPUZsG-awvHU4-mJro3rGqCTxJEN_XbecdOUbEoIuMvfn2bakUYw_WBYzdne9Ifc44MSF3XgWp8r5cBop-vM-5pxpuvwTd9dlJbKnS5TP9rcPjBGadLoJt_Px8L4F28PXW-qLtFK4fyIt9RI5maaI0CXaemFZfpRAs3MMtzZumywWKYeoJrN0rdeKbmkDlY4"/>
</a>
</div>
</div>
</section>
<!-- Section: Perangkat Desa -->
<section id="perangkat-desa">
<div class="text-center mb-8">
<h2 class="text-3xl font-bold text-text-light dark:text-text-dark">Kenali Perangkat Desa Kami</h2>
<p class="text-subtle-text-light dark:text-subtle-text-dark mt-2">Tim yang berdedikasi untuk melayani masyarakat Desa Blanakan.</p>
</div>
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
<!-- Profile Card -->
<div class="bg-card-light dark:bg-card-dark rounded-lg shadow-md text-center p-6 transform transition-transform duration-300 hover:-translate-y-1">
<img alt="Foto Kepala Desa" class="w-28 h-28 mx-auto rounded-full object-cover mb-4 border-4 border-primary/20" data-alt="Foto Kepala Desa" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCJB_r5yKm4WwONGlM_uDncofYijRJWPw0UsU5SdIVr5VTo3O89PMl2yHUFPy5eOqXaCWDsrLVQ_evS1MpE70-4cfkyqzm7WmNq-CsPjvCOgA7mkThAGxNCtzb4_QEGLeBENV_YDPyTQcXjBy73SGuHxTzx6Twh5QyboncbFfGxc7XeX7CEljm3_Vsr9z7X_43jMqfWDwsERuYG4A1bfjKNBcgsTYUmBw5Hh5SWbv0hVW3lg5CcYkIgOj6NuJJ1AJU42R-ye1BkxP5o"/>
<h3 class="font-bold text-lg text-text-light dark:text-text-dark">Hj. Siti Aisyah, S.Pd.</h3>
<p class="text-primary dark:text-accent font-semibold text-sm">Kepala Desa</p>
</div>
<!-- Profile Card -->
<div class="bg-card-light dark:bg-card-dark rounded-lg shadow-md text-center p-6 transform transition-transform duration-300 hover:-translate-y-1">
<img alt="Foto Sekretaris Desa" class="w-28 h-28 mx-auto rounded-full object-cover mb-4 border-4 border-primary/20" data-alt="Foto Sekretaris Desa" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCDq5nAkWbySA4bota2bbbX-kAuEzrjbfbd2vgUKGZuHn_TSFwGqaBStzMY50xHbsEd1wGnA1NQyeQC0xZ6CJ2vCbSCTQ14-9K-medkwVd-67zAMNlSdyiYveSwgehS5RIqkvLhAASIV15T79I8YEkZOhfH4bki8-8ZLlLBZFymUJDY_BZNCIERkzOc3v4kTKp7DbvJMpzitKnGI_GJCwRVpv570Bp0Xgd1BeYaKFyoso10hBdCg58ojkET-4YCCZxjHt3uNdTlR_iN"/>
<h3 class="font-bold text-lg text-text-light dark:text-text-dark">Ahmad Budi Santoso, S.Kom.</h3>
<p class="text-primary dark:text-accent font-semibold text-sm">Sekretaris Desa</p>
</div>
<!-- Profile Card -->
<div class="bg-card-light dark:bg-card-dark rounded-lg shadow-md text-center p-6 transform transition-transform duration-300 hover:-translate-y-1">
<img alt="Foto Kaur Keuangan" class="w-28 h-28 mx-auto rounded-full object-cover mb-4 border-4 border-primary/20" data-alt="Foto Kaur Keuangan" src="https://lh3.googleusercontent.com/aida-public/AB6AXuARyHBZhqaVWRdidGYvZ0QyjQEnEXAiJaX3iWQ_FFBYop60OtvT-_3vz6186QGVB024WFQwPfQ-QOGQ081E9ysRQXTMn_ttVs_S61gl9YFwVnzD8lqslZRzAH3W6lj61lJ8EAlvBDU8U7cHE7Orqa_GrcH7JZEZwoOOyWgkKtGW6LumxuQMPbsQ9yodCfnSOYYh-u-WufMCEYM9sIntmDhUoqjmaTTD_mdFdjirvlt8WnwY2Itayd01zU-H0J4hA-pYTNygWE9Eu82z"/>
<h3 class="font-bold text-lg text-text-light dark:text-text-dark">Joko Susilo</h3>
<p class="text-primary dark:text-accent font-semibold text-sm">Kaur Keuangan</p>
</div>
<!-- Profile Card -->
<div class="bg-card-light dark:bg-card-dark rounded-lg shadow-md text-center p-6 transform transition-transform duration-300 hover:-translate-y-1">
<img alt="Foto Kaur Umum" class="w-28 h-28 mx-auto rounded-full object-cover mb-4 border-4 border-primary/20" data-alt="Foto Kaur Umum" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDXlC2Unm0bMAbyenbSGjYgjMlucnAjZV_Jo3Fz9LagViVtDs1J9b2YVUgDKXb20ts2oauZ30itSI9yFaTy0l8mIcgTNqzVdAEgdQyG-HnjifXdGdfIFHeIzUt5Ts6BNKs9kH3LnExW0_eNQZbD_K6LIf-MuM7mygP63x91UvauMS5PcOqZ4u3b7phXoEm6D7JdvUlfLD-WCkWQ_v_-CNvx-eey6sjFUJtIXYHtUhk8TzcoF-MRUKRwn7yBv8L57AoxrKrT46G6LYTc"/>
<h3 class="font-bold text-lg text-text-light dark:text-text-dark">Bambang Irawan</h3>
<p class="text-primary dark:text-accent font-semibold text-sm">Kaur Umum &amp; Perencanaan</p>
</div>
<!-- Profile Card -->
<div class="bg-card-light dark:bg-card-dark rounded-lg shadow-md text-center p-6 transform transition-transform duration-300 hover:-translate-y-1">
<img alt="Foto Kasi Pemerintahan" class="w-28 h-28 mx-auto rounded-full object-cover mb-4 border-4 border-primary/20" data-alt="Foto Kasi Pemerintahan" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBEfG-KOMNiTkmr45_6stWJcs3zSdw29AunQg4nk5illryzhnEcOnS8AfiSVLUm2HK-PnqYkm8CSXW37cEV4G8jJXshmDVVR435PIBQvinbeREO8PGAPQfmaz1skZ5KMmTpdFyRPjfxflp6SsDE7MSINXp1JfKXwB1-bkpLQtEPEw_yaWuaEmwcF7uykIi51Uht9qrsZkfSATNjT_Mh4zQqKccN_yAe9h-v1nCTkg9iHn_gIwTGAPa67yMwOSUsIm392a3PSt03XFIT"/>
<h3 class="font-bold text-lg text-text-light dark:text-text-dark">Dewi Lestari, S.IP.</h3>
<p class="text-primary dark:text-accent font-semibold text-sm">Kasi Pemerintahan</p>
</div>
<!-- Profile Card -->
<div class="bg-card-light dark:bg-card-dark rounded-lg shadow-md text-center p-6 transform transition-transform duration-300 hover:-translate-y-1">
<img alt="Foto Kasi Kesejahteraan" class="w-28 h-28 mx-auto rounded-full object-cover mb-4 border-4 border-primary/20" data-alt="Foto Kasi Kesejahteraan" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCdsFhJbnp90WVZVpGWuGP156R3060bnTKiW0GbBgccXyTtYwRG7zMKVqJeb2uT5d3ikbxzF5jq-JqI0p80-60LH-J2oefjZnGeVfFqS4-jBILUQv-mrqEe9MmJTD5bEeqy5N8aMctVYSX2SzosEvf5KXc4DKEl1i0zCfE8meopELQd6Bys6r2gaxSXbKAIKPkmx0HkWWbq0BUA_9T5_uAHe64_jVho0FHLURY6TlfoOo96khpKBNJwPKWi9x-NdqhrLJT1Mg_OeJp"/>
<h3 class="font-bold text-lg text-text-light dark:text-text-dark">Rina Anggraini</h3>
<p class="text-primary dark:text-accent font-semibold text-sm">Kasi Kesejahteraan</p>
</div>
</div>
</section>
</div>
</div>
<?= $this->endSection() ?>