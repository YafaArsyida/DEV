<?php

namespace App\Services\Akademik;

use App\Models\JadwalMengajar;
use App\Models\PenugasanGuru;

class JadwalMengajarConflictService
{
    private const HARI = [
        'SENIN' => 'Senin',
        'SELASA' => 'Selasa',
        'RABU' => 'Rabu',
        'KAMIS' => 'Kamis',
        'JUMAT' => 'Jumat',
        'SABTU' => 'Sabtu',
        'MINGGU' => 'Minggu',
    ];

    /**
     * Detect teacher and class conflicts for a candidate schedule.
     *
     * @return array{teacher: JadwalMengajar|null, class: JadwalMengajar|null}
     */
    public function detect(PenugasanGuru $assignment, array $data, ?int $ignoreId = null): array
    {
        $result = ['teacher' => null, 'class' => null];

        if (($data['status'] ?? 'AKTIF') !== 'AKTIF' || $assignment->status !== 'AKTIF') {
            return $result;
        }

        $jamMulai = $this->normalizeTime($data['jam_mulai']);
        $jamSelesai = $this->normalizeTime($data['jam_selesai']);
        $tanggalMulai = $this->normalizeDate($data['tanggal_mulai']);
        $tanggalSelesai = $this->normalizeDate($data['tanggal_selesai']);

        $candidates = JadwalMengajar::query()
            ->where('status', 'AKTIF')
            ->where('hari', strtoupper($data['hari']))
            ->whereHas('ms_penugasan_guru', fn ($query) => $query->where('status', 'AKTIF'))
            ->when($ignoreId, fn ($query) => $query->whereKeyNot($ignoreId))
            ->with($this->relations())
            ->get();

        foreach ($candidates as $candidate) {
            $candidateAssignment = $candidate->ms_penugasan_guru;

            if (! $candidateAssignment) {
                continue;
            }

            if (! self::timeOverlaps(
                $jamMulai,
                $jamSelesai,
                $this->normalizeTime($candidate->jam_mulai),
                $this->normalizeTime($candidate->jam_selesai)
            )) {
                continue;
            }

            if (! self::dateRangesOverlap(
                $tanggalMulai,
                $tanggalSelesai,
                $candidate->tanggal_mulai?->format('Y-m-d') ?? '0000-01-01',
                $candidate->tanggal_selesai?->format('Y-m-d') ?? '9999-12-31'
            )) {
                continue;
            }

            if (! $result['teacher']
                && (int) $candidateAssignment->ms_pegawai_id === (int) $assignment->ms_pegawai_id) {
                $result['teacher'] = $candidate;
            }

            if (! $result['class']
                && (int) $candidateAssignment->ms_kelas_id === (int) $assignment->ms_kelas_id) {
                $result['class'] = $candidate;
            }

            if ($result['teacher'] && $result['class']) {
                break;
            }
        }

        return $result;
    }

    public function describe(JadwalMengajar $conflict, string $type): string
    {
        $assignment = $conflict->ms_penugasan_guru;
        $guru = $assignment?->ms_pegawai?->nama_pegawai ?? '-';
        $kelas = $assignment?->ms_kelas?->nama_kelas ?? '-';
        $mapel = $assignment?->ms_mata_pelajaran?->nama_mapel ?? '-';
        $hari = self::HARI[strtoupper((string) $conflict->hari)] ?? '-';
        $jam = substr((string) $conflict->jam_mulai, 0, 5)
            . '-'
            . substr((string) $conflict->jam_selesai, 0, 5);
        $tanggal = ($conflict->tanggal_mulai?->format('d/m/Y') ?? '-')
            . ' s.d. '
            . ($conflict->tanggal_selesai?->format('d/m/Y') ?? '-');

        if ($type === 'teacher') {
            return "Jadwal bentrok: guru {$guru} sudah memiliki jadwal {$mapel} di kelas {$kelas} pada {$hari}, {$jam} ({$tanggal}).";
        }

        return "Jadwal bentrok: kelas {$kelas} sudah memiliki jadwal {$mapel} oleh {$guru} pada {$hari}, {$jam} ({$tanggal}).";
    }

    public static function timeOverlaps(string $aStart, string $aEnd, string $bStart, string $bEnd): bool
    {
        return $aStart < $bEnd && $aEnd > $bStart;
    }

    public static function dateRangesOverlap(string $aStart, string $aEnd, string $bStart, string $bEnd): bool
    {
        return $aStart <= $bEnd && $aEnd >= $bStart;
    }

    private function relations(): array
    {
        return [
            'ms_penugasan_guru.ms_pegawai',
            'ms_penugasan_guru.ms_kelas',
            'ms_penugasan_guru.ms_mata_pelajaran',
        ];
    }

    private function normalizeTime(?string $time): string
    {
        if (! $time) {
            return '00:00:00';
        }

        return strlen($time) === 5 ? $time . ':00' : $time;
    }

    private function normalizeDate($date): string
    {
        if ($date instanceof \DateTimeInterface) {
            return $date->format('Y-m-d');
        }

        return (string) $date;
    }
}
