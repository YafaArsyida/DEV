<?php

namespace App\Http\Livewire\TES;

use Livewire\Component;
use Illuminate\Support\Facades\Storage;
use Elibyy\TCPDF\Facades\TCPDF as PDF;


class PrintPdf extends Component
{
    public function generatePdf()
    {
        // Buat file PDF
        $data = ['title' => 'Laporan', 'content' => 'Ini adalah laporan PDF'];
        $pdf = PDF::loadView('PDF.template', $data);

        // Kirim output PDF ke browser untuk ditampilkan langsung
        return response($pdf->output(), 200)
            ->header('Content-Type', 'application/pdf');
    }

    public function render()
    {
        return view('livewire.t-e-s.print-pdf');
    }
}
