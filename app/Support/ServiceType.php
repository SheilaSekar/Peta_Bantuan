<?php

namespace App\Support;

enum ServiceType: string
{
    case Uptd = 'uptd_ppa';
    case Lbh = 'lbh';
    case Shelter = 'rumah_aman';
    case Hospital = 'rs_pppt';
    case Police = 'unit_ppa_polres';
    case Community = 'lembaga_komunitas';

    public function label(): string
    {
        return match ($this) {
            self::Uptd => 'Layanan resmi',self::Police => 'Kepolisian',default => 'Berbasis komunitas'
        };
    }
}
