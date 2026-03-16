<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Str;

class HelperController extends Controller
{
    /**
     * Format tanggal dengan nama bulan dalam bahasa Indonesia.
     *
     * @param string $date
     * @param string $format
     * @return string
     */
    public static function formatTanggalIndonesia($date, $format = 'd F Y H:i:s')
    {
        $carbonDate = Carbon::parse($date);

        // Array nama bulan dalam bahasa Indonesia
        $bulanIndonesia = [
            'January' => 'Januari',
            'February' => 'Februari',
            'March' => 'Maret',
            'April' => 'April',
            'May' => 'Mei',
            'June' => 'Juni',
            'July' => 'Juli',
            'August' => 'Agustus',
            'September' => 'September',
            'October' => 'Oktober',
            'November' => 'November',
            'December' => 'Desember',
        ];

        // Format tanggal dan ganti nama bulan dengan bahasa Indonesia
        $formattedDate = $carbonDate->format($format);
        foreach ($bulanIndonesia as $english => $indonesian) {
            $formattedDate = str_replace($english, $indonesian, $formattedDate);
        }

        return $formattedDate;
    }

    /**
     * Normalisasi nomor telepon menjadi format standar.
     *
     * @param string $phone
     * @return string|null
     */
    public static function normalizePhoneNumber($phone)
    {
        if (empty($phone)) {
            return null;
        }

        // Hapus karakter non-digit kecuali tanda plus di awal
        $normalized = preg_replace('/[^\d+]/', '', $phone);

        // Pastikan nomor dimulai dengan "+" atau "0" (untuk format Indonesia)
        if (Str::startsWith($normalized, '+62')) {
            $normalized = '0' . substr($normalized, 3); // Ganti +62 dengan 0
        } elseif (Str::startsWith($normalized, '62')) {
            $normalized = '0' . substr($normalized, 2); // Ganti 62 dengan 0
        }

        // Pastikan hanya angka yang tersisa
        return preg_match('/^0\d+$/', $normalized) ? $normalized : null;
    }
    public static function generateEmailFromNama(string $nama): string
    {
        $words = collect(preg_split('/\s+/', strtolower(trim($nama))))
            ->filter()
            ->take(2)
            ->toArray();

        $base = implode('.', $words) ?: 'user';
        return $base . '@sekolah.local';
    }

    public static function makeUniqueEmail(string $email): string
    {
        $base = Str::before($email, '@');
        $domain = Str::after($email, '@');

        $final = $email;
        $i = 1;

        while (User::where('email', $final)->exists()) {
            $final = $base . $i . '@' . $domain;
            $i++;
        }

        return $final;
    }

    public static function mapRoleDariJabatan($jabatanId): string
    {
        return match ((int) $jabatanId) {
            1  => 'KOMITE_SEKOLAH',
            2  => 'KEPALA_SEKOLAH',
            3  => 'GURU',
            9  => 'TATA_USAHA',
            10 => 'BK',
            default => 'PEGAWAI',
        };
    }
}
