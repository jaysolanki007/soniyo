@extends('admin.layout')
@section('title', 'POS · New Sale')
@section('subtitle', 'Bill services & products')

@section('content')
@php
  $inp='w-full px-3 py-2 rounded-lg bg-ink-900 border border-white/10 focus:border-gold focus:outline-none text-stone-100 text-sm';
  $catalog = [
    ...$services->map(fn($s)=>['type'=>'service','id'=>$s->id,'name'=>$s->name,'price'=>(float)$s->price])->all(),
    ...$products->map(fn($p)=>['type'=>'product','id'=>$p->id,'name'=>$p->name,'price'=>(float)$p->selling_price,'stock'=>$p->stock_qty])->all(),
  ];
@endphp

<form method="POST" action="{{ route('admin.pos.store') }}" id="posForm">
  @csrf
  <div class="grid lg:grid-cols-3 gap-6">

    <!-- Catalog -->
    <div class="lg:col-span-2 space-y-4">
      <div class="rounded-xl border border-white/5 bg-ink-800 p-4">
        <input type="text" id="catalogSearch" placeholder="Search services or products…" class="{{ $inp }} mb-4">
        <div id="catalog" class="grid sm:grid-cols-2 xl:grid-cols-3 gap-3 max-h-[60vh] overflow-y-auto pr-1"></div>
      </div>
    </div>

    <!-- Cart -->
    <div class="space-y-4">
      <div class="rounded-xl border border-white/5 bg-ink-800 p-5">
        <h2 class="serif text-xl text-stone-100 mb-4">Current Sale</h2>

        <div class="grid grid-cols-2 gap-3 mb-4">
          <div>
            <label class="block text-[10px] uppercase tracking-wider text-gold/80 mb-1">Customer</label>
            <select name="customer_id" class="{{ $inp }}">
              <option value="">Walk-in</option>
              @foreach ($customers as $c)<option value="{{ $c->id }}">{{ $c->name }}</option>@endforeach
            </select>
          </div>
          <div>
            <label class="block text-[10px] uppercase tracking-wider text-gold/80 mb-1">Stylist</label>
            <select name="staff_id" class="{{ $inp }}">
              <option value="">—</option>
              @foreach ($staff as $s)<option value="{{ $s->id }}">{{ $s->name }}</option>@endforeach
            </select>
          </div>
        </div>

        <div id="cart" class="space-y-2 mb-4 max-h-64 overflow-y-auto"></div>
        <p id="cartEmpty" class="text-stone-500 text-sm text-center py-6">Tap items to add them.</p>

        <div class="space-y-2 text-sm border-t border-white/10 pt-3">
          <div class="flex justify-between text-stone-400"><span>Subtotal</span><span id="subtotalLbl">₹0.00</span></div>
          <div class="flex justify-between items-center text-stone-400">
            <span>Discount (₹)</span>
            <input type="number" name="discount" id="discount" value="0" min="0" step="0.01" class="w-24 px-2 py-1 rounded bg-ink-900 border border-white/10 text-right text-stone-100">
          </div>
          <div class="flex justify-between items-center text-stone-400">
            <span>Tax (%)</span>
            <input type="number" name="tax_percent" id="taxPercent" value="0" min="0" step="0.01" class="w-24 px-2 py-1 rounded bg-ink-900 border border-white/10 text-right text-stone-100">
          </div>
          <div class="flex justify-between items-center text-stone-400">
            <span>Offer code</span>
            <input type="text" name="offer_code" class="w-28 px-2 py-1 rounded bg-ink-900 border border-white/10 text-right text-stone-100">
          </div>
          <div class="flex justify-between text-lg text-stone-100 border-t border-white/10 pt-2"><span class="serif">Total</span><span id="totalLbl" class="serif text-gold-soft">₹0.00</span></div>
        </div>

        <div class="mt-4">
          <label class="block text-[10px] uppercase tracking-wider text-gold/80 mb-1">Payment Method</label>
          <select name="payment_method" class="{{ $inp }} mb-3">
            <option value="cash">Cash</option><option value="card">Card</option><option value="upi">UPI</option><option value="wallet">Wallet</option><option value="bank">Bank Transfer</option>
          </select>
          <button type="submit" id="checkoutBtn" disabled class="w-full py-3 rounded-lg font-medium uppercase tracking-wider text-sm text-ink-900 bg-gradient-to-r from-gold-soft to-gold-deep disabled:opacity-40">Complete Sale</button>
        </div>
        <div id="hiddenItems"></div>
      </div>
    </div>
  </div>
