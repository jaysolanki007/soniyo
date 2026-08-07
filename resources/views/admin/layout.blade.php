<!DOCTYPE html>
<html lang="en" class="dark">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', 'Dashboard') · SoNiYo Admin</title>
<link rel="icon" type="image/svg+xml" href="{{ asset('assets/soniyo-emblem.svg') }}">
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600;700&family=Jost:wght@300;400;500;600&display=swap" rel="stylesheet">
<script>
tailwind.config = {
  darkMode: 'class',
  theme: { extend: {
    fontFamily: { sans: ['Jost','system-ui','sans-serif'], serif: ['"Cormorant Garamond"','serif'] },
    colors: {
      gold: { DEFAULT:'#D4AF37', soft:'#f0d98f', deep:'#a87f2c' },
      ink: { 900:'#1b1a17', 800:'#262420', 700:'#322f28', 600:'#3c382f', 500:'#48433a' },
    }
  }}
}
</script>
<style>
  html{font-size:17px}
  body{font-family:'Jost',sans-serif}
  .serif{font-family:'Cormorant Garamond',serif}
  ::-webkit-scrollbar{width:9px;height:9px}
  ::-webkit-scrollbar-thumb{background:#48433a;border-radius:8px}
  ::-webkit-scrollbar-track{background:transparent}
  .nav-link.active{background:linear-gradient(90deg,rgba(212,175,55,.22),transparent);color:#f0d98f;border-color:#D4AF37}

  /* ---- Readability boosts (brighter secondary text) ---- */
  .text-stone-200{color:#f1ede6!important}
  .text-stone-300{color:#e4ded4!important}
  .text-stone-400{color:#cfc6b6!important}
  .text-stone-500{color:#b7ac98!important}
  .text-stone-600{color:#a39a89!important}
  /* ---- Larger, clearer small text ---- */
  .text-xs{font-size:.84rem!important;line-height:1.2rem!important}
  .text-sm{font-size:.97rem!important;line-height:1.45rem!important}
  /* nudge up the tiny uppercase labels too */
  .text-\[9px\]{font-size:10.5px!important}
  .text-\[10px\]{font-size:11.5px!important}
  .text-\[11px\]{font-size:12.5px!important}
  /* ---- Stronger separators / card edges ---- */
  .border-white\/5{border-color:rgba(255,255,255,.12)!important}
  .divide-white\/5 > :not([hidden]) ~ :not([hidden]){border-color:rgba(255,255,255,.1)!important}
  /* subtle card lift for definition */
  .bg-ink-800{box-shadow:0 1px 0 rgba(255,255,255,.03) inset}

  /* ============================================================
     Modern 3D hover & motion layer (applies across every module)
     ============================================================ */
  @media (prefers-reduced-motion: no-preference){

    /* gentle entrance for the page content */
    @keyframes adminFadeUp{from{opacity:0;transform:translateY(14px)}to{opacity:1;transform:none}}
    main{animation:adminFadeUp .5s cubic-bezier(.2,.7,.2,1) both}

    /* smooth transitions on interactive surfaces */
    a, button, select, input, textarea,
    .rounded-xl, .rounded-lg, .nav-link, tbody tr, .rounded-full{
      transition: transform .3s cubic-bezier(.2,.7,.2,1),
                  box-shadow .3s ease, border-color .3s ease,
                  background-color .25s ease, color .25s ease;
    }

    /* CARDS / PANELS — lift + soft gold-edged 3D shadow */
    .rounded-xl{will-change:transform;transform-style:preserve-3d}
    .rounded-xl:hover{
      transform:translateY(-5px);
      box-shadow:0 22px 48px -20px rgba(0,0,0,.75), 0 0 0 1px rgba(212,175,55,.22);
    }
    /* stat / metric cards (direct grid children) pop a little more */
    .grid > .rounded-xl:hover{transform:translateY(-6px) scale(1.018)}

    /* TABLE ROWS — slide in with a gold accent bar */
    tbody tr:hover{transform:translateX(4px);box-shadow:inset 3px 0 0 0 #D4AF37}

    /* SIDEBAR LINKS — glide right, icon pops */
    .nav-link span:first-child{transition:transform .3s cubic-bezier(.2,.7,.2,1)}
    .nav-link:hover{transform:translateX(6px)}
    .nav-link:hover span:first-child{transform:scale(1.3)}

    /* PRIMARY (gold gradient) BUTTONS — raise + glow + press */
    .from-gold-soft:hover{transform:translateY(-2px);box-shadow:0 14px 30px -10px rgba(212,175,55,.55)}
    .from-gold-soft:active{transform:translateY(0) scale(.97)}

    /* OUTLINE / GHOST BUTTONS — subtle raise */
    .border-white\/15:hover{transform:translateY(-2px)}

    /* INPUTS — focus glow ring */
    input:focus, select:focus, textarea:focus{box-shadow:0 0 0 3px rgba(212,175,55,.18)}

    /* AVATARS & ICON CHIPS — playful pop */
    a:hover > .rounded-full, .rounded-full:hover{transform:scale(1.08) rotate(-3deg)}

    /* POS catalog tiles & gallery cards — tactile lift */
    #catalog button:hover{transform:translateY(-4px) scale(1.03);box-shadow:0 18px 34px -18px rgba(0,0,0,.85)}
    .group:hover img{transform:scale(1.05)}
    .group img{transition:transform .5s cubic-bezier(.2,.7,.2,1)}
  }
</style>
</head>
<body class="bg-ink-900 text-stone-200 antialiased">
<div class="flex min-h-screen">

  <!-- Sidebar -->
  <aside id="sidebar" class="fixed lg:sticky top-0 z-40 h-screen w-72 shrink-0 bg-ink-800 border-r border-white/5 flex flex-col -translate-x-full lg:translate-x-0 transition-transform">
    <div class="flex items-center gap-3 px-6 h-20 border-b border-white/5">
      <img src="{{ asset('assets/soniyo-logo.png') }}" onerror="this.onerror=null;this.src='{{ asset('assets/soniyo-emblem.svg') }}'" class="h-12 w-auto" alt="SoNiYo">
      <div class="leading-tight">
        <div class="serif text-xl font-bold text-gold-soft tracking-wider">SoNiYo</div>
        <div class="text-[9px] tracking-[0.3em] text-stone-500 uppercase">Admin Suite</div>
      </div>
    </div>

    <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-6 text-sm">
      @php
        $me = auth()->user();
        // [label, route, icon, active(bool), module-key]
        $nav = [
          'Overview' => [
            ['Dashboard','admin.dashboard','▦', true, 'dashboard'],
          ],
          'Operations' => [
            ['Appointments','admin.appointments.index','📅', true, 'appointments'],
            ['Customers (CRM)','admin.customers.index','👥', true, 'customers'],
            ['Services','admin.services.index','✂', true, 'services'],
            ['Team / Staff','admin.staff.index','💇', true, 'staff'],
            ['POS — New Sale','admin.pos.create','🧾', true, 'pos'],
            ['Invoices','admin.invoices.index','📄', true, 'invoices'],
            ['Memberships','admin.soon','★', false, null],
            ['Loyalty & Packages','admin.soon','🎁', false, null],
          ],
          'Website CMS' => [
            ['Site Content','admin.settings.index','⚙', true, 'settings'],
            ['Gallery','admin.gallery.index','🖼', true, 'gallery'],
            ['Offers & Coupons','admin.offers.index','%', true, 'offers'],
            ['Reviews','admin.testimonials.index','☆', true, 'testimonials'],
          ],
          'Inventory & Finance' => [
            ['Products & Stock','admin.products.index','📦', true, 'products'],
            ['Suppliers','admin.suppliers.index','🚚', true, 'suppliers'],
            ['Reports & Analytics','admin.reports.index','📊', true, 'reports'],
            ['Commissions','admin.commissions.index','💸', true, 'commissions'],
            ['Payroll','admin.payroll.index','💰', true, 'payroll'],
          ],
          'Growth' => [
            ['Marketing','admin.soon','📣', false, null],
            ['WhatsApp / SMS','admin.soon','💬', false, null],
            ['AI Tools','admin.soon','✨', false, null],
          ],
          'System' => [
            ['My Profile','admin.profile.edit','👤', true, 'profile'],
            ['Users & Access','admin.users.index','🔐', true, 'users'],
            ['Branches','admin.soon','🏢', false, null],
            ['Notifications','admin.soon','🔔', false, null],
          ],
        ];
      @endphp

      @foreach ($nav as $group => $items)
        @php
          $visible = collect($items)->filter(function ($it) use ($me) {
            if ($it[3]) { return $me->canAccess($it[4]); }   // real module → permission check
            return $me->isSuperAdmin();                       // "soon" placeholders → super admin only
          });
        @endphp
        @if ($visible->isNotEmpty())
        <div>
          <div class="px-3 mb-2 text-[10px] uppercase tracking-[0.2em] text-stone-600">{{ $group }}</div>
          <div class="space-y-1">
            @foreach ($visible as $it)
              @php
                $parts = explode('.', $it[1]);
                $active = request()->routeIs($parts[0].'.'.$parts[1].'*');
              @endphp
              <a href="{{ route($it[1]) }}"
                 class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg border border-transparent hover:bg-white/5 transition {{ $active ? 'active' : 'text-stone-400' }}">
                <span class="w-5 text-center text-gold/80">{{ $it[2] }}</span>
                <span class="flex-1">{{ $it[0] }}</span>
                @unless ($it[3])<span class="text-[8px] uppercase tracking-wider bg-white/5 text-stone-500 px-1.5 py-0.5 rounded">soon</span>@endunless
              </a>
            @endforeach
          </div>
        </div>
        @endif
      @endforeach
    </nav>

    <div class="p-4 border-t border-white/5">
      <a href="{{ route('home') }}" target="_blank" class="block text-center text-xs text-stone-400 hover:text-gold py-2">↗ View Live Website</a>
    </div>
  </aside>

  <!-- Main -->
  <div class="flex-1 min-w-0 flex flex-col">
    <!-- Topbar -->
    <header class="sticky top-0 z-30 h-20 bg-ink-900/80 backdrop-blur border-b border-white/5 flex items-center gap-4 px-5 lg:px-8">
      <button onclick="document.getElementById('sidebar').classList.toggle('-translate-x-full')" class="lg:hidden text-2xl text-stone-300">☰</button>
      <div class="flex-1">
        <h1 class="serif text-2xl text-stone-100">@yield('title', 'Dashboard')</h1>
        @hasSection('subtitle')<p class="text-xs text-stone-500">@yield('subtitle')</p>@endif
      </div>
      @yield('actions')
      <div class="flex items-center gap-3 pl-3 border-l border-white/10">
        <a href="{{ route('admin.profile.edit') }}" class="flex items-center gap-3 group/profile" title="My Profile">
          <div class="text-right hidden sm:block">
            <div class="text-sm text-stone-200 group-hover/profile:text-gold transition">{{ auth()->user()->name ?? 'Admin' }}</div>
            <div class="text-[10px] uppercase tracking-wider text-gold/70">{{ str_replace('_',' ', auth()->user()->role ?? '') }}</div>
          </div>
          <div class="w-10 h-10 rounded-full overflow-hidden bg-gradient-to-br from-gold-soft to-gold-deep text-ink-900 font-semibold flex items-center justify-center">
            @if (auth()->user()->avatar)
              <img src="{{ \App\Support\Img::url(auth()->user()->avatar) }}" class="w-full h-full object-cover" alt="">
            @else
              {{ strtoupper(substr(auth()->user()->name ?? 'A',0,1)) }}
            @endif
          </div>
        </a>
        <form method="POST" action="{{ route('admin.logout') }}">@csrf
          <button class="text-xs text-stone-400 hover:text-gold ml-1" title="Logout">⏻</button>
        </form>
      </div>
    </header>

    <main class="flex-1 p-5 lg:p-8">
      @if (session('success'))
        <div class="mb-5 px-4 py-3 rounded-lg bg-green-500/10 border border-green-500/30 text-green-300 text-sm">{{ session('success') }}</div>
      @endif
      @if (session('error'))
        <div class="mb-5 px-4 py-3 rounded-lg bg-red-500/10 border border-red-500/30 text-red-300 text-sm">{{ session('error') }}</div>
      @endif
      @if ($errors->any())
        <div class="mb-5 px-4 py-3 rounded-lg bg-red-500/10 border border-red-500/30 text-red-300 text-sm">
          <ul class="list-disc list-inside">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
      @endif

      @yield('content')
    </main>
  </div>
</div>
@stack('scripts')
</body>
</html>
