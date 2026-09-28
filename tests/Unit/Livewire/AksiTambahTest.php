<?php

namespace Tests\Unit\Livewire;

use App\Http\Livewire\Keuangan\TransaksiTagihanSiswa\AksiTambah;
use Tests\TestCase;

class AksiTambahTest extends TestCase
{
    public function test_normalize_amount_handles_formatted_strings()
    {
        $component = new AksiTambah();
        $method = new \ReflectionMethod($component, 'normalizeAmount');
        $method->setAccessible(true);

        $this->assertSame(1000, $method->invoke($component, '1.000'));
        $this->assertSame(1500000, $method->invoke($component, '1.500.000'));
        $this->assertSame(0, $method->invoke($component, null));
        $this->assertSame(2500, $method->invoke($component, '2.500'));
    }
}
