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
                            <th class="">No</th>
                            <th class="text-start" style="width: 200px;">Tanggal</th>
                            <th class="text-start">Petugas</th>
                            <th class="text-start" style="min-width: 500px;">Deskripsi Transaksi</th>
                            {{-- <th class="text-center">Akun Debit</th> --}}
                            {{-- <th class="text-center">Akun Kredit</th> --}}
                            <th class="text-center">Nominal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $nomorUrut = 1; // Inisialisasi nomor urut
                        @endphp

                        @foreach ($transaksiJurnal as $deskripsi => $transaksiByNominal)
                            @foreach ($transaksiByNominal as $nominal => $transaksi)
                                @php
                                    $debit = $transaksi->where('posisi', 'debit')->first();
                                    $kredit = $transaksi->where('posisi', 'kredit')->first();
                                    $tanggal = $transaksi->first()->tanggal_transaksi ?? null;
                                @endphp
                                <tr>
                                    <td class="text-start">{{ $nomorUrut++ }}.</td>
                                    <td class="text-start">
                                        {{ $tanggal ? \App\Http\Controllers\HelperController::formatTanggalIndonesia($tanggal, 'd F Y H:i:s') : '-' }}
                                    </td>
                                    <td class="text-start">{{ $debit ? $debit->ms_pengguna->nama : ($kredit ? $kredit->ms_pengguna->nama : '-') }}</td>
                                    <td>{{ $deskripsi }}</td>
                                    {{-- <td style="white-space: nowrap;" class="text-center">{{ $debit ? $debit->akuntansi_rekening->nama_rekening : '-' }}</td> --}}
                                    {{-- <td style="white-space: nowrap;" class="text-center">{{ $kredit ? $kredit->akuntansi_rekening->nama_rekening : '-' }}</td> --}}
                                    <td class="text-center">
                                        <span class="fs-14 text-info">
                                            RP{{ number_format($nominal, 0, ',', '.') }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{-- <div class="mt-3 d-flex justify-content-end">
                {{ $transaksiJurnal->links() }}
            </div> --}}
        </div>  
    </div>
</div><!-- end col -->