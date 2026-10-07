<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\Uploads;
use Illuminate\Http\Request;

/** Zengin metin editöründen görsel yükleme. */
class UploadController extends Controller
{
    public function store(Request $request)
    {
        $request->validate(['file' => ['required', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:8192']]);
        return response()->json(['url' => '/'.Uploads::image($request->file('file'))]);
    }
}
