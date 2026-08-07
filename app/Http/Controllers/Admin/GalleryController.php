<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryItem;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function index()
    {
        $items = GalleryItem::orderBy('sort_order')->paginate(18);
        return view('admin.gallery.index', compact('items'));
    }

    public function create()
    {
        return view('admin.gallery.form', ['item' => new GalleryItem()]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['image'] = $this->resolveImage($request, 'image_file', 'image_url') ?? '';
        $data['is_public'] = $request->boolean('is_public');
        GalleryItem::create($data);
        return redirect()->route('admin.gallery.index')->with('success', 'Gallery item added.');
    }

    public function edit(GalleryItem $gallery)
    {
        return view('admin.gallery.form', ['item' => $gallery]);
    }

    public function update(Request $request, GalleryItem $gallery)
    {
        $data = $this->validated($request);
        $data['image'] = $this->resolveImage($request, 'image_file', 'image_url', $gallery->image);
        $data['is_public'] = $request->boolean('is_public');
        $gallery->update($data);
        return redirect()->route('admin.gallery.index')->with('success', 'Gallery item updated.');
    }

    public function destroy(GalleryItem $gallery)
    {
        $gallery->delete();
        return back()->with('success', 'Gallery item deleted.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'title' => ['nullable', 'string'],
            'category' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer'],
        ]);
    }
}
