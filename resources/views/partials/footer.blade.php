@php $set = fn($k,$d='') => \App\Models\SiteSetting::get($k,$d); @endphp
<!-- ===== Footer ===== -->
<footer class="footer" id="contact">
  <div class="wrap">
    <div class="foot-grid">
      <div class="foot-brand">
        <img class="foot-logo" src="{{ asset('assets/soniyo-logo.png') }}" alt="SoNiYo Beauty Salon" onerror="this.onerror=null;this.src='{{ asset('assets/soniyo-emblem.svg') }}'">
        <p>An award-winning luxury hair and beauty atelier where master craftsmanship, premium products and timeless style come together for an unforgettable experience.</p>
        <div class="foot-social">
          <a href="{{ $set('social_instagram','#') }}">Instagram</a><a href="{{ $set('social_pinterest','#') }}">Pinterest</a><a href="{{ $set('social_tiktok','#') }}">TikTok</a>
        </div>
      </div>
      <div class="foot-col">
        <h4>Explore</h4>
        <a href="#about">About Us</a>
        <a href="#services">Services</a>
        <a href="#pricing">Pricing</a>
        <a href="#team">Our Team</a>
        <a href="#gallery">Gallery</a>
      </div>
      <div class="foot-col">
        <h4>Services</h4>
        <a href="#services">Precision Cuts</a>
        <a href="#services">Couture Color</a>
        <a href="#bridal">Bridal Beauty</a>
        <a href="#services">Hair Spa</a>
        <a href="#products">Products</a>
      </div>
      <div class="foot-col foot-news">
        <h4>Visit &amp; Stay in Touch</h4>
        <p>{{ $set('contact_address','148 Madison Avenue, New York, NY 10016') }}</p>
        <p style="color:var(--gold-soft)">{{ $set('contact_phone','+1 (212) 555-0188') }}</p>
        <p>{{ $set('contact_email','hello@soniyosalon.com') }}</p>
        <div class="news-form">
          <input type="email" placeholder="Email for offers" aria-label="Email">
          <button type="button" aria-label="Subscribe">→</button>
        </div>
      </div>
    </div>
    <div class="foot-bottom">
      <span>© {{ date('Y') }} <span class="gold">SoNiYo Beauty Salon</span>. All rights reserved.</span>
      <span>Crafted with care · Privacy · Terms</span>
    </div>
  </div>
</footer>
