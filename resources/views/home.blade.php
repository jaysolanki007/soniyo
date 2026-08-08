@extends('layouts.app')

@php
  use App\Models\SiteSetting;
  use App\Support\Img;
  $set = fn ($k, $d = '') => SiteSetting::get($k, $d);

  $svcImgs = [
    'https://images.unsplash.com/photo-1599351431202-1e0f0137899a?auto=format&fit=crop&w=700&q=80',
    'https://images.unsplash.com/photo-1562322140-8baeececf3df?auto=format&fit=crop&w=700&q=80',
    'https://images.unsplash.com/photo-1521590832167-7bcbfaa6381f?auto=format&fit=crop&w=700&q=80',
    'https://images.unsplash.com/photo-1633681926035-ec1ac984418a?auto=format&fit=crop&w=700&q=80',
  ];
  $teamImgs = [
    'https://images.unsplash.com/photo-1580489944761-15a19d654956?auto=format&fit=crop&w=600&q=80',
    'https://images.unsplash.com/photo-1592621385612-4d7129426394?auto=format&fit=crop&w=600&q=80',
    'https://images.unsplash.com/photo-1607990281513-2c110a25bd8c?auto=format&fit=crop&w=600&q=80',
    'https://images.unsplash.com/photo-1605497788044-5a32c7078486?auto=format&fit=crop&w=600&q=80',
  ];
  $testiImgs = [
    'https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=700&q=80',
    'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?auto=format&fit=crop&w=700&q=80',
    'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=700&q=80',
  ];
@endphp

@section('content')

<!-- ===== Hero ===== -->
<section class="hero" id="home">
  <div class="hero-slides">
    <div class="hero-slide active"><img src="https://images.unsplash.com/photo-1560066984-138dadb4c035?auto=format&fit=crop&w=1920&q=80" alt="Luxury salon styling" /></div>
    <div class="hero-slide"><img src="https://images.unsplash.com/photo-1521590832167-7bcbfaa6381f?auto=format&fit=crop&w=1920&q=80" alt="Premium hair styling" /></div>
    <div class="hero-slide"><img src="https://images.unsplash.com/photo-1633681926022-84c23e8cb2d6?auto=format&fit=crop&w=1920&q=80" alt="Salon interior" /></div>
  </div>
  <div class="wrap hero-inner">
    <span class="eyebrow gold reveal">{{ $set('hero_eyebrow', 'Award-winning luxury salon · Est. 2009') }}</span>
    <h1 class="reveal" data-delay="1">The Art of<br><em>Luxury Beauty</em></h1>
    <p class="reveal" data-delay="2">{{ $set('hero_text', 'Where master craftsmanship meets couture style. Precision cuts, bespoke color and signature rituals — designed entirely around you, in a space made for indulgence.') }}</p>
    <div class="hero-actions reveal" data-delay="3">
      <a href="#booking" class="btn">Book Appointment</a>
      <a href="#services" class="btn ghost">Explore Services</a>
    </div>
  </div>
  <div class="hero-meta">
    <div class="wrap">
      <div class="hero-stats">
        <div class="s"><b>15+</b><span>Years of Artistry</span></div>
        <div class="s"><b>28k</b><span>Happy Clients</span></div>
        <div class="s"><b>4.9★</b><span>Average Rating</span></div>
      </div>
      <div class="slide-dots" id="dots">
        <button class="active" data-i="0"></button>
        <button data-i="1"></button>
        <button data-i="2"></button>
      </div>
      <div class="scroll-hint">Scroll<div class="scroll-line"></div></div>
    </div>
  </div>
</section>

<!-- ===== Marquee ===== -->
<div class="marquee">
  <div class="marquee-track">
    <span>Precision Cuts</span><span>Couture Color</span><span>Balayage</span><span>Bridal Glam</span><span>Hair Spa Rituals</span><span>Keratin Therapy</span>
    <span>Precision Cuts</span><span>Couture Color</span><span>Balayage</span><span>Bridal Glam</span><span>Hair Spa Rituals</span><span>Keratin Therapy</span>
  </div>
