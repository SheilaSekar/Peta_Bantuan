<?php

return [
    'quick_exit_url' => env('PETA_QUICK_EXIT_URL', 'https://www.bmkg.go.id/'),
    'quick_exit_title' => env('PETA_QUICK_EXIT_TITLE', 'Cuaca'),
    'stale_after_days' => (int) env('PETA_STALE_AFTER_DAYS', 90),
    'contact_email' => env('PETA_CONTACT_EMAIL', 'koreksi@contoh.id'),
    // VERIFIKASI ULANG sebelum rilis
    'hotlines' => [
        'polisi_darurat' => ['label' => 'Polisi darurat', 'nilai' => '110', 'url' => 'tel:110', 'terakhir_dicek' => null],
        'sapa_telepon' => ['label' => 'SAPA 129', 'nilai' => '129', 'url' => 'tel:129', 'terakhir_dicek' => null],
        'sapa_wa' => ['label' => 'WhatsApp SAPA 129', 'nilai' => '08111-129-129', 'url' => 'https://wa.me/628111129129', 'terakhir_dicek' => null],
        'sapa_form' => ['label' => 'Formulir SAPA 129', 'nilai' => 'Kanal resmi', 'url' => 'https://laporsapa129.kemenpppa.go.id', 'terakhir_dicek' => null],
        'kpai_wa' => ['label' => 'WhatsApp KPAI', 'nilai' => '0811-1002-7727', 'url' => null, 'terakhir_dicek' => null],
        'tepsa' => ['label' => 'TePSA', 'nilai' => '1500-771', 'url' => null, 'terakhir_dicek' => null],
    ],
];
