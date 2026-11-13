<?php

namespace App\Support;

class ContactLink
{
    public static function telephone(?string $number): ?string
    {
        if ($number === null || trim($number) === '') {
            return null;
        }

        return 'tel:'.preg_replace('/[^+0-9]/', '', $number);
    }

    public static function whatsapp(?string $number): ?string
    {
        if ($number === null || trim($number) === '') {
            return null;
        }

        $digits = preg_replace('/\D/', '', $number);
        $international = str_starts_with($digits, '0') ? '62'.substr($digits, 1) : $digits;

        return 'https://wa.me/'.$international;
    }
}
