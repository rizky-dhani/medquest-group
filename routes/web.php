<?php

use App\Livewire\Public\DetailAsset;
use App\Models\ITAsset;
use Illuminate\Support\Facades\Route;
use Spatie\Browsershot\Browsershot;

Route::get('public/it-assets/details/{assetId}', DetailAsset::class)
    ->name('assets.show');
Route::get('/assets/bulk-export-pdf/export', function () {
    $ids = session()->get('export_asset_ids', []);
    $assets = ITAsset::whereIn('id', $ids)->get();
    // dd($assets);
    $html = view('pdf.assets-list', compact('assets'));
    $filename = 'IT-ASSETS-'.now()->format('Y-m-d').'.pdf';
    $pdf = Browsershot::html($html)
        ->format('A4')
        ->showBackground()
        ->pdf();

    // return $html;
    return response($pdf, 200)
        ->header('Content-Type', 'application/pdf')
        ->header('Content-Disposition', 'inline; filename="'.$filename.'"');

})->name('assets.bulk-export-pdf.export');
