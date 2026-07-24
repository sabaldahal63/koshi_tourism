<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Koshi Province Tourism Hub')</title>
  <meta name="description" content="Explore Koshi Province – Mt. Everest, Ilam tea gardens, Koshi Tappu wildlife, sacred temples, local Sherpa guides, and hotels.">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap">
  <link rel="stylesheet" href="{{ asset('css/variables.css') }}">
  <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
  <link rel="stylesheet" href="{{ asset('css/components.css') }}">
</head>
<body>

<div class="app-container">

  <!-- Navigation -->
  <header id="main-header">
    <div class="nav-content">
      <a href="{{ route('home') }}" class="logo">
        <div class="logo-icon">⛰️</div>
        <span>Koshi</span>Hub
      </a>
      <nav>
        <ul class="nav-links">
          <li><a href="{{ route('home') }}"     class="{{ request()->routeIs('home')     ? 'active' : '' }}">Home</a></li>
          <li><a href="{{ route('explore') }}"  class="{{ request()->routeIs('explore')  ? 'active' : '' }}">Explore</a></li>
          <li><a href="{{ route('stay') }}"     class="{{ request()->routeIs('stay')     ? 'active' : '' }}">Stay</a></li>
          <li><a href="{{ route('planner') }}"  class="{{ request()->routeIs('planner')  ? 'active' : '' }}">Plan Trip</a></li>
          <li><a href="{{ route('guides') }}"   class="{{ request()->routeIs('guides')   ? 'active' : '' }}">Guides</a></li>
          <li><a href="{{ route('advisor') }}"  class="{{ request()->routeIs('advisor')  ? 'active' : '' }}">Advisor</a></li>
          <li>
            <a href="{{ route('bookings') }}" class="{{ request()->routeIs('bookings') ? 'active' : '' }}" style="display:flex;align-items:center;gap:8px;">
              My Bookings
              <span id="booking-count-badge" style="background:var(--primary);color:#030712;border-radius:50%;font-size:11px;padding:2px 7px;display:none;font-weight:800;line-height:1.2;">0</span>
            </a>
          </li>
        </ul>
      </nav>
      <button class="nav-cta" onclick="window.location.href='{{ route('planner') }}'">Plan My Trip</button>
    </div>
  </header>

  <main>
    @yield('content')
  </main>

  <!-- Footer -->
  <footer>
    <div class="container">
      <div class="footer-grid">
        <div class="footer-brand">
          <h3>⛰️ KoshiHub</h3>
          <p>Your premium gateway to Eastern Nepal. Experience the highest peaks, pristine tea fields, religious caves, and wildlife safaris.</p>
        </div>
        <div class="footer-links">
          <h4>Explore</h4>
          <ul>
            <li><a href="{{ route('home') }}">Home</a></li>
            <li><a href="{{ route('explore') }}">Destinations</a></li>
            <li><a href="{{ route('stay') }}">Hotels & Stay</a></li>
            <li><a href="{{ route('planner') }}">Itinerary Planner</a></li>
          </ul>
        </div>
        <div class="footer-links">
          <h4>Services</h4>
          <ul>
            <li><a href="{{ route('guides') }}">Local Guides</a></li>
            <li><a href="{{ route('advisor') }}">Travel Advisor</a></li>
            <li><a href="{{ route('bookings') }}">My Bookings</a></li>
          </ul>
        </div>
        <div class="footer-newsletter">
          <h4>Stay Updated</h4>
          <p>Get seasonal trekking updates, hotel deals and advisory guides.</p>
          <form class="newsletter-form" onsubmit="event.preventDefault();alert('Subscribed!');this.reset();">
            <input type="email" placeholder="Your email address" required>
            <button type="submit">Join</button>
          </form>
        </div>
      </div>
      <div class="footer-bottom">
        <p>© {{ date('Y') }} Koshi Province Tourism Management Hub. All rights reserved.</p>
        <div class="social-icons">
          <a href="#">Facebook</a>
          <a href="#">Twitter</a>
          <a href="#">Instagram</a>
        </div>
      </div>
    </div>
  </footer>

</div>

<!-- Booking Modal -->
<div class="modal-overlay" id="booking-modal">
  <div class="modal-content">
    <div class="modal-header">
      <h3>Reserve Now</h3>
      <button class="modal-close" id="modal-close-btn">&times;</button>
    </div>
    <div class="modal-body">
      <div class="booking-summary-box">
        <img id="book-summary-img" src="" alt="Thumbnail">
        <div class="summary-info">
          <h4 id="book-summary-title">-</h4>
          <p id="book-summary-meta">-</p>
        </div>
      </div>
      <form id="book-modal-form">
        <div class="form-group">
          <label for="book-name">Lead Guest Full Name</label>
          <input type="text" id="book-name" required placeholder="e.g. Ram Prasad Shrestha">
        </div>
        <div class="form-group">
          <label for="book-email">Email Address</label>
          <input type="email" id="book-email" required placeholder="email@example.com">
        </div>
        <div class="form-group">
          <label for="book-date">Check-in / Start Date</label>
          <input type="date" id="book-date" required>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label for="book-guests">Guests</label>
            <input type="number" id="book-guests" min="1" value="1" required>
          </div>
          <div class="form-group">
            <label for="book-nights" id="book-nights-label">Nights</label>
            <input type="number" id="book-nights" min="1" value="1" required>
          </div>
        </div>
        <div class="price-breakdown">
          <div class="breakdown-row total">
            <span>Total Cost</span>
            <span id="calc-total">Rs. 0</span>
          </div>
        </div>
        <button type="submit" class="booking-submit-btn">✓ Confirm Reservation</button>
      </form>
    </div>
  </div>
</div>

<!-- Toast -->
<div class="toast-notification" id="toast-notif">
  <div class="toast-icon">✓</div>
  <div class="toast-msg">Done!</div>
</div>

<script src="{{ asset('js/app.js') }}"></script>
@yield('scripts')

</body>
</html>
