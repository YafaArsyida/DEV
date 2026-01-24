<div class="table-responsive">
    <table id="PegawaiData" class="table table-hover table-nowrap align-middle mb-0">
        <thead class="table-light">
            <tr>
                <th width="40">#</th>
                <th>Pegawai</th>
                <th>Jabatan</th>
                <th>Kontak</th>
                <th>Nomor Induk</th>
                <th>Educard</th>
                <th>PIN Fingerspot</th>
                <th width="120" class="text-uppercase text-center">Aksi</th>
            </tr>
        </thead>

        <tbody>
            @forelse($listPegawai as $row)
            <tr>
                <td>{{ $loop->iteration }}</td>
        
                <td>
                    <span class="fw-medium">{{ $row->nama_pegawai }}</span>
                    <p class="text-muted mb-0">{{ $row->deskripsi }}</p>
                </td>
        
                <td>
                    <span class="fw-medium">
                        {{ $row->ms_jabatan->nama_jabatan ?? '-' }}
                    </span>
                    <p class="text-muted mb-0">
                        Unit : {{ $row->ms_jenjang->nama_jenjang ?? '-' }}
                    </p>
                </td>
        
                <td>
                    <span class="fw-medium text-success">
                        Telepon : {{ $row->telepon ?? '-' }}
                    </span>
                    <p class="text-primary mb-0">
                        e-mail : <i>{{ $row->email ?? '-' }}</i>
                    </p>
                </td>
        
                <td>
                    <span class="fw-medium fs-14 text-info">
                        {{ $row->nip ?? '-' }}
                    </span>
                </td>
        
                <td>
                    @if ($row->ms_educard)
                    <span class="fw-medium fs-14 text-warning">
                        {{ $row->ms_educard->kode_kartu }}
                    </span>
                    @else
                    <em>Belum memiliki kartu</em>
                    @endif
                </td>
        
                <td>
                    <span class="fw-medium fs-14 text-danger">
                        {{ $row->pin_fingerspot ?? '-' }}
                    </span>
                </td>
        
                <td class="text-center">
                    <div class="hstack gap-2 justify-content-center">
        
                        {{-- Edit --}}
                        <a href="#ModalEditPegawai" data-bs-toggle="modal" class="text-warning d-inline-block"
                            title="Edit Pegawai" wire:click.prevent="$emit('loadDataPegawai', {{ $row->ms_pegawai_id }})">
                            <i class="ri-mark-pen-line fs-17 align-middle"></i> Edit
                        </a>
        
                        {{-- Delete --}}
                        <a href="#deletePegawai" data-bs-toggle="modal" class="text-danger d-inline-block" title="Hapus Pegawai"
                            wire:click.prevent="$emit('confirmDeletePegawai', {{ $row->ms_pegawai_id }})">
                            <i class="ri-delete-bin-5-line fs-17 align-middle"></i> Hapus
                        </a>
        
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center text-muted py-3">
                    Tidak ada data pegawai
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>