<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use League\Csv\Reader;
use Validator;

class ProductsController extends Controller
{
    /**
     * Get the JSON for all products.
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        return Product::all();
    }

    /**
     * Get the JSON for a single product.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return $product;
    }

    // TODO: currently saves until it hits a missing field, then returns errors.
    // Should probably save valid rows, and return invalid row SKUs in JSON.
    // Translation, this is a mess and needs improvement.
    public function store(Request $request)
    {
        $rules = array(
            'sku'=>'required',
            'title'=>'required',
            'price'=>'required',
            'description'=>'required',
            'availability'=>'required',
            'color'=>'required',
            'dimensions'=>'required'
        );
        if ($request->hasFile('file'))
        {
            $uploadedFile = $request->file('file');
        }
        $reader = Reader::createFromPath($uploadedFile, 'r');
        $reader->setHeaderOffset(0);
        $rows = $reader->getRecords();
        $responseErrors = array();
        foreach ($rows as $offset => $record) {
            $errors = Validator::make(
                $record,
                $rules
            )->errors();
            if ($errors->any()) {
                // TODO: return meaningful errors
                $responseErrors[] = $errors;
            } else {
                $product = new Product();
                $product->sku = $record['sku'];
                $product->title = $record['title'];
                $product->description = $record['description'];
                $product->price = $record['price'];
                $product->availability = filter_var($record['availability'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
                $product->color = $record['color'];
                $product->dimensions = $record['dimensions'];
                
                if (Product::find($record['sku'])) {
                    $this->update($product);
                } else {
                    $product->save();
                }
            }
        }
        if (count($responseErrors)) {
            return $responseErrors;
        }
        return Product::all();
    }
    /**
     * Update the specified resource in storage.
     * Because this isn't publicly available, we don't re-validate after
     * validating in store()
     *
     * @param  \App\Product  $product
     */
    private function update(Product $product)
    {
        $target = Product::find($product->sku);
        $target->price = $product->price;
        $target->availability = $product->availability;
        //TODO error handling?
        $target->save();

    }



}
