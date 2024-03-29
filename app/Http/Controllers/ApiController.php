<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ApiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.custom:api', ['except' => ['login', 'getSuras', 'getChapters', 'getChapterBySura', 'searchChapters','getDuas', 'getDua', 'getLatestPosts', 'getPost', 'getPosts']]);
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
            $suras = DB::table('suras')->orderByRaw('CONVERT(serial_no, SIGNED) asc')->get();
            $status = true;
            return response()->json(compact('status', 'suras'));
        }
    }

    public function getSura($id)
    {
        $sura = DB::table('suras')->where('id', $id)->first();
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
        $save = DB::table('suras')->insert(
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
        DB::table('suras')->where('id', $request->id)->update([
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
            $deleted = DB::table('suras')->where('id', $id)->delete();
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
    public function getChapters($sura, Request $request)
    {
        $all = $request->allData;
        if (empty($all)) {
            $chapters = DB::table('chapters')->where('sura', $sura)->orderByRaw('CONVERT(serial, SIGNED) asc')->paginate(50);
            $status = true;
            return response()->json(compact('status', 'chapters'));
        } else {
            $chapters = DB::table('chapters')->where('sura', $sura)->orderByRaw('CONVERT(serial, SIGNED) asc')->get();
            $status = true;
            return response()->json(compact('status', 'chapters'));
        }
    }

    public function storeChapter(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'arabic' => 'required',
                'sura' => 'required',
                'serial' => 'required',
            ]
        );
        if ($validator->fails()) {
            $status = false;
            $errors = $validator->errors();
            return response()->json(compact('status', 'errors'));
        }
        $save = DB::table('chapters')->insert(
            [
                'arabic' => $request->arabic,
                'bangla' => $request->bangla,
                'shortTafsil' => $request->shortTafsil,
                'longTafsil' => $request->longTafsil,
                'serial' => $request->serial,
                'sura' => $request->sura,
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

    public function deleteChapter(Request $request)
    {
        $id = $request->id;
        if (!empty($id)) {
            $deleted = DB::table('chapters')->where('id', $id)->delete();
            if ($deleted) {
                $status = true;
                $message = 'Chapter deleted';
                return response()->json(compact('status', 'message'));
            } else {
                $status = false;
                $error = 'Chapter not found';
                return response()->json(compact('status', 'error'));
            }
        } else {
            $status = false;
            $error = 'Chapter not found';
            return response()->json(compact('status', 'error'));
        }
    }

    public function getChapter($id)
    {
        $chapter = DB::table('chapters')->where('id', $id)->first();
        $status = true;
        return response()->json(compact('status', 'chapter'));
    }

    public function updateChapter(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'arabic' => 'required',
                'sura' => 'required',
                'serial' => 'required',
                'id' => 'required',
            ]
        );
        if ($validator->fails()) {
            $status = false;
            $errors = $validator->errors();
            return response()->json(compact('status', 'errors'));
        }
        $save = DB::table('chapters')->where('id', $request->id)->update(
            [
                'arabic' => $request->arabic,
                'bangla' => $request->bangla,
                'shortTafsil' => $request->shortTafsil,
                'longTafsil' => $request->longTafsil,
                'serial' => $request->serial
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
    /*
    |--------------------------------------------------------------------------
    | Chapter Functions End
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | Dua Functions Start
    |--------------------------------------------------------------------------
    */
    public function getDuas(Request $request)
    {
        $all = $request->allData;
        $type = $request->type;
        if (empty($all) && empty($type)) {
            $duas = DB::table('duas')->paginate(50);
            $status = true;
            return response()->json(compact('status', 'duas'));
        }elseif (!empty($type)){
            $duas = DB::table('duas')->where('type',$type)->get();
            $status = true;
            return response()->json(compact('status', 'duas'));
        } else {
            $duas = DB::table('duas')->get();
            $status = true;
            return response()->json(compact('status', 'duas'));
        }
    }
    public function storeDua(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'banglaName' => 'required',
                'arabicName' => 'required',
                'banglaText' => 'required',
                'arabicText' => 'required',
                'type' => 'required',
            ]
        );
        if ($validator->fails()) {
            $status = false;
            $errors = $validator->errors();
            return response()->json(compact('status', 'errors'));
        }
        $save = DB::table('duas')->insert(
            [
                'banglaName' => $request->banglaName,
                'arabicName' => $request->arabicName,
                'banglaText' => $request->banglaText,
                'arabicText' => $request->arabicText,
                'type' => $request->type
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

    public function getDua($id)
    {
        $dua = DB::table('duas')->where('id', $id)->first();
        $status = true;
        return response()->json(compact('status', 'dua'));
    }

    public function updateDua(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'banglaName' => 'required',
                'arabicName' => 'required',
                'banglaText' => 'required',
                'arabicText' => 'required',
                'type' => 'required',
            ]
        );
        if ($validator->fails()) {
            $status = false;
            $errors = $validator->errors();
            return response()->json(compact('status', 'errors'));
        }
        $save = DB::table('duas')->where('id', $request->id)->update(
            [
                'banglaName' => $request->banglaName,
                'arabicName' => $request->arabicName,
                'banglaText' => $request->banglaText,
                'arabicText' => $request->arabicText,
                'type' => $request->type
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
    public function deleteDua(Request $request)
    {
        $id = $request->id;
        if (!empty($id)) {
            $deleted = DB::table('duas')->where('id', $id)->delete();
            if ($deleted) {
                $status = true;
                $message = 'Dua deleted';
                return response()->json(compact('status', 'message'));
            } else {
                $status = false;
                $error = 'Dua not found';
                return response()->json(compact('status', 'error'));
            }
        } else {
            $status = false;
            $error = 'Dua not found';
            return response()->json(compact('status', 'error'));
        }
    }
    /*
    |--------------------------------------------------------------------------
    | Dua Functions End
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | Post Functions Start
    |--------------------------------------------------------------------------
    */
    public function getPosts(Request $request)
    {
        $all = $request->allData;
        if (empty($all)) {
            $posts = DB::table('posts')->paginate(50);
            $status = true;
            return response()->json(compact('status', 'posts'));
        } else {
            $posts = DB::table('posts')->get();
            $status = true;
            return response()->json(compact('status', 'posts'));
        }
    }

    public function storePost(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'title' => 'required',
                'data' => 'required',
            ]
        );
        if ($validator->fails()) {
            $status = false;
            $errors = $validator->errors();
            return response()->json(compact('status', 'errors'));
        }
        $save = DB::table('posts')->insert(
            [
                'title' => $request->title,
                'content' => $request->data,
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

    public function deletePost(Request $request)
    {
        $id = $request->id;
        if (!empty($id)) {
            $deleted = DB::table('posts')->where('id', $id)->delete();
            if ($deleted) {
                $status = true;
                $message = 'Post deleted';
                return response()->json(compact('status', 'message'));
            } else {
                $status = false;
                $error = 'Post not found';
                return response()->json(compact('status', 'error'));
            }
        } else {
            $status = false;
            $error = 'Post not found';
            return response()->json(compact('status', 'error'));
        }
    }

    public function getPost($id)
    {
        $post = DB::table('posts')->where('id', $id)->first();
        $status = true;
        return response()->json(compact('status', 'post'));
    }

    public function updatePost(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'title' => 'required',
                'data' => 'required',
                'id' => 'required',
            ]
        );
        if ($validator->fails()) {
            $status = false;
            $errors = $validator->errors();
            return response()->json(compact('status', 'errors'));
        }
        $save = DB::table('posts')->where('id', $request->id)->update(
            [
                'title' => $request->title,
                'content' => $request->data,
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
    /*
    |--------------------------------------------------------------------------
    | Post Functions End
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | Frontend Functions Start
    |--------------------------------------------------------------------------
    */
    public function getChapterBySura(Request $request)
    {
        $sura = $request->sura;
        $chapter = $request->chapter;
        if (empty($chapter)) {
            $chapters = DB::table('chapters')->where('sura', $sura)->paginate(10);
        } else {
            $chapters = DB::table('chapters')->where('sura', $sura)->where('serial', '>=', $chapter)->paginate(20);
        }
        $status = true;
        return response()->json(compact('status', 'chapters'));
    }

    public function searchChapters(Request $request)
    {
        $keyword = $request->keyword;
        $chapters = DB::table('chapters')
            ->select('chapters.id', 'suras.serial_no', 'chapters.serial', 'chapters.arabic')
            ->join('suras', 'suras.id', '=' , 'chapters.sura')
            ->where('chapters.arabic', 'like', "%$keyword%")
            ->paginate(10);
        $status = true;
        return response()->json(compact('status', 'chapters'));
    }

    public function getLatestPosts()
    {
        $posts = DB::table('posts')
            ->limit(5)->get();
        $status = true;
        return response()->json(compact('status', 'posts'));
    }
    /*
    |--------------------------------------------------------------------------
    | Frontend Functions End
    |--------------------------------------------------------------------------
    */
}
