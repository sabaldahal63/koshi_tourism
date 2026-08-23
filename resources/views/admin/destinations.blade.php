@extends('admin.layout')
@section('title', 'Destinations Catalog | Koshi Tourism Admin')
@section('header_title', 'Destinations & Landmarks')
@section('header_subtitle', 'Overview of all active expedition and trekking packages across Koshi Province.')

@section('content')

<div class="admin-card">
  <div class="admin-card-header">
    <div class="admin-card-title">
      <span style="font-size:20px;">🏔️</span>
      <div>
        <h3>All Destinations ({{ count($destinations) }})</h3>
        <p>Tour packages, elevations, base prices, and ratings.</p>
      </div>
    </div>
    <div class="admin-card-actions">
      <a href="{{ route('explore') }}" target="_blank" class="admin-btn">
        <span>↗</span> View on Site
      </a>
    </div>
  </div>

  <div class="table-responsive">
    <table class="admin-table">
      <thead>
        <tr>
          <th>Destination</th>
          <th>Category</th>
          <th>Location</th>
          <th>Elevation</th>
          <th>Rating</th>
          <th>Est. Package Cost</th>
          <th>Best Time to Visit</th>
        </tr>
      </thead>
      <tbody>
        @foreach($destinations as $d)
          <tr>
            <td>
              <div class="table-booking-info">
                <img src="{{ asset($d['image']) }}" alt="{{ $d['name'] }}" class="table-booking-img" onerror="this.src='{{ asset('assets/everest.png') }}'">
                <div class="table-booking-title">
                  <strong>{{ $d['name'] }}</strong>
                  <span>{{ Str::limit($d['desc'], 60) }}</span>
                </div>
              </div>
            </td>
            <td>
              <span class="status-pill completed">{{ ucfirst($d['category']) }}</span>
            </td>
            <td>
              <span style="color:#fff;font-weight:600;">📍 {{ $d['location'] }}</span>
            </td>
            <td>
              <span style="color:var(--admin-primary);font-weight:700;">⛰️ {{ $d['elevation'] }}</span>
            </td>
            <td>
              <strong style="color:var(--admin-primary);">★ {{ $d['rating'] }}</strong>
              <span style="color:#9ca3af;font-size:11px;">({{ $d['reviews'] }})</span>
            </td>
            <td>
              <strong style="color:#fff;font-size:14px;">Rs. {{ number_format($d['price']) }}</strong>
            </td>
            <td>
              <span style="color:#9ca3af;font-size:12px;">{{ $d['bestTime'] }}</span>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>

@endsection
