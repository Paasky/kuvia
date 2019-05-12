<?php

namespace App\Http\Controllers;

use App\Permissions\SalePermissions;
use App\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function create(Request $request) : Product
    {
        SalePermissions::createProduct();
        return Product::create($request->all());
    }

    public function list() : array
    {
        SalePermissions::viewAllProducts();
        return Product::all();
    }

    public function get(int $id) : Product
    {
        $product = Product::findOrFail($id);
        SalePermissions::viewProduct($product);
        return $product;
    }

    public function edit(Request $request, int $id) : Product
    {
        $product = Product::findOrFail($id);
        SalePermissions::editProduct($product);
        $product->update($request->all());
        return $product;
    }

    public function enable(int $id) : Product
    {
        $product = Product::findOrFail($id);
        SalePermissions::toggleProduct();
        $product->update(['disabled_at' => null]);
        return $product;
    }

    public function disable(int $id) : Product
    {
        $product = Product::findOrFail($id);
        SalePermissions::toggleProduct();
        $product->update(['disabled_at' => Carbon::now()]);
        return $product;
    }

    /**
     * @param int $id
     * @return bool
     * @throws \Exception
     */
    public function delete(int $id) : bool
    {
        $product = Product::findOrFail($id);
        SalePermissions::deleteProduct($product);
        $product->delete();
        return true;
    }
}
