<?php

namespace App\Http\Controllers;

use App\Models\{Company, Exhibition, Favorite, Pavilion, Product, ProductLike, Table};
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ExhibitionController extends Controller
{

    public function search(Request $request,$name)
    {
        if ($name=='exhibition')
        {
            $data1=[];
            $data=Exhibition::without('admin')->get();
            foreach ($data as $item) {
                if (str_contains($item->name,$request->name))
                {
                    $data1[]=$item;
                }
            }
            return response()->json(['message'=>$data1]);
        }

        elseif($name=='company')
        {
            $data1=[];
            $data=Company::all();
            foreach ($data as $item) {
                   if (str_contains($item->company_name,$request->name)){
                       $data1[]=$item;
                   }

            }
            return response()->json(['message'=>$data1]);
        }
        elseif($name=='product')
        {
            $data1=[];
            $data=Product::without('table','company')->get();
            foreach ($data as $item) {
                if (str_contains($item->name,$request->name)){
                    $data1[]=$item;
                }

            }
            return response()->json(['message'=>$data1]);
        }

            return response()->json(['message'=>'error']);
    }
    //---------------------------------show exhibitions--------------------------------------------------------------------------------------------------

      public function show_exh(Request $request)
      {
          $data=Exhibition::query()->without('admin')->get();

          foreach ($data as $item) {
              $start=Carbon::parse($item->exhibition_start);
              if($start <= now()){$item->status='0n';$item->save();}
              if($start> now()){$item->status='pre';$item->save();}
              if ($request->user()->tokenCan('user')){
                  if (Favorite::query()->where(['exhibition_id'=>$item->id,'user_id'=>auth()->id()])->exists())
                  {
                      $item->favorite=true;
                  }
                  else
                      $item->favorite=false;
              }
              if ($request->user()->tokenCan('company')){
                  if (Favorite::query()->where(['exhibition_id'=>$item->id,'company_id'=>auth()->id()])->exists())
                  {
                      $item->favorite=true;
                  }
                  else
                      $item->favorite=false;
              }
              $end=Carbon::parse($item->exhibition_end);
              $end->day=$end->day+1;
              if ($end<now()){
                  $pav=$item->pavilions()->get();
                  foreach ($pav as $item1) {
                      $tab=Table::query()->where('pavilion_id',$item1->id)->get();
                      foreach ($tab as $item2) {
                          $pro=$item2->products()->get();
                          $item2->invites()->delete();
                          $item2->registerrequests()->delete();
                          foreach ($pro as $item3) {
                              ProductLike::query()->find($item3->id)->delete();
                          }
                          $item2->products()->delete();
                      }
                      Table::query()->where('pavilion_id',$item1->id)->delete();
                  }
                  $item->pavilions()->delete();
                  $item->delete();
              }
          }
         return response()->json(['message'=> $data]);
      }

      public function visitor_exh()
      {
          $data=Exhibition::query()->without('admin')->get();

          foreach ($data as $item) {
              $start=Carbon::parse($item->exhibition_start);
              if($start <= now()){$item->status='0n';$item->save();}
              if($start> now()){$item->status='pre';$item->save();}


              $end=Carbon::parse($item->exhibition_end);
              $end->day=$end->day+1;
              if ($end<now()){
                  $pav=$item->pavilions()->get();
                  foreach ($pav as $item1) {
                      $tab=Table::query()->where('pavilion_id',$item1->id)->get();
                      foreach ($tab as $item2) {
                          $pro=$item2->products()->get();
                          $item2->invites()->delete();
                          $item2->registerrequests()->delete();
                          foreach ($pro as $item3) {
                              ProductLike::query()->find($item3->id)->delete();
                          }
                          $item2->products()->delete();
                      }
                      Table::query()->where('pavilion_id',$item1->id)->delete();
                  }
                  $item->pavilions()->delete();
                  $item->delete();
              }
          }
         return response()->json(['message'=> $data]);
      }
    //---------------------------------show my exhibitions--------------------------------------------------------------------------------------------------

      public function show_my_exh(Request $request)
      {
          if ($request->user()->tokenCan('admin')) {
          $data=Exhibition::query();
          if ($data->where(['admin_id'=>auth()->id()])->exists()){
              $data=$data->without('admin')->where(['admin_id'=>auth()->id()])->get();
              return response()->json(['message'=>$data]);
          }
          else
              return response()->json(['message'=>[]]);
      }
          return response()->json(['message'=>'access denied']);
      }


    //---------------------------------show pavilions----------------------------------------------------------------------------------------------------

    public function show_pav(Request $request,$id)
    {
        if ($request->user()->tokenCan('admin')||$request->user()->tokenCan('company')){

            if ( Exhibition::query()->where('id',$id)->exists()) {
            $data = Exhibition::query()->find($id);
            $data = $data->pavilions()->without('exhibition')->get();
            $tables=[];
            foreach ($data as $item) {
                $table = Pavilion::query()->find($item->id);
                $a=$table->tables()->without('pavilion','company')->get();

                foreach ($a as $table) {

                if ($request->user()->tokenCan('company')) {
                    if (Favorite::query()->where(['table_id' => $table->id, 'company_id' => auth()->id()])->exists()) {
                        $table->favorite = true;
                    } else
                        $table->favorite = false;
                   }
                }
                $tables[] =['pavilion'=>$item,'table'=>$a];
            }
                return response()->json(['message'=>$tables]);
            }
        else
            return response()->json(['message' => 'exhibition not found']); }

        else
            return response()->json(['message' => 'access denied ']);

    }
    //---------------------------------show pavilions for user-----------------------------------------------------------------------------------------------

    public function show_user_pav(Request $request,$id)
    {
        if ( Exhibition::query()->where('id',$id)->exists()) {
            $data = Exhibition::query()->find($id);
            $data = $data->pavilions()->without('exhibition')->get();

            $tables=[];
            foreach ($data as $item) {
                $data=$item->tables()->without('pavilion','company')->get();
                foreach ( $data as $item1) {
                    if (!$item1->company_id==null){
                        $data1[]=$item1;
                        if ($request->user()->tokenCan('user')){
                            if (Favorite::query()->where(['table_id'=>$item1->id,'user_id'=>auth()->id()])->exists()) {
                                $item1->favorite=true;
                            }
                            else
                                $item1->favorite=false;
                        }
                    }
                }
                if (isset($data1)){
                    $tables[] =['pavilion'=>$item,'table'=>$data1];
                }
            }
                return response()->json(['message'=>$tables]);
        }
        else
            return response()->json(['message' => 'exhibition not found']);

    }
    public function visitor_pav($id)
    {
        if ( Exhibition::query()->where('id',$id)->exists()) {
            $data = Exhibition::query()->find($id);
            $data = $data->pavilions()->without('exhibition')->get();
            $tables=[];
            foreach ($data as $item) {
                $data=$item->tables()->without('pavilion','company')->get();
                foreach ( $data as $item1) {
                    if (!$item1->company_id==null){
                        $data1[]=$item1;
                    }
                }
                if (isset($data1)){
                $tables[] =['pavilion'=>$item,'table'=>$data1];
                }
            }
                return response()->json(['message'=>$tables]);
        }
        else
            return response()->json(['message' => 'exhibition not found']);

    }

    //---------------------------------add exhibition----------------------------------------------------------------------------------------------------

    public function add_exh(Request $request)
      {
          if ($request->user()->tokenCan('admin')) {
          $a=$request->validate([
              'name'=>['required'],
              'exhibition_start'=>['required','date','before_or_equal:exhibition_end'],
              'exhibition_end'=>['required','date'],
              'preparation_duration'=>['required','date','before:exhibition_start'],
              'district'=>['required','string'],
              'city'=>['required','string'],
          ]);

          if(Carbon::parse($request->exhibition_end) > now()){

              $data=Exhibition::query()->create([
                  'admin_id'=>auth()->id(),
                  'name'=>$request->name,
                  'exhibition_start'=>$request->exhibition_start,
                  'exhibition_end'=>$request->exhibition_end,
                  'preparation_duration'=>$request->preparation_duration,
                  'district'=>$request->district,
                  'city'=>$request->city,

              ]);

              return response()->json(['message'=>'exhibition added successfully','exhibition'=>$data]);
          }
          else
              return response()->json(['message'=>'incorrect information']);
          }
          return response()->json(['message'=>'access denied']);
      }

    //---------------------------------add pavilions----------------------------------------------------------------------------------------------------

      public function add_pavilion(Request $request,$id)
      {
          if ($request->user()->tokenCan('admin')) {
              $request->validate([
                  'name' => ['required', 'string',],
                  'start' => ['digits_between:1,2', 'lt:end'],
                  'end' => ['digits_between:1,2'],
                  'price' => ['digits_between:1,100']
              ]);
              if( Exhibition::query()->where('id',$id)->exists()){
                  $exh = Exhibition::query()->find($id);

              if ($exh->admin_id == auth()->id()) {
                  foreach ($exh->pavilions()->get() as $item) {
                      if (!($request->start > $item->start && $request->start > $item->end && $request->end > $item->start && $request->end > $item->end ||
                          $request->start < $item->start && $request->start < $item->end && $request->end < $item->start && $request->end < $item->end)) {
                          return response()->json(['message' => 'tables had been token']);
                      }
                  }

                  $pav = $exh->pavilions()->create([
                      'name' => $request->name,
                      'start' => $request->start,
                      'end' => $request->end,
                      'price' => $request->price
                  ]);
                  for ($i = $request->start; $i <= $request->end; $i++) {
                      $pav->tables()->create([
                          'table_number' => $i,
                      ]);

                  }
                  return response()->json(['message'=>'pavilion added successfully']);
              }
              else
                  return response()->json(['message'=>'exhibition not found']);
          }
              else
                  return response()->json(['message'=>"you don't have permission"]);
          }
          else
              return response()->json(['message'=>'access denied']);
      }

    //---------------------------------edit pavilions----------------------------------------------------------------------------------------------------

      public function update_exh(Request $request,$id)
      {
          if ($request->user()->tokenCan('admin')) {
          $data=Exhibition::query();
          if($data->where(['id'=>$id])->exists()){
              if($data->find($id)->admin_id==auth()->id()){
                  $request->validate([
                      'name'=>['string'],
                      'exhibition_start'=>['date','before_or_equal:exhibition_end'],
                      'exhibition_end'=>['date'],
                      'preparation_duration'=>['date','before:exhibition_start'],
                      'district'=>['string'],
                  ]);

                  $data=Exhibition::query()->find($id)->first();
                  $data->name= $request->name ?? $data->name;
                  $data->exhibition_start= $request->exhibition_start ?? $data->exhibition_start;
                  $data->exhibition_end= $request->exhibition_end ?? $data->exhibition_end;
                  $data->preparation_duration= $request->preparation_duration ?? $data->preparation_duration;
                  $data->district= $request->district ?? $data->district;
                  $data->save();
                  $data=Exhibition::query()->find($id);

                   return response()->json(['message'=>'exhibition updated','exhibition'=>$data]);

                  }
              else return response()->json(['message'=>'access denied']);
              }
              else
                  return response()->json(['message'=>'exhibition not found']);
          }
              return response()->json(['message'=>'access denied']);
      }

    //---------------------------------delete pavilions----------------------------------------------------------------------------------------------------

      public function delete_exh(Request $request,$id)
      {
          if ($request->user()->tokenCan('admin')) {
          $data=Exhibition::query();
          $admin_id=auth()->id();
          if($data->where(['id'=>$id,'admin_id'=>$admin_id])->exists()){
                  $data=Exhibition::query()->find($id);
                  $tab=$data->pavilions()->get();
              foreach ($tab as $item) {
                  $table = Pavilion::query()->find($item->id);
                  $table->tables()->delete();
              }
                  $data->pavilions()->delete();
                  $data->delete();

                  return response()->json(['message'=>'exhibition deleted successfully']);
              }
              else return response()->json(['message'=>'exhibition not found']);
          }return response()->json(['message'=>'access denied']);
      }
}
