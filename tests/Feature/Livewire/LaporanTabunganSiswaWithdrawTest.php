<?php

namespace Tests\Feature\Livewire;

use App\Http\Livewire\LaporanTabunganSiswa\Withdraw;
use Livewire\Livewire;
use Tests\TestCase;

class LaporanTabunganSiswaWithdrawTest extends TestCase
{
    public function test_withdraw_event_accepts_payload_and_validates_required_data(): void
    {
        Livewire::test(Withdraw::class)
            ->emit('withdrawTabungan', [
                'selectedKelas' => null,
                'selectedJenjang' => 1,
                'selectedTahunAjar' => 1,
            ])
            ->assertDispatchedBrowserEvent('alertify-error', [
                'message' => 'Data kelas, jenjang, atau tahun ajar tidak valid.'
            ]);
    }
}
