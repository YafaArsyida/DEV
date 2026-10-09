<?php

namespace App\Http\Controllers\Ppdb;

use App\Http\Controllers\Controller;

class PpdbLandingController extends Controller
{
    public function index()
    {
        return view('ppdb.landing.index');
    }

    public function register()
    {
        return view('ppdb.auth.register');
    }

    public function login()
    {
        return view('ppdb.auth.login');
    }

    public function dashboard()
    {
        return view('ppdb.portal.dashboard');
    }

    public function dataSiswa()
    {
        return view('ppdb.portal.data-siswa');
    }

    public function orangTua()
    {
        return view('ppdb.portal.orang-tua');
    }

    public function alamat()
    {
        return view('ppdb.portal.alamat');
    }

    public function pendidikan()
    {
        return view('ppdb.portal.pendidikan');
    }

    public function berkas()
    {
        return view('ppdb.portal.berkas');
    }

    public function review()
    {
        return view('ppdb.portal.review');
    }

    public function status()
    {
        return view('ppdb.portal.status');
    }

    public function buktiPendaftaran()
    {
        return view('ppdb.portal.bukti-pendaftaran');
    }
}
