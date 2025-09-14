<?php


if(!function_exists('formatRupiah')) {
    /**
     * Format a given number as Indonesian Rupiah.
     *
     * @param float|int $amount
     * @return string
     */
     function formatRupiah($amount){
        return 'Rp. ' . number_format($amount, 0, ',', '.');
     }
}

if(!function_exists('formatRupiahAngka')) {
    /**
     * Format a given number as Indonesian Rupiah.
     *
     * @param float|int $amount
     * @return string
     */
     function formatRupiahAngka($amount){
        return number_format($amount, 0, ',', '.');
     }
}

if (!function_exists('parseRupiah')) {
    /**
     * Convert formatted Rupiah string to float.
     *
     * Contoh: "Rp 2.000.000,50" -> 2000000.50
     */
    function parseRupiah($value)
    {
        if (!$value) return 0;

        // Hapus Rp, titik, spasi, dan ganti koma desimal jadi titik
        $clean = str_replace(['Rp', '.', ' '], '', $value);
        $clean = str_replace(',', '.', $clean);

        return (float) $clean;
    }
}
