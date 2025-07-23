<?php

namespace App\Http\Livewire\Parameter;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Jenjang as ModelsJenjang;

class Jenjang extends Component
{
    public $selectedJenjang = null;

    public function updatedSelectedJenjang()
    {
        $this->checkAndEmitParameters();
    }

    public function mount()
    {
        // Tetapkan nilai pertama dari data yang tersedia jika ada
        $firstJenjang = ModelsJenjang::whereIn('ms_jenjang_id', function ($query) {
            $query->select('ms_jenjang_id')
                ->from('ms_akses_jenjang')
                ->where('ms_pengguna_id', Auth::id());
        })->where('status', 'Aktif')->first();

        $this->selectedJenjang = $firstJenjang->ms_jenjang_id ?? null;
    }

    private function checkAndEmitParameters()
    {
        // Emit hanya jika kedua parameter tidak null
        if ($this->selectedJenjang !== null) {
            $this->emit('parameterUpdated', $this->selectedJenjang);
            $this->dispatchBrowserEvent('alertify-success', ['message' => 'Memperbarui...']);
        }
    }

    public function refreshParameters()
    {
        $this->selectedJenjang = null;
        $this->emit('parameterUpdated', null, null);
    }

    public function render()
    {
        // Emit parameterUpdated saat komponen dirender pertama kali
        if ($this->selectedJenjang) {
            $this->emit('parameterUpdated', $this->selectedJenjang);
        }

        return view('livewire.parameter.jenjang', [
            'select_jenjang' => ModelsJenjang::whereIn('ms_jenjang_id', function ($query) {
                $query->select('ms_jenjang_id')
                    ->from('ms_akses_jenjang')
                    ->where('ms_pengguna_id', Auth::id()); // Filter berdasarkan pengguna yang login
            })->where('status', 'Aktif')->get(),
        ]);
    }
}
