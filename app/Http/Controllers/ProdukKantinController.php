<?php

namespace App\Http\Controllers;

use App\Models\ProdukKantin;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProdukKantinRequest;
use App\Http\Requests\UpdateProdukKantinRequest;

class ProdukKantinController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProdukKantinRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ProdukKantin $produkKantin)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProdukKantin $produkKantin)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProdukKantinRequest $request, ProdukKantin $produkKantin)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProdukKantin $produkKantin)
    {
        //
    }
}
