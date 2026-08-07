<?php

namespace App\Support;

class Modules
{
    /**
     * Canonical list of assignable admin modules.
     * key => [label, icon, route name]
     */
    public static function all(): array
    {
        return [
            'appointments' => ['Appointments', '📅', 'admin.appointments.index'],
            'customers'    => ['Customers (CRM)', '👥', 'admin.customers.index'],
            'services'     => ['Services', '✂', 'admin.services.index'],
            'staff'        => ['Team / Staff', '💇', 'admin.staff.index'],
            'pos'          => ['POS — New Sale', '🧾', 'admin.pos.create'],
            'invoices'     => ['Invoices', '📄', 'admin.invoices.index'],
            'products'     => ['Products & Stock', '📦', 'admin.products.index'],
            'suppliers'    => ['Suppliers', '🚚', 'admin.suppliers.index'],
            'reports'      => ['Reports & Analytics', '📊', 'admin.reports.index'],
            'commissions'  => ['Commissions', '💸', 'admin.commissions.index'],
            'payroll'      => ['Payroll', '💰', 'admin.payroll.index'],
            'gallery'      => ['Gallery', '🖼', 'admin.gallery.index'],
            'offers'       => ['Offers & Coupons', '%', 'admin.offers.index'],
            'testimonials' => ['Reviews', '☆', 'admin.testimonials.index'],
            'settings'     => ['Site Content (CMS)', '⚙', 'admin.settings.index'],
        ];
    }

    /** Module keys only. */
    public static function keys(): array
    {
        return array_keys(static::all());
    }

    /** Modules always available to any admin user. */
    public static function alwaysAllowed(): array
    {
        return ['dashboard', 'profile', 'soon', 'logout'];
    }
}
