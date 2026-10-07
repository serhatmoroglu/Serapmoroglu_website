<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Treatment;
use App\Support\Uploads;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class TreatmentController extends Controller
{
    public function index()
    {
        return view('admin.treatments.index', ['items' => Treatment::orderBy('sort')->get()]);
    }

    public function create()
    {
        return view('admin.treatments.form', ['item' => new Treatment(['group' => 'tedavi', 'published' => true, 'sort' => (int) Treatment::max('sort') + 1])]);
    }

    public function store(Request $request)
    {
        $item = new Treatment();
        return $this->save($request, $item, 'Tedavi eklendi.');
    }

    public function edit(Treatment $treatment)
    {
        return view('admin.treatments.form', ['item' => $treatment]);
    }

    public function update(Request $request, Treatment $treatment)
    {
        return $this->save($request, $treatment, 'Tedavi güncellendi.');
    }

    public function destroy(Treatment $treatment)
    {
        Uploads::forget($treatment->image);
        $treatment->delete();
        return redirect()->route('admin.tedaviler.index')->with('ok', 'Tedavi silindi.');
    }

    private function save(Request $request, Treatment $item, string $msg)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:160'],
            'slug' => ['nullable', 'string', 'max:160', 'regex:/^[a-z0-9-]+$/', Rule::unique('treatments', 'slug')->ignore($item->id)],
            'group' => ['required', Rule::in(array_keys(Treatment::GROUPS))],
            'summary' => ['nullable', 'string', 'max:200'],
            'body' => ['nullable', 'string'],
            'meta_description' => ['nullable', 'string', 'max:300'],
            'sort' => ['nullable', 'integer', 'min:0'],
            'image_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:8192'],
        ], ['slug.regex' => 'Adres yalnızca küçük harf, rakam ve tire içerebilir.']);

        $data['slug'] = $data['slug'] ?: Str::slug(Str::ascii($data['title']));
        $data['sort'] = $data['sort'] ?? 0;
        $data['published'] = $request->boolean('published');
        unset($data['image_file']);

        if ($request->hasFile('image_file')) {
            Uploads::forget($item->image);
            $data['image'] = Uploads::image($request->file('image_file'));
        } elseif ($request->boolean('remove_image')) {
            Uploads::forget($item->image);
            $data['image'] = null;
        }
        $item->fill($data)->save();
        return redirect()->route('admin.tedaviler.edit', $item)->with('ok', $msg);
    }
}
