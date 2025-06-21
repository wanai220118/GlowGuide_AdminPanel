<?php

namespace App\Exports;

use App\Models\Product;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Response;

class ProductPdfExporter
{
    public static function export()
    {
        $products = Product::with('category')->get();

        $pdf = Pdf::loadView('exports.products', [
            'products' => $products,
        ]);

        return Response::streamDownload(
            fn () => print($pdf->stream()),
            'products.pdf'
        );
    }
}
