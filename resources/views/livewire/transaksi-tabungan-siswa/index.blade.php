<div wire:ignore.self class="offcanvas offcanvas-top" id="offcanvasTabungan" aria-labelledby="offcanvasTabunganLabel" style="min-height:100vh;">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title" id="offcanvasTabunganLabel">Tabungan Siswa</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <div class="row">
            <div class="col-lg-12">
                <!-- Tab panes -->
                <div class="tab-content text-muted">
                    <div class="tab-pane active" id="tabSiswaKelas" role="tabpanel">
                        <div class="row">
                            <div class="col-xxl-4">
                                <div class="sticky-side-div">
                                    <div class="card">
                                        @livewire('transaksi-tabungan-siswa.data-siswa')
                                    </div><!-- end card -->
                                </div>
                            </div>
                            <!--end col-->
                            <div class="col-xxl-8">
                                @livewire('transaksi-tabungan-siswa.data-tabungan')
                            </div>
                        </div>
                        <!--end row-->
                    </div>
                </div>
                <!--end tab-content-->
            </div>
        </div>
    </div>
</div>
