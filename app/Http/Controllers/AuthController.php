<?php

namespace App\Http\Controllers;

use App\Models\{Admin, Table, User, Company, CompanyRequest};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Hash, Validator};
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;


class AuthController extends Controller
{
//------------------------------------signup admin-------------------------------------------------------------------------------------------------------

    public function admin(Request $request)
    {
        $a=$request->validate([
            'username'=>['required','unique:users','unique:company_requests','unique:admins','unique:companies','max:191','string'],
            'email'=>['required','email','unique:users','unique:admins','unique:company_requests','unique:companies','max:191'],
            'password'=>['required',Password::min(8)],
            'phone_number'=>['required','unique:users','unique:admins','unique:company_requests','unique:companies','digits_between:7,12'],
            //'photo'=>['required','image'],

        ]);
          if($a) {
              if ($request->verification_code == 'abc') {
//                  $file = $request->file('photo');
//                  $filename =Str::random(40).$file->getClientOriginalName();
//                  $file->move(public_path('public/Image'),$filename);
//                  $photo = $filename;


                  $data = Admin::query()->create([
                      'username' => $request->username,
                      'email' => $request->email,
                      'password' => Hash::make($request['password']),
                      'phone_number' => $request->phone_number,

                  ]);
                  $accessToken = $data->createToken('Personal Access Token',['admin'])->accessToken;
                  $data->user_type='admin';
                  $data->password=$request->password;
                  $data1['user']=$data;
                  $data1['token_type']='Bearer';
                  $data1['access_token']=$accessToken;

                  return response()->json($data1);
              }return response()->json(['message' => 'verification code incorrect']);

          }

            return response()->json(['message'=>'not found']);
    }

//------------------------------------signup user--------------------------------------------------------------------------------------------------------

    public function user(Request $request)
    {
        $validator = $request->validate([
            'username' => ['required', 'unique:users','unique:company_requests', 'unique:admins', 'unique:companies', 'max:191', 'string'],
            'email' => ['required', 'email', 'unique:users', 'unique:admins','unique:company_requests', 'unique:companies', 'max:191'],
            'password' => ['required',Password::min(8)],
            'phone_number' => ['required', 'unique:users', 'unique:admins','unique:company_requests', 'unique:companies', 'digits_between:7,12'],
            //'photo' => ['required','image'],

        ]);

//            $file= $request->file('photo');
//            $filename=Str::random(40).$file->getClientOriginalName();
//            $file-> move(public_path('public/Image'), $filename);
//            $photo= $filename;
            $user = User::query()->create([
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request['password']),
                'phone_number' => $request->phone_number,
                //'photo' => $photo,

            ]);
        $accessToken = $user->createToken('Personal Access Token',['user'])->accessToken;

        $user->user_type='user';
        $user->password=$request->password;
        $data['user']=$user;
        $data['token_type']='Bearer';
        $data['access_token']=$accessToken;

            return response()->json($data);
        }




//------------------------------------signup company------------------------------------------------------------------------------------------------------

    public function company(Request $request)
    {

        $a = $request->validate([
            'username' => ['required', 'unique:users','unique:company_requests', 'unique:admins', 'unique:companies', 'max:191', 'string'],
            'email' => ['required', 'email', 'unique:users', 'unique:admins','unique:company_requests', 'unique:companies', 'max:191'],
            'password' => ['required',Password::min(8)],
            'phone_number' => ['required', 'unique:users', 'unique:admins','unique:company_requests', 'unique:companies', 'digits_between:7,12'],
            'photo' => ['required','image'],
            'company_name' => ['required','unique:company_requests', 'unique:companies', 'max:191', 'string'],
            'company_email' => ['required', 'email','unique:company_requests', 'unique:companies', 'max:191'],
            'company_address' => ['required', 'string'],
            'commercial_record' => ['required','image'],
        ]);

        if ($a) {

            $file= $request->file('photo');
            $filename=Str::random(40).$file->getClientOriginalName();
            $file-> move(public_path('public/Image'), $filename);
            $photo= $filename;
            $file= $request->file('commercial_record');
            $filename=Str::random(40).$file->getClientOriginalName();
            $file-> move(public_path('public/Image'), $filename);
            $com= $filename;
            $data = CompanyRequest::query()->create([
                'admin_id'=>auth()->id(),
                'status'=>'waiting',
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request['password']),
                'phone_number' => $request->phone_number,
                'photo' => $photo,
                'company_name' => $request->company_name,
                'company_email' => $request->company_email,
                'company_address' => $request->company_address,
                'commercial_record' => $com,
            ]);

            return response()->json($data);
        }
        else
            return response()->json(['message'=>'not found']);
    }

