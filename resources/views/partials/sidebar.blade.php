<!-- ===== Sidebar ===== -->
<div class="overlay" id="overlay"></div>
<aside class="sidebar" id="sidebar">
  <div class="sidebar-top">
    <img class="sb-logo" src="{{ asset('assets/soniyo-logo.png') }}" alt="SoNiYo Beauty Salon" onerror="this.onerror=null;this.src='{{ asset('assets/soniyo-emblem.svg') }}'">
    <button class="close-btn" id="closeBtn" aria-label="Close menu">✕</button>
  </div>
  <nav>
    <a href="#home">Home <span>01</span></a>
    <a href="#about">About Us <span>02</span></a>
    <a href="#services">Services <span>03</span></a>
    <a href="#pricing">Pricing <span>04</span></a>
    <a href="#team">Our Team <span>05</span></a>
    <a href="#bridal">Bridal <span>06</span></a>
    <a href="#gallery">Gallery <span>07</span></a>
    <a href="#products">Products <span>08</span></a>
    <a href="#booking">Book Now <span>09</span></a>
  </nav>
  @php $set = fn($k,$d='') => \App\Models\SiteSetting::get($k,$d); @endphp
  <div class="sidebar-foot">
    <p class="gold">VISIT THE ATELIER</p>
    <p>{{ $set('contact_address','148 Madison Avenue, New York, NY 10016') }}</p>
    <p>Open Tue–Sun · 9am – 8pm</p>
    <p class="gold" style="margin-top:14px">{{ $set('contact_phone','+1 (212) 555-0188') }}</p>
    <div class="sidebar-social">
      <a href="{{ $set('social_instagram','#') }}">Instagram</a><a href="{{ $set('social_pinterest','#') }}">Pinterest</a><a href="{{ $set('social_tiktok','#') }}">TikTok</a>
    </div>
  </div>
</aside>
