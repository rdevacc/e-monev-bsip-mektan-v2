@extends('layouts.app-v2')

@section('title')
    Panduan PJ | E-Monev BBRM Mektan
@endsection

@section('content')
    <main id="main" class="main">

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <!-- Card Body -->

                        <h4 class="card-title">Panduan Penggunaan Website E-Monev BRMP Mektan - Role PJ</h4>
                        
                        @if($fileExists)
                            <div class="d-flex justify-content-start mb-3">
                                <a href="{{ route('guide.pdf', $pdfFile) }}" 
                                   class="btn btn-primary py-2 px-4" 
                                   download>
                                    <iconify-icon icon="bi: download" width="18" height="18" style="margin-right: 8px;"></iconify-icon>
                                    Download PDF
                                </a>
                            </div>

                            <!-- PDF Viewer -->
                            <div class="pdf-viewer-container" style="width: 100%; height: 800px; border: 1px solid #ddd; border-radius: 4px; overflow: hidden;">
                                <iframe 
                                    src="{{ route('guide.pdf', $pdfFile) }}" 
                                    width="100%" 
                                    height="100%" 
                                    style="border: none;">
                                    <p>Browser Anda tidak mendukung PDF viewer. 
                                        <a href="{{ route('guide.pdf', $pdfFile) }}" download>Download PDF di sini</a>
                                    </p>
                                </iframe>
                            </div>
                        @else
                            <div class="alert alert-warning" role="alert">
                                <iconify-icon icon="bi: exclamation-triangle" width="20" height="20" style="margin-right: 8px;"></iconify-icon>
                                File panduan tidak ditemukan.  Silakan hubungi administrator.
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@push('styles')
<style>
    .pdf-viewer-container {
        background: #f8f9fa;
    }
    
    .pdf-viewer-container iframe {
        background: white;
    }
</style>
@endpush