</div>

<!-- ===== About ===== -->
<section class="about section-pad" id="about">
  <div class="wrap about-grid">
    <div class="about-imgs reveal-left">
      <div class="main"><img src="https://images.unsplash.com/photo-1600948836101-f9ffda59d250?auto=format&fit=crop&w=900&q=80" alt="Luxury salon interior" /></div>
      <div class="float"><img src="https://images.unsplash.com/photo-1595476108010-b4d1f102b1b1?auto=format&fit=crop&w=600&q=80" alt="Styling detail" /></div>
      <div class="badge"><b>15</b><span>Years of<br>Excellence</span></div>
    </div>
    <div class="about-copy">
      <span class="eyebrow reveal">Welcome to SoNiYo</span>
      <h2 class="section-title reveal" data-delay="1">{{ $set('about_title', 'A sanctuary of style & refinement') }}</h2>
      <p class="lead reveal" data-delay="1">SoNiYo is more than a salon — it is a destination for those who believe beauty deserves artistry, time and absolute care.</p>
      <p class="reveal" data-delay="2">{{ $set('about_text', 'From the moment you step onto our marble floors, every detail is composed for your comfort: hand-selected stylists, premium organic products, and private styling suites bathed in warm, golden light.') }}</p>
      <ul class="about-list reveal" data-delay="2">
        <li>Master-trained stylists &amp; colorists</li>
        <li>Premium organic &amp; cruelty-free products</li>
        <li>Private luxury styling suites</li>
        <li>Complimentary consultation &amp; refreshments</li>
      </ul>
      <p class="sign reveal" data-delay="3">Soniya R. — Founder &amp; Creative Director</p>
    </div>
  </div>
</section>

<!-- ===== Services ===== -->
<section class="services section-pad" id="services">
  <div class="wrap">
    <div class="head-center reveal">
      <span class="eyebrow center">What We Offer</span>
      <h2 class="section-title">Signature <em>Services</em></h2>
      <p>A curated menu of hair and beauty experiences — each delivered with precision, premium products and an obsession for detail.</p>
    </div>
    <div class="svc-grid">
      @php $svcList = $featuredServices->count() ? $featuredServices : $services->take(4); @endphp
      @foreach ($svcList as $i => $s)
        <article class="svc reveal" data-delay="{{ $i+1 }}">
          <div class="svc-img"><img src="{{ Img::url($s->image, $svcImgs[$i % count($svcImgs)]) }}" alt="{{ $s->name }}"></div>
          <div class="svc-body"><span class="no">{{ str_pad($i+1,2,'0',STR_PAD_LEFT) }}</span><h3>{{ $s->name }}</h3><p>{{ $s->description }}</p><a class="more" href="#pricing">From ₹{{ number_format($s->price,0) }} →</a></div>
        </article>
      @endforeach
    </div>
  </div>
</section>

<!-- ===== Stylist at work (scroll-animated) ===== -->
<section class="atwork" id="atwork">
  <div class="atwork-words" data-parallax="0.12" data-axis="x">CRAFTED BY HAND · CRAFTED BY HAND ·</div>
  <div class="atwork-grid">
    <div class="atwork-media reveal-img">
      <div class="layer" data-parallax="0.18"><img src="https://images.unsplash.com/photo-1562322140-8baeececf3df?auto=format&fit=crop&w=1100&q=80" alt="Hair stylist giving a precision haircut"></div>
      <div class="atwork-float" data-parallax="-0.12"><img src="https://images.unsplash.com/photo-1595476108010-b4d1f102b1b1?auto=format&fit=crop&w=500&q=80" alt="Stylist detail at work"></div>
    </div>
    <div class="atwork-copy">
      <span class="eyebrow gold reveal">In the Chair</span>
      <h2 class="section-title reveal" data-delay="1">Watch Artistry <em>Come Alive</em></h2>
      <p class="reveal" data-delay="1">Every cut begins with a conversation and ends with a transformation. Our master stylists work strand by strand — sculpting, shaping and styling until the look is unmistakably you.</p>
      <div class="atwork-steps">
        <div class="atwork-step reveal-right" data-delay="1"><b>01</b><div><span class="t">Consult &amp; Design</span><span class="d">We study your features, hair and lifestyle.</span></div></div>
        <div class="atwork-step reveal-right" data-delay="2"><b>02</b><div><span class="t">Precision Cutting</span><span class="d">Architectural, hand-sculpted technique.</span></div></div>
        <div class="atwork-step reveal-right" data-delay="3"><b>03</b><div><span class="t">Style &amp; Finish</span><span class="d">A flawless, photo-ready final reveal.</span></div></div>
      </div>
    </div>
  </div>
