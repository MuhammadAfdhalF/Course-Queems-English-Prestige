<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RichTextUploadController extends Controller
{
    public function uploadImage(Request $request)
    {
        $validated = $request->validate([
            'file' => ['required', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:4096'],
        ]);

        $file = $validated['file'];

        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();

        $path = $file->storeAs(
            'rich-text/' . now()->format('Y/m'),
            $filename,
            'public'
        );

        return response()->json([
            'location' => asset('storage/' . $path),
        ]);
    }
}
