<?php

namespace App\Http\Controllers;

use App\Models\HasilPenjualan;
use PDF;
use Illuminate\Http\Request;

class PDFController extends Controller
{
    public function generatePDFRekapDropping()
    {

        $hasilPenjualan = HasilPenjualan::query()
        ->whereHas('suplai', function ($query) {
            $query->whereHas('produk', function ($query) {
                $query->where('lapak', 'Diluar Nyoofresh');
            });
        })->get();

    // Kirim data ke view PDF
    $pdf = PDF::loadView('pdf.PDFRekapDropping', compact('hasilPenjualan'));

    // Unduh PDF
    return $pdf->download('PDFRekapDropping' . '.pdf');
    }
}
