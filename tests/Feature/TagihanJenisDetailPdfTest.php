<?php

namespace Tests\Feature;

use App\Http\Controllers\TagihanJenis;
use Illuminate\Http\Request;
use Tests\TestCase;

class TagihanJenisDetailPdfTest extends TestCase
{
    public function test_detail_pdf_requires_selected_jenis_tagihan(): void
    {
        $controller = new TagihanJenis();
        $request = new Request();

        $response = $controller->detailPDF($request);

        $this->assertSame(400, $response->getStatusCode());
        $this->assertJsonStringEqualsJsonString(
            json_encode(['error' => 'Data jenis tagihan wajib dipilih']),
            $response->getContent()
        );
    }
}
