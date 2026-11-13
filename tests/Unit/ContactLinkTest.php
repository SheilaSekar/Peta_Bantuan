<?php

namespace Tests\Unit;

use App\Support\ContactLink;
use PHPUnit\Framework\TestCase;

class ContactLinkTest extends TestCase
{
    public function test_telephone_link_strips_visual_separators(): void
    {
        self::assertSame('tel:08111129129', ContactLink::telephone('08111-129-129'));
    }

    public function test_whatsapp_link_converts_indonesian_local_number_to_country_code(): void
    {
        self::assertSame('https://wa.me/628111129129', ContactLink::whatsapp('08111-129-129'));
        self::assertSame('https://wa.me/628111129129', ContactLink::whatsapp('+62 8111 129 129'));
    }

    public function test_empty_contact_number_has_no_link(): void
    {
        self::assertNull(ContactLink::telephone(null));
        self::assertNull(ContactLink::whatsapp(''));
    }
}