//------------------------------------------login--------------------------------------------------------------------------------------------------------

    public function login(Request $request)
    {
        $request->validate([
            'username' => ['required','string'],
            'password' => ['required','string']
        ]);

 //------------------------------------admin login------------------------------------

        if (Admin::query()->where(['username' => $request->username])->exists()) {
            $user = Admin::query()->where(['username' => $request->username])->first();

            if (Hash::check($request->password, $user->password)) {
                $accessToken = $user->createToken('Personal Access Token',['admin'])->accessToken;
                $user->user_type='admin';
                $user->password=$request->password;
                $data['user']=$user;
                $data['token_type']='Bearer';
                $data['access_token']=$accessToken;

                return response()->json($data);
            }
            return response()->json(['message'=>'incorrect password']);
        }
//------------------------------------user login------------------------------------

        elseif (User::query()->where(['username' => $request->username])->exists()) {
            $user = User::query()->where(['username' => $request->username])->first();

            if (Hash::check($request->password, $user->password)) {
                $accessToken = $user->createToken('Personal Access Token',['user'])->accessToken;

                $user->user_type='user';
                $user->password=$request->password;
                $data['user']=$user;
                $data['token_type']='Bearer';
                $data['access_token']=$accessToken;
                return response()->json($data);
            }
            return response()->json(['message'=>'incorrect password']);
        }
//------------------------------------company login------------------------------------

        elseif (Company::query()->where(['username' => $request->username])->exists()) {
            $user = Company::query()->where(['username' => $request->username])->first();

            if (Hash::check($request->password, $user->password)) {

                $accessToken = $user->createToken('Personal Access Token',['company'])->accessToken;

                $user->user_type='company';
                $user->password=$request->password;
                $data['user']=$user;
                $data['token_type']='Bearer';
                $data['access_token']=$accessToken;
                return response()->json($data);
            }
            return response()->json(['message'=>'incorrect password']);
        }

            return response()->json(['message'=>'incorrect username']);
    }
//------------------------------------logout-------------------------------------------------------------------------------------------------------------

    public function logout(Request $request){

        $request->user()->token()->revoke();

        return response()->json([
            "message"=>"user logged out successfully"
        ]);}

//------------------------------------edit admin-------------------------------------------------------------------------------------------------------

    public function edit_admin(Request $request)
    {
        if ($request->user()->tokenCan('admin')) {
            $id=auth()->id();

            $request->validate([
                'photo'=>['image','nullable'],
                'username' => [
                    'nullable',Rule::unique('admins')->ignore($id),'string',
                    'unique:company_requests', 'unique:companies', 'max:191','unique:users'],
                'phone_number' => [
                    'digits_between:7,12',Rule::unique('admins')->ignore($id),'nullable',
                    'unique:users','unique:company_requests', 'unique:companies'],
                'password' => [Password::min(8)],

            ]);


            if($request->photo) {
            $file= $request->file('photo');
            $filename=Str::random(40).$file->getClientOriginalName();
            $file-> move(public_path('public/Image'), $filename);
            $photo=$filename;
            }
            $data=Admin::query()->find($id);
            $data->username= $request->username ?? $data->username;
            $data->phone_number= $request->phone_number ?? $data->phone_number;
            $data->photo= $photo ?? $data->photo;
            if($request->password){
            $data->password=Hash::make($request['password']) ;
            }
            $data->save();

            $data=Admin::query()->find($id);
                return response()->json(['message'=>'info updated','user'=>$data ]);

        }

            return response()->json(['message'=>'access denied']);
    }

