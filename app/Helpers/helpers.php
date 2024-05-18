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
