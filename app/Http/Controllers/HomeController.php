<?php

namespace App\Http\Controllers;

use App\Models\GalleryItem;
use App\Models\Offer;
use App\Models\Service;
use App\Models\Staff;
use App\Models\Testimonial;

class HomeController extends Controller
{
    public function index()
    {
        try {
            $services = Service::where('is_active', true)->orderBy('sort_order')->get();
            $featuredServices = Service::where('is_active', true)->where('is_featured', true)->orderBy('sort_order')->take(4)->get();
            $gallery = GalleryItem::where('is_public', true)->orderBy('sort_order')->take(8)->get();
            $offers = Offer::where('is_active', true)->orderByDesc('is_featured')->get();
            $team = Staff::where('is_public', true)->where('is_active', true)->orderBy('sort_order')->take(4)->get();
            $testimonials = Testimonial::where('is_public', true)->orderBy('sort_order')->get();
        } catch (\Throwable $e) {
            $services = collect();
            $featuredServices = collect();
            $gallery = collect();
            $offers = collect();
            $team = collect();
            $testimonials = collect();
        }

        return view('home', compact('services', 'featuredServices', 'gallery', 'offers', 'team', 'testimonials'));
    }

}
