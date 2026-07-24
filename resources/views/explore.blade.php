@extends('layouts.app')
@section('title','Explore Destinations | Koshi Province')
@section('content')
<section class="view-section active">
  <div class="container">
    <div class="section-header">
      <h2>Explore Koshi Destinations</h2>
      <p>Search, filter, and plan your journey across world-renowned landmarks and hidden getaways.</p>
    </div>

    <div style="max-width:620px;margin:0 auto 30px auto;">
      <input type="text" id="dest-search-input" placeholder="Search by name, district, keyword..." style="width:100%;background:var(--bg-card);border:1px solid var(--border-color);border-radius:var(--radius-sm);padding:14px 20px;color:white;outline:none;font-family:var(--font-sans);font-size:15px;">
    </div>

    <div class="grid-filters" id="explore-filters">
      <button class="filter-btn active" data-filter="all">All Packages</button>
      <button class="filter-btn" data-filter="adventure">Altitudes & Peaks</button>
      <button class="filter-btn" data-filter="nature">Tea Gardens & Hills</button>
      <button class="filter-btn" data-filter="wildlife">Wildlife Reserves</button>
      <button class="filter-btn" data-filter="spiritual">Spiritual Sites</button>
    </div>

    <div class="card-grid" id="explore-grid">
      @foreach($destinations as $dest)
      <div class="travel-card" data-category="{{ $dest['category'] }}" data-name="{{ strtolower($dest['name'].' '.$dest['location'].' '.$dest['desc']) }}">
        <div class="card-img-container">
          <img src="{{ asset($dest['image']) }}" alt="{{ $dest['name'] }}">
          <span class="card-tag">{{ ucfirst($dest['category']) }}</span>
          <span class="card-badge">★ {{ $dest['rating'] }}</span>
        </div>
        <div class="card-body">
          <div class="card-meta">
            <span>📍 {{ $dest['location'] }}</span>
            <span>⛰️ {{ $dest['elevation'] }}</span>
          </div>
          <h3 class="card-title">{{ $dest['name'] }}</h3>
          <p class="card-desc">{{ $dest['desc'] }}</p>
          <div class="card-footer">
            <div class="card-price">Est. Cost<br><strong>Rs. {{ number_format($dest['price']) }}</strong> / traveler</div>
            <button class="card-btn" onclick="KoshiApp.openBooking('destination','{{ $dest['id'] }}','{{ addslashes($dest['name']) }}','{{ asset($dest['image']) }}',{{ $dest['price'] }})">Inquire</button>
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
  const search = document.getElementById('dest-search-input');
  const btns   = document.querySelectorAll('#explore-filters .filter-btn');
  const cards  = document.querySelectorAll('#explore-grid .travel-card');

  const filter = () => {
    const q   = search.value.toLowerCase().trim();
    const cat = document.querySelector('#explore-filters .filter-btn.active').dataset.filter;
    cards.forEach(c => {
      const matchCat  = cat === 'all' || c.dataset.category === cat;
      const matchText = !q || c.dataset.name.includes(q);
      c.style.display = (matchCat && matchText) ? '' : 'none';
    });
  };

  search.addEventListener('input', filter);
  btns.forEach(btn => btn.addEventListener('click', () => {
    btns.forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    filter();
  }));

  // Apply URL params from homepage search
  const params = new URLSearchParams(window.location.search);
  if (params.get('search'))   { search.value = params.get('search'); }
  if (params.get('category') && params.get('category') !== 'all') {
    const match = document.querySelector(`#explore-filters .filter-btn[data-filter="${params.get('category')}"]`);
    if (match) { btns.forEach(b=>b.classList.remove('active')); match.classList.add('active'); }
  }
  filter();
});
</script>
@endsection
