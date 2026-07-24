@extends('layouts.app')
@section('title','Local Guides | Koshi Province')
@section('content')
<section class="view-section active">
  <div class="container">
    <div class="section-header">
      <h2>Certified Local Guides</h2>
      <p>Expert Nepali guides in high-altitude trekking, wildlife ornithology, cave exploration, and cultural heritage.</p>
    </div>

    <div class="card-grid">
      @foreach($guides as $guide)
      <div class="travel-card guide-card">
        <div class="card-img-container" style="height:200px;">
          <img src="{{ asset($guide['image']) }}" alt="{{ $guide['name'] }}" style="object-position:top;">
          <span class="card-badge">★ {{ $guide['rating'] }}</span>
        </div>
        <div class="card-body">
          <h3 class="card-title" style="font-size:20px;">{{ $guide['name'] }}</h3>
          <p style="color:var(--primary);font-weight:600;margin-bottom:8px;font-size:13px;">{{ $guide['specialty'] }}</p>
          <p style="font-size:13px;opacity:.7;margin-bottom:12px;">{{ $guide['experience'] }} Experience</p>
          <div class="amenities-list" style="margin-bottom:14px;">
            @foreach($guide['languages'] as $lang)
            <span class="amenity-tag">🗣 {{ $lang }}</span>
            @endforeach
          </div>
          <div class="card-footer">
            <div class="card-price"><span style="font-size:11px;opacity:.6;">Per Day</span><br><strong>Rs. {{ number_format($guide['rate']) }}</strong></div>
            <button class="card-btn" onclick="KoshiApp.openBooking('guide','{{ $guide['id'] }}','{{ addslashes($guide['name']) }}','{{ asset($guide['image']) }}',{{ $guide['rate'] }})">Hire Guide</button>
          </div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>
@endsection
