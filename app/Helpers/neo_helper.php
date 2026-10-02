<?php

if (! function_exists('neo_asset')) {
    /**
     * URL aset dengan nomor versi otomatis (waktu ubah file), supaya browser
     * selalu mengambil CSS/JS terbaru setelah ada pembaruan.
     */
    function neo_asset(string $path): string
    {
        $file = FCPATH . 'public' . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $path);

        return base_url('public/assets/' . $path) . (is_file($file) ? '?v=' . filemtime($file) : '');
    }
}

if (! function_exists('logo_pdf_uri')) {
    /** Logo sekolah sebagai data URI untuk kop laporan PDF (mPDF). */
    function logo_pdf_uri(): string
    {
        $file = FCPATH . 'public' . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'logo-pdf.png';

        return is_file($file) ? 'data:image/png;base64,' . base64_encode(file_get_contents($file)) : '';
    }
}
