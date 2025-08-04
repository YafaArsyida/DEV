<div class="col-xl-8">
    <div class="card card-height-100">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Overview Tagihan Siswa</h4>
            <div class="flex-shrink-0">
                <select wire:model="selectedKategoriTagihan" class="form-select form-select-sm">
                    <option value="">Semua Kategori</option>
                    @foreach($select_kategori as $kategori)
                        <option value="{{ $kategori->ms_kategori_tagihan_siswa_id }}">
                            {{ $kategori->nama_kategori_tagihan_siswa }}
                        </option>
                    @endforeach
                </select>
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
                            <th class="text-center">%</th>
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
                                <td class="text-center text-info">Rp{{ number_format($estimasi, 0, ',', '.') }}</td>
                                <td class="text-center text-success">Rp{{ number_format($dibayarkan, 0, ',', '.') }}</td>
                                <td class="text-center text-danger">Rp{{ number_format($kekurangan, 0, ',', '.') }}</td>
                                <td class="text-center">
                                    <span class="badge bg-success-subtle text-success">{{ $presentase }}%</span>
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
</div>
