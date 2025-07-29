<div class="container">
    <div class="row align-items-start justify-content-center gy-4">
        <!-- Kolom Foto & Dummy Card -->
        {{-- <div class="col-lg-5 col-sm-7">
            <div class="about-img-section mb-4 mb-lg-0 text-center">
                <div class="card feedback-box shadow">
                    <div class="card-body d-flex">
                        <div class="flex-grow-1">
                            <h5 class="fs-14 lh-base mb-0">
                                {{ $nama_siswa ?? 'Belum dipilih' }}
                            </h5>
                        </div>
                    </div>
                </div>
                <img src="{{ asset('assets/images/about.jpg') }}" alt="About"
                    class="img-fluid mx-auto rounded-3 mt-3" />
            </div>
        </div> --}}

        <!-- Form -->
        <div class="col-lg-6">
            <div class="">
                <h1 class="mb-3 lh-base fw-semibold">Daftar Ekstrakurikuler Favoritmu!</h1>
                <p class="fs-16">Bangun minat dan bakat sejak dini lewat kegiatan ekstrakurikuler seru di sekolah. Cari nama kamu, pilih ekskul yang diminati, dan langsung daftar sekarang!</p>

                <!-- Pilih Jenjang -->
                <div class="mb-3">
                    <div class="d-flex align-items-center mb-2">
                        <div class="flex-shrink-0 me-2">
                            <div class="avatar-xs icon-effect">
                                <div class="avatar-title bg-transparent text-success rounded-circle h2">
                                    <i class="ri-check-fill"></i>
                                </div>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <p class="mb-0">Jenjang</p>
                        </div>
                    </div>
                    <select wire:model="selectedJenjang" class="form-select">
                        <option value="">-- Pilih Jenjang--</option>
                        @foreach ($select_jenjang as $item)
                            <option value="{{ $item->ms_jenjang_id }}">{{ $item->nama_jenjang }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Cari Siswa -->
                <div class="mb-3">
                    <div class="d-flex align-items-center mb-2">
                        <div class="flex-shrink-0 me-2">
                            <div class="avatar-xs icon-effect">
                                <div class="avatar-title bg-transparent text-success rounded-circle h2">
                                    <i class="ri-check-fill"></i>
                                </div>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <p class="mb-0">Siswa {{ $nama_siswa }} - Kelas <strong>{{ $nama_kelas }}</strong></p>
                        </div>
                    </div>
                     <input type="text" class="form-control" placeholder="Pilih siswa..."  value="{{ $nama_siswa }}" wire:model.debounce.300ms="search">
                    {{-- Hasil Pencarian --}}
                    @if (!empty($search))
                        <div class="list-group mt-2 shadow-sm">
                            @forelse ($siswa as $siswa)
                                <button type="button" class="list-group-item list-group-item-action" wire:click="siswaSelected('{{ $siswa->ms_penempatan_siswa_id }}')">
                                    {{ $siswa->ms_siswa->nama_siswa }} - Kelas {{ $siswa->ms_kelas->nama_kelas }}
                                </button>
                            @empty
                                <div class="list-group-item text-muted">Tidak ada hasil</div>
                            @endforelse
                        </div>
                    @endif
                </div>

                <!-- Pilih Ekstrakurikuler -->
                <div class="mb-4">
                    <div class="d-flex align-items-center mb-2">
                        <div class="flex-shrink-0 me-2">
                            <div class="avatar-xs icon-effect">
                                <div class="avatar-title bg-transparent text-success rounded-circle h2">
                                    <i class="ri-check-fill"></i>
                                </div>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <p class="mb-0">Ekstrakurikuler</p>
                        </div>
                    </div>
                    <select wire:model="selectedEkstrakurikuler" class="form-select">
                        <option value="">-- Pilih --</option>
                        @foreach ($select_ekstrakurikuler as $item)
                            <option value="{{ $item->ms_ekstrakurikuler_id }}"
                                @if(in_array($ms_kelas_id, [8,9,10,11]) && $item->ms_ekstrakurikuler_id == 1) disabled @endif>
                                {{ $item->nama_ekstrakurikuler }} 
                                @if(in_array($ms_kelas_id, [8,9,10,11]) && $item->ms_ekstrakurikuler_id == 1)
                                    (Tidak tersedia untuk kelas 1-2)
                                @endif
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Tombol Daftar -->
                <div class="mt-3">
                    <button type="button" wire:click="daftar" class="btn btn-primary w-100">
                        <i class="ri-checkbox-circle-line me-1 align-middle"></i> Daftar Sekarang
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>