@extends('admin.layout')
@section('title', 'Hotels & Lodges | Koshi Tourism Admin')
@section('header_title', 'Hotels & Accommodations')
@section('header_subtitle', 'Overview of luxury resorts, homestays, and highland lodges.')

@section('content')

<div class="admin-card">
  <div class="admin-card-header">
    <div class="admin-card-title">
      <span style="font-size:20px;">🏨</span>
      <div>
        <h3>All Accommodations ({{ count($hotels) }})</h3>
        <p>Current listings, per-night rates in Nepali Rs., amenities, and reviews.</p>
      </div>
    </div>
    <div class="admin-card-actions">
      <a href="{{ route('stay') }}" target="_blank" class="admin-btn">
        <span>↗</span> View on Site
      </a>
    </div>
  </div>

  <div class="table-responsive">
    <table class="admin-table">
      <thead>
        <tr>
          <th>Hotel / Resort</th>
          <th>Type</th>
          <th>Location</th>
          <th>Price / Night</th>
          <th>Rating</th>
          <th>Key Amenities</th>
        </tr>
      </thead>
      <tbody>
        @foreach($hotels as $h)
          <tr>
            <td>
              <div class="table-booking-info">
                <img src="{{ asset($h['image']) }}" alt="{{ $h['name'] }}" class="table-booking-img" onerror="this.src='{{ asset('assets/resort.png') }}'">
                <div class="table-booking-title">
                  <strong>{{ $h['name'] }}</strong>
                  <span>ID: {{ $h['id'] }}</span>
                </div>
              </div>
            </td>
            <td>
              <span class="status-pill {{ strtolower($h['type']) === 'luxury' ? 'confirmed' : (strtolower($h['type']) === 'moderate' ? 'pending' : 'completed') }}">
                {{ $h['type'] }}
              </span>
            </td>
            <td>
              <span style="color:#fff;font-weight:600;">📍 {{ $h['location'] }}</span>
            </td>
            <td>
              <strong style="color:var(--admin-primary);font-size:15px;">Rs. {{ number_format($h['price']) }}</strong>
            </td>
            <td>
              <strong style="color:var(--admin-primary);">★ {{ $h['rating'] }}</strong>
              <span style="color:#9ca3af;font-size:11px;">({{ $h['reviews'] }} reviews)</span>
            </td>
            <td>
              <div style="display:flex;gap:4px;flex-wrap:wrap;">
                @foreach($h['amenities'] as $amenity)
                  <span style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.08);color:#9ca3af;border-radius:4px;padding:2px 6px;font-size:11px;">
                    {{ $amenity }}
                  </span>
                @endforeach
              </div>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>

@endsection