</section>

<!-- ===== Pricing (full service menu from DB) ===== -->
<section class="pricing section-pad" id="pricing">
  <div class="wrap">
    <div class="head-center reveal">
      <span class="eyebrow center">Investment</span>
      <h2 class="section-title">Pricing &amp; <em>Packages</em></h2>
      <p>Transparent pricing for our most-loved experiences. Bespoke quotes available on consultation.</p>
    </div>
    <div class="menu-table">
      @php $half = (int) ceil($services->count() / 2); @endphp
      <div class="menu-col reveal">
        <h4>✦ Our Services</h4>
        @foreach ($services->take($half) as $s)
          <div class="menu-row"><div class="nm">{{ $s->name }} <small>{{ $s->duration_min }} min</small></div><div class="dots"></div><div class="pr">₹{{ number_format($s->price,0) }}</div></div>
        @endforeach
      </div>
      <div class="menu-col reveal" data-delay="1">
        <h4>✦ More Treatments</h4>
        @foreach ($services->slice($half) as $s)
          <div class="menu-row"><div class="nm">{{ $s->name }} <small>{{ $s->duration_min }} min</small></div><div class="dots"></div><div class="pr">₹{{ number_format($s->price,0) }}</div></div>
        @endforeach
      </div>
    </div>
    <p class="price-note">Prices are starting guides and may vary with hair length &amp; density · Memberships available — ask our concierge.</p>
  </div>
</section>

<!-- ===== Team ===== -->
<section class="team section-pad" id="team">
  <div class="wrap">
    <div class="head-center reveal">
      <span class="eyebrow center">The Artists</span>
      <h2 class="section-title">Meet Our <em>Master Team</em></h2>
      <p>Internationally trained stylists and colorists who turn every appointment into a work of art.</p>
    </div>
    <div class="team-grid">
      @foreach ($team as $i => $m)
        <article class="member reveal" data-delay="{{ $i+1 }}">
          <div class="member-img"><img src="{{ Img::url($m->photo, $teamImgs[$i % count($teamImgs)]) }}" alt="{{ $m->name }}"></div>
          <div class="member-info"><h3>{{ $m->name }}</h3><span>{{ $m->title }}</span>
            <div class="soc">@if($m->social_instagram)<a href="{{ $m->social_instagram }}">IG</a>@endif @if($m->social_linkedin)<a href="{{ $m->social_linkedin }}">in</a>@endif</div>
          </div>
        </article>
      @endforeach
    </div>
  </div>
</section>

<!-- ===== Bridal ===== -->
<section class="bridal" id="bridal">
  <div class="bridal-bg" data-parallax="0.15"><img src="https://images.unsplash.com/photo-1519741497674-611481863552?auto=format&fit=crop&w=1920&q=80" alt="Bridal beauty"></div>
  <div class="wrap">
    <div class="bridal-inner reveal">
      <span class="eyebrow gold">For Your Special Day</span>
      <h2 class="section-title">Bridal <em>Couture</em> Beauty</h2>
      <p>Your wedding day deserves nothing less than perfection. Our bridal artists craft a flawless, photograph-ready look that lasts from first light to last dance — with private trials, on-site styling and a personal beauty concierge.</p>
      <div class="bridal-feat">
        <div class="f"><b>Trial</b><span>Pre-wedding session</span></div>
        <div class="f"><b>On-site</b><span>We come to you</span></div>
        <div class="f"><b>Glam</b><span>Hair &amp; makeup</span></div>
      </div>
      <a href="#booking" class="btn">Enquire About Bridal</a>
    </div>
  </div>
