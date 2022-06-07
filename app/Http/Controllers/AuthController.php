<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function admin(Request $request)
    {
        $request->validate([
            'username'=>['required','unique:users','unique:admins','unique:companies','max:191','string'],
            'email'=>['required','email','unique:users','unique:admins','unique:companies','max:191'],
            'password'=>['required','max:191','string'],
            'phone_number'=>['required','unique:users','unique:admins','unique:companies','integer'],
            'photo'=>['required','file'],
            'social_media_account'=>['required'],



        ]);

        $pass=$request['password']=Hash::make($request['password']);


        if($request->verification_code=='abc'){
            $file= $request->file('photo');
            $filename= $file->getClientOriginalName();
            $file-> move(public_path('public/Image'), $filename);
            $photo= $filename;


            $data=Admin::query()->create([
                'username'=>$request->username,
                'email'=>$request->email,
                'password'=>$pass,
                'phone_number'=>$request->phone_number,
                'photo'=>$photo,
                'social_media_account'=>$request->social_media_account,

            ]);

            return response()->json($data);
        }

        else
            return response()->json(['not found',404]);
    }

    /*------------------------------------------------------------*/

    public function user(Request $request)
    {
        $a = $request->validate([
            'username' => ['required', 'unique:users', 'unique:admins', 'unique:companies', 'max:191', 'string'],
            'email' => ['required', 'email', 'unique:users', 'unique:admins', 'unique:companies', 'max:191'],
            'password' => ['required', 'max:191', 'string'],
            'phone_number' => ['required', 'unique:users', 'unique:admins', 'unique:companies', 'integer'],
            'photo' => ['required'],
            'social_media_account' => ['required'],



        ]);
        if ($a) {
            $file= $request->file('photo');
            $filename= $file->getClientOriginalName();
            $file-> move(public_path('public/Image'), $filename);
            $photo= $filename;
            $user = User::query()->create([
                'username' => $request->username,
                'email' => $request->email,
                'password' => $request->password,
                'phone_number' => $request->phone_number,
                'photo' => $photo,
                'social_media_account' => $request->social_media_account,


            ]);

            return response()->json($user);
        }
        else
            return response()->json(['not found',404]);

    }



    /*------------------------------------------------------------*/


    public function company(Request $request)
    {

        $a = $request->validate([
            'username' => ['required', 'unique:users', 'unique:admins', 'unique:companies', 'max:191', 'string'],
            'email' => ['required', 'email', 'unique:users', 'unique:admins', 'unique:companies', 'max:191'],
            'password' => ['required', 'max:191', 'string'],
            'phone_number' => ['required', 'unique:users', 'unique:admins', 'unique:companies', 'integer'],
            'photo' => ['required'],
            'social_media_account' => ['required'],


            'company_name' => ['required', 'unique:companies', 'max:191', 'string'],
            'company_email' => ['required', 'email', 'unique:companies', 'max:191'],
            'company_address' => ['required', 'string'],
            'commercial_record' => ['required'],
        ]);
        if ($a) {
            $file= $request->file('photo');
            $filename= $file->getClientOriginalName();
            $file-> move(public_path('public/Image'), $filename);
            $photo= $filename;
            $data = Company::query()->create([
                'username' => $request->username,
                'email' => $request->email,
                'password' => $request->password,
                'phone_number' => $request->phone_number,
                'photo' => $photo,
                'social_media_account' => $request->social_media_account,
                'company_name' => $request->company_name,
                'company_email' => $request->company_email,
                'company_address' => $request->company_address,
                'commercial_record' => $request->commercial_record,
            ]);

            return response()->json($data);
        }
        else
            return response()->json(["not found",404]);
    }




    /*------------------------------------------------------------*/
    public function login(Request $request)
    {
         $request->validate([
            'username' => ['required', 'max:191', 'string'],
            'password' => ['required', 'max:191', 'string'],
        ]);
         

        if (Admin::where(['username' => $request->username])->exists()) {
          $user=Admin::where(['username' => $request->username])->first();

          if (Hash::check($request->password,$user->password)){
            $accessToken = $user->createToken('Personal Access Token')->accessToken;

            return response()->json(['token',$accessToken]);
        }
          return response()->json(['incorrect password']);
        }

        elseif (User::where(['username' => $request->username])->exists()) {
          $user=User::where(['username' => $request->username])->first();

          if (Hash::check($request->password,$user->password)){
            $accessToken = $user->createToken('Personal Access Token')->accessToken;

            return response()->json(['token',$accessToken]);
        }
          return response()->json(['incorrect password']);
        }

        elseif (Company::where(['username' => $request->username])->exists()) {
          $user=Company::where(['username' => $request->username])->first();

          if (Hash::check($request->password,$user->password)){
            $accessToken = $user->createToken('Personal Access Token')->accessToken;

            return response()->json(['token',$accessToken]);
        }
          return response()->json(['incorrect password']);
        }
        else
            return response()->json('error',404);

    }
    public function ad()
    {
       return ('aa');
    }
public function aaa(){dd(3);}



























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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
