<?php

namespace App\Http\Livewire\Jenjang;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Jenjang as JenjangModel;

class Index extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap'; // Gunakan tema Bootstrap

    public $search = '';
    public $selectedStatus = null;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingSelectedStatus()
    {
        $this->resetPage();
    }

    protected $listeners = ['refreshJenjangs' => '$refresh']; // Gunakan Livewire refresh untuk memuat ulang data

    public function toggleStatus($jenjangId, $status)
    {
        $jenjang = JenjangModel::find($jenjangId);

        if ($jenjang) {
            $jenjang->status = $status ? 'Aktif' : 'Tidak Aktif';
            $jenjang->save();

            $this->dispatchBrowserEvent('alertify-success', ['message' => 'Status berhasil diubah!']);
            $this->emit('refreshJenjangs');
        } else {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Gagal mengubah status. Data tidak ditemukan.']);
        }
    }

    public function render()
    {
        $query = JenjangModel::query();

        if ($this->selectedStatus) {
            $query->where('status', $this->selectedStatus);
        }

        $jenjangs = $query->where(function ($query) {
            $query->where('nama_jenjang', 'like', '%' . $this->search . '%')
                ->orWhere('deskripsi', 'like', '%' . $this->search . '%');
        })
            ->orderBy('urutan', 'ASC')
            ->paginate(50);

        return view('livewire.jenjang.index', [
            'jenjangs' => $jenjangs,
        ]);
    }
}
