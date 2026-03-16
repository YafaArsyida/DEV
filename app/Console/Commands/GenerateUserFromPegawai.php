<?php

namespace App\Console\Commands;

use App\Models\Pegawai;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class GenerateUserFromPegawai extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-user-from-pegawai';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate akun ms_pengguna dari seluruh ms_pegawai';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $pegawais = Pegawai::all();
        $created = 0;
        $updated = 0;
        $skipped = 0;

        foreach ($pegawais as $pegawai) {
            if ($pegawai->user_id && !$this->option('force')) {
                $this->line("Skip: {$pegawai->nama_pegawai} (sudah punya akun)");
                $skipped++;
                continue;
            }

            // Email: pakai email pegawai, kalau kosong generate dari 2 kata nama
            $email = $pegawai->email ?: $this->generateEmailFromNama($pegawai->nama_pegawai);

            // Pastikan unik
            $email = $this->makeUniqueEmail($email);

            $user = User::updateOrCreate(
                ['email' => $email],
                [
                    'nama'     => $pegawai->nama_pegawai,
                    'email'    => $email,
                    'telepon'  => $pegawai->telepon,
                    'password' => Hash::make('123456'),
                    'peran'    => $this->mapRoleDariJabatan($pegawai->ms_jabatan_id),
                    'current_session' => null,
                    // 'must_change_password' => 1, // kalau kamu pakai flag ini
                ]
            );

            $pegawai->update(['user_id' => $user->ms_pengguna_id]);

            $this->info("OK: {$pegawai->nama_pegawai} → {$email}");
            $created++;
        }

        $this->info("Selesai. Created/Updated: $created | Skipped: $skipped");
        return self::SUCCESS;
    }

    private function generateEmailFromNama(string $nama): string
    {
        $words = collect(preg_split('/\s+/', strtolower(trim($nama))))
            ->filter()
            ->take(2)
            ->toArray();

        $base = implode('.', $words) ?: 'user';
        return $base . '@gmail.com'; // domain internal
    }

    private function makeUniqueEmail(string $email): string
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

    private function mapRoleDariJabatan($jabatanId): string
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
