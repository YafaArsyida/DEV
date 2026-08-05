 <div class="col-xl-8">
    <div class="card card-height-100">
         <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Jurnal Hari Ini</h4>
            <div class="flex-shrink-0">
                {{-- <select wire:model="selectedKategoriTagihan" class="form-select form-select-sm">
                    <option value="">Semua Kategori</option>
                    @foreach($select_kategori as $kategori)
                        <option value="{{ $kategori->ms_kategori_tagihan_siswa_id }}">
                            {{ $kategori->nama_kategori_tagihan_siswa }}
                        </option>
                    @endforeach
                </select> --}}
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive table-card">
                @php
                    $saldo = 0;
                @endphp
                <table class="table table-centered table-hover align-middle table-nowrap mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="text-start">Tanggal</th>
                            <th class="">Nomor Jurnal</th>
                            <th class="text-start">Deskripsi</th>
                            <th class="text-start">Petugas</th>
                            <th class="text-center">Nominal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transaksiJurnal as $jurnal)
                            <tr>
                                <td>
                                    {{ $jurnal->tanggal_transaksi
                                        ? \Carbon\Carbon::parse($jurnal->tanggal_transaksi)
                                            ->format('d/m/Y')
                                        : '-' }}
                                </td>
                                <td>
                                    {{ $jurnal->nomor_jurnal }}
                                </td>

                                <td>
                                    {{ $jurnal->deskripsi }}
                                </td>

                                <td>
                                    {{ $jurnal->ms_pengguna->nama ?? '-' }}
                                </td>
                                @php
                                    $nominal = optional(
                                        $jurnal->akuntansi_jurnal_detail->firstWhere('posisi', 'debit')
                                    )->nominal ?? 0;
                                @endphp

                                <td class="text-center">
                                    <span class="fs-12 fw-medium">
                                        RP{{ number_format($nominal, 0, ',', '.') }}
                                    </span>
                                </td>
                            </tr>
                        @empty

                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    Tidak ada data jurnal.
                                </td>
                            </tr>

                        @endforelse
                    </tbody>
                </table>
            </div>
            {{-- <div class="mt-3 d-flex justify-content-end">
                {{ $transaksiJurnal->links() }}
            </div> --}}
        </div>  
    </div>
</div><!-- end col -->