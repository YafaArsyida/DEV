@php
    $colspan = count($days) + 1;
@endphp

<div class="table-responsive">
    <table class="table table-bordered align-middle mb-0">
        <thead class="table-light">
            <tr>
                <th class="text-nowrap" style="min-width: 120px;">Jam</th>
                @foreach ($days as $day)
                    <th class="text-center text-nowrap">{{ $hariOptions[$day] ?? $day }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @forelse ($slots as $slot)
                <tr>
                    <th class="table-light text-nowrap">
                        <div class="fw-semibold">{{ substr($slot['mulai'], 0, 5) }}</div>
                        <div class="small text-muted">{{ substr($slot['selesai'], 0, 5) }}</div>
                    </th>
                    @foreach ($days as $day)
                        <td class="align-top" style="min-width: 150px;">
                            @php $items = $matrix[$slot['key']][$day] ?? []; @endphp
                            @forelse ($items as $jadwal)
                                @php $assignment = $jadwal->ms_penugasan_guru; @endphp
                                @if ($clickable)
                                    <button type="button"
                                        class="btn btn-sm btn-soft-primary text-start w-100 mb-1 rounded-3"
                                        wire:click="showDetail({{ $jadwal->ej_jadwal_mengajar_id }})">
                                @else
                                    <div class="border rounded-3 p-2 mb-1 bg-light-subtle">
                                @endif
                                    <div class="fw-semibold">{{ $assignment?->ms_mata_pelajaran?->nama_mapel ?? '-' }}</div>
                                    <div class="small text-muted">{{ $assignment?->ms_kelas?->nama_kelas ?? '-' }}</div>
                                    @if ($showTeacher)
                                        <div class="small text-muted">
                                            <i class="ri-user-line me-1"></i>{{ $assignment?->ms_pegawai?->nama_pegawai ?? '-' }}
                                        </div>
                                    @endif
                                @if ($clickable)
                                    </button>
                                @else
                                    </div>
                                @endif
                            @empty
                                <span class="text-muted small">-</span>
                            @endforelse
                        </td>
                    @endforeach
                </tr>
            @empty
                <tr>
                    <td colspan="{{ $colspan }}">
                        <div class="text-center py-4">
                            <div class="avatar-md mx-auto mb-3">
                                <span class="avatar-title rounded-circle bg-light text-muted fs-24">
                                    <i class="ri-calendar-close-line"></i>
                                </span>
                            </div>
                            <h5 class="mt-2">Tidak Ada Jadwal</h5>
                            <p class="text-muted mb-0">Belum ada jadwal mengajar yang sesuai dengan filter saat ini.</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
