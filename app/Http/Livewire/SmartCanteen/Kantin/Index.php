<?php

namespace App\Http\Livewire\SmartCanteen\Kantin;

use App\Models\SmartCanteen\Kantin;
use Livewire\Component;

class Index extends Component
{
    public $search = '';

    protected $listeners = [
        'refreshKantin' => '$refresh',
    ];

    public function getAllKantinProperty()
    {
        return Kantin::query()
            ->with('ms_pengguna') // ✅ penting
            ->when($this->search, function ($q) {
                $q->where(function ($sub) {
                    $sub->where('nama_kantin', 'like', '%' . $this->search . '%')
                        ->orWhere('deskripsi', 'like', '%' . $this->search . '%');
                });
            })
            ->latest()
            ->get();
    }

    public function render()
    {
        return view('livewire.smart-canteen.kantin.index',[
            'kantin' => $this->allKantin
        ]);
    }
}
