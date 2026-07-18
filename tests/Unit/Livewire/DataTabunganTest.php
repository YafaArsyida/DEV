<?php

namespace Tests\Unit\Livewire;

use App\Http\Livewire\TransaksiTabunganSiswa\DataTabungan;
use Illuminate\Pagination\LengthAwarePaginator;
use Tests\TestCase;

class DataTabunganTest extends TestCase
{
    public function test_paginate_collection_uses_requested_page_size()
    {
        $component = new DataTabungan();
        $items = collect(range(1, 45));

        $paginator = $component->paginateCollection($items, 40);

        $this->assertInstanceOf(LengthAwarePaginator::class, $paginator);
        $this->assertSame(40, $paginator->perPage());
        $this->assertSame(45, $paginator->total());
        $this->assertCount(40, $paginator->items());
        $this->assertSame(1, $paginator->currentPage());
    }
}
