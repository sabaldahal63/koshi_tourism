@extends('layouts.app')
@section('title','My Bookings | Koshi Province')
@section('content')
<section class="view-section active">
  <div class="container">
    <div class="section-header">
      <h2>My Reservations</h2>
      <p>All your hotels, guide hires, and destination enquiries in one place.</p>
    </div>

    <!-- This div is populated by JavaScript AFTER the DOM loads -->
    <div id="bookings-container">
      <div class="planner-placeholder">
        <div class="spinner"></div>
        <p>Loading your bookings…</p>
      </div>
    </div>
  </div>
</section>
@endsection
@section('scripts')
<script>
/**
 * Render bookings on this page.
 * This runs AFTER app.js has loaded and initialized KoshiApp,
 * so KoshiApp is guaranteed to exist.
 */
document.addEventListener('DOMContentLoaded', () => {
  const container = document.getElementById('bookings-container');
  const bookings  = KoshiApp.getBookings();  // reads from localStorage

  if (!bookings || bookings.length === 0) {
    container.innerHTML = `
      <div class="planner-placeholder">
        <div style="font-size:64px;margin-bottom:16px;">📋</div>
        <h3>No bookings yet</h3>
        <p>Start exploring Koshi Province and make your first reservation!</p>
        <div style="display:flex;gap:12px;justify-content:center;margin-top:24px;flex-wrap:wrap;">
          <a href="/stay"    class="card-btn" style="text-decoration:none;">🏨 Browse Hotels</a>
          <a href="/guides"  class="card-btn" style="text-decoration:none;">🧭 Find a Guide</a>
          <a href="/explore" class="card-btn" style="text-decoration:none;">🏔️ Explore Destinations</a>
        </div>
      </div>`;
    return;
  }

  // Stats bar
  const totalSpend = bookings.reduce((s, b) => s + (b.total || 0), 0);
  const statsHtml = `
    <div class="stats-grid" style="margin-bottom:32px;">
      <div class="stat-card"><h3>${bookings.length}</h3><p>Total Reservations</p></div>
      <div class="stat-card"><h3>Rs. ${totalSpend.toLocaleString()}</h3><p>Total Spend</p></div>
      <div class="stat-card"><h3>${bookings.filter(b=>b.type==='hotel').length}</h3><p>Hotel Stays</p></div>
      <div class="stat-card"><h3>${bookings.filter(b=>b.type==='guide').length}</h3><p>Guide Bookings</p></div>
    </div>`;

  // Booking cards
  const cardsHtml = bookings.map((b, idx) => {
    const typeIcon = b.type === 'hotel' ? '🏨' : b.type === 'guide' ? '🧭' : '🏔️';
    const typLabel = b.type === 'hotel' ? 'Hotel Reservation' : b.type === 'guide' ? 'Guide Booking' : 'Destination Package';
    return `
      <div class="booking-card" id="booking-card-${idx}">
        <div class="booking-card-img">
          <img src="${b.image || ''}" alt="${b.title}" onerror="this.parentElement.style.background='var(--bg-card)'">
          <span class="booking-type-badge">${typeIcon} ${typLabel}</span>
        </div>
        <div class="booking-card-body">
          <h3>${b.title}</h3>
          <div class="booking-info-grid">
            <div class="booking-info-item"><span>👤 Guest</span><strong>${b.name}</strong></div>
            <div class="booking-info-item"><span>📧 Email</span><strong>${b.email}</strong></div>
            <div class="booking-info-item"><span>📅 Date</span><strong>${b.date}</strong></div>
            <div class="booking-info-item"><span>👥 Guests</span><strong>${b.guests}</strong></div>
            <div class="booking-info-item"><span>🌙 Nights/Days</span><strong>${b.nights}</strong></div>
            <div class="booking-info-item highlight"><span>💰 Total</span><strong>Rs. ${(b.total||0).toLocaleString()}</strong></div>
          </div>
          <div class="booking-card-footer">
            <span class="booking-status confirmed">✓ Confirmed</span>
            <button class="danger-btn" onclick="KoshiApp.cancelBooking(${idx})">🗑 Cancel</button>
          </div>
        </div>
      </div>`;
  }).join('');

  container.innerHTML = statsHtml + `<div class="bookings-list">${cardsHtml}</div>`;
});
</script>
@endsection
