<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Encoders\JpegEncoder;
use Intervention\Image\Encoders\PngEncoder;
use Intervention\Image\Laravel\Facades\Image;

class ImageController extends Controller
{
    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif',
        ]);

        // $path = $request->file('image')->store('uploads');

        $upload = $request->file('image');
        $name = pathinfo($upload->getClientOriginalName(), PATHINFO_FILENAME);

        $thumbnailFilename = 'uploads/thumbnails/' . $name . '_small.';
        $fullSizeFilename = 'uploads/' . $name . '_large.';

        //     return response()->json(['message' => 'Invalid image'], 400);

        Storage::put(
            $fullSizeFilename . $upload->getClientOriginalExtension(),
            file_get_contents($upload->getRealPath())
        );

        $image = Image::read($upload)->resizeDown(200, 100);

        Storage::put(
            $thumbnailFilename . $upload->getClientOriginalExtension(),
            $image->encodeByExtension($upload->getClientOriginalExtension(), quality: 80)
        );


        return $upload;


        // return response()->json(['message' => 'Image uploaded successfully']);

        // $upload = $request->file('image');
        // $extension = $upload->getClientOriginalExtension();

        // // ✅ Extract only filename without extension
        // $originalName = pathinfo($upload->getClientOriginalName(), PATHINFO_FILENAME);

        // // ✅ Rename the original and thumbnail files
        // $fullsizeFilename = 'uploads/' . $originalName . '_large.' . $extension;
        // $thumbnailFilename = 'uploads/thumbnails/' . $originalName . '_small.' . $extension;

        // // ✅ Save Full-Size Image Without Modification
        // Storage::put($fullsizeFilename, file_get_contents($upload->getRealPath()));

        // // ✅ Resize Image for Thumbnail
        // $image = Image::read($upload)->resize(300, 200);

        // // Select correct encoder
        // $encoder = match ($extension) {
        //     'jpeg', 'jpg' => new JpegEncoder(quality: 70),
        //     'png' => new PngEncoder(),
        //     default => throw new \Exception("Unsupported image format: $extension"),
        // };

        // // ✅ Save Resized Thumbnail
        // Storage::put($thumbnailFilename, $image->encode($encoder));

        // return response()->json([
        //     'message' => 'Image uploaded successfully!',
        //     'fullsize_url' => Storage::url($fullsizeFilename),
        //     'thumbnail_url' => Storage::url($thumbnailFilename),
        // ]);

    }
}
