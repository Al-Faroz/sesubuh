<?php
// File Induk untuk menyatukan Header, Sidebar, dan Footer

echo view('Modules\Infaq\Views\layout\v_header'); // Memanggil file v_header.php
echo view('Modules\Infaq\Views\layout\v_sidebar'); // Memanggil file v_sidebar.php
?>

<div class="content-wrapper">
    <?= $this->renderSection('content') ?>
</div>

<?php
echo view('Modules\Infaq\Views\layout\v_footer'); // Memanggil file v_footer.php
?>