//------------------------------------edit user---------------------------------------------------------------------------------------------------------

    public function edit_user(Request $request)
    {
        if ($request->user()->tokenCan('user')) {
            $id=auth()->id();

            $request->validate([
                'photo'=>['image','nullable'],
                'username' => [
                    'nullable',Rule::unique('users')->ignore($id),'string',
                    'unique:company_requests', 'unique:companies', 'max:191','unique:admins'],
                'phone_number' => [
                    'digits_between:7,12',Rule::unique('users')->ignore($id),'nullable',
                    'unique:admins','unique:company_requests', 'unique:companies'],
                'password' => [Password::min(8),'nullable'],

            ]);

            if($request->photo) {
                $file= $request->file('photo');
                $filename=Str::random(40).$file->getClientOriginalName();
                $file-> move(public_path('public/Image'), $filename);
            }
            $data=User::query()->find($id);
            $data->username= $request->username ?? $data->username;
            $data->phone_number= $request->phone_number ?? $data->phone_number;
            $data->photo= $photo ?? $data->photo;
            if($request->password){
                $data->password=Hash::make($request['password']) ;
            }
            $data->save();

            $data=User::query()->find($id);
            return response()->json(['message'=>'info updated','user'=>$data]);
        }
        return response()->json(['message'=>'access denied']);
    }

//------------------------------------edit company-------------------------------------------------------------------------------------------------------


    public function edit_company(Request $request)
    {
        if ($request->user()->tokenCan('company')) {

            $id = Auth::id();

                $request->validate([
                    'username' => ['unique:users', 'unique:admins', 'unique:company_requests',
                        Rule::unique('companies')->ignore($id), 'max:191', 'string','nullable'],
                    'phone_number' => ['digits_between:7,12', 'unique:users', 'unique:company_requests',
                        'unique:admins',Rule::unique('companies')->ignore($id),'nullable'],
                    'password' => [Password::min(8)],
                    'company_name' => ['unique:company_requests',Rule::unique('companies')->ignore($id), 'max:191', 'string','nullable'],
                    'company_email' => ['email', Rule::unique('companies')->ignore($id), 'max:191','nullable'],
                    'company_address' => ['string','nullable'],
                    'photo' => ['image','nullable'],
                ]);
                if ($request->photo) {
                    $file = $request->file('photo');
                    $filename =Str::random(40).$file->getClientOriginalName();
                    $file->move(public_path('public/Image'), $filename);
                    $photo = $filename;
                }


                    $table=Table::query()->where('company_id',$id);
                    if ($table->exists()){
                        $table1=$table->get();
                        foreach ($table1 as $item) {
                            $item->company_name = $request->company_name ?? $item->company_name;
                            $item->company_email = $request->company_email ?? $item->company_email;
                            $item->phone_number = $request->phone_number ?? $item->phone_number;
                            $item->save();
                        }
                    }
                    $data = Company::query()->find($id);
                    $data->username = $request->username ?? $data->username;
                    $data->phone_number = $request->phone_number ?? $data->phone_number;
                    $data->company_name = $request->company_name ?? $data->company_name;
                    $data->company_email = $request->company_email ?? $data->company_email;
                    $data->company_address = $request->company_address ?? $data->company_address;
                    $data->photo = $photo ?? $data->photo;
                    if($request->password){
                        $data->password=Hash::make($request['password']) ;
                    }
                    $data->save();
                    $data = Company::query()->find($id);
                    return response()->json(['message' => 'info updated', 'user' => $data]);
                 }
        return response()->json(['message'=>'access denied']);
    }

    public function image($file)
    {
        $data=public_path().'/public/Image/'.$file;
        if(file_exists($data))
        return response()->file($data);
   else
       return response()->json('not found');
    }
}
