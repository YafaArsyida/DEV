<?php

namespace App\Http\Livewire\SmartCanteen\Kantin;

use App\Models\SmartCanteen\Kantin;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap'; // Gunakan tema Bootstrap

    public $search = '';

    public $perPage = 50;

    protected $listeners = [
        'refreshKantin' => '$refresh',
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function getAllKantinProperty()
    {
        return Kantin::query()
            ->with('ms_pengguna')
            ->when($this->search, function ($query) {
                $query->where(function ($subQuery) {
                    $subQuery->where('nama_kantin', 'like', '%' . $this->search . '%')
                        ->orWhere('deskripsi', 'like', '%' . $this->search . '%');
                });
            })
            ->latest()
            ->paginate($this->perPage);
    }

    public function render()
    {
        return view('livewire.smart-canteen.kantin.index',[
            'kantin' => $this->allKantin
        ]);
    }
}
