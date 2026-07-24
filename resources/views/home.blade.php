@extends('layouts.app')
@section('title','Koshi Province Tourism Hub | Eastern Nepal')
@section('content')
<section class="view-section active">
  <div class="container">

    <div class="hero-banner" style="background-image:url('{{ asset('assets/everest.png') }}');">
      <div class="hero-content">
        <span class="hero-tag">Welcome to Eastern Nepal</span>
        <h1>Discover the Wonders of <br><span>Koshi Province</span></h1>
        <p>From the peak of Mount Everest (8,848m) to rolling Ilam tea fields and the wetlands of Koshi Tappu — Nepal's most diverse landscape awaits.</p>
      </div>
    </div>

    <div class="search-container">
      <div class="search-panel">
        <div class="search-field">
          <label for="home-search-dest">Where to go</label>
          <input type="text" id="home-search-dest" placeholder="e.g. Everest, Ilam, Koshi Tappu...">
        </div>
        <div class="search-field">
          <label for="home-search-cat">Vibe / Style</label>
          <select id="home-search-cat">
            <option value="all">Any Category</option>
            <option value="adventure">High Altitude</option>
            <option value="nature">Scenic Tea Gardens</option>
            <option value="wildlife">Wildlife Safari</option>
            <option value="spiritual">Spiritual Pilgrimage</option>
          </select>
        </div>
        <div class="search-field">
          <label for="home-search-date">Start Date</label>
          <input type="date" id="home-search-date">
        </div>
        <button class="search-submit" id="home-search-btn">🔍 Search</button>
      </div>
    </div>

    <div class="stats-grid">
      <div class="stat-card"><h3>8,848m</h3><p>Top of the World</p></div>
      <div class="stat-card"><h3>500+</h3><p>Bird Species</p></div>
      <div class="stat-card"><h3>3</h3><p>Climate Zones</p></div>
      <div class="stat-card"><h3>100%</h3><p>Authentic Hospitality</p></div>
    </div>

    <div class="section-header">
      <h2>Featured Experiences</h2>
      <p>Curated trekking trails, spiritual sites, and wildlife basecamps in Koshi Province.</p>
    </div>

    <div class="card-grid">
      @foreach($featured as $dest)
      <div class="travel-card">
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
  document.getElementById('home-search-btn').addEventListener('click',()=>{
    const dest = document.getElementById('home-search-dest').value;
    const cat  = document.getElementById('home-search-cat').value;
    window.location.href = `{{ route('explore') }}?search=${encodeURIComponent(dest)}&category=${encodeURIComponent(cat)}`;
  });
</script>
@endsection