</section>

@if ($offers->count())
<!-- ===== Offers ===== -->
<section class="products section-pad" id="offers">
  <div class="wrap">
    <div class="head-center reveal">
      <span class="eyebrow center">Limited Time</span>
      <h2 class="section-title">Current <em>Offers</em></h2>
      <p>Exclusive seasonal savings — quote the code when you book.</p>
    </div>
    <div class="prod-grid">
      @foreach ($offers->take(4) as $i => $o)
        <article class="prod reveal" data-delay="{{ $i+1 }}">
          <div class="prod-img"><img src="{{ Img::url($o->image, $svcImgs[$i % count($svcImgs)]) }}" alt="{{ $o->title }}"></div>
          <h3>{{ $o->title }}</h3>
          <span class="cat">{{ $o->description }}</span>
          <div class="price">{{ $o->discount_type==='percent' ? $o->discount_value.'% OFF' : '₹'.$o->discount_value.' OFF' }}</div>
          @if($o->code)<span class="cat" style="display:block;margin-top:6px;color:var(--gold-soft)">Code: {{ $o->code }}</span>@endif
        </article>
      @endforeach
    </div>
  </div>
</section>
@endif

<!-- ===== Gallery ===== -->
<section class="gallery section-pad" id="gallery">
  <div class="wrap">
    <div class="head-center reveal">
      <span class="eyebrow center">Portfolio</span>
      <h2 class="section-title">A Gallery of <em>Artistry</em></h2>
      <p>A glimpse of the looks, colors and moments created inside the SoNiYo atelier.</p>
    </div>
    <div class="gal-grid">
      @php $shapes = ['tall','wide','','','wide','tall','','']; @endphp
      @foreach ($gallery as $i => $g)
        <div class="gal-item {{ $shapes[$i % count($shapes)] }} reveal" @if($i%3) data-delay="{{ $i%3 }}" @endif><img src="{{ Img::url($g->image) }}" alt="{{ $g->title }}"></div>
      @endforeach
    </div>
  </div>
</section>

<!-- ===== Testimonials ===== -->
<section class="testi section-pad" id="testimonials">
  <div class="wrap testi-wrap">
    <div class="testi-photo reveal">
      @foreach ($testimonials as $i => $t)
        <div class="testi-slide {{ $i===0 ? 'active' : '' }}"><img src="{{ Img::url($t->photo, $testiImgs[$i % count($testiImgs)]) }}" alt="{{ $t->customer_name }}"></div>
      @endforeach
    </div>
    <div class="testi-copy">
      <span class="eyebrow gold reveal">Client Stories</span>
      <h2 class="section-title reveal" data-delay="1" style="color:var(--cream)">Loved by <em style="color:var(--gold-soft)">Many</em></h2>
      <div class="testi-track">
        @foreach ($testimonials as $i => $t)
          <div class="testi-slide {{ $i===0 ? 'active' : '' }}">
            <div class="stars">{{ str_repeat('★',$t->rating) }}</div>
            <p class="quote">"{{ $t->quote }}"</p>
            <div class="author"><div class="av"><img src="{{ Img::url($t->photo, $testiImgs[$i % count($testiImgs)]) }}" alt=""></div><div><b>{{ $t->customer_name }}</b><span>{{ $t->role }}</span></div></div>
          </div>
        @endforeach
      </div>
      <div class="testi-nav">
        <button id="tPrev">←</button>
        <button id="tNext">→</button>
      </div>
    </div>
  </div>
</section>

