<div class="mt-3 mt-lg-0">
    <div class="row g-3 mb-0 align-items-center">
        <div class="col-sm-auto">
            <div class="input-group">
                <select wire:model="selectedJenjang" style="cursor: pointer" class="form-select border-0 dash-filter-picker shadow" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Pilih Jenjang">
                    {{-- <option value="" selected disabled>Pilih Jenjang</option> --}}
                    @foreach ($select_jenjang as $item)
                        <option value="{{ $item->ms_jenjang_id }}">{{ $item->nama_jenjang }}</option>
                    @endforeach
                </select>
                <div class="input-group-text bg-primary border-primary text-white">
                    <i class=" ri-government-line"></i>
                </div>
            </div>
        </div>
        <!--end col-->
        {{-- <div class="col-auto">
            <button type="button" class="btn btn-soft-success shadow-none">
                <i class="ri-add-circle-line align-middle me-1"></i> Export
            </button>
        </div>
        <!--end col-->
        <div class="col-auto">
            <button type="button" class="btn btn-soft-info btn-icon waves-effect waves-light layout-rightside-btn shadow-none">
                <i class="ri-pulse-line"></i>
            </button>
        </div> --}}
        <!--end col-->
    </div>
    <script>
        document.addEventListener('livewire:load', function () {
            Livewire.emit('parameterUpdated', @json($selectedJenjang));
        });
    </script>
</div>
