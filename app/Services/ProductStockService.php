<?php

namespace App\Services;

use AizPackages\CombinationGenerate\Services\CombinationService;
use App\Models\ProductStock;
use App\Utility\ProductUtility;

class ProductStockService
{
    // public function store(array $data, $product)
    // {
    //     $collection = collect($data);

    //     $options = ProductUtility::get_attribute_options($collection);
        
    //     //Generates the combinations of customer choice options
    //     $combinations = (new CombinationService())->generate_combination($options);
        
    //     $variant = '';
    //     if (count($combinations) > 0) {
    //         $product->variant_product = 1;
    //         $product->save();
    //         foreach ($combinations as $key => $combination) {
    //             $str = ProductUtility::get_combination_string($combination, $collection);
    //             $product_stock = new ProductStock();
    //             $product_stock->product_id = $product->id;
    //             $product_stock->variant = $str;
    //             $product_stock->price = request()['price_' . str_replace('.', '_', $str)];
    //             $product_stock->barcode = generateRandomCode('MM');
    //             $product_stock->sku = request()['sku_' . str_replace('.', '_', $str)] ?? generateRandomCode('SKU');
    //             $product_stock->qty = request()['qty_' . str_replace('.', '_', $str)];
    //             $product_stock->image = request()['img_' . str_replace('.', '_', $str)];
    //             $product_stock->save();
    //         }
    //     } else {
    //        // dd($collection);
    //         unset($collection['colors_active'], $collection['colors'], $collection['choice_no']);
    //         $qty = $collection['current_stock'];
    //         $price = $collection['unit_price'];
    //         $barcode =  generateRandomCode('MM');
    //         $sku =  generateRandomCode('SKU');
    //         unset($collection['current_stock']);

    //         $data = $collection->merge(compact('variant', 'qty', 'price','barcode','sku'))->toArray();
    //         //dd($data);
    //         ProductStock::create($data);
    //     }
    // }
    
public function store(array $data, $product)
{
    
    $collection = collect($data);

    $options = ProductUtility::get_attribute_options($collection);

    // Generate all possible combinations
    $combinations = (new CombinationService())->generate_combination($options);

    $variant = '';

    if (count($combinations) > 0) {
        $product->variant_product = 1;
        $product->save();

        // Request থেকে আসা সব SKU রাখার জন্য
        $incomingSkus = [];

        foreach ($combinations as $combination) {
            $str = ProductUtility::get_combination_string($combination, $collection);
            $sku = request()['sku_' . str_replace('.', '_', $str)] ?? null;
            $incomingSkus[] = $sku;

            // আগের DB থেকে sku খুঁজি
            $existingStock = ProductStock::where('product_id', $product->id)
                ->where('sku', $sku)
                ->first();
            if ($existingStock) {
                // যদি SKU আগে থেকেই থাকে → শুধু update হবে (sku & barcode ছাড়া সব)
                $existingStock->update([
                    'variant'    => $str,
                    'price'      => request()['price_' . str_replace('.', '_', $str)],
                    'qty'        => request()['qty_' . str_replace('.', '_', $str)],
                    'image'      => request()['img_' . str_replace('.', '_', $str)],
                ]);
            } else {
                // নতুন SKU হলে create হবে
                ProductStock::create([
                    'product_id' => $product->id,
                    'variant'    => $str,
                    'sku'        => $sku ?? generateRandomCode('SKU'),
                    'barcode'    => generateRandomCode('MM'),
                    'price'      => request()['price_' . str_replace('.', '_', $str)],
                    'qty'        => request()['qty_' . str_replace('.', '_', $str)],
                    'image'      => request()['img_' . str_replace('.', '_', $str)],
                ]);
            }
        }

        // যেসব SKU DB তে আছে কিন্তু request এ নাই → সেগুলো delete হবে
        ProductStock::where('product_id', $product->id)
            ->whereNotIn('sku', $incomingSkus)
            ->delete();

    } else {
        // Simple Product (no variant)
        unset($collection['colors_active'], $collection['colors'], $collection['choice_no']);

        $qty = $collection['current_stock'];
        $price = $collection['unit_price'];
        $barcode = generateRandomCode('MM');
        $sku = generateRandomCode('SKU');
        unset($collection['current_stock']);

        $data = $collection->merge(compact('variant', 'qty', 'price','barcode','sku'))->toArray();

        ProductStock::updateOrCreate(
            ['product_id' => $product->id], // যদি আগে থেকে থাকে update করবে
            $data
        );
    }
}

    
    public function product_duplicate_store($product_stocks , $product_new)
    {
        foreach ($product_stocks as $key => $stock) {
            $product_stock              = new ProductStock;
            $product_stock->product_id  = $product_new->id;
            $product_stock->variant     = $stock->variant;
            $product_stock->price       = $stock->price;
            $product_stock->sku         = $stock->sku;
            $product_stock->qty         = $stock->qty;
            $product_stock->save();
        }
    }
}
