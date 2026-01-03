<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UsageGuideController extends Controller
{
    public function pj(){
        $pdfFile = "panduan-pj.pdf";

        // Cek apakah file ada
        $fileExists = file_exists(storage_path('app/public/' . $pdfFile));

        return view('apps.guide.pj', compact('pdfFile', 'fileExists'));
    }

    public function admin(){
        $pdfFile = "panduan-admin.pdf";

        // Cek apakah file ada
        $fileExists = file_exists(storage_path("app/public/" . $pdfFile));

        return view('apps.guide.admin', compact('pdfFile', 'fileExists'));
    }

   public function showPdf($filename){
        // Log untuk debug
        Log::info('showPdf called with filename: ' .  $filename);
        
        // Validasi filename
        $allowedFiles = [
            'panduan-pj.pdf',
            'panduan-admin.pdf'
        ];

        if (!in_array($filename, $allowedFiles)) {
            Log::error('File not allowed: ' . $filename);
            abort(404, 'File tidak diizinkan');
        }

        // Path ke file
        $path = storage_path('app/public/' . $filename);
        
        Log::info('Looking for file at: ' . $path);
        Log::info('File exists: ' . (file_exists($path) ? 'YES' : 'NO'));

        // Cek apakah file ada
        if (!file_exists($path)) {
            Log::error('File not found at: ' . $path);
            abort(404, 'File PDF tidak ditemukan di:  ' . $path);
        }

        Log::info('Serving PDF file: ' . $filename);

        // Return PDF
        return response()->file($path, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' .  $filename . '"'
        ]);
    }
}
