<?php

namespace App\Services;

use App\Models\AkuntansiJurnal;
use App\Models\AkuntansiJurnalDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class AccountingService
{
    /**
     * Membuat jurnal beserta detailnya.
     *
     * @param array $data
     * @return AkuntansiJurnal
     * @throws \Throwable
     */
    public static function create(array $data): AkuntansiJurnal
    {
        self::validateDetail($data['detail']);

        return DB::transaction(function () use ($data) {

            $nomorJurnal = self::generateNomorJurnal(
                $data['tanggal'] ?? now()
            );

            $jurnal = AkuntansiJurnal::create([
                'nomor_jurnal'       => $nomorJurnal,
                'tanggal_transaksi'  => $data['tanggal'],
                'deskripsi'          => $data['deskripsi'],
                'ms_pengguna_id'     => $data['ms_pengguna_id'],
                'ms_tahun_ajaran_id' => $data['ms_tahun_ajaran_id'],
                'ms_jenjang_id'      => $data['ms_jenjang_id'],
                'ms_departemen_id'   => $data['ms_departemen_id'],
            ]);

            foreach ($data['detail'] as $detail) {

                AkuntansiJurnalDetail::create([
                    'akuntansi_jurnal_id' => $jurnal->akuntansi_jurnal_id,
                    'kode_rekening'       => $detail['kode_rekening'],
                    'posisi'              => strtolower($detail['posisi']),
                    'nominal'             => $detail['nominal'],
                ]);
            }

            return $jurnal->load('akuntansi_jurnal_detail');
        });
    }
    
    public static function update(int $jurnalId, array $data): AkuntansiJurnal
    {
        self::validateDetail($data['detail']);

        return DB::transaction(function () use ($jurnalId, $data) {

            $jurnal = AkuntansiJurnal::lockForUpdate()->findOrFail($jurnalId);

            $jurnal->update([
                'tanggal_transaksi' => $data['tanggal'],
                'deskripsi' => $data['deskripsi'],
            ]);

            // Hapus detail lama
            $jurnal->akuntansi_jurnal_detail()->delete();

            // Insert ulang detail
            foreach ($data['detail'] as $detail) {

                AkuntansiJurnalDetail::create([
                    'akuntansi_jurnal_id' => $jurnal->akuntansi_jurnal_id,
                    'kode_rekening' => $detail['kode_rekening'],
                    'posisi' => $detail['posisi'],
                    'nominal' => $detail['nominal'],
                ]);
            }

            return $jurnal->load('akuntansi_jurnal_detail');
        });
    }

    public static function reverse(int $jurnalId, array $data = []): AkuntansiJurnal 
    {
        return DB::transaction(function () use ($jurnalId, $data) {

            // =====================================================
            // 1. AMBIL JURNAL ASLI BESERTA DETAIL
            // =====================================================
            $jurnalAsli = AkuntansiJurnal::with('akuntansi_jurnal_detail')
                ->lockForUpdate()
                ->findOrFail($jurnalId);

            $detailAsli = $jurnalAsli->akuntansi_jurnal_detail;

            if ($detailAsli->isEmpty()) {
                throw new \Exception(
                    'Detail jurnal asli tidak ditemukan.'
                );
            }

            // =====================================================
            // 2. VALIDASI DETAIL JURNAL
            // =====================================================
            foreach ($detailAsli as $detail) {
                if (!in_array(
                    strtolower($detail->posisi),
                    ['debit', 'kredit']
                )) {
                    throw new \Exception(
                        'Posisi jurnal tidak valid pada rekening '
                        . $detail->kode_rekening
                    );
                }

                if ($detail->nominal <= 0) {
                    throw new \Exception(
                        'Nominal jurnal tidak valid pada rekening '
                        . $detail->kode_rekening
                    );
                }
            }

            // =====================================================
            // 3. BUAT NOMOR JURNAL REVERSAL
            // =====================================================
            $nomorJurnal = self::generateNomorJurnal(
                $data['tanggal'] ?? now()
            );

            // =====================================================
            // 4. BUAT HEADER JURNAL REVERSAL
            // =====================================================
            $jurnalReversal = AkuntansiJurnal::create([

                'nomor_jurnal' => $nomorJurnal,

                'tanggal_transaksi' => $data['tanggal'] ?? now(),

                'deskripsi' => $data['deskripsi'] ?? 'Reversal ' . $jurnalAsli->nomor_jurnal,

                'ms_pengguna_id' => $data['ms_pengguna_id'] ?? auth()->user()->ms_pengguna_id,

                'ms_tahun_ajaran_id' => $data['ms_tahun_ajaran_id'] ?? $jurnalAsli->ms_tahun_ajaran_id,

                'ms_jenjang_id' => $data['ms_jenjang_id'] ?? $jurnalAsli->ms_jenjang_id,

                'ms_departemen_id' => $data['ms_departemen_id'] ?? $jurnalAsli->ms_departemen_id,

                'status' => 'active',
            ]);

            // =====================================================
            // 5. INSERT DETAIL REVERSAL
            //
            // Debit  -> Kredit
            // Kredit -> Debit
            // =====================================================
            foreach ($detailAsli as $detail) {

                AkuntansiJurnalDetail::create([

                    'akuntansi_jurnal_id' => $jurnalReversal->akuntansi_jurnal_id,

                    'kode_rekening' => $detail->kode_rekening,

                    'posisi' => strtolower($detail->posisi) === 'debit'
                            ? 'kredit'
                            : 'debit',

                    'nominal' => $detail->nominal,
                ]);
            }

            // =====================================================
            // 6. RETURN JURNAL REVERSAL BESERTA DETAIL
            // =====================================================
            return $jurnalReversal->load(
                'akuntansi_jurnal_detail'
            );
        });
    }

    public static function delete(int $jurnalId): void
    {
        DB::transaction(function () use ($jurnalId) {

            $jurnal = AkuntansiJurnal::with('akuntansi_jurnal_detail')
                ->lockForUpdate()
                ->findOrFail($jurnalId);

            $jurnal->akuntansi_jurnal_detail()->delete();

            $jurnal->delete();
        });
    }
    /**
     * Generate nomor jurnal.
     */
    public static function generateNomorJurnal($tanggal = null): string
    {
        $tanggal = $tanggal
            ? Carbon::parse($tanggal)
            : now();

        $prefix = 'JV' . $tanggal->format('ymd') . '-';

        $last = AkuntansiJurnal::withTrashed()
            ->where('nomor_jurnal', 'like', $prefix.'%')
            ->lockForUpdate()
            ->latest('akuntansi_jurnal_id')
            ->value('nomor_jurnal');

        $urut = $last
            ? ((int) substr($last, -4)) + 1
            : 1;

        return $prefix . str_pad($urut, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Validasi jurnal balance.
     */
    protected static function validateDetail(array $details): void
    {
        if (count($details) < 2) {
            throw new InvalidArgumentException(
                'Minimal terdapat 2 detail jurnal.'
            );
        }

        $debit = 0;
        $kredit = 0;

        foreach ($details as $detail) {

            if (!isset(
                $detail['kode_rekening'],
                $detail['posisi'],
                $detail['nominal']
            )) {
                throw new InvalidArgumentException(
                    'Format detail jurnal tidak valid.'
                );
            }

            if ($detail['nominal'] <= 0) {
                throw new InvalidArgumentException(
                    'Nominal harus lebih besar dari nol.'
                );
            }

            switch (strtolower($detail['posisi'])) {

                case 'debit':
                    $debit += $detail['nominal'];
                    break;

                case 'kredit':
                    $kredit += $detail['nominal'];
                    break;

                default:
                    throw new InvalidArgumentException(
                        'Posisi harus debit atau kredit.'
                    );
            }
        }

        if ($debit != $kredit) {
            throw new InvalidArgumentException(
                "Jurnal tidak balance. Debit {$debit} ≠ Kredit {$kredit}"
            );
        }
    }
}