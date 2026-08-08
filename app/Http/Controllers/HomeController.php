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
            $data = \Illuminate\Support\Facades\Cache::remember('home_page_data_v2', 600, function () {
                return [
                    'services' => Service::where('is_active', true)->orderBy('sort_order')->get()->toArray(),
                    'featuredServices' => Service::where('is_active', true)->where('is_featured', true)->orderBy('sort_order')->take(4)->get()->toArray(),
                    'gallery' => GalleryItem::where('is_public', true)->orderBy('sort_order')->take(8)->get()->toArray(),
                    'offers' => Offer::where('is_active', true)->orderByDesc('is_featured')->get()->toArray(),
                    'team' => Staff::where('is_public', true)->where('is_active', true)->orderBy('sort_order')->take(4)->get()->toArray(),
                    'testimonials' => Testimonial::where('is_public', true)->orderBy('sort_order')->get()->toArray(),
                ];
            });

            $services = Service::hydrate($data['services'] ?? []);
            $featuredServices = Service::hydrate($data['featuredServices'] ?? []);
            $gallery = GalleryItem::hydrate($data['gallery'] ?? []);
            $offers = Offer::hydrate($data['offers'] ?? []);
            $team = Staff::hydrate($data['team'] ?? []);
            $testimonials = Testimonial::hydrate($data['testimonials'] ?? []);
        } catch (\Throwable $e) {
            $services = Service::where('is_active', true)->orderBy('sort_order')->get();
            $featuredServices = Service::where('is_active', true)->where('is_featured', true)->orderBy('sort_order')->take(4)->get();
            $gallery = GalleryItem::where('is_public', true)->orderBy('sort_order')->take(8)->get();
            $offers = Offer::where('is_active', true)->orderByDesc('is_featured')->get();
            $team = Staff::where('is_public', true)->where('is_active', true)->orderBy('sort_order')->take(4)->get();
            $testimonials = Testimonial::where('is_public', true)->orderBy('sort_order')->get();
        }

        return view('home', compact('services', 'featuredServices', 'gallery', 'offers', 'team', 'testimonials'));
    }



}
