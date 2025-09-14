<?php

namespace App\Http\Livewire\Parameter;

use Livewire\Component;
use App\Models\Jenjang;
use App\Models\TahunAjar;
use Illuminate\Support\Facades\Auth;

class SmartCanteen extends Component
{
    public $selectedJenjang = null;
    public $selectedTahunAjar = null;

    public $saldoKas;
    public $saldoBank;

    protected $listeners = [
        'refreshParameters',
        'refreshSaldo'
    ];

    public function updatedSelectedJenjang()
    {
        $this->checkAndEmitParameters();
    }

    public function updatedSelectedTahunAjar()
    {
        $this->checkAndEmitParameters();
    }

    public function mount()
    {
        // Tetapkan nilai pertama dari data yang tersedia jika ada
        $firstJenjang = Jenjang::whereIn('ms_jenjang_id', function ($query) {
            $query->select('ms_jenjang_id')
                ->from('ms_akses_jenjang')
                ->where('ms_pengguna_id', Auth::id());
        })->where('status', 'Aktif')->first();

        $firstTahunAjar = TahunAjar::where('status', 'Aktif')
            ->orderBy('urutan', 'asc')->first();

        $this->selectedJenjang = $firstJenjang->ms_jenjang_id ?? null;
        $this->selectedTahunAjar = $firstTahunAjar->ms_tahun_ajar_id ?? null;
    }

    private function checkAndEmitParameters()
    {
        // Emit hanya jika kedua parameter tidak null
        if ($this->selectedJenjang !== null && $this->selectedTahunAjar !== null) {
            $this->emit('parameterUpdated', $this->selectedJenjang, $this->selectedTahunAjar);
            $this->dispatchBrowserEvent('alertify-success', ['message' => 'Memperbarui...']);
        }
    }

    public function refreshParameters()
    {
        $this->selectedJenjang = null;
        $this->selectedTahunAjar = null;
        $this->emit('parameterUpdated', null, null);
    }
    
    public function render()
    {
        // Emit parameterUpdated saat komponen dirender pertama kali
        if ($this->selectedJenjang && $this->selectedTahunAjar) {
            $this->emit('parameterUpdated', $this->selectedJenjang, $this->selectedTahunAjar);
        }

        return view('livewire.parameter.smart-canteen', [
            'select_jenjang' => Jenjang::whereIn('ms_jenjang_id', function ($query) {
                $query->select('ms_jenjang_id')
                    ->from('ms_akses_jenjang')
                    ->where('ms_pengguna_id', Auth::id()); // Filter berdasarkan pengguna yang login
            })->where('status', 'Aktif')->get(),
            'select_tahun_ajar' => TahunAjar::where('status', 'Aktif')->orderBy('urutan', 'asc')->get(),
        ]);
    }
}
