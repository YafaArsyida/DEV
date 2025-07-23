<div>
    <div class="modal fade" id="ModalKelolaTagihan" tabindex="-1" aria-labelledby="kelolaTagihanLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false" wire:ignore.self>
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header bg-light p-3">
                    <h5 class="modal-title">Kelola Tagihan Siswa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-xxl-9">
                            <div class="card">
                                <div class="card-header align-items-center d-flex border-0">
                                    <h4 class="card-title mb-0 flex-grow-1">Tagihan Siswa</h4>
                                    <div class="flex-shrink-0">
                                        <div class="d-flex gap-2 flex-wrap">
                                            <!-- Jumlah Tagihan Dipilih -->
                                            <div class="mb-0 alert alert-secondary alert-dismissible alert-label-icon rounded-label shadow fade show" role="alert">
                                                <i class="ri-check-double-line label-icon"></i>
                                                <strong>{{ count($TagihanSelected) }}</strong> - tagihan telah dipilih
                                            </div>

                                            <!-- Tombol Hapus -->
                                            <button type="button" class="btn btn-soft-danger" wire:click="HapusTagihan" {{ count($TagihanSelected) === 0 ? 'disabled' : '' }}>
                                                <i class="ri-delete-bin-2-line"></i> Hapus {{ count($TagihanSelected) }}
                                            </button>
                                        </div>
                                    </div>
                                </div><!-- end card header -->
                                <div class="card-body">
                                    <div class="row g-3 mb-3">
                                         <div class="col-xxl-2 col-sm-6"> 
                                            <select wire:model="selectedKelas" style="cursor: pointer" class="form-select" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Pilih Kelas">
                                                <option value="">Semua Kelas</option>
                                                @foreach ($select_kelas as $kelas)
                                                    <option value="{{ $kelas->ms_kelas_id }}">{{ $kelas->nama_kelas }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-xxl-10 col-sm-6">
                                            <div class="search-box">
                                                <input type="text" class="form-control search" wire:model.debounce.300ms="search" placeholder="cari nama, deskripsi atau lainnya...">
                                                <i class="ri-search-line search-icon"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="live-preview">
                                        <!-- Jika Jenjang atau Tahun Ajar belum dipilih -->
                                        @if (!$selectedJenjang || !$selectedTahunAjar)
                                        <div class="text-center py-4">
                                            <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop"
                                                colors="primary:#405189,secondary:#08a88a"
                                                style="width:75px;height:75px">
                                            </lord-icon>
                                            <h5 class="mt-2">Silakan Pilih Jenjang dan Tahun Ajar</h5>
                                            <p class="text-muted mb-0">Untuk melihat data kelas, harap pilih Jenjang dan Tahun Ajar terlebih dahulu.</p>
                                        </div>
                                        @else
                                        <div class="table-responsive">
                                            <table class="table table-hover nowrap align-middle" style="width:100%">
                                                <thead class="table-light">
                                                    <tr style="white-space: nowrap;">
                                                        <th scope="col" style="width: 50px;">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" id="checkAll" wire:model="TagihanSelectAll">
                                                            </div>
                                                        </th>
                                                        <th class="text-uppercase" style="width: 50px;">NO</th>
                                                        <th class="text-uppercase">Siswa</th>
                                                        <th class="text-uppercase">Kelas</th>
                                                        <th class="text-uppercase">Jenis Tagihan</th>
                                                        <th class="text-uppercase">Kategori</th>
                                                        <th class="text-uppercase text-center">Estimasi</th>
                                                        <th class="text-uppercase text-center">Dibayarkan</th>
                                                        <th class="text-uppercase text-center">Kekurangan</th>
                                                        <th class="text-uppercase">Jatuh Tempo</th>
                                                        <th class="text-uppercase">Status</th>
                                                        {{-- <th class="text-uppercase">Catatan</th> --}}
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse ($tagihans as $item)
                                                    <tr style="white-space: nowrap;" >
                                                        <th scope="row">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" wire:key="{{ $item->ms_tagihan_siswa_id }}" wire:model.live="TagihanSelected" value="{{ $item->ms_tagihan_siswa_id }}">
                                                            </div>
                                                        </th>
                                                        <td>{{ $loop->iteration }}. </td>
                                                        <td class="text-start">
                                                            {{ $item->nama_siswa() }}
                                                        </td>
                                                        <td>{{ $item->nama_kelas() }}</td>
                                                        <td class="text-start">
                                                            <span class="fw-medium">
                                                                {{ $item->ms_jenis_tagihan_siswa->nama_jenis_tagihan_siswa }}
                                                            </span>
                                                            {{-- <p class="text-muted mb-0">{{ $item->deskripsi }}</p> --}}
                                                        </td>
                                                        <td>{{ $item->nama_kategori_tagihan_siswa() }}</td>
                                                        <td class="text-center">
                                                            <span class="fs-14 fw-medium text-info">
                                                                Rp{{ number_format($item->jumlah_tagihan_siswa, 0, ',', '.') }}
                                                            </span>
                                                        </td>
                                                        <td class="text-center">
                                                            <span class="fs-14 fw-medium text-success">
                                                                Rp{{ number_format($item->jumlah_sudah_dibayar(), 0, ',', '.') }}</td>
                                                            </span>
                                                        </td>
                                                        <td class="text-center">
                                                            <span class="fs-14 fw-medium text-danger">
                                                                Rp{{ number_format($item->jumlah_tagihan_siswa - $item->jumlah_sudah_dibayar(), 0, ',', '.') }}
                                                            </span>
                                                        </td>
                                                        <td>{{ \App\Http\Controllers\HelperController::formatTanggalIndonesia($item->tanggal_jatuh_tempo, 'd F Y') }}</td>
                                                        <td class="
                                                            {{ $item->status === 'Belum Dibayar' ? 'text-warning' : '' }}
                                                            {{ $item->status === 'Masih Dicicil' ? 'text-info' : '' }}
                                                            {{ $item->status === 'Lunas' ? 'text-success' : '' }}">
                                                            <i class="ri-{{ $item->status === 'Belum Dibayar' ? 'time-line' : ($item->status === 'Masih Dicicil' ? 'money-dollar-circle-line' : 'checkbox-circle-line') }} fs-17 align-middle"></i>
                                                            {{ $item->status }}
                                                        </td>

                                                        {{-- <td>{{ $item->deskripsi }}</td> --}}
                                                    </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="11">
                                                                <div class="noresult text-center py-3">
                                                                    <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop"
                                                                        colors="primary:#405189,secondary:#08a88a"
                                                                        style="width:75px;height:75px">
                                                                    </lord-icon>
                                                                    <h5 class="mt-2">Maaf, Tidak Ada Data yang Ditemukan</h5>
                                                                    <p class="text-muted mb-0">Kami telah mencari keseluruhan data, namun tidak ditemukan hasil yang sesuai.</p>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td class="text-start fw-bold">TOTAL</td>
                                                        <td class="text-center">
                                                            <span class="fs-14 fw-medium text-info">
                                                                Rp{{ number_format($totalEstimasi, 0, ',', '.') }}
                                                            </span>
                                                        </td>
                                                        <td class="text-center">
                                                            <span class="fs-14 fw-medium text-success">
                                                                Rp{{ number_format($totalDibayarkan, 0, ',', '.') }}
                                                            </span>
                                                        </td>
                                                        <td class="text-center">
                                                            <span class="fs-14 fw-medium text-danger">
                                                                Rp{{ number_format($totalKekurangan, 0, ',', '.') }}
                                                            </span>
                                                        </td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                            {{-- {{ $tagihans->links() }} --}}
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-3">
                            <div class="card">
                                <div class="card-header border-0">
                                    <h4 class="card-title mb-0 flex-grow-1">Edit Nominal Tagihan</h4>
                                </div><!-- end card header -->
                                <div class="card-body">
                                    <div class="row gy-4">
                                        <div class="col-xxl-12 col-md-6">
                                            <label for="" class="form-label">Nominal</label>
                                            <div class="input-group">
                                                <span class="input-group-text">Rp</span>
                                                <input type="text" class="form-control" wire:model.defer="jumlahTagihan" aria-label="Amount">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <a href="javascript:void(0);" class="btn btn-link link-success shadow-none fw-medium" data-bs-dismiss="modal"><i class="ri-close-line me-1 align-middle"></i> Tutup</a>
                    @if ($TagihanSelected)
                    <button type="button" class="btn btn-primary" wire:click="editTagihan">Simpan Perubahan</button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
