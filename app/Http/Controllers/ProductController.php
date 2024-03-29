<?php
namespace App\Http\Controllers;

use App\Models\{Favorite, Invite, Product, ProductLike, Table, User};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ProductController

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

                    Favorite::query()->create([
                        'user_id'=>auth()->id(),
                        'product_id'=>$id
                    ]);

                    return response()->json(['message'=>'like added','likes'=>Product::query()->find($id)->likes,'status'=>true]);
                }
                else{
                    ProductLike::query()->where(['product_id'=>$id,'user_id'=>auth()->id()])->delete();
                    Favorite::query()->where(['product_id'=>$id,'user_id'=>auth()->id()])->delete();
                    Product::query()->find($id)->decrement('likes');

                    return response()->json(['message'=>'like removed','likes'=>Product::query()->find($id)->likes,'status'=>false]);
                }
            } else
                return response()->json(['message'=>'product not found']);
        }
        elseif ($request->user()->tokenCan('company')){
            if (Product::query()->where('id',$id)->exists()){
                if (ProductLike::query()->where(['product_id'=>$id,'company_id'=>auth()->id()])->doesntExist()){
                    ProductLike::query()->create([
                        'company_id'=>auth()->id(),
                        'product_id'=>$id
                    ]);
                    Product::query()->find($id)->increment('likes');

                    Favorite::query()->create([
                        'company_id'=>auth()->id(),
                        'product_id'=>$id
                    ]);

                    return response()->json(['message'=>'like added','likes'=>Product::query()->find($id)->likes,'status'=>true]);
                }
                else{
                    ProductLike::query()->where(['product_id'=>$id,'company_id'=>auth()->id()])->delete();
                    Product::query()->find($id)->decrement('likes');
                    Favorite::query()->where(['product_id'=>$id,'company_id'=>auth()->id()])->delete();

                    return response()->json(['message'=>'like removed','likes'=>Product::query()->find($id)->likes,'status'=>false]);
                }
            } else
                return response()->json(['message'=>'product not found']);
        }
        else
            return response()->json(['message'=>'access denied']);
    }

    public function show_managers($id)
    {
        if (Table::query()->find($id))
        {
            if (Invite::query()->where('table_id',$id)->exists()){
            $man=Invite::query()->where('table_id',$id)->get();
            foreach ($man as $item) {
                $data[]=User::query()->find($item->user_id);}
            return response()->json(['message'=>$data]);
        }else
            return response()->json(['message'=>[]]);
        }
        else
            return response()->json(['message'=>[]]);
    }
    //-----------------------------------show products by table id------------------------------------------------------------------------------------------


    public function show(Request $request,$id)
    {
        if( Product::without(['table'])->where('table_id',$id)->exists()){
            $product=Product::without(['table'])->where('table_id',$id)->get();
            if ($request->user()->tokenCan('user')){
            foreach ($product as $item) {
                if (ProductLike::query()->where(['product_id'=>$item->id,'user_id'=>auth()->id()])->exists()){
                    $item->like_status=true;}
                else
                    $item->like_status=false;
                  }
            }
            elseif ($request->user()->tokenCan('company')){
                foreach ($product as $item) {
                    if (ProductLike::query()->where(['product_id'=>$item->id,'company_id'=>auth()->id()])->exists()){
                        $item->like_status=true;}
                    else
                        $item->like_status=false;
                }
            }
            return response()->json(['message' =>$product ]);
        }
        else
            return response()->json(['message'=>[]]);
    }
    public function visitor_show($id)
    {
        if( Product::without(['table'])->where('table_id',$id)->exists()){
            $product=Product::without(['table'])->where('table_id',$id)->get();
            return response()->json(['message' =>$product ]);
        }
        else
            return response()->json(['message'=>[]]);
    }
    //-----------------------------------add products------------------------------------------------------------------------------------------------------

    public function add_product(Request $request, $id)
    {
        if ($request->user()->tokenCan('company')) {

         $request->validate([
            'name' => ['required', 'string'],
            'photo' => ['required', 'image'],
            'price' => ['required', 'numeric']
        ]);

            $file = $request->file('photo');
            $filename =Str::random(40).$file->getClientOriginalName();
            $file->move(public_path('public/Image'), $filename);
            $photo = $filename;
            $data = Table::query();
            if ($data->where(['id' => $id, 'company_id' => auth()->id()])->exists()) {
                $data = $data->find($id)->products()->create([
                    'name' => $request->name,
                    'photo' => $photo,
                    'price' => $request->price,

                ]);
                return response()->json(['message' => 'product added successfully', 'product' => $data]);
            } else
                return response()->json(['message' => "you don't have permission"]);
        }
        elseif($request->user()->tokenCan('user')&&Invite::query()->where(['user_id'=>auth()->id(),'table_id'=>$id])->exists())
            {
                $request->validate([
                    'name' => ['required', 'string'],
                    'photo' => ['required', 'image'],
                    'price' => ['required', 'numeric']
                ]);

                $file = $request->file('photo');
                $filename =Str::random(40).$file->getClientOriginalName();
                $file->move(public_path('public/Image'), $filename);
                $photo = $filename;
                $data = Table::query();
                    $data = $data->find($id)->products()->create([
                        'name' => $request->name,
                        'photo' => $photo,
                        'price' => $request->price,

                    ]);
                    return response()->json(['message' => 'product added successfully', 'product' => $data]);

        }
        return response()->json(['message' => 'access denied']);
    }

    //-----------------------------------edit products------------------------------------------------------------------------------------------------------


    public function edit_product(Request $request, $id)
    {
        if (Product::query()->where(['id' => $id])->exists()) {
            $tab=Product::query()->find($id)->table_id;

            if ($request->user()->tokenCan('company')) {
         $request->validate([
            'name' => ['string'],
            'photo' => ['image'],
            'price' => ['numeric']
        ]);

             if (Table::query()->where(['id' => $tab, 'company_id' => auth()->id()])->exists()) {

                if($request->photo){
                $file = $request->file('photo');
                $filename =Str::random(40).$file->getClientOriginalName();
                $file->move(public_path('public/Image'), $filename);
                $photo = $filename;}
                $data = Product::query()->find($id);
                $data->name = $request->name ?? $data->name;
                $data->photo = $photo ?? $data->photo;
                $data->price = $request->price ?? $data->price;
                $data->save();
                return response()->json(['message' => 'product updated successfully', 'product' => $data]);
            }
            else
                return response()->json(['message' => "you don't have permission"]);
        }
            elseif ($request->user()->tokenCan('user')&&Invite::query()->where(['user_id'=>auth()->id(),'table_id'=>$tab])->exists())
            {
                $request->validate([
                    'name' => ['string'],
                    'photo' => ['image'],
                    'price' => ['numeric']
                ]);
                if($request->photo){
                    $file = $request->file('photo');
                    $filename =Str::random(40).$file->getClientOriginalName();
                    $file->move(public_path('public/Image'), $filename);
                    $photo = $filename;}
                $data = Product::query()->find($id);
                $data->name = $request->name ?? $data->name;
                $data->photo = $photo ?? $data->photo;
                $data->price = $request->price ?? $data->price;
                $data->save();
                return response()->json(['message' => 'product updated successfully', 'product' => $data]);

            }
        else
            return response()->json(['message' => 'access denied']);
        }
        else
            return response()->json(['message'=>'product not found']);
    }

    //-----------------------------------delete products----------------------------------------------------------------------------------------------------

    public function delete_product(Request $request,$id)
    {
        if (Product::query()->where(['id' => $id])->exists()) {
            $b = Product::query()->find($id);

            if ($request->user()->tokenCan('company')) {

                if (Table::query()->where(['id' => $b->table_id, 'company_id' => Auth::id()])->exists())
                {
                    $b->delete();
                    return response()->json(['message' => 'deleted successfully']);
                }
                else
                    return response()->json(['message' => "you don't have permission"]);

        }
            elseif ($request->user()->tokenCan('user')&&Invite::query()->where(['user_id'=>auth()->id(),'table_id'=>$b->table_id])->exists())
            {
                $b->delete();
                return response()->json(['message' => 'deleted successfully']);
            }
       else
           return response()->json(['message' => 'access denied']);
        }
        else
            return response()->json(['message' => 'product not found']);
    }
}
