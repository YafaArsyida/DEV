
<div class="card-body p-4 pb-0">
    <div class="d-flex">
        <div class="flex-grow-1">
            <h4>{{ $nama_siswa ?? 'Siswa belum dipilih' }}</h4>
            <div class="hstack gap-3 flex-wrap">
                <div><a href="#" class="text-primary d-block">{{ $ms_penempatan_siswa_id }}-TemanSekolah</a></div>
                <div class="vr"></div>
                <div class="text-muted">EduCard : <span class="text-warning fw-medium">{{ $educard ?? 'Belum ada' }}</span></div>
                <div class="vr"></div>
                <div class="text-muted">Kelas : <span class="text-body fw-medium">{{ $nama_kelas ?? 'Belum ada' }}</span></div>
                <div class="vr"></div>
                <div class="text-muted">Telepon : <span class="text-body fw-medium">{{ $telepon ?? 'Tidak tersedia' }}</span></div>
                <div class="vr"></div>
                <div class="text-muted">Virtual Akun : <span class="text-body fw-medium">9888838383838</span></div>
            </div>
        </div>
        <div class="flex-shrink-0">
            @if ($ms_penempatan_siswa_id)
                <div data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Edit Siswa">
                    <a href="#ModalEditSiswa" data-bs-toggle="modal" class="btn btn-light" wire:click.prevent="$emit('loadDataSiswa', {{ $ms_penempatan_siswa_id }})">
                        <i class="ri-pencil-fill align-bottom"></i>
                    </a>
                </div>
            @endif
        </div>
    </div>
    <div class="mt-4 text-muted">
        <p>{{ $deskripsi ?? 'Tidak ada catatan' }}</p>
    </div>
</div>

