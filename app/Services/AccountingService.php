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
                // 'status'             => $data['status'] ?? 'POSTED',
                'status'             => 'active',
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