<?php

namespace App\Http\Livewire\PPDB\Pendaftaran;

use App\Models\PPDBBerkas;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class PreviewBerkas extends Component
{
    public $berkas;

    protected $listeners = [
        'showPreviewBerkas',
    ];

    public function showPreviewBerkas($berkasId)
    {
        $this->berkas = null;

        $berkas = PPDBBerkas::with('ppdb_pendaftaran')
            ->findOrFail($berkasId);

        $user = Auth::user();
        $pendaftaran = $berkas->ppdb_pendaftaran;

        abort_unless($pendaftaran, 404);

        $isPemilik = (string) $pendaftaran->ms_pengguna_id
            === (string) $user->getAuthIdentifier();

        $isAdmin = in_array(
            strtoupper($user->peran ?? ''),
            ['SUPERADMIN', 'ADMIN', 'KEUANGAN', 'PANITIA_PPDB'],
            true
        );

        abort_unless($isPemilik || $isAdmin, 403);

        abort_unless(
            in_array($berkas->mime_type, [
                'image/webp',
                'image/jpeg',
                'image/png',
                'application/pdf',
            ], true),
            415
        );

        $this->berkas = [
            'id'        => $berkas->ppdb_berkas_id,
            'jenis'     => $berkas->jenis_berkas,
            'nama_file' => $berkas->nama_file,
            'mime_type' => $berkas->mime_type,
            'url' => $isAdmin
                ? route(
                    'ppdb.admin.berkas.preview',
                    $berkas->ppdb_berkas_id
                )
                : route(
                    'ppdb.berkas.preview',
                    $berkas->ppdb_berkas_id
                ),
        ];
    }

    public function render()
    {
        return view('livewire.p-p-d-b.pendaftaran.preview-berkas');
    }
}
