{{-- Be like water. --}}
<div wire:ignore.self class="offcanvas offcanvas-top" id="offcanvasEduPay" aria-labelledby="offcanvasEduPayLabel" style="min-height:100vh;">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title" id="offcanvasEduPayLabel">EduPay - Uang Digital Teman Sekolah</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <div class="row">
            <div class="col-lg-12">
                <!-- Tab panes -->
                <div class="tab-content text-muted">
                    <div class="tab-pane active" id="tabSiswaKelas" role="tabpanel">
                        <div class="row">
                            <div class="col-xxl-4 col-md-4">
                                <div class="sticky-side-div">
                                    <div class="card">
                                        @livewire('transaksi-edu-pay-siswa.data-siswa')
                                    </div><!-- end card -->
                                </div>
                            </div>
                            <!--end col-->
                            <div class="col-xxl-8 col-md-8">
                                @livewire('transaksi-edu-pay-siswa.data-edu-pay')
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
