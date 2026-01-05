<div class="row justify-content-center g-0">
    {{-- If your happiness depends on money, you will never be happy with yourself. --}}
    <div class="col-lg-6">
        <div class="p-lg-5 p-4 h-100">
            {{-- <div class="bg-overlay"></div> --}}
            <div class="position-relative h-100 d-flex align-items-center justify-content-center">
                <div class="card shadow-lg border-0" style="max-width: 520px; width: 100%;">
                    <div class="card-header bg-white border-0 mb-4">
                        <!-- ================= LOGO & IDENTITAS ================= -->
                        <div class="text-center mb-4 pb-4">
                            <img src="{{ asset('assets/images/logo-sm.png') }}" alt="Logo Instansi" style="max-height: 90px;" class="mb-2">
    
                            <h5 class="fw-bold mb-0">
                                Yayasan Pandanaran
                            </h5>
                            <small class="text-muted">
                                <strong>
                                    {{ $listJenjang->firstWhere('ms_jenjang_id', $selectedJenjang)?->nama_jenjang }}
                                </strong>
                            </small>
                        </div>
    
                        <hr class="my-4">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
    
                            <!-- JUDUL -->
                            <div>
                                <h4 class="fw-bold mb-0">
                                    Absensi Pegawai
                                </h4>
                                <small class="text-muted">
                                    Presensi berbasis kartu RFID
                                </small>
                            </div>
    
                            <!-- SELECT JENJANG -->
                            <div class="d-flex align-items-center gap-2">
                                <div class="mt-3 mt-lg-0">
                                    <div class="row g-3 mb-0 align-items-center">
                                        <div class="col-sm-auto">
                                            <div class="input-group">
                                                <select wire:model="selectedJenjang" style="cursor: pointer"
                                                    class="form-select border-0 dash-filter-picker shadow"
                                                    data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top"
                                                    title="Pilih Jenjang">
                                                    @foreach ($listJenjang as $item)
                                                    <option value="{{ $item->ms_jenjang_id }}">
                                                        {{ $item->nama_jenjang }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                                <div class="input-group-text bg-primary border-primary text-white">
                                                    <i class=" ri-government-line"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <!--end col-->
                                    </div>
                                </div>
                            </div>
    
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row g-4">
                            {{-- barcode --}}
                            <div class="col-md-12">
    
                                <!-- Input Barcode -->
                                <label class="fw-semibold">Silakan scan kartu RFID pegawai</label>
    
                                <div class="input-group input-group-lg shadow-sm">
                                    <span class="input-group-text bg-primary text-white border-primary">
                                        <i class="ri-barcode-line fs-4"></i>
                                    </span>
    
                                    <input type="text" wire:model.lazy="barcodeInput" wire:keydown.enter="scanDariBarcode"
                                        class="form-control border-primary" placeholder="Scan / ketik kode kartu..."
                                        autofocus>
                                </div>
    
                                <small class="text-muted d-block mt-2">
                                    Sistem akan otomatis menentukan
                                    <strong>Masuk</strong> atau <strong>Pulang</strong>
                                </small>
    
                                <!-- INFO WAKTU -->
                                <div class="mt-4 text-muted small">
                                    <i class="ri-time-line me-1"></i>
                                    {{ date('d M Y H:i') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-6">
        <div class="p-lg-5 p-4 h-100">
            <div class="card shadow-sm h-100">
    
                <!-- HEADER -->
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ri-history-line me-1"></i>
                        Riwayat Absensi
                    </h5>
                    <small class="text-muted">
                        Data absensi hari ini
                    </small>
                </div>
    
                <!-- BODY -->
                <div class="card-body p-0">
                    <div class="mx-n3">
                        <div data-simplebar data-simplebar-auto-hide="false" style="max-height: 420px;" class="px-3">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light sticky-top">
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Jabatan</th>
                                        {{-- <th>Jenjang</th> --}}
                                        <th>Masuk</th>
                                        <th>Pulang</th>
                                        <th>Kode Kartu</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @forelse ($riwayatAbsensi as $index => $item)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td class="fw-semibold">
                                                {{ $item->ms_pegawai->nama_pegawai ?? '-' }}
                                            </td>

                                            <td class="text-muted">
                                                {{ $item->ms_pegawai->ms_jabatan->nama_jabatan ?? '-' }}
                                            </td>
                                            {{-- <td class="text-muted">
                                                {{ $item->ms_pegawai->ms_jenjang->nama_jenjang ?? '-' }}
                                            </td> --}}

                                            <td>
                                                {{ $item->jam_masuk ? substr($item->jam_masuk, 0, 5) : '-' }}
                                            </td>

                                            <td>
                                                {{ $item->jam_pulang ? substr($item->jam_pulang, 0, 5) : '-' }}
                                            </td>

                                            <td>
                                               {{ $item->kode_kartu ?? '-' }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-4">
                                                Belum ada presensi hari ini
                                            </td>
                                        </tr>
                                    @endforelse

                                </tbody>
                            </table>

    
                        </div>
                    </div>
                </div>
    
                <!-- FOOTER -->
                <div class="card-footer text-muted small text-center">
                    <i class="ri-refresh-line me-1"></i>
                    Update otomatis
                </div>
    
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('livewire:load', function () {
                Livewire.on('focusBarcode', () => {
                    let input = document.querySelector('input[wire\\:model.lazy="barcodeInput"]');
                    if (input) input.focus();
                });
            });
    </script>
</div>
