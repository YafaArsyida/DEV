 <div class="col-xl-8">
    <div class="card">
        <div class="card-header">
            <div class="row g-4 align-items-center">
                <div class="col-sm-auto">
                    <div>
                        <h4 class="card-title mb-0 flex-grow-1">Jurnal Keuangan Hari Ini</h4>
                    </div>
                </div>
                <div class="col-sm">
                    <div class="d-flex justify-content-sm-end">
                        <div class="search-box ms-2">
                            <input type="text" class="form-control" id="searchResultList" placeholder="Search for jobs...">
                            <i class="ri-search-line search-icon"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="live-preview">
                <div class="table-responsive">
                    @php
                        $saldo = 0;
                    @endphp
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="text-uppercase">No</th>
                                <th class="text-uppercase text-start" style="width: 200px;">Tanggal</th>
                                <th class="text-uppercase text-start">Petugas</th>
                                <th class="text-uppercase text-start" style="min-width: 500px;">Deskripsi Transaksi</th>
                                {{-- <th class="text-uppercase text-center">Akun Debit</th> --}}
                                {{-- <th class="text-uppercase text-center">Akun Kredit</th> --}}
                                <th class="text-uppercase text-center">Nominal</th>
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
            </div>
            {{-- <div class="mt-3 d-flex justify-content-end">
                {{ $transaksiJurnal->links() }}
            </div> --}}
        </div>  
    </div>
</div><!-- end col -->