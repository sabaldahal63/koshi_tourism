@extends('admin.layout')
@section('title', 'Certified Guides | Koshi Tourism Admin')
@section('header_title', 'Certified Guides & Sherpas')
@section('header_subtitle', 'Certified local professionals for high-altitude climbing, birdwatching, and cultural tours.')

@section('content')

<div class="admin-card">
  <div class="admin-card-header">
    <div class="admin-card-title">
      <span style="font-size:20px;">🧭</span>
      <div>
        <h3>Certified Guides Roster ({{ count($guides) }})</h3>
        <p>Expertise, spoken languages, daily rates, and verified reviews.</p>
      </div>
    </div>
    <div class="admin-card-actions">
      <a href="{{ route('guides') }}" target="_blank" class="admin-btn">
        <span>↗</span> View on Site
      </a>
    </div>
  </div>

  <div class="table-responsive">
    <table class="admin-table">
      <thead>
        <tr>
          <th>Guide Profile</th>
          <th>Specialization</th>
          <th>Experience</th>
          <th>Daily Hire Rate</th>
          <th>Rating</th>
          <th>Languages Spoken</th>
        </tr>
      </thead>
      <tbody>
        @foreach($guides as $g)
          <tr>
            <td>
              <div class="table-booking-info">
                <img src="{{ asset($g['image']) }}" alt="{{ $g['name'] }}" class="table-booking-img" onerror="this.src='{{ asset('assets/everest.png') }}'">
                <div class="table-booking-title">
                  <strong>{{ $g['name'] }}</strong>
                  <span>ID: {{ $g['id'] }}</span>
                </div>
              </div>
            </td>
            <td>
              <span style="color:var(--admin-primary);font-weight:600;">{{ $g['specialty'] }}</span>
            </td>
            <td>
              <span style="color:#fff;font-weight:600;">{{ $g['experience'] }}</span>
            </td>
            <td>
              <strong style="color:var(--admin-primary);font-size:15px;">Rs. {{ number_format($g['rate']) }} / day</strong>
            </td>
            <td>
              <strong style="color:var(--admin-primary);">★ {{ $g['rating'] }}</strong>
            </td>
            <td>
              <div style="display:flex;gap:4px;flex-wrap:wrap;">
                @foreach($g['languages'] as $lang)
                  <span style="background:rgba(59,130,246,0.1);border:1px solid rgba(59,130,246,0.25);color:var(--admin-blue);border-radius:4px;padding:2px 6px;font-size:11px;">
                    🗣 {{ $lang }}
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
