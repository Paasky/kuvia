<?php

namespace App\Http\Controllers;

use App\Collage;
use App\Permissions\SalePermissions;
use App\Product;
use App\Purchase;

class PurchaseController extends Controller
{
    public function create(int $productId, int $collageId) : Purchase
    {
        $collage = Collage::findOrFail($collageId);
        $product = Product::findOrFail($productId);
        SalePermissions::createPurchase($product, $collage);
        return Purchase::create([
            'product_id' => $product->id,
            'collage_id' => $collage->id,
        ]);
    }

    public function list() : array
    {
        SalePermissions::viewAllPurchases();
        return Purchase::all();
    }

    public function get(int $id) : Purchase
    {
        $purchase = Purchase::findOrFail($id);
        SalePermissions::viewPurchase($purchase);
        return $purchase;
    }
}
