<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\Uploadfile;
use Hash;
use Illuminate\Http\Request;
use JWTAuth;
use Storage;

class UploadController extends Controller {

    public function __construct() {
        $this->middleware('jwt.auth');
        $this->middleware('log', ['only' => ['uploadImage']]);
    }

    /*
     * [POST] upload/image
     */
    public function uploadImage(Request $request) {
        $this->validate($request, [
            'file' => 'required|mimes:jpeg,bmp,png'
        ]);

        $user = JWTAuth::parseToken()->authenticate();
        $file = $request->file('file');
        $content = file_get_contents($file->getRealPath());
        $path = 'images/' . md5(md5($content).'$'.$user->id) . '.' . $file->getClientOriginalExtension();
        Storage::put('public/' . $path, $content);
        $path = Storage::url($path);
        $uploadedFile = Uploadfile::where('path', $path)->first();
        if ($uploadedFile) {
//            $uploadedFile->uploader_id = $user->id;
            $uploadedFile->exist = 1;
            $uploadedFile->save();
        } else {
            Uploadfile::create([
                'path' => $path,
                'uploader_id' => $user->id
            ]);
        }

        Log::create([
            'ip' => $request->ip(),
            'user_id' => $user->id,
            'method' => $request->method(),
            'path' => $request->path(),
            'params' => json_encode(['file' => $path])
        ]);

        return $path;
    }
}