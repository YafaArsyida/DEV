<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    {{-- HEADER --}}
    <div class="card-header">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-4">
            {{-- TITLE --}}
            <div>
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-sm">
                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                            <i class="ri-bill-line"></i>
                        </div>
                    </div>

                    <div>
                        <h5 class="fw-bold mb-1">
                            Overview Tagihan Siswa
                        </h5>
                    </div>

                </div>
            </div>

            {{-- FILTER --}}
            <div class="flex-shrink-0">
                <select class="form-select form-select-sm rounded-pill px-3"
                    wire:model="selectedKategoriTagihan">
                    <option value="">Semua Kategori</option>

                    @foreach($select_kategori as $kategori)
                        <option value="{{ $kategori->ms_kategori_tagihan_siswa_id }}">
                            {{ $kategori->nama_kategori_tagihan_siswa }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="card-body">
        <div class="table-responsive table-card">
            <table class="table table-centered table-hover align-middle table-nowrap mb-0">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Jenis Tagihan</th>
                        {{-- <th>Kategori</th> --}}
                        <th class="text-center">Estimasi</th>
                        <th class="text-center">Dibayarkan</th>
                        <th class="text-center">Kekurangan</th>
                        <th class="text-left">%</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($laporans as $index => $item)
                        @php
                            $estimasi = $item->total_tagihan_siswa() ?? 0;
                            $dibayarkan = $item->total_tagihan_siswa_dibayarkan() ?? 0;
                            $kekurangan = $estimasi - $dibayarkan;
                            $presentase = $estimasi > 0 ? round(($dibayarkan / $estimasi) * 100, 2) : 0;
                        @endphp
                        <tr>
                            <td>{{ $laporans->firstItem() + $index }}</td>
                            <td>{{ $item->nama_jenis_tagihan_siswa }}</td>
                            {{-- <td>{{ $item->ms_kategori_tagihan_siswa->nama_kategori_tagihan_siswa ?? '-' }}</td> --}}
                            <td class="text-center fw-medium fs-12 ">Rp{{ number_format($estimasi, 0, ',', '.') }}</td>
                            <td class="text-center fw-medium fs-12 text-success">Rp{{ number_format($dibayarkan, 0, ',', '.') }}</td>
                            <td class="text-center fw-medium fs-12 text-danger">Rp{{ number_format($kekurangan, 0, ',', '.') }}</td>
                            {{-- <td class="text-center">
                                <span class="badge bg-success-subtle text-success">{{ $presentase }}%</span>
                            </td> --}}
                            <td class="text-left">
                                <h5 class="fs-12 mb-0">{{ $presentase }}%<i class="ri-bar-chart-fill text-success fs-14 align-middle ms-2"></i></h5>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center">Data tidak tersedia</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-3 d-flex justify-content-end">
        {{ $laporans->links() }}
    </div>
</div>