</form>

@push('scripts')
<script>
const CATALOG = @json($catalog);
const cart = [];
const money = n => '₹'+Number(n).toFixed(2);

function renderCatalog(filter=''){
  const el = document.getElementById('catalog');
  el.innerHTML = '';
  CATALOG.filter(c => c.name.toLowerCase().includes(filter.toLowerCase())).forEach(c => {
    const d = document.createElement('button');
    d.type='button';
    d.className='text-left p-3 rounded-lg border border-white/10 hover:border-gold bg-ink-900 transition';
    d.innerHTML = `<div class="text-stone-100 text-sm">${c.name}</div>
      <div class="text-xs text-gold-soft">${money(c.price)}</div>
      <div class="text-[10px] uppercase tracking-wider mt-1 ${c.type==='product'?'text-sky-400':'text-stone-500'}">${c.type}${c.type==='product'?' · stock '+(c.stock??0):''}</div>`;
    d.onclick = () => addToCart(c);
    el.appendChild(d);
  });
}

function addToCart(c){
  const ex = cart.find(i => i.type===c.type && i.id===c.id);
  if (ex) ex.qty++; else cart.push({...c, qty:1, price:c.price});
  renderCart();
}

function renderCart(){
  const el = document.getElementById('cart');
  el.innerHTML='';
  document.getElementById('cartEmpty').style.display = cart.length ? 'none':'block';
  cart.forEach((i,idx)=>{
    const row=document.createElement('div');
    row.className='flex items-center gap-2 text-sm';
    row.innerHTML=`<div class="flex-1 min-w-0"><div class="text-stone-200 truncate">${i.name}</div><div class="text-xs text-stone-500">${money(i.price)}</div></div>
      <input type="number" min="1" value="${i.qty}" class="w-14 px-2 py-1 rounded bg-ink-900 border border-white/10 text-stone-100 text-center">
      <span class="w-16 text-right text-stone-300">${money(i.price*i.qty)}</span>
      <button type="button" class="text-rose-400 hover:text-rose-300">✕</button>`;
    row.querySelector('input').onchange = e => { i.qty = Math.max(1, parseInt(e.target.value)||1); renderCart(); };
    row.querySelector('button').onclick = () => { cart.splice(idx,1); renderCart(); };
    el.appendChild(row);
  });
  recalc();
}

function recalc(){
  const subtotal = cart.reduce((s,i)=>s+i.price*i.qty,0);
  const discount = parseFloat(document.getElementById('discount').value)||0;
  const taxP = parseFloat(document.getElementById('taxPercent').value)||0;
  const taxable = Math.max(0, subtotal-discount);
  const tax = taxable*taxP/100;
  document.getElementById('subtotalLbl').textContent = money(subtotal);
  document.getElementById('totalLbl').textContent = money(taxable+tax);
  document.getElementById('checkoutBtn').disabled = cart.length===0;
}

document.getElementById('catalogSearch').addEventListener('input', e=>renderCatalog(e.target.value));
document.getElementById('discount').addEventListener('input', recalc);
document.getElementById('taxPercent').addEventListener('input', recalc);

document.getElementById('posForm').addEventListener('submit', e=>{
  const box = document.getElementById('hiddenItems');
  box.innerHTML='';
  cart.forEach((i,idx)=>{
    const f=(k,v)=>{const inp=document.createElement('input');inp.type='hidden';inp.name=`items[${idx}][${k}]`;inp.value=v;box.appendChild(inp);};
    f('type',i.type); f('item_id',i.id); f('name',i.name); f('qty',i.qty); f('unit_price',i.price);
  });
});

renderCatalog();
renderCart();
</script>
@endpush
@endsection
