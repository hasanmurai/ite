<?php

namespace App\Http\Controllers;



use App\Models\Product;
use App\Models\ProductLike;
use Illuminate\Http\Request;

class ProductLikeController extends Controller
{
    public function like(Request $request,$id)
    {
        if ($request->user()->tokenCan('user'))
        {
            if (Product::query()->where('id',$id)->exists()){
                if (ProductLike::query()->where(['product_id'=>$id,'user_id'=>auth()->id()])->doesntExist()){
                    ProductLike::query()->create([
                        'user_id'=>auth()->id(),
                        'product_id'=>$id
                    ]);
                    Product::query()->find($id)->increment('likes');

                    return response()->json(['message'=>'like added']);
                }
                else{
                    ProductLike::query()->where(['product_id'=>$id,'user_id'=>auth()->id()])->delete();
                    Product::query()->find($id)->decrement('likes');

                    return response()->json(['message'=>'like removed']);
                }
            } else
                return response()->json(['message'=>'product not found']);
        }
        else
            return response()->json(['message'=>'access denied']);
    }

}
