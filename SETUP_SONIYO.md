# SoNiYo Salon Management System — Setup Guide

A modern, Laravel-based Salon Management System with a public website (CMS-driven)
and an admin panel. This is **Phase 1**: Foundation + Website CMS + Core CRM.

---

## 1. Prerequisites
- XAMPP running **Apache** and **MySQL**
- PHP 8.2+ (bundled with recent XAMPP)

## 2. Create the database
Open **phpMyAdmin** (http://localhost/phpmyadmin) and create an empty database named:

```
soniyo
```

(Collation `utf8mb4_unicode_ci`.) The DB connection is already configured in `.env`
(`DB_DATABASE=soniyo`, user `root`, no password — adjust if your MySQL differs).

## 3. Install & run (one time)
Open a terminal in the project folder `C:\xampp\htdocs\soniyo\soniyo` and run:

```bash
php artisan migrate --seed        # create tables + demo data
php artisan storage:link          # makes uploaded images public
php artisan optimize:clear        # clears any stale caches
```

## 4. Start the app
**Option A — artisan (recommended):**
```bash
php artisan serve
```
Then open: http://127.0.0.1:8000

**Option B — Apache (XAMPP):**
Point your browser to: http://localhost/soniyo/soniyo/public/

## 5. Log in to the admin
- URL: `/admin/login`  (e.g. http://127.0.0.1:8000/admin/login)
- Email: **admin@soniyo.com**
- Password: **password**

> Change this password after first login (and update the seeder for production).

---

## What's included in Phase 1

### Public website (DB-driven)
The existing luxury site is now powered by the database. Editing content in the
admin updates the live site instantly:
- **Hero / About / Contact text** → Admin → *Website Content*
- **Services & prices** → Admin → *Services*
- **Team members** → Admin → *Team / Staff* (toggle "Show on website")
- **Gallery images** → Admin → *Gallery*
- **Offers / coupons** → Admin → *Offers* (shown in a new Offers section)
- **Reviews** → Admin → *Reviews*
- **Booking form** → submissions are saved as **Appointments** (status: pending)
  and the customer is auto-added to the CRM.

### Admin panel
- **Dashboard** — today's appointments, monthly revenue, customers, pending bookings,
  status breakdown, upcoming birthdays, newest customers.
- **Appointments** — full CRUD, status workflow (pending → confirmed → checked-in →
  started → completed / cancelled / no-show), filter by status.
- **Customers (CRM)** — profiles, history, membership, loyalty points, search.
- **Services**, **Team/Staff**, **Gallery**, **Offers**, **Reviews**, **Website Content** — full CRUD.
- The sidebar also shows the **full module map** (POS, Inventory, Payroll, Marketing,
  Reports, Branches, AI tools, …) marked *“soon”* — these are the next phases.

### Images
Every image field accepts **either** a pasted URL **or** an uploaded file.
Uploads are stored in `storage/app/public/uploads` and served via the `storage` symlink.

---

## Tech notes
- **Auth:** custom session login restricted to admin roles (super_admin, owner, manager, receptionist) via the `admin` middleware.
- **Models/entities:** Users, Customers, Staff, ServiceCategories, Services, Appointments,
  GalleryItems, Offers, Testimonials, SiteSettings.
- **Common assets:** `public/css/style.css` and `public/js/main.js` (shared by the site);
  the admin uses Tailwind via CDN (no build step).
- **Roadmap (next phases):** POS & invoicing, inventory & suppliers, memberships/loyalty,
  packages, commissions & payroll, marketing/WhatsApp, reports & analytics, multi-branch,
  roles & permissions UI, and AI features.

---

## Troubleshooting
- **"could not find driver" / DB errors** → ensure MySQL is started in XAMPP and the
  `soniyo` database exists.
- **Images/CSS not loading on Apache** → run `php artisan storage:link` and confirm you're
  visiting the `/public/` path (or use `php artisan serve`).
- **Old content showing** → run `php artisan optimize:clear`.
- **Login loops** → run `php artisan migrate` (the `sessions` table must exist; SESSION_DRIVER=database).
