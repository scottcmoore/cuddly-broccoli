<?php

namespace App\Http\Controllers;

use App\Product;
use App\Rules\RequiredHeaders;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use League\Csv\Reader;
use Validator;

class ProductsController extends Controller
{
    protected $requiredHeadersArray = array(
        'sku',
        'title',
        'description',
        'price',
        'availability',
        'color',
        'dimensions',
    );

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

    /**
     * Validate a CSV of items and store them in the database.
     * 
     * @param   Illuminate\Http\Request $request
     */
    public function store(Request $request)
    {   
        if ($request->hasFile('inputCSV')) {
            $uploadedFile = $request->file('inputCSV');
        } else {
            return response('Bad Request: Please attach a file', 422)->header('Content-Type', 'text/plain');
        }

        $reader = Reader::createFromPath($uploadedFile, 'r');
        $reader->setHeaderOffset(0);

        // Use a placeholder field for header row validation:
        $request->replace(['csvHeader' => $reader->getHeader()]);
        
        // Merge the CSV fields into a request array so we can validate with '*' notation.
        // This doesn't feel very tidy -- although it makes validation simpler, in the real world we probably
        // need custom validation object of some kind anyways, so I'm not sure this approach is a good idea.
        $products = array();
        foreach ($reader as $key=>$value) {
            $products[] = $value;
        }
        $request->request->add(["products" => $products]);

        // Since we're doing our validation inline here, the error messages aren't as useful as they
        // could be with a more custom approach to validation.
        $request->validate([
            'csvHeader' => [new RequiredHeaders],
            'products.*.sku' => 'required',
            'products.*.title' => 'required',
            'products.*.description' => 'required',
            'products.*.price' => 'required',
            'products.*.availability' => 'required',
            'products.*.color' => 'required',
            'products.*.dimensions' => 'required'
        ]);
       
        foreach ($request->input('products') as $index=>$product) {
            if ($target = Product::find($product['sku'])) {
                $this->update($target, $product);
            } else {
                $target = new Product;
                $target->sku = $product['sku'];
                $target->title = $product['title'];
                $target->description = $product['description'];
                $target->price = $product['price'];
                $target->availability = $product['availability'] === FALSE ? FALSE : filter_var($product['availability'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
                $target->color = $product['color'];
                $target->dimensions = $product['dimensions'];
                $target->save();
            }
        }
        return response("", 200);
    }
    /**
     * Update the specified resource in storage.
     * Because this isn't publicly available, we don't re-validate after
     * validating in store()
     *
     * @param   \App\Product    $target
     * @param   array   $update
     */
    private function update(Product $target, array $update)
    {
        $target->price = $update['price'];
        $target->availability = $update['availability'] === FALSE ? FALSE : filter_var($update['availability'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE); 
        $target->save();
    }
}
