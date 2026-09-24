<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ScannerController extends Controller
{
    /**
     * Tampilkan halaman scanner E-Ticket.
     */
    public function index(): View
    {
        return view('admin.scanner.index');
    }
}
