<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    public function index()
    {
        $offers = Offer::latest()->paginate(15);
        return view('admin.offers.index', compact('offers'));
    }

    public function create()
    {
        return view('admin.offers.form', ['offer' => new Offer()]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['image'] = $this->resolveImage($request, 'image_file', 'image_url');
        $data['is_active'] = $request->boolean('is_active');
        $data['is_featured'] = $request->boolean('is_featured');
        Offer::create($data);
        return redirect()->route('admin.offers.index')->with('success', 'Offer created.');
    }

    public function edit(Offer $offer)
    {
        return view('admin.offers.form', ['offer' => $offer]);
    }

    public function update(Request $request, Offer $offer)
    {
        $data = $this->validated($request);
        $data['image'] = $this->resolveImage($request, 'image_file', 'image_url', $offer->image);
        $data['is_active'] = $request->boolean('is_active');
        $data['is_featured'] = $request->boolean('is_featured');
        $offer->update($data);
        return redirect()->route('admin.offers.index')->with('success', 'Offer updated.');
    }

    public function destroy(Offer $offer)
    {
        $offer->delete();
        return back()->with('success', 'Offer deleted.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'code' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'discount_type' => ['required', 'in:percent,fixed'],
            'discount_value' => ['nullable', 'numeric'],
            'valid_from' => ['nullable', 'date'],
            'valid_to' => ['nullable', 'date'],
            'usage_limit' => ['nullable', 'integer'],
        ]);
    }
}
