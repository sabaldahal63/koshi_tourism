@extends('layouts.app')
@section('title', 'My Bookings & Reservations | Koshi Province Tourism')

@section('content')
<section class="view-section active">
  <div class="container">
    
    <div class="section-header">
      <span class="hero-tag" style="margin-bottom:12px;">Reservations Portal</span>
      <h2>My Bookings & Stays</h2>
      <p>View all your confirmed hotel stays, local guide bookings, and trekking expeditions in Koshi Province.</p>
    </div>

    @if(isset($bookings) && $bookings->count() > 0)
      @php
        $totalSpend = $bookings->sum('total');
        $hotelCount = $bookings->where('type', 'hotel')->count();
        $guideCount = $bookings->where('type', 'guide')->count();
        $destCount  = $bookings->where('type', 'destination')->count();
      @endphp

      <!-- Stats Summary -->
      <div class="stats-grid" style="margin-bottom: 36px;">
        <div class="stat-card">
          <h3>{{ $bookings->count() }}</h3>
          <p>Total Reservations</p>
        </div>
        <div class="stat-card">
          <h3>Rs. {{ number_format($totalSpend) }}</h3>
          <p>Total Booked Value</p>
        </div>
        <div class="stat-card">
          <h3>{{ $hotelCount }}</h3>
          <p>Hotel Stays</p>
        </div>
        <div class="stat-card">
          <h3>{{ $guideCount }}</h3>
          <p>Guide Hires</p>
        </div>
      </div>

      <!-- Bookings List -->
      <div class="bookings-list">
        @foreach($bookings as $b)
          @php
            $typeIcon  = $b->type === 'hotel' ? '🏨' : ($b->type === 'guide' ? '🧭' : '🏔️');
            $typeLabel = $b->type === 'hotel' ? 'Hotel Stay' : ($b->type === 'guide' ? 'Local Guide' : 'Expedition');
            $imgSrc    = $b->image ? asset($b->image) : asset('assets/everest.png');
          @endphp
          <div class="booking-card" id="booking-card-{{ $b->id }}">
            <div class="booking-card-img">
              <img src="{{ $imgSrc }}" alt="{{ $b->title }}" onerror="this.src='{{ asset('assets/everest.png') }}'">
              <span class="booking-type-badge">{{ $typeIcon }} {{ $typeLabel }}</span>
            </div>
            <div class="booking-card-body">
              <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:10px;margin-bottom:14px;">
                <h3 style="margin:0;font-size:22px;">{{ $b->title }}</h3>
                <span class="booking-status confirmed">✓ Confirmed</span>
              </div>

              <div class="booking-info-grid">
                <div class="booking-info-item">
                  <span>Lead Guest</span>
                  <strong>👤 {{ $b->name }}</strong>
                </div>
                <div class="booking-info-item">
                  <span>Contact Email</span>
                  <strong>✉️ {{ $b->email }}</strong>
                </div>
                <div class="booking-info-item">
                  <span>Check-in / Date</span>
                  <strong>📅 {{ date('M d, Y', strtotime($b->date)) }}</strong>
                </div>
                <div class="booking-info-item">
                  <span>Travelers</span>
                  <strong>👥 {{ $b->guests }} {{ $b->guests > 1 ? 'Guests' : 'Guest' }}</strong>
                </div>
                <div class="booking-info-item">
                  <span>Duration</span>
                  <strong>🌙 {{ $b->nights }} {{ $b->type === 'guide' ? ($b->nights > 1 ? 'Days' : 'Day') : ($b->nights > 1 ? 'Nights' : 'Night') }}</strong>
                </div>
                <div class="booking-info-item highlight">
                  <span>Total Amount</span>
                  <strong>Rs. {{ number_format($b->total) }}</strong>
                </div>
              </div>

              <div class="booking-card-footer">
                <span style="font-size:12px;color:var(--text-muted);">
                  Booked on: {{ $b->created_at ? $b->created_at->format('M d, Y H:i') : 'Recently' }}
                </span>
                <div style="display:flex;gap:12px;">
                  <button class="danger-btn" onclick="KoshiApp.cancelBooking({{ $b->id }})">
                    🗑 Cancel Reservation
                  </button>
                </div>
              </div>
            </div>
          </div>
        @endforeach
      </div>

    @else
      <!-- Empty State -->
      <div class="planner-output-panel" style="text-align:center;padding:60px 20px;max-width:720px;margin:0 auto;">
        <div style="font-size:64px;margin-bottom:16px;line-height:1;">📋</div>
        <h3 style="font-size:26px;margin-bottom:10px;">No Bookings Yet</h3>
        <p style="color:var(--text-secondary);margin-bottom:28px;font-size:15px;max-width:480px;margin-left:auto;margin-right:auto;">
          You haven't reserved any hotels or guides yet. Explore Koshi Province’s breathtaking destinations and book your adventure today!
        </p>
        <div style="display:flex;gap:14px;justify-content:center;flex-wrap:wrap;">
          <a href="{{ route('stay') }}" class="card-btn" style="text-decoration:none;padding:12px 24px;">🏨 Browse Hotels</a>
          <a href="{{ route('guides') }}" class="card-btn" style="text-decoration:none;padding:12px 24px;">🧭 Hire Local Guides</a>
          <a href="{{ route('explore') }}" class="card-btn" style="text-decoration:none;padding:12px 24px;">🏔️ Explore Destinations</a>
        </div>
      </div>
    @endif

  </div>
</section>
@endsection
