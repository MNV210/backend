<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use Aws\S3\S3Client;


class UploadController extends Controller
{
    public function uploadImageToS3(Request $request)
    {
        $path = $request->file('image')->store('public/images');

        return response()->json([
            'path' => $path
        ]);
    }
}
