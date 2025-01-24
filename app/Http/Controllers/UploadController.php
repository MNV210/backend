<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use Aws\S3\S3Client;


class UploadController extends Controller
{
    public function uploadToS3(Request $request)
    {
        // Validate the file input
        $request->validate([
            'image' => 'required|image|max:2048', // Restrict size to 2MB
        ]);

        // Get the uploaded file
        $file = $request->file('image');

        // Generate a unique file name
        $fileName = time() . '_' . $file->getClientOriginalName();

        // Upload the file to the S3 bucket
        $filePath = "uploads/" . $fileName;
        $disk = Storage::disk('s3'); // Use the 's3' disk configuration

        $disk->put($filePath, file_get_contents($file), 'public'); // Use 'public' for publicly accessible files

        // Get the file URL
        $url = $disk->url($filePath);

        return $url;
    }
}
