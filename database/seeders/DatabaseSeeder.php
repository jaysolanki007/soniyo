<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Customer;
use App\Models\GalleryItem;
use App\Models\Offer;
use App\Models\Product;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\SiteSetting;
use App\Models\Staff;
use App\Models\Supplier;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ---------- Admin user ----------
        User::updateOrCreate(
            ['email' => 'admin@soniyo.com'],
            [
                'name' => 'Soniya R.',
                'username' => 'soniya',
                'password' => bcrypt('password'),
                'role' => 'super_admin',
                'phone' => '+91 98765 43210',
                'permissions' => null, // super admin = full access
                'is_active' => true,
            ]
        );

        // Demo limited user (receptionist) — can only use a few modules
        User::updateOrCreate(
            ['email' => 'reception@soniyo.com'],
            [
                'name' => 'Front Desk',
                'username' => 'reception',
                'password' => bcrypt('password'),
                'role' => 'receptionist',
                'phone' => '+91 98765 00000',
                'permissions' => ['appointments', 'customers', 'pos', 'invoices'],
                'is_active' => true,
            ]
        );

        // ---------- Site settings (CMS content) ----------
        $settings = [
            ['key' => 'site_name', 'value' => 'SoNiYo Beauty Salon', 'group' => 'general', 'type' => 'text', 'label' => 'Site Name'],
            ['key' => 'hero_eyebrow', 'value' => 'Award-winning luxury salon · Est. 2009', 'group' => 'hero', 'type' => 'text', 'label' => 'Hero Eyebrow'],
            ['key' => 'hero_title', 'value' => 'The Art of Luxury Beauty', 'group' => 'hero', 'type' => 'text', 'label' => 'Hero Title'],
            ['key' => 'hero_text', 'value' => 'Where master craftsmanship meets couture style. Precision cuts, bespoke color and signature rituals — designed entirely around you, in a space made for indulgence.', 'group' => 'hero', 'type' => 'textarea', 'label' => 'Hero Text'],
            ['key' => 'about_title', 'value' => 'A sanctuary of style & refinement', 'group' => 'about', 'type' => 'text', 'label' => 'About Title'],
            ['key' => 'about_text', 'value' => "From the moment you step onto our marble floors, every detail is composed for your comfort: hand-selected stylists, premium organic products, and private styling suites bathed in warm, golden light.", 'group' => 'about', 'type' => 'textarea', 'label' => 'About Text'],
            ['key' => 'contact_phone', 'value' => '+1 (212) 555-0188', 'group' => 'contact', 'type' => 'text', 'label' => 'Phone'],
            ['key' => 'contact_email', 'value' => 'hello@soniyosalon.com', 'group' => 'contact', 'type' => 'text', 'label' => 'Email'],
            ['key' => 'contact_address', 'value' => '148 Madison Avenue, New York, NY 10016', 'group' => 'contact', 'type' => 'text', 'label' => 'Address'],
            ['key' => 'social_instagram', 'value' => '#', 'group' => 'social', 'type' => 'text', 'label' => 'Instagram URL'],
            ['key' => 'social_pinterest', 'value' => '#', 'group' => 'social', 'type' => 'text', 'label' => 'Pinterest URL'],
            ['key' => 'social_tiktok', 'value' => '#', 'group' => 'social', 'type' => 'text', 'label' => 'TikTok URL'],
        ];
        foreach ($settings as $s) {
            SiteSetting::updateOrCreate(['key' => $s['key']], $s);
        }

        // ---------- Service categories ----------
        $cats = ['Haircut', 'Hair Color', 'Styling', 'Hair Spa', 'Bridal', 'Skin & Facial'];
        $catModels = [];
        foreach ($cats as $i => $name) {
            $catModels[$name] = ServiceCategory::updateOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name, 'sort_order' => $i, 'is_active' => true]
            );
        }

        // ---------- Services ----------
        $services = [
            ['Precision Cuts', 'Haircut', 85, 45, 'Architectural cuts shaped to your face and lifestyle by master stylists.', true],
            ['Couture Color', 'Hair Color', 140, 90, 'Balayage, highlights and bespoke tones using premium ammonia-free color.', true],
            ['Styling & Blowout', 'Styling', 65, 45, 'Red-carpet finishes, elegant updos and glamorous event styling.', true],
            ['Hair Spa Rituals', 'Hair Spa', 95, 60, 'Restorative scalp therapy and deep nourishment for radiant, healthy hair.', true],
            ['Full Balayage', 'Hair Color', 240, 120, 'Premium ammonia-free hand-painted balayage.', false],
            ['Keratin Smoothing Therapy', 'Hair Spa', 280, 120, 'Long-lasting smoothing and frizz control.', false],
            ['Bridal Couture', 'Bridal', 480, 180, 'Complete bridal hair & makeup package with trial.', true],
        ];
        foreach ($services as $i => $s) {
            Service::updateOrCreate(
                ['slug' => Str::slug($s[0])],
                [
                    'name' => $s[0],
                    'category_id' => $catModels[$s[1]]->id ?? null,
                    'price' => $s[2],
                    'duration_min' => $s[3],
                    'description' => $s[4],
                    'is_featured' => $s[5],
                    'is_active' => true,
                    'sort_order' => $i,
                ]
            );
        }

        // ---------- Staff / Team ----------
        // name, title, role, base_salary, commission_type, service%, product%, target, bonus
        $team = [
            ['Soniya R.', 'Founder · Creative Director', 'manager', 60000, 'flat', 0, 0, 0, 0],
            ['Elena Voss', 'Master Hair Stylist', 'stylist', 25000, 'split', 15, 8, 80000, 5000],
            ['Maya Chen', 'Color Specialist', 'stylist', 22000, 'split', 18, 10, 70000, 4000],
            ['Aria Laurent', 'Bridal & Makeup Artist', 'makeup_artist', 24000, 'flat', 20, 0, 90000, 6000],
        ];
        foreach ($team as $i => $t) {
            Staff::updateOrCreate(
                ['name' => $t[0]],
                [
                    'title' => $t[1], 'role' => $t[2], 'is_public' => true, 'is_active' => true,
                    'sort_order' => $i, 'experience_years' => 8 + $i,
                    'base_salary' => $t[3], 'commission_type' => $t[4],
                    'commission_percent' => $t[5], 'product_commission_percent' => $t[6],
                    'target_amount' => $t[7], 'target_bonus' => $t[8],
                ]
            );
        }

        // ---------- Testimonials ----------
        $testi = [
            ['Isabella Moreau', 'Bridal Client', 5, "SoNiYo isn't a salon, it's an experience. I walked out feeling like the most elegant version of myself."],
            ['Sophia Laurent', 'Color Client', 5, 'The best balayage of my life. The color has lasted months. Worth every penny.'],
            ['Amara Okafor', 'Regular Client', 5, "From the espresso on arrival to the final blow-dry, everything is luxurious."],
        ];
        foreach ($testi as $i => $t) {
            Testimonial::updateOrCreate(
                ['customer_name' => $t[0]],
                ['role' => $t[1], 'rating' => $t[2], 'quote' => $t[3], 'is_public' => true, 'sort_order' => $i]
            );
        }

        // ---------- Offers ----------
        Offer::updateOrCreate(
            ['title' => 'First Visit Welcome'],
            ['code' => 'WELCOME20', 'description' => '20% off your first appointment.', 'discount_type' => 'percent', 'discount_value' => 20, 'valid_to' => Carbon::now()->addMonths(3), 'is_featured' => true, 'is_active' => true]
        );
        Offer::updateOrCreate(
            ['title' => 'Bridal Season Special'],
            ['code' => 'BRIDE50', 'description' => '$50 off any bridal package.', 'discount_type' => 'fixed', 'discount_value' => 50, 'valid_to' => Carbon::now()->addMonths(6), 'is_active' => true]
        );

        // ---------- Gallery (uses public site placeholder images) ----------
        $galleryImgs = [
            'https://images.unsplash.com/photo-1605980776566-0486c3ac7617?auto=format&fit=crop&w=700&q=80',
            'https://images.unsplash.com/photo-1633681926022-84c23e8cb2d6?auto=format&fit=crop&w=900&q=80',
            'https://images.unsplash.com/photo-1595476108010-b4d1f102b1b1?auto=format&fit=crop&w=600&q=80',
            'https://images.unsplash.com/photo-1502823403499-6ccfcf4fb453?auto=format&fit=crop&w=600&q=80',
            'https://images.unsplash.com/photo-1457972729786-0411a3b2b626?auto=format&fit=crop&w=900&q=80',
            'https://images.unsplash.com/photo-1487412947147-5cebf100ffc2?auto=format&fit=crop&w=700&q=80',
        ];
        foreach ($galleryImgs as $i => $img) {
            GalleryItem::updateOrCreate(
                ['image' => $img],
                ['title' => 'Salon work '.($i + 1), 'category' => 'general', 'is_public' => true, 'sort_order' => $i]
            );
        }

        // ---------- Sample customers & appointments ----------
        $custData = [
            ['Olivia Bennett', 'female', 'olivia@example.com', '+1 (212) 555-0101', 'gold'],
            ['James Carter', 'male', 'james@example.com', '+1 (212) 555-0102', 'silver'],
            ['Priya Sharma', 'female', 'priya@example.com', '+1 (212) 555-0103', 'platinum'],
        ];
        $custModels = [];
        foreach ($custData as $c) {
            $custModels[] = Customer::updateOrCreate(
                ['email' => $c[2]],
                ['name' => $c[0], 'gender' => $c[1], 'phone' => $c[3], 'membership' => $c[4], 'loyalty_points' => rand(50, 500), 'visit_count' => rand(1, 12), 'last_visit_at' => Carbon::now()->subDays(rand(1, 40))]
            );
        }

        $svc = Service::first();
        $stf = Staff::first();
        foreach ($custModels as $i => $cust) {
            Appointment::updateOrCreate(
                ['reference' => 'APT-'.str_pad((string) ($i + 1), 5, '0', STR_PAD_LEFT)],
                [
                    'customer_id' => $cust->id,
                    'customer_name' => $cust->name,
                    'customer_phone' => $cust->phone,
                    'customer_email' => $cust->email,
                    'service_id' => $svc?->id,
                    'staff_id' => $stf?->id,
                    'scheduled_at' => Carbon::now()->addDays($i)->setTime(10 + $i, 0),
                    'duration_min' => 45,
                    'price' => $svc?->price ?? 85,
                    'status' => ['confirmed', 'pending', 'completed'][$i % 3],
                    'source' => 'online',
                ]
            );
        }

        // ---------- Suppliers ----------
        $sup1 = Supplier::updateOrCreate(['name' => 'Luxe Beauty Distributors'], ['company' => 'Luxe Beauty Co.', 'email' => 'orders@luxebeauty.com', 'phone' => '+1 (212) 555-0200']);
        $sup2 = Supplier::updateOrCreate(['name' => 'Pro Salon Supplies'], ['company' => 'ProSalon Inc.', 'email' => 'sales@prosalon.com', 'phone' => '+1 (212) 555-0201']);

        // ---------- Products ----------
        $products = [
            ['Gold Repair Shampoo', 'SH-001', 'Shampoo', 18, 42, 24, 6, $sup1->id],
            ['Silk Nourishing Oil', 'OIL-002', 'Hair Oil', 22, 58, 15, 5, $sup1->id],
            ['Velvet Repair Mask', 'MSK-003', 'Treatment', 28, 64, 4, 5, $sup2->id],
            ['Couture Finishing Serum', 'SRM-004', 'Styling', 20, 48, 18, 5, $sup2->id],
            ['Color Protect Conditioner', 'CND-005', 'Conditioner', 16, 38, 3, 5, $sup1->id],
        ];
        foreach ($products as $p) {
            Product::updateOrCreate(
                ['sku' => $p[1]],
                ['name' => $p[0], 'category' => $p[2], 'cost_price' => $p[3], 'selling_price' => $p[4], 'stock_qty' => $p[5], 'low_stock_threshold' => $p[6], 'supplier_id' => $p[7], 'is_active' => true]
            );
        }
    }
}
