<?php

namespace App\Http\Controllers;

use App\Models\{Exhibition, Favorite, Product, ProductLike, Table};
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function favorite_exh(Request $request,$id)
    {
        if ($request->user()->tokenCan('user')){
            if (Exhibition::query()->where('id',$id)->exists()){
                if (Favorite::query()->where(['exhibition_id'=>$id,'user_id'=>auth()->id()])->exists())
                {
                    Favorite::query()->where(['exhibition_id'=>$id,'user_id'=>auth()->id()])->delete();
                    return response()->json(['message'=>'removed from favorite','status'=>false]);
                }
                else{
                    Favorite::query()->create([
                        'exhibition_id'=>$id,
                        'user_id'=>auth()->id()
                    ]);
                return response()->json(['message'=>'added to favorite','status'=>true]);
                }
            }
            else
                return response()->json(['message'=>'exhibition not found']);
        }
        elseif ($request->user()->tokenCan('company')){
            if (Exhibition::query()->where('id',$id)->exists()){
                if (Favorite::query()->where(['exhibition_id'=>$id,'company_id'=>auth()->id()])->exists())
                {
                    Favorite::query()->where(['exhibition_id'=>$id,'company_id'=>auth()->id()])->delete();
                    return response()->json(['message'=>'removed from favorite','status'=>false]);
                }
                else
                    Favorite::query()->create([
                        'exhibition_id'=>$id,
                        'company_id'=>auth()->id()
                    ]);
                return response()->json(['message'=>'added to favorite','status'=>true]);
            }
            else
                return response()->json(['message'=>'exhibition not found ']);
        }
        else
            return response()->json(['message'=>'access denied']);
    }


    public function favorite_tab(Request $request,$id)
    {
        if ($request->user()->tokenCan('user')){
            if (Table::query()->where('id',$id)->exists()&&Table::query()->find($id)->company_id!==null){

                if (Favorite::query()->where(['table_id'=>$id,'user_id'=>auth()->id()])->exists())
            {
                Favorite::query()->where(['table_id'=>$id,'user_id'=>auth()->id()])->delete();
                return response()->json(['message'=>'removed from favorite','status'=>false]);
            }
            else{
                Favorite::query()->create([
                    'table_id'=>$id,
                    'user_id'=>auth()->id()
                ]);
            return response()->json(['message'=>'added to favorite','status'=>true]);
            }
            }
            else
                return response()->json(['message'=>'table not found']);

        }

        elseif ($request->user()->tokenCan('company')){
            if (Table::query()->where('id',$id)->exists()&&Table::query()->find($id)->company_id!==null){
                    if (Favorite::query()->where(['table_id'=>$id,'company_id'=>auth()->id()])->exists())
                    {
                        Favorite::query()->where(['table_id'=>$id,'company_id'=>auth()->id()])->delete();
                        return response()->json(['message'=>'removed from favorite','status'=>false]);
                    }
                    else
                        Favorite::query()->create([
                            'table_id'=>$id,
                            'company_id'=>auth()->id()
                        ]);
                    return response()->json(['message'=>'added to favorite','status'=>true]);
                }
                else
                    return response()->json(['message'=>'table not found']);
            }
        else
            return response()->json(['message'=>'access denied']);
    }

    public function show_favorite_exh(Request $request)
    {
        if ($request->user()->tokenCan('user')){
            $fav=Favorite::query()->where('user_id',auth()->id())->get();
            $exh=[];
            foreach ($fav as $item) {
                if (Exhibition::query()->where('id',$item->exhibition_id)->exists()){
                    $exh[]=Exhibition::query()->without('admin')->find($item->exhibition_id );
                }
            }
            return response()->json(['message'=>$exh]);
        }
        elseif($request->user()->tokenCan('company')){
            $fav=Favorite::query()->where('company_id',auth()->id())->get();
            $exh=[];
            foreach ($fav as $item) {
                if (Exhibition::query()->where('id',$item->exhibition_id)->exists()){
                    $exh[]=Exhibition::query()->without('admin')->find($item->exhibition_id);
                }
            }
                return response()->json(['message'=>$exh]);
        }
        else
            return response()->json(['message'=>'access denied']);
    }

    public function show_favorite_tab(Request $request)
    {
        if ($request->user()->tokenCan('user')){
            $fav=Favorite::query()->where('user_id',auth()->id())->get();
            $tab=[];
            foreach ($fav as $item) {
                if (Table::query()->where('id',$item->table_id)->exists()){

                    $tab[]=Table::query()->without('pavilion','company')->find($item->table_id);
                }
            }
                return response()->json(['message'=>$tab]);
        }

        elseif($request->user()->tokenCan('company')){
            $fav=Favorite::query()->where('company_id',auth()->id())->get();
            $tab=[];
            foreach ($fav as $item) {
                if (Table::query()->where('id',$item->table_id)->exists()){
                    $tab[]=Table::query()->without('pavilion','company')->find($item->table_id);
                }
            }
                return response()->json(['message'=>$tab]);
        }
        else
            return response()->json(['message'=>'access denied']);
    }

    public function show_favorite_product(Request $request)
    {
        if ($request->user()->tokenCan('user')){
            $fav=Favorite::query()->where('user_id',auth()->id())->get();
            $product=[];
            foreach ($fav as $item) {
                if (Product::query()->where('id',$item->product_id)->exists()){

                    $product[]=Product::query()->without('table','company')->find($item->product_id);
                }
            }
                return response()->json(['message'=>$product]);
        }

        elseif($request->user()->tokenCan('company')){
            $fav=Favorite::query()->where('company_id',auth()->id())->get();
            $product=[];
            foreach ($fav as $item) {
                if (Product::query()->where('id',$item->product_id)->exists()){
                    $product[]=Product::query()->without('table','company')->find($item->product_id);
                }
            }
                return response()->json(['message'=>$product]);
        }
        else
            return response()->json(['message'=>'access denied']);
    }
}

