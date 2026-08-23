@extends('layouts.app')
@section('title', 'My Traveler Dashboard | Koshi Province Tourism')

@section('content')
<section class="view-section active">
  <div class="container">

    <!-- Traveler Profile Banner -->
    <div class="glass-panel" style="margin-bottom: 32px; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:20px; padding:32px 36px; border-radius:20px; background:linear-gradient(135deg, rgba(17,26,46,0.9), rgba(11,17,32,0.8));">
      <div style="display:flex; align-items:center; gap:20px;">
        <div style="width:64px; height:64px; border-radius:50%; background:linear-gradient(135deg, var(--primary), var(--primary-dark)); color:#030712; display:flex; align-items:center; justify-content:center; font-size:24px; font-weight:800; font-family:var(--font-display);">
          {{ strtoupper(substr($user->name, 0, 2)) }}
        </div>
        <div>
          <span class="hero-tag" style="margin-bottom:6px; font-size:11px; padding:3px 10px;">Verified Traveler</span>
          <h2 style="font-size:28px; margin:0 0 4px 0;">Namaste, {{ $user->name }}! 🙏</h2>
          <p style="color:var(--text-secondary); margin:0; font-size:14px;">
            <span>✉️ {{ $user->email }}</span>
            @if($user->phone)
              <span style="margin-left:14px;">📞 {{ $user->phone }}</span>
            @endif
          </p>
        </div>
      </div>
      <div style="display:flex; gap:12px; align-items:center;">
        <a href="{{ route('stay') }}" class="card-btn" style="text-decoration:none; padding:11px 20px;">+ Book New Stay</a>
        <form method="POST" action="{{ route('logout') }}" style="margin:0;">
          @csrf
          <button type="submit" class="danger-btn" style="padding:10px 18px; font-size:13px;">
            🚪 Logout
          </button>
        </form>
      </div>
    </div>

    <!-- Stats Summary -->
    <div class="stats-grid" style="margin-bottom: 36px;">
      <div class="stat-card">
        <h3>{{ $bookings->count() }}</h3>
        <p>My Total Bookings</p>
      </div>
      <div class="stat-card">
        <h3>Rs. {{ number_format($totalSpent) }}</h3>
        <p>Total Booked Value</p>
      </div>
      <div class="stat-card">
        <h3>{{ $hotelStays }}</h3>
        <p>Hotel Stays</p>
      </div>
      <div class="stat-card">
        <h3>{{ $guideHires }}</h3>
        <p>Guide Hires</p>
      </div>
    </div>

    <!-- Active Reservations Section -->
    <div class="section-header" style="text-align:left; margin-bottom:24px;">
      <h3 style="font-size:24px; margin:0;">My Bookings & Reservations</h3>
      <p style="margin-top:4px; font-size:14px;">All your confirmed hotel accommodations, guide bookings, and expedition packages.</p>
    </div>

    @if($bookings->count() > 0)
      <div class="bookings-list">
        @foreach($bookings as $b)
          @php
            $typeIcon  = $b->type === 'hotel' ? '🏨' : ($b->type === 'guide' ? '🧭' : '🏔️');
            $typeLabel = $b->type === 'hotel' ? 'Hotel Stay' : ($b->type === 'guide' ? 'Certified Guide' : 'Destination Package');
            $imgSrc    = $b->image ? asset($b->image) : asset('assets/everest.png');
          @endphp
          <div class="booking-card" id="booking-card-{{ $b->id }}">
            <div class="booking-card-img">
              <img src="{{ $imgSrc }}" alt="{{ $b->title }}" onerror="this.src='{{ asset('assets/everest.png') }}'">
              <span class="booking-type-badge">{{ $typeIcon }} {{ $typeLabel }}</span>
            </div>
            <div class="booking-card-body">
              <div style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:10px; margin-bottom:14px;">
                <div>
                  <h3 style="margin:0; font-size:22px;">{{ $b->title }}</h3>
                  <span style="font-size:12px; color:var(--text-muted);">Booking Reference: #{{ $b->id }}</span>
                </div>
                <span class="booking-status confirmed">✓ {{ ucfirst($b->status) }}</span>
              </div>

              <div class="booking-info-grid">
                <div class="booking-info-item">
                  <span>Guest Name</span>
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
                <span style="font-size:12px; color:var(--text-muted);">
                  Reserved on {{ $b->created_at ? $b->created_at->format('M d, Y') : 'Recently' }}
                </span>
                <button class="danger-btn" onclick="KoshiApp.cancelBooking({{ $b->id }})">
                  🗑 Cancel Reservation
                </button>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    @else
      <div class="planner-output-panel" style="text-align:center; padding:60px 20px; border-radius:16px;">
        <div style="font-size:56px; margin-bottom:16px;">🗺️</div>
        <h3 style="font-size:22px; margin-bottom:8px;">No trips booked yet</h3>
        <p style="color:var(--text-secondary); margin-bottom:24px; font-size:14px;">Ready to start your Koshi Province adventure? Explore hotels, local sherpas, or landmark trekking packages.</p>
        <div style="display:flex; gap:14px; justify-content:center; flex-wrap:wrap;">
          <a href="{{ route('stay') }}" class="card-btn" style="text-decoration:none;">🏨 Browse Hotels</a>
          <a href="{{ route('guides') }}" class="card-btn" style="text-decoration:none;">🧭 Find Local Guides</a>
          <a href="{{ route('explore') }}" class="card-btn" style="text-decoration:none;">🏔️ Explore Destinations</a>
        </div>
      </div>
    @endif

  </div>
</section>
@endsection
