<?php

namespace App\Http\Controllers;


use App\Models\{Company, Exhibition, Pavilion, Table, RegisterRequest};
use Illuminate\Http\Request;


class RegisterRequestController extends Controller
{

    public function show_register(Request $request)
    {
        if ($request->user()->tokenCan('admin')) {

            $i = 0;
            $exh = Exhibition::where('admin_id', auth()->id())->get();
            foreach ($exh as $item) {

                $pav = Pavilion::where('exhibition_id', $item->id)->get();
                foreach ($pav as $item1) {

                    $tab = Table::where('pavilion_id', $item1->id)->get();
                    foreach ($tab as $item2) {

                        if (RegisterRequest::where('table_id', $item2->id)->exists()) {
                            $i = $i + 1;
                            $req[] = RegisterRequest::without(['company', 'table'])->where('table_id', $item2->id)->get()->first();
                        }

                    }

                }

            }
            if (!$i == 0)
                return response()->json(['message' => $req]);
            else
                return response()->json(['message' => []]);
        } else
            return response()->json(['message' => 'access denies']);
    }

    public function register_table(Request $request, $id)
    {
        if ($request->user()->tokenCan('company')) {
            $id = (int)$id;
            if (Table::query()->where(['id' => $id])->exists()) {
                if (Table::query()->find($id)->company_id == null) {
                    $table = Table::query()->find($id);
                    if (RegisterRequest::query()->where(['table_id' => $table->id, 'company_id' => auth()->id()])->doesntExist()) {
                        $com = Company::query()->find(auth()->id());


                        $data = RegisterRequest::create([
                            'company_id' => auth()->id(),
                            'table_id' => $id,
                            'table_number' => $table->table_number,
                            'company_name' => $com->company_name,
                            'company_email' => $com->company_email,
                            'phone_number' => $com->phone_number,
                            'commercial_record' => $com->commercial_record,

                        ]);

                        return response()->json(['message' => 'request send successfully', 'table' => $data]);
                    } else
                        return response()->json(['message' => 'request already exists']);
                } else
                    return response()->json(['message' => 'table not available']);
            } else
                return response()->json(['message' => 'table not found']);
        } else
            return response()->json(['message' => 'access denied']);
    }

    public function accept_table(Request $request, $id)
    {
        if ($request->user()->tokenCan('admin')) {
            $data = RegisterRequest::query();

            if ($data->where(['id' => $id])->exists()) {

                $data1 = Table::query();
                $req = $data->find($id)->table_id;
                $pav = $data1->find($req)->pavilion_id;
                $exh = Pavilion::query()->find($pav)->exhibition_id;
                $adm = Exhibition::query()->find($exh)->admin_id;

                if (auth()->id() == $adm) {
                    if ($data1->find($req)->company_id == null) {


                        if ($data1->where(['pavilion_id' => $pav, 'company_id' => $data->find($id)->company_id])->doesntExist()) {

                            $data = $data->find($id);

                            $table = Table::query()->find($req);
                            $table->company_id = $data->company_id;
                            $table->company_name = $data->company_name;
                            $table->company_email = $data->company_email;
                            $table->phone_number = $data->phone_number;
                            $table->commercial_record = $data->commercial_record;
                            $table->save();
                            RegisterRequest::where('table_id', $req)->delete();

                            $table1 = Table::query()->find($req);
                            return response()->json(['message' => 'request accepted', 'table' => $table1]);
                        } else
                            return response()->json(['message' => 'company had already entered the pavilion']);
                    } else
                        return response()->json(['message' => 'table not available']);
                } else
                    return response()->json(['message' => "you don't have permission"]);
            } else
                return response()->json(['message' => 'request not found']);
        } else
            return response()->json(['message' => 'access denied']);
    }

    public function reject_table(Request $request, $id)
    {
        if ($request->user()->tokenCan('admin')) {
            $data = RegisterRequest::query();

            if ($data->where('id', $id)->exists()) {

                $data1 = Table::query();
                $req = $data->find($id)->table_id;
                $pav = $data1->find($req)->pavilion_id;
                $exh = Pavilion::query()->find($pav)->exhibition_id;
                $adm = Exhibition::query()->find($exh)->admin_id;

                if (auth()->id() == $adm) {
                    $data->where('id', $id)->delete();
                    return response()->json(['message' => 'request deleted successfully']);
                } else
                    return response()->json(['message' => "you don't have permission"]);
            } else
                return response()->json(['message' => 'request not found']);
        } else
            return response()->json(['message' => 'access denied']);
    }
}
