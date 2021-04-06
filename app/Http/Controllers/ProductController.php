<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductVariantPrice;
use App\Models\Variant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index()
    {
        $products = Product::paginate(2);
        foreach($products as $key => $product)
        {
            $products_variant_prices[$key] = ProductVariantPrice::where('product_id',$product->id)->get();
            foreach ($products_variant_prices[$key] as $key2 => $variant_price)
            {
                $product_variant[$key][$key2] = ProductVariant::where('product_id',$product)
                                                               ->where('id',$variant_price->product_variant_one)
                                                                ->orWhere('id',$variant_price->product_variant_two)
                                                               ->orWhere('id',$variant_price->product_variant_three)
                                                               ->get();
            }
        }
        $variants = Variant::get();
        $data = array(
            'products' => $products,
            'products_variant_prices' => $products_variant_prices,
            'product_variant' => $product_variant
        );

        return view('products.index',compact('variants','data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function create()
    {
        $variants = Variant::all();
        return view('products.create', compact('variants'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $count = array();
        try{
            $product_insert = Product::create($data);
            foreach($data['product_variant'] as $variant)
            {
                // dd($variant['option']);
                foreach ($variant['tags'] as $tag)
                {
                    $product_variant_insert = new ProductVariant();
                    $product_variant_insert->variant = $tag;
                    $product_variant_insert->variant_id = $variant['option'];
                    $product_variant_insert->product_id = $product_insert->id;
                    $product_variant_insert->save();
                    $count[] = $product_variant_insert->id;

                }

            }
            $product_variant_price_insert = new ProductVariantPrice();
            $product_variant_price_insert->product_variant_one = $count[0] ? $count[0] : null;
            $product_variant_price_insert->product_variant_two = $count[1] ? $count[1] : null;
            $product_variant_price_insert->product_variant_three = $count[2] ? $count[2] : null;
            $product_variant_price_insert->price = $request->product_variant_prices[0]['price'];
            $product_variant_price_insert->stock = $request->product_variant_prices[0]['stock'];
            $product_variant_price_insert->product_id = $product_insert->id;
            $product_variant_price_insert->save();
            return response()->json([
                'message' => "Insert Successfully",
            ]);
        }
        catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ]);
            //return $e->getMessage();
        }



    }


    /**
     * Display the specified resource.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function show($product)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $variants = Variant::all();
        return view('products.edit', compact('variants'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }
    public function productSearch(Request $request)
    {
        $form_data = $request->all();
        $products_variant_prices = null;
        $product_variant = null;
        if(!empty($request->title))
        {
            $products = Product::where('title', 'like', "%{$request->title}%")->paginate(2);
        }
        else{
            $products = Product::paginate(2);
        }
        foreach($products as $key => $product)
        {
            $products_variant_prices_pre = ProductVariantPrice::where('product_id',$product->id);
            if(!empty($request->price_from) && !empty($request->price_to)){
                $products_variant_prices_pre->whereBetween('price', [$request->price_from, $request->price_to]);
            }
            $products_variant_prices[$key] = $products_variant_prices_pre->get();

            foreach ($products_variant_prices[$key] as $key2 => $variant_price)
            {
                $product_variant_pre = ProductVariant::where('product_id',$product);
                if(!empty ($request->variant))
                {
                    $product_variant_pre->where('variant',$request->variant);
                }
                $product_variant_pre->where('id',$variant_price->product_variant_one);
                $product_variant_pre->orWhere('id',$variant_price->product_variant_two);
                $product_variant_pre->orWhere('id',$variant_price->product_variant_three);
                $product_variant[$key][$key2] = $product_variant_pre->get();
            }
        }
        $variants = Variant::get();
        $data = array(
            'products' => $products,
            'products_variant_prices' => $products_variant_prices,
            'product_variant' => $product_variant
        );

        return view('products.index',compact('variants','data'));
    }
}
