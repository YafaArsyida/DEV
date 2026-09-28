<div class="mt-3 mt-lg-0">
    <div class="row g-3 mb-0 align-items-center">
        {{-- <div class="col-auto">
            <span class="fs-14 text-info">
                Saldo Tersedia : Rp{{ number_format($saldoPendapatanKantin, 0, ',', '.') }}
            </span>
        </div> --}}
        <!--end col-->
        <div class="col-sm-auto">
            <div class="input-group">
                <select wire:model="selectedKantin" class="form-select border-0 dash-filter-picker shadow" title="Pilih Kantin">
        
                    @foreach ($select_kantin as $item)
                    <option value="{{ $item->ms_kantin_id }}">
                        {{ $item->nama_kantin }}
                    </option>
                    @endforeach
                </select>
        
                <div class="input-group-text bg-primary text-white">
                    <i class="ri-store-2-line"></i>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('livewire:load', function () {
            Livewire.emit('parameterUpdated', @json($selectedKantin));
        });
    </script>
</div>
