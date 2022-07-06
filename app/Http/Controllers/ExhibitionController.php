<?php

namespace App\Http\Controllers;

use App\Models\{Exhibition,Pavilion};
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class ExhibitionController extends Controller
{

    //---------------------------------show exhibitions--------------------------------------------------------------------------------------------------

      public function show_exh()
      {
          foreach (Exhibition::all() as $item) {

              if(Carbon::parse($item->exhibition_start) <= now()){$item->status='0n';$item->save();}
              if(Carbon::parse($item->exhibition_start) >= now()){$item->status='pre';$item->save();}
              if (Carbon::parse($item->exhibition_end) <= now()){$item->delete();}
          }

         return response()->json(['message'=>Exhibition::without(['admin'])->orderBy('name')->get()]);
      }
    //---------------------------------show my exhibitions--------------------------------------------------------------------------------------------------

      public function show_my_exh(Request $request)
      {
          if ($request->user()->tokenCan('admin')) {
          $data=Exhibition::query();
          if ($data->where(['admin_id'=>auth()->id()])->exists()){
              $data=$data->without('admin')->where(['admin_id'=>auth()->id()])->get();
              return response()->json(['my exhibitions'=>$data]);
          }
          else
              return response()->json(['message'=>'no exhibitions found']);
      }return response()->json(['message'=>'access denied']);
      }


    //---------------------------------show pavilions----------------------------------------------------------------------------------------------------

    public function show_pav($id)
    {
        if ($data = Exhibition::query()->find($id)){
            $data=$data->pavilions()->without('exhibition')->get();
            $i=0;
        foreach ($data as $item) {
            $table = Pavilion::query()->find($item->id);
            $tables[++$i]=$table->tables()->get();

        }
        return response()->json(['pavilions' => $tables]);
    }
          else
              return response()->json(['message'=>'exhibition not found']);
      }
    //---------------------------------show pavilions for user-----------------------------------------------------------------------------------------------

    public function show_user_pav($id)
    {
        if ($data = Pavilion::query()->find($id)){
            $data1=$data->tables()->without('pavilion','company')->get();
            $i=0;
            foreach ($data1 as $item) {
                if (!$item->company_id==null){
                    $table[]=$item;
                    $i++;}}

            if (!$i==0){
                return response()->json(['message'=>$table]);
            }
            else
                return response()->json(['message'=>null]);
        }
        else
            return response()->json(['message'=>'pavilion not found']);
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
              'photo'=>['required','image'],

          ]);

          if($a){
              $file = $request->file('photo');
              $filename =Str::random(40).$file->getClientOriginalName();
              $file->move(public_path('public/Image'),$filename);
              $photo = $filename;

              $data=Exhibition::query()->create([
                  'admin_id'=>auth()->id(),
                  'name'=>$request->name,
                  'exhibition_start'=>$request->exhibition_start,
                  'exhibition_end'=>$request->exhibition_end,
                  'preparation_duration'=>$request->preparation_duration,
                  'district'=>$request->district,
                  'city'=>$request->city,
                  'photo'=>$photo,
                  'status'=>'pre'

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
