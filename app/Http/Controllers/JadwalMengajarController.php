<?php

namespace App\Http\Controllers;

use App\Models\JadwalMengajar;
use App\Http\Requests\StoreJadwalMengajarRequest;
use App\Http\Requests\UpdateJadwalMengajarRequest;

class JadwalMengajarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('akademik.jadwal-mengajar');
    }

    /**
     * Display the authenticated teacher's own schedule.
     */
    public function saya()
    {
        return view('akademik.jadwal-mengajar-saya');
    }
}
