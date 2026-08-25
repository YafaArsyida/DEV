<?php

namespace App\Http\Livewire\SmartCanteen\AksesKantin;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $perPage = 50;

    protected $listeners = [
        'refreshPengguna' => '$refresh',
        'refreshKantin' => '$refresh',
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function getAllPenggunaProperty()
    {
        return User::query()
            ->with('ms_kantin')
            ->where('peran', 'KANTIN')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('nama', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            })
            ->latest('ms_pengguna_id')
            ->paginate($this->perPage);
    }

    public function render()
    {
        return view('livewire.smart-canteen.akses-kantin.index', [
            'pengguna' => $this->allPengguna,
        ]);
    }
}
