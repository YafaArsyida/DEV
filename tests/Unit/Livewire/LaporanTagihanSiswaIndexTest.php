<?php

namespace Tests\Unit\Livewire;

use App\Http\Livewire\LaporanTagihanSiswa\Index;
use Carbon\Carbon;
use ReflectionMethod;
use Tests\TestCase;

class LaporanTagihanSiswaIndexTest extends TestCase
{
    public function test_get_filtered_tagihan_applies_current_filters()
    {
        $component = new Index();
        $component->selectedJenisTagihan = [2];
        $component->selectedKategoriTagihan = [5];
        $component->endDate = Carbon::now()->toDateString();

        $matchingTagihan = new class {
            public $status = 'Belum Dibayar';
            public $jumlah_tagihan_siswa = 100000;
            public $ms_jenis_tagihan_siswa;
            public $dt_transaksi_tagihan_siswa = [];

            public function jumlah_sudah_dibayar()
            {
                return 0;
            }
        };
        $matchingTagihan->ms_jenis_tagihan_siswa = new class {
            public $ms_kategori_tagihan_siswa_id = 5;
            public $ms_jenis_tagihan_siswa_id = 2;
            public $tanggal_jatuh_tempo = '2026-07-01';
        };

        $otherJenisTagihan = new class {
            public $status = 'Belum Dibayar';
            public $jumlah_tagihan_siswa = 200000;
            public $ms_jenis_tagihan_siswa;
            public $dt_transaksi_tagihan_siswa = [];

            public function jumlah_sudah_dibayar()
            {
                return 0;
            }
        };
        $otherJenisTagihan->ms_jenis_tagihan_siswa = new class {
            public $ms_kategori_tagihan_siswa_id = 5;
            public $ms_jenis_tagihan_siswa_id = 9;
            public $tanggal_jatuh_tempo = '2026-07-01';
        };

        $otherKategoriTagihan = new class {
            public $status = 'Belum Dibayar';
            public $jumlah_tagihan_siswa = 300000;
            public $ms_jenis_tagihan_siswa;
            public $dt_transaksi_tagihan_siswa = [];

            public function jumlah_sudah_dibayar()
            {
                return 0;
            }
        };
        $otherKategoriTagihan->ms_jenis_tagihan_siswa = new class {
            public $ms_kategori_tagihan_siswa_id = 8;
            public $ms_jenis_tagihan_siswa_id = 2;
            public $tanggal_jatuh_tempo = '2026-07-01';
        };

        $lunasTagihan = new class {
            public $status = 'Lunas';
            public $jumlah_tagihan_siswa = 400000;
            public $ms_jenis_tagihan_siswa;
            public $dt_transaksi_tagihan_siswa = [];

            public function jumlah_sudah_dibayar()
            {
                return 400000;
            }
        };
        $lunasTagihan->ms_jenis_tagihan_siswa = new class {
            public $ms_kategori_tagihan_siswa_id = 5;
            public $ms_jenis_tagihan_siswa_id = 2;
            public $tanggal_jatuh_tempo = '2026-07-01';
        };

        $method = new ReflectionMethod(Index::class, 'getFilteredTagihan');
        $method->setAccessible(true);

        $result = $method->invoke($component, collect([$matchingTagihan, $otherJenisTagihan, $otherKategoriTagihan, $lunasTagihan]));

        $this->assertCount(1, $result);
        $this->assertSame($matchingTagihan, $result->first());
    }
}
