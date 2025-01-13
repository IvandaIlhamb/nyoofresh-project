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
                $query->whereHas('user_produk', function ($query) {
                    $query->whereHas('roles', function ($query) {
                        $query->where('name', 'dropping');
                    });
                });
            });
        })->get();

    // Kirim data ke view PDF
    $pdf = PDF::loadView('pdf.PDFRekapDropping', compact('hasilPenjualan'));

    // Unduh PDF
    return $pdf->download('PDFRekapDropping' . '.pdf');
    }

    public function index()
    {
        $dataHasil = HasilPenjualan::with('produk')->get();

        // Menghitung total keuntungan
        $totalKeuntungan = $dataHasil->sum(function ($hasil) {
            return $hasil->keuntungan;
        });

        return view('pdf.PDFRekapLapak', compact('dataHasil', 'totalKeuntungan'));
    }


    public function generatePDFRekapLapak()
    {

        $hasilPenjualan = HasilPenjualan::query()
        ->whereHas('suplai', function ($query) {
            $query->whereHas('produk', function ($query) {
                $query->whereHas('user_produk', function ($query) {
                    $query->whereHas('roles', function ($query) {
                        $query->where('name', 'penjaga lapak');
                    });
                });
            });
        })->get();

    // Kirim data ke view PDF
    $pdf = PDF::loadView('pdf.PDFRekapLapak', compact('hasilPenjualan'));

    // Unduh PDF
    return $pdf->download('PDFRekapLapak' . '.pdf');
    }
}
