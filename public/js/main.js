// Loader
window.addEventListener('load',()=>{
  const l=document.getElementById('loader');
  setTimeout(()=>l.classList.add('hide'),500);
});

// Header solid + on-hero
const header=document.getElementById('header');
const hero=document.getElementById('home');
const toTop=document.getElementById('toTop');
function onScroll(){
  const y=window.scrollY;
  header.classList.toggle('solid',y>60);
  const heroBottom=hero.offsetHeight-90;
  header.classList.toggle('on-hero',y<heroBottom);
  toTop.classList.toggle('show',y>700);
}
window.addEventListener('scroll',onScroll);onScroll();

// Sidebar
const sidebar=document.getElementById('sidebar');
const overlay=document.getElementById('overlay');
function openMenu(){sidebar.classList.add('show');overlay.classList.add('show')}
function closeMenu(){sidebar.classList.remove('show');overlay.classList.remove('show')}
document.getElementById('menuBtn').addEventListener('click',openMenu);
document.getElementById('closeBtn').addEventListener('click',closeMenu);
overlay.addEventListener('click',closeMenu);
sidebar.querySelectorAll('a').forEach(a=>a.addEventListener('click',closeMenu));

// Reveal on scroll (directional variants included)
const io=new IntersectionObserver((entries)=>{
  entries.forEach(e=>{if(e.isIntersecting){e.target.classList.add('in');io.unobserve(e.target)}});
},{threshold:.12});
document.querySelectorAll('.reveal,.reveal-left,.reveal-right,.reveal-zoom').forEach(el=>io.observe(el));
// Clipped "curtain" images need a near-zero threshold — their visible area
// is tiny until revealed, so a .12 ratio would never be reached.
const ioImg=new IntersectionObserver((entries)=>{
  entries.forEach(e=>{if(e.isIntersecting){e.target.classList.add('in');ioImg.unobserve(e.target)}});
},{threshold:.01});
document.querySelectorAll('.reveal-img').forEach(el=>ioImg.observe(el));

// Scroll-linked parallax engine — sections "run" as you scroll.
// Disabled on small screens & for reduced-motion users; vertical shift is
// clamped so images can never slide out of their frame (black-box bug).
const pxEls=[...document.querySelectorAll('[data-parallax]')];
const pxOff=window.matchMedia('(max-width: 900px)');
const pxReduce=window.matchMedia('(prefers-reduced-motion: reduce)');
let ticking=false;
function parallax(){
  const vh=window.innerHeight;
  if(pxOff.matches||pxReduce.matches){
    pxEls.forEach(el=>{el.style.transform=''});
    ticking=false;return;
  }
  pxEls.forEach(el=>{
    const r=el.getBoundingClientRect();
    if(r.bottom<-200||r.top>vh+200)return;          // skip off-screen
    const speed=parseFloat(el.dataset.parallax)||0.15;
    const center=r.top+r.height/2-vh/2;             // distance from viewport center
    let shift=-center*speed;
    if(el.dataset.axis==='x'){
      el.style.transform='translateX('+shift+'px)';
    }else{
      const max=r.height*0.09;                      // stay within the oversized image
      shift=Math.max(-max,Math.min(max,shift));
      el.style.transform='translateY('+shift.toFixed(1)+'px)';
    }
  });
  ticking=false;
}
function reqPx(){if(!ticking){requestAnimationFrame(parallax);ticking=true;}}
window.addEventListener('scroll',reqPx,{passive:true});
window.addEventListener('resize',reqPx);
parallax();

// Hero slider
const slides=document.querySelectorAll('.hero-slide');
const dots=document.querySelectorAll('#dots button');
let hi=0;
function goHero(i){slides[hi].classList.remove('active');dots[hi].classList.remove('active');hi=i;slides[hi].classList.add('active');dots[hi].classList.add('active')}
dots.forEach(d=>d.addEventListener('click',()=>goHero(+d.dataset.i)));
setInterval(()=>goHero((hi+1)%slides.length),5500);

// Testimonials
const tSlides=document.querySelectorAll('.testi-track .testi-slide');
const tPhotos=document.querySelectorAll('.testi-photo .testi-slide');
let ti=0;
function goT(i){
  tSlides[ti].classList.remove('active');tPhotos[ti].classList.remove('active');
  ti=(i+tSlides.length)%tSlides.length;
  tSlides[ti].classList.add('active');tPhotos[ti].classList.add('active');
}
document.getElementById('tNext').addEventListener('click',()=>goT(ti+1));
document.getElementById('tPrev').addEventListener('click',()=>goT(ti-1));
setInterval(()=>goT(ti+1),6500);

// Before / After slider
const baInput=document.getElementById('baInput');
const baAfter=document.getElementById('baAfter');
const baDiv=document.getElementById('baDiv');
const baHandle=document.getElementById('baHandle');
function setBA(v){baAfter.style.clipPath='inset(0 0 0 '+v+'%)';baDiv.style.left=v+'%';baHandle.style.left=v+'%'}
// Section is optional — guard so a missing slider can't crash the script
if(baInput&&baAfter&&baDiv&&baHandle){
  baInput.addEventListener('input',e=>setBA(e.target.value));
  setBA(50);
}

// Graceful image fallback — keeps the luxury look even if a photo fails
const grads=[
 'linear-gradient(135deg,#0a0908,#1c1813 60%,#5a4423)',
 'linear-gradient(135deg,#13100c,#3a2c14 70%,#bb8a2e)',
 'linear-gradient(135deg,#000,#17130f 55%,#7a5e28)',
 'linear-gradient(135deg,#1c1813,#3a2c14 70%,#D4AF37)'
];
document.querySelectorAll('img').forEach((img,i)=>{
  img.addEventListener('error',()=>{
    const p=img.parentElement;
    p.style.background=grads[i%grads.length];
    img.style.display='none';
  });
});

// Booking form submits to the server (Laravel). No preventDefault —
// the success message is rendered server-side after redirect.
