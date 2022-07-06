<?php

namespace App\Http\Controllers;

use App\Models\ProductLike;
use App\Http\Requests\StoreProductLikeRequest;
use App\Http\Requests\UpdateProductLikeRequest;

class ProductLikeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreProductLikeRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductLikeRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProductLike  $productLike
     * @return \Illuminate\Http\Response
     */
    public function show(ProductLike $productLike)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateProductLikeRequest  $request
     * @param  \App\Models\ProductLike  $productLike
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductLikeRequest $request, ProductLike $productLike)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProductLike  $productLike
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductLike $productLike)
    {
        //
    }
}
