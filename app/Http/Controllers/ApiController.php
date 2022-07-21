<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
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
            $suras = DB::table('suras')->select('*')->orderByRaw('CONVERT(serial_no, SIGNED) asc')->paginate(50);
            $status = true;
            return response()->json(compact('status', 'suras'));
        } else {
            $suras = DB::table('suras')->orderByRaw('CONVERT(serial_no, SIGNED) asc')->all();
            $status = true;
            return response()->json(compact('status', 'suras'));
        }
    }

    public function getSura($id)
    {
        $sura = Sura::where('id', $id)->first();
        $status = true;
        return response()->json(compact('status', 'sura'));
    }

    public function storeSura(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'banglaName' => 'required',
                'arabicName' => 'required',
                'serial_no' => ['required', 'unique:suras'],
            ]
        );
        if ($validator->fails()) {
            $status = false;
            $errors = $validator->errors();
            return response()->json(compact('status', 'errors'));
        }
        $save = Sura::create(
            [
                'arabic_name' => $request->arabicName,
                'bangla_name' => $request->banglaName,
                'serial_no' => $request->serial_no
            ]
        );
        if ($save) {
            $status = true;
            return response()->json(compact('status'));
        } else {
            $status = false;
            return response()->json(compact('status'));
        }
    }

    public function updateSura(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'id' => 'required',
                'banglaName' => 'required',
                'arabicName' => 'required',
                'serial_no' => 'required',
            ]
        );
        if ($validator->fails()) {
            $status = false;
            $errors = $validator->errors();
            return response()->json(compact('status', 'errors'));
        }
        Sura::where('id', $request->id)->update([
            'arabic_name' => $request->arabicName,
            'bangla_name' => $request->banglaName,
            'serial_no' => $request->serial_no
        ]);
        $status = true;
        $message = 'Updated';
        return response()->json(compact('status', 'message'));
    }
    public function deleteSura(Request $request)
    {
        $id = $request->id;
        if (!empty($id)) {
            $deleted = Sura::where('id', $id)->delete();
            if ($deleted) {
                $status = true;
                $message = 'Sura deleted';
                return response()->json(compact('status', 'message'));
            } else {
                $status = false;
                $error = 'Sura not found';
                return response()->json(compact('status', 'error'));
            }
        } else {
            $status = false;
            $error = 'Sura not found';
            return response()->json(compact('status', 'error'));
        }
    }
    /*
    |--------------------------------------------------------------------------
    | Sura Functions End
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | Chapter Functions Start
    |--------------------------------------------------------------------------
    */
    public function getChapters($sura)
    {
        $chapters = Chapter::where('sura', $sura)->get();
        $status = true;
        return response()->json(compact('status', 'chapters'));
    }
}
