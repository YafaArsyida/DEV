<?php

namespace App\Http\Controllers;

use App\Models\ModelLogPeraga;
use App\Models\ModelPeraga;
use App\Models\ModelPerangkatPembaca;
use Illuminate\Http\Request;
use App\Models\ModelRFID;
use App\Models\ModelVideo;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $jumlah_perangkat = ModelPerangkatPembaca::count();
        // $jumlah_video = ModelVideo::count();
        // $jumlah_peraga = ModelPeraga::count();
        // $jumlah_pemutaran = ModelLogPeraga::where('keterangan', 'memutar video')->count();

        return view('DASHBOARD.v_index', [
            'jumlah_perangkat' => 1,
            'jumlah_video' => 2,
            'jumlah_peraga' => 3,
            'jumlah_pemutaran' => 4,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
