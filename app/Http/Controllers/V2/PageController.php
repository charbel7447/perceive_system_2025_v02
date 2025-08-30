<?php

namespace App\Http\Controllers\V2;

use Illuminate\Http\Request;
use Response;
use File;
use Auth;

class PageController extends Controller
{

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|max:255',
            'password' => 'required|min:6|max:255'
        ]);

        $auth = Auth::attempt([
            'email' => $request->email,
            'password' => $request->password,
            'is_active' => 1
        ]);

        if($auth) {
            return response()->json([
                'result' => true,
                'message' => 'Authorised!'
            ]);
        }

        return back()
            ->withInput()
            ->withErrors(['email' => ['Invalid email and password combination!']]);
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }

    public function showAttachment($filename)
    {
        $path = storage_path() . '/app/uploads/' . $filename;
        // dd($path);
        if(!File::exists($path)) {
            abort(404);
        }

        $file = File::get($path);
        $type = File::mimeType($path);

        $response = Response::make($file, 200);
        $response->header("Content-Type", $type);
        return $response;
    }
}
