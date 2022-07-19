<?php

namespace App\Http\Controllers;

use App\Models\Sura;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ApiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.custom:api', ['except' => ['login']]);
    }

    protected function guard()
    {
        return Auth::guard();
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'email' => 'required',
                'password' => 'required',
            ]
        );
        if ($validator->fails()) {
            $status = false;
            $errors = $validator->errors();
            return response()->json(compact('status', 'errors'));
        }
        if (!$token = $this->guard()->attempt($validator->validated())) {
            $status = false;
            $errors = 'Email and password did not matched';
            return response()->json(compact('status', 'errors'));
        }
        $status = true;
        $user = User::select('id', 'name', 'email')->where('email', $request->email)->first();
        return response()->json(compact('status', 'user', 'token'));
    }

    public function profile()
    {
        return response()->json($this->guard()->user());
    }

    public function logout()
    {
        $this->guard()->logout();
        return response()->json(['message' => 'Successfully logged out'], 200);
    }

    /*
    |--------------------------------------------------------------------------
    | Sura Functions Start
    |--------------------------------------------------------------------------
    */
    public function getSuras(Request $request)
    {
        $all = $request->allData;
        if (empty($all)) {
            $data = Sura::select('*')->orderBy('serial_no','asc')->paginate(50);
            $status = true;
            return response()->json(compact('status', 'data'));
        } else {
            $data = Sura::all();
            $status = true;
            return response()->json(compact('status', 'data'));
        }
    }

    public function storeSura(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'arabic_name' => 'required',
                'bangla_name' => 'required',
                'serial_no' => ['required', 'unique:suras'],
            ]
        );
        if ($validator->fails()) {
            $status = false;
            $errors = $validator->errors();
            return response()->json(compact('status', 'errors'));
        }
        $save = Sura::create([
            'arabic_name' => $request->arabic_name,
            'bangla_name' => $request->arabic_name,
            'serial_no' => $request->serial_no]
        );
        if ($save) {
            $status = true;
            return response()->json(compact('status'));
        } else {
            $status = false;
            return response()->json(compact('status'));
        }
    }
    /*
    |--------------------------------------------------------------------------
    | Sura Functions End
    |--------------------------------------------------------------------------
    */

}
