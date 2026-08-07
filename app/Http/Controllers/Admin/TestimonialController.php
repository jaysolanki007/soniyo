<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function index()
    {
        $testimonials = Testimonial::orderBy('sort_order')->paginate(15);
        return view('admin.testimonials.index', compact('testimonials'));
    }

    public function create()
    {
        return view('admin.testimonials.form', ['testimonial' => new Testimonial()]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['photo'] = $this->resolveImage($request, 'photo_file', 'photo_url');
        $data['is_public'] = $request->boolean('is_public');
        Testimonial::create($data);
        return redirect()->route('admin.testimonials.index')->with('success', 'Review added.');
    }

    public function edit(Testimonial $testimonial)
    {
        return view('admin.testimonials.form', ['testimonial' => $testimonial]);
    }

    public function update(Request $request, Testimonial $testimonial)
    {
        $data = $this->validated($request);
        $data['photo'] = $this->resolveImage($request, 'photo_file', 'photo_url', $testimonial->photo);
        $data['is_public'] = $request->boolean('is_public');
        $testimonial->update($data);
        return redirect()->route('admin.testimonials.index')->with('success', 'Review updated.');
    }

    public function destroy(Testimonial $testimonial)
    {
        $testimonial->delete();
        return back()->with('success', 'Review deleted.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'role' => ['nullable', 'string'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'quote' => ['required', 'string'],
            'sort_order' => ['nullable', 'integer'],
        ]);
    }
}
