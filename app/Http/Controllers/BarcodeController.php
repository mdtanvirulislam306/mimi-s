<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Milon\Barcode\DNS1D;
use Milon\Barcode\Facades\DNS1DFacade;


class BarcodeController extends Controller
{
    public function index()
    {
        $products = Product::select('id', 'name', 'barcode')->get();

        return view('backend.product.barcode.index', compact('products'));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
            'quantity'   => 'required|integer|min:1',
            'per_row'    => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);
        $quantity = $request->quantity;
        $perRow = $request->per_row;

        $barcodeImage = DNS1DFacade::getBarcodePNG($product->barcode, 'C128');

        return response()->json([
            'product'      => $product,
            'quantity'     => $quantity,
            'perRow'       => $perRow,
            'barcodeImage' => $barcodeImage
        ]);
    }
}