<!-- ===== Booking ===== -->
<section class="booking section-pad" id="booking">
  <div class="booking-bg" data-parallax="0.15"><img src="https://images.unsplash.com/photo-1633681926022-84c23e8cb2d6?auto=format&fit=crop&w=1920&q=80" alt="Reception"></div>
  <div class="wrap book-grid">
    <div class="book-info">
      <span class="eyebrow reveal">Reservations</span>
      <h2 class="section-title reveal" data-delay="1">Book Your <em>Visit</em></h2>
      <p class="lead reveal" data-delay="1">Reserve your seat in our atelier. Our concierge will confirm your appointment within 24 hours.</p>
      <p class="reveal" data-delay="2">Prefer to talk? Call us at <strong style="color:var(--brown)">{{ $set('contact_phone','+1 (212) 555-0188') }}</strong> or visit us at {{ $set('contact_address','148 Madison Avenue, New York') }}.</p>
      <div class="hours reveal" data-delay="2">
        <div><span>Monday</span><span>Closed</span></div>
        <div><span>Tuesday – Friday</span><span>9:00 AM – 8:00 PM</span></div>
        <div><span>Saturday</span><span>9:00 AM – 7:00 PM</span></div>
        <div><span>Sunday</span><span>10:00 AM – 6:00 PM</span></div>
      </div>
    </div>
    <form class="book-form reveal" data-delay="1" id="bookForm" method="POST" action="{{ route('booking.store') }}">
      @csrf
      <h3>Request an Appointment</h3>
      <p class="sub">Complimentary consultation with your first visit.</p>
      @if (session('booking_success') || request()->boolean('booked'))
        <p class="form-msg" style="color:var(--gold-soft)">Thank you! Your request has been received — our concierge will confirm within 24 hours.</p>
      @endif
      @if ($errors->any() || request()->boolean('booking_error'))
        <p class="form-msg" style="color:#e08">{{ $errors->first() ?: 'Sorry, something went wrong — please check your details and try again.' }}</p>
      @endif
      <div class="field-row">
        <div class="field"><label>Full Name</label><input type="text" name="customer_name" value="{{ old('customer_name') }}" required placeholder="Jane Doe"></div>
        <div class="field"><label>Phone</label><input type="tel" name="customer_phone" value="{{ old('customer_phone') }}" required placeholder="+1 ..."></div>
      </div>
      <div class="field"><label>Email</label><input type="email" name="customer_email" value="{{ old('customer_email') }}" placeholder="you@email.com"></div>
      <div class="field-row">
        <div class="field"><label>Service</label>
          <select name="service_id">
            <option value="">Select a service</option>
            @foreach ($services as $s)
              <option value="{{ $s->id }}" @selected(old('service_id')==$s->id)>{{ $s->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="field"><label>Preferred Date</label><input type="datetime-local" name="scheduled_at" value="{{ old('scheduled_at') }}"></div>
      </div>
      <div class="field"><label>Notes</label><textarea name="notes" rows="3" placeholder="Tell us about your hair goals...">{{ old('notes') }}</textarea></div>
      <button type="submit" class="btn dark" style="width:100%;justify-content:center">Request Appointment</button>
      <p class="form-msg" id="formMsg"></p>
    </form>
  </div>
</section>

<!-- ===== CTA ===== -->
<section class="cta">
  <div class="cta-bg" data-parallax="0.15"><img src="https://images.unsplash.com/photo-1521590832167-7bcbfaa6381f?auto=format&fit=crop&w=1920&q=80" alt="Luxury beauty"></div>
  <div class="wrap cta-inner reveal">
    <span class="eyebrow gold center">Your Transformation Awaits</span>
    <h2>Begin Your <em>SoNiYo</em> Experience</h2>
    <p>Step into a world where beauty is an art form and you are the masterpiece. Reserve your appointment today.</p>
    <a href="#booking" class="btn">Book Your Luxury Experience</a>
  </div>
</section>

@endsection
