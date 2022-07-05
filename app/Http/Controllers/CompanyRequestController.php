<?php

namespace App\Http\Controllers;

use App\Models\{Company,CompanyRequest};
use Illuminate\Http\Request;


class CompanyRequestController extends Controller
{
    public function show_request(Request $request)
    {
        if ($request->user()->tokenCan('admin')){
            return response()->json(['message'=>CompanyRequest::without('admin')->get()]);
        }
        else
            return response()->json(['message'=>'access denied']);
    }
    public function accept(Request $request, $id)
    {
        if ($request->user()->tokenCan('admin')) {

            $data = CompanyRequest::query();
            if ($data->where(['id' => $id])->exists()) {

                if ($data->find($id)->status == 'waiting') {
                    $data = CompanyRequest::query()->find($id);

                    $com = Company::query()->create([
                        'username' => $data->username,
                        'email' => $data->email,
                        'password' => $data->password,
                        'phone_number' => $data->phone_number,
                        'photo' => $data->photo,
                        'company_name' => $data->company_name,
                        'company_email' => $data->company_email,
                        'company_address' => $data->company_address,
                        'commercial_record' => $data->commercial_record,
                    ]);
                    $a = $data->find($id);
                    $a->admin_id = auth()->id();
                    $a->status = 'accepted';
                    $a->save();
                    return response()->json(['message' => 'company accepted', 'user' => $com]);
                } else
                    return response()->json(['message' => 'error']);

            } else
                return response()->json(['message' => 'company not found']);

        }
        return response()->json(['message' => 'access denied']);
    }


    public function reject(Request $request, $id)
    {
        if ($request->user()->tokenCan('admin')) {
            $data = CompanyRequest::query();
            if ($data->where(['id' => $id])->exists()) {
                if ($data->find($id)->value('status') == 'waiting') {
                    $a = $data->find($id);
                    $a->delete();

                    return response()->json(['message' => 'company rejected', 'user' => $a]);

                } else
                    return response()->json(['message' => 'error']);

            } else
                return response()->json(['message' => 'company not found']);
        }
        return response()->json(['message' => 'access denied']);
    }


    public function delete(Request $request, $id)
    {
        if ($request->user()->tokenCan('admin')) {
            if (CompanyRequest::query()->where(['id' => $id])->exists()) {
                CompanyRequest::query()->find($id)->delete();

                return response()->json(['message' => 'company deleted']);

            } else
                return response()->json(['message' => 'company not found']);
        }
        return response()->json(['message' => 'access denied']);

    }
}
