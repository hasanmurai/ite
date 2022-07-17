<?php

namespace App\Http\Controllers;

use App\Models\Invite;
use App\Models\Table;
use App\Models\User;
use Illuminate\Http\Request;

class InviteController extends Controller
{
    public function search(Request $request){
        $request->validate(['username'=>['required','string']]);
        if(User::query()->where('username',$request->username)->exists()){
            $data=User::query()->where('username',$request->username)->get();
            return response()->json(['message'=>$data]);
        }
        else
            return response()->json(['message'=>[]]);
    }

    public function invite(Request $request,$table_id,$user_id){
        if ($request->user()->tokenCan('company'))
        {
            if (Table::query()->where(['id'=>$table_id,'company_id'=>auth()->id()])->exists()){
            if (User::where('id',$user_id)->exists()){

                if (Invite::where(['user_id'=>$user_id,'company_id'=>auth()->id(),'table_id'=>$table_id])->doesntExist()){

            Invite::create([
                'user_id'=>$user_id,
                'company_id'=>auth()->id(),
                'table_id'=>$table_id
            ]);
            return response()->json(['message'=>'invite send successfully']);
        }
            else
                return response()->json(['message'=>'invite already exists']);
        }
        else
            return response()->json(['message'=>'user not found']);
        }
            else
                return response()->json(['message'=>"you don't have permission"]);
        }
        else
            return response()->json(['message'=>'access denied']);
    }

    public function accept_invite(Request $request,$id){
        if ($request->user()->tokenCan('user'))
        {
            if(Invite::query()->where(['id'=>$id,'user_id'=>auth()->id()])->exists()){
                $invite=Invite::query()->find($id);
            if ($invite->invite_status==false&&$invite->user_id==auth()->id()){
                $invite->invite_status=true;
                $invite->save();
                return response()->json(['message'=>'invite accepted successfully']);
            }
            else
                return response()->json(['message'=>"you don't have permission"]);
        }
            else
                return response()->json(['message'=>'invite not found']);
        }
        else
            return response()->json(['message'=>'access denied']);
    }

    public function reject_invite(Request $request,$id){
        if ($request->user()->tokenCan('user'))
        {
            if (Invite::where('id',$id)->exists()){
                $invite=Invite::query()->find($id);
                if ($invite->invite_status==false&&$invite->user_id==auth()->id()){
                    $invite->delete();
                    return response()->json(['message'=>'invite rejected successfully']);
            }
                else
                    return response()->json(['message'=>"you don't have permission"]);
        }
            else
                return response()->json(['message'=>'invite not found']);
        }
        else
            return response()->json(['message'=>'access denied']);
    }

    public function show_invites(Request $request){
        if ($request->user()->tokenCan('user'))
        {
            $invite=Invite::query()->without('table','company','user')->where('user_id',auth()->id())->get();

            return response()->json(['message'=>$invite]);
}
        else
            return response()->json(['message'=>'access denied']);
    }
}
