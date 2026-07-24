@extends('layouts.app')
@section('title','Hotels & Stay | Koshi Province')
@section('content')
<section class="view-section active">
  <div class="container">
    <div class="section-header">
      <h2>Hotels & Accommodation</h2>
      <p>From eco-glamping camps by the Koshi river to warm highland lodges near Everest Base Camp.</p>
    </div>

    <div class="grid-filters">
      <button class="filter-btn active" data-type="all">All Types</button>
      <button class="filter-btn" data-type="Luxury">Luxury</button>
      <button class="filter-btn" data-type="Moderate">Moderate</button>
      <button class="filter-btn" data-type="Economy">Economy</button>
    </div>

    <div class="card-grid" id="hotels-grid">
      @foreach($hotels as $hotel)
      <div class="travel-card" data-type="{{ $hotel['type'] }}">
        <div class="card-img-container">
          <img src="{{ asset($hotel['image']) }}" alt="{{ $hotel['name'] }}">
          <span class="card-tag">{{ $hotel['type'] }}</span>
          <span class="card-badge">★ {{ $hotel['rating'] }}</span>
        </div>
        <div class="card-body">
          <div class="card-meta">
            <span>📍 {{ $hotel['location'] }}</span>
            <span>💬 {{ $hotel['reviews'] }} Reviews</span>
          </div>
          <h3 class="card-title">{{ $hotel['name'] }}</h3>
          <div class="amenities-list">
            @foreach(array_slice($hotel['amenities'],0,3) as $a)
            <span class="amenity-tag">✓ {{ $a }}</span>
            @endforeach
            @if(count($hotel['amenities'])>3)
            <span class="amenity-tag muted">+{{ count($hotel['amenities'])-3 }} more</span>
            @endif
          </div>
          <div class="card-footer">
            <div class="card-price"><span style="font-size:11px;opacity:.6;">Per Night</span><br><strong>Rs. {{ number_format($hotel['price']) }}</strong></div>
            <button class="card-btn" onclick="KoshiApp.openBooking('hotel','{{ $hotel['id'] }}','{{ addslashes($hotel['name']) }}','{{ asset($hotel['image']) }}',{{ $hotel['price'] }})">Reserve</button>
          </div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>
@endsection
@section('scripts')
<script>
document.addEventListener('DOMContentLoaded',()=>{
  const btns  = document.querySelectorAll('.grid-filters .filter-btn');
  const cards = document.querySelectorAll('#hotels-grid .travel-card');
  btns.forEach(btn => btn.addEventListener('click', () => {
    btns.forEach(b=>b.classList.remove('active'));
    btn.classList.add('active');
    const t = btn.dataset.type;
    cards.forEach(c => { c.style.display = (t==='all' || c.dataset.type===t) ? '' : 'none'; });
  }));
});
</script>
@endsection
