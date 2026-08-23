<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Koshi Province Tourism Hub | Eastern Nepal')</title>
  <meta name="description" content="Explore Koshi Province – Mt. Everest, Ilam tea gardens, Koshi Tappu wildlife, sacred temples, local Sherpa guides, and hotels.">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap">
  <link rel="stylesheet" href="{{ asset('css/variables.css') }}">
  <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
  <link rel="stylesheet" href="{{ asset('css/components.css') }}">
  <script>
    window.APP_URL = "{{ url('/') }}";
    window.CSRF_TOKEN = "{{ csrf_token() }}";
    window.CURRENT_USER = @json(Auth::check() ? ['id' => Auth::id(), 'name' => Auth::user()->name, 'email' => Auth::user()->email] : null);
  </script>
</head>
<body>

<div class="app-container">

  <!-- Public Navigation -->
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
          <li><a href="{{ route('stay') }}"     class="{{ request()->routeIs('stay')     ? 'active' : '' }}">Stay & Hotels</a></li>
          <li><a href="{{ route('planner') }}"  class="{{ request()->routeIs('planner')  ? 'active' : '' }}">Plan Trip</a></li>
          <li><a href="{{ route('guides') }}"   class="{{ request()->routeIs('guides')   ? 'active' : '' }}">Local Guides</a></li>
          <li><a href="{{ route('advisor') }}"  class="{{ request()->routeIs('advisor')  ? 'active' : '' }}">AI Advisor</a></li>
          <li>
            <a href="{{ route('bookings') }}" class="{{ request()->routeIs('bookings') ? 'active' : '' }}" style="display:flex;align-items:center;gap:8px;">
              My Bookings
              <span id="booking-count-badge" class="nav-badge" style="display:none;">0</span>
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

  <!-- Public Footer -->
  <footer>
    <div class="container">
      <div class="footer-grid">
        <div class="footer-brand">
          <h3>⛰️ KoshiHub</h3>
          <p>Your premium gateway to Eastern Nepal. Experience the highest peaks on Earth, pristine tea fields of Ilam, sacred caves, and wetland wildlife safaris.</p>
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
            <li><a href="{{ route('advisor') }}">AI Travel Advisor</a></li>
            <li><a href="{{ route('bookings') }}">My Bookings</a></li>
            <li><a href="{{ route('planner') }}">Plan My Trip</a></li>
          </ul>
        </div>
        <div class="footer-newsletter">
          <h4>Stay Updated</h4>
          <p>Get seasonal trekking updates, hotel deals, and advisory guides for Koshi Province.</p>
          <form class="newsletter-form" onsubmit="event.preventDefault();showToast('Thank you for subscribing!');this.reset();">
            <input type="email" placeholder="Your email address" required>
            <button type="submit">Join</button>
          </form>
        </div>
      </div>
      <div class="footer-bottom">
        <p>© {{ date('Y') }} Koshi Province Tourism Management Hub. All rights reserved.</p>
        <div class="social-icons" style="display:flex;align-items:center;gap:18px;">
          <a href="{{ route('admin.login') }}" style="color:var(--text-muted);font-size:12px;display:flex;align-items:center;gap:4px;" title="Staff / Admin Portal">
            🔒 Staff Login
          </a>
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
  <div class="modal-content" style="max-width:520px;">
    <!-- Modal Header -->
    <div class="modal-header">
      <div style="display:flex;align-items:center;gap:10px;">
        <span style="font-size:24px;" id="modal-header-icon">📝</span>
        <h3 id="modal-header-title">Reserve Now</h3>
      </div>
      <button class="modal-close" id="modal-close-btn">&times;</button>
    </div>

    <!-- Step Indicator -->
    <div id="step-indicator" style="display:flex;align-items:center;gap:0;padding:0 24px 0;margin-top:-2px;border-bottom:1px solid rgba(255,255,255,0.07);background:rgba(0,0,0,0.2);">
      <div class="step-pill active" id="step-pill-1" style="display:flex;align-items:center;gap:8px;padding:12px 16px 12px 0;font-size:13px;font-weight:700;color:var(--primary);border-bottom:2px solid var(--primary);margin-bottom:-1px;">
        <span style="width:22px;height:22px;border-radius:50%;background:var(--primary);color:#030712;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:900;">1</span>
        Booking Details
      </div>
      <div style="width:30px;height:1px;background:rgba(255,255,255,0.15);margin:0 4px;"></div>
      <div class="step-pill" id="step-pill-2" style="display:flex;align-items:center;gap:8px;padding:12px 0 12px 4px;font-size:13px;font-weight:600;color:var(--text-muted);border-bottom:2px solid transparent;margin-bottom:-1px;">
        <span id="step2-num" style="width:22px;height:22px;border-radius:50%;background:rgba(255,255,255,0.1);color:var(--text-muted);display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:900;">2</span>
        Payment
      </div>
    </div>

    <div class="modal-body">

      <!-- ===== STEP 1: Booking Details ===== -->
      <div id="booking-step-1">
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
            <input type="text" id="book-name" required placeholder="e.g. Nabin Adhikari">
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
              <input type="number" id="book-guests" min="1" max="50" value="1" required>
            </div>
            <div class="form-group">
              <label for="book-nights" id="book-nights-label">Nights</label>
              <input type="number" id="book-nights" min="1" max="60" value="1" required>
            </div>
          </div>
          <div class="price-breakdown">
            <div class="breakdown-row">
              <span>Price / Unit</span>
              <span id="calc-unit-rate">Rs. 0</span>
            </div>
            <div class="breakdown-row total">
              <span>Total Amount</span>
              <span id="calc-total">Rs. 0</span>
            </div>
          </div>
          <button type="submit" class="booking-submit-btn" id="book-next-btn">
            Proceed to Payment →
          </button>
        </form>
      </div>

      <!-- ===== STEP 2: Payment Method ===== -->
      <div id="booking-step-2" style="display:none;">

        <!-- Amount Summary Bar -->
        <div id="pay-summary-bar" style="background:rgba(232,184,75,0.08);border:1px solid rgba(232,184,75,0.2);border-radius:12px;padding:14px 18px;margin-bottom:22px;display:flex;justify-content:space-between;align-items:center;">
          <div>
            <div style="font-size:11px;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-muted);margin-bottom:2px;">Booking For</div>
            <div style="font-size:14px;font-weight:700;color:#fff;" id="pay-summary-title">-</div>
          </div>
          <div style="text-align:right;">
            <div style="font-size:11px;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-muted);margin-bottom:2px;">Total Payable</div>
            <div style="font-size:22px;font-weight:900;color:var(--primary);" id="pay-summary-amount">Rs. 0</div>
          </div>
        </div>

        <!-- Payment Method Cards -->
        <div style="font-size:12px;text-transform:uppercase;letter-spacing:0.06em;color:var(--text-muted);font-weight:700;margin-bottom:14px;">Select Payment Method</div>
        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:10px;margin-bottom:22px;">

          <!-- eSewa -->
          <div class="payment-method-card" id="pay-esewa" onclick="KoshiApp.selectPayment('esewa')" style="cursor:pointer;border:2px solid rgba(255,255,255,0.08);border-radius:14px;padding:16px 10px;text-align:center;transition:all 0.2s ease;background:rgba(7,11,20,0.5);">
            <div style="width:48px;height:48px;border-radius:12px;background:#60bb46;margin:0 auto 10px;display:flex;align-items:center;justify-content:center;">
              <span style="color:#fff;font-weight:900;font-size:14px;font-family:var(--font-display);">eSewa</span>
            </div>
            <div style="font-size:13px;font-weight:700;color:#fff;">eSewa</div>
            <div style="font-size:11px;color:var(--text-muted);margin-top:2px;">Digital Wallet</div>
          </div>

          <!-- Khalti -->
          <div class="payment-method-card" id="pay-khalti" onclick="KoshiApp.selectPayment('khalti')" style="cursor:pointer;border:2px solid rgba(255,255,255,0.08);border-radius:14px;padding:16px 10px;text-align:center;transition:all 0.2s ease;background:rgba(7,11,20,0.5);">
            <div style="width:48px;height:48px;border-radius:12px;background:#5c2d91;margin:0 auto 10px;display:flex;align-items:center;justify-content:center;">
              <span style="color:#fff;font-weight:900;font-size:12px;font-family:var(--font-display);">Khalti</span>
            </div>
            <div style="font-size:13px;font-weight:700;color:#fff;">Khalti</div>
            <div style="font-size:11px;color:var(--text-muted);margin-top:2px;">Digital Wallet</div>
          </div>

          <!-- Mobile Banking -->
          <div class="payment-method-card" id="pay-mobile" onclick="KoshiApp.selectPayment('mobile_banking')" style="cursor:pointer;border:2px solid rgba(255,255,255,0.08);border-radius:14px;padding:16px 10px;text-align:center;transition:all 0.2s ease;background:rgba(7,11,20,0.5);">
            <div style="width:48px;height:48px;border-radius:12px;background:linear-gradient(135deg,#1d4ed8,#2563eb);margin:0 auto 10px;display:flex;align-items:center;justify-content:center;">
              <span style="font-size:22px;">🏦</span>
            </div>
            <div style="font-size:13px;font-weight:700;color:#fff;">Bank</div>
            <div style="font-size:11px;color:var(--text-muted);margin-top:2px;">Mobile Banking</div>
          </div>

        </div>

        <!-- eSewa Detail Panel -->
        <div id="pay-panel-esewa" style="display:none;">
          <div style="background:rgba(96,187,70,0.08);border:1px solid rgba(96,187,70,0.25);border-radius:12px;padding:18px 20px;margin-bottom:18px;">
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:14px;">
              <div style="width:36px;height:36px;border-radius:8px;background:#60bb46;display:flex;align-items:center;justify-content:center;">
                <span style="color:#fff;font-weight:900;font-size:10px;">eSewa</span>
              </div>
              <div>
                <div style="font-size:14px;font-weight:800;color:#fff;">Pay with eSewa</div>
                <div style="font-size:12px;color:var(--text-muted);">Enter your eSewa registered number</div>
              </div>
            </div>
            <div class="form-group" style="margin-bottom:12px;">
              <label style="font-size:11px;text-transform:uppercase;letter-spacing:0.06em;color:var(--text-muted);font-weight:700;display:block;margin-bottom:6px;">eSewa Mobile Number</label>
              <input type="tel" id="esewa-number" placeholder="98XXXXXXXX" style="width:100%;background:rgba(7,11,20,0.7);border:1px solid rgba(96,187,70,0.3);border-radius:10px;padding:11px 14px;color:#fff;font-size:14px;outline:none;font-family:inherit;">
            </div>
            <div style="font-size:12px;color:var(--text-muted);padding:10px;background:rgba(96,187,70,0.06);border-radius:8px;">
              💡 You will receive a payment request on your eSewa app. Approve it to confirm your booking.
            </div>
          </div>
        </div>

        <!-- Khalti Detail Panel -->
        <div id="pay-panel-khalti" style="display:none;">
          <div style="background:rgba(92,45,145,0.12);border:1px solid rgba(92,45,145,0.3);border-radius:12px;padding:18px 20px;margin-bottom:18px;">
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:14px;">
              <div style="width:36px;height:36px;border-radius:8px;background:#5c2d91;display:flex;align-items:center;justify-content:center;">
                <span style="color:#fff;font-weight:900;font-size:10px;">Khalti</span>
              </div>
              <div>
                <div style="font-size:14px;font-weight:800;color:#fff;">Pay with Khalti</div>
                <div style="font-size:12px;color:var(--text-muted);">Enter your Khalti registered number</div>
              </div>
            </div>
            <div class="form-group" style="margin-bottom:12px;">
              <label style="font-size:11px;text-transform:uppercase;letter-spacing:0.06em;color:var(--text-muted);font-weight:700;display:block;margin-bottom:6px;">Khalti Mobile Number</label>
              <input type="tel" id="khalti-number" placeholder="98XXXXXXXX" style="width:100%;background:rgba(7,11,20,0.7);border:1px solid rgba(92,45,145,0.4);border-radius:10px;padding:11px 14px;color:#fff;font-size:14px;outline:none;font-family:inherit;">
            </div>
            <div style="font-size:12px;color:var(--text-muted);padding:10px;background:rgba(92,45,145,0.08);border-radius:8px;">
              💡 You will receive an OTP on your Khalti app. Enter it to confirm your payment.
            </div>
          </div>
        </div>

        <!-- Mobile Banking Detail Panel -->
        <div id="pay-panel-mobile_banking" style="display:none;">
          <div style="background:rgba(29,78,216,0.1);border:1px solid rgba(29,78,216,0.25);border-radius:12px;padding:18px 20px;margin-bottom:18px;">
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:14px;">
              <div style="font-size:24px;">🏦</div>
              <div>
                <div style="font-size:14px;font-weight:800;color:#fff;">Mobile Banking Transfer</div>
                <div style="font-size:12px;color:var(--text-muted);">Select your bank and enter account details</div>
              </div>
            </div>
            <div class="form-group" style="margin-bottom:12px;">
              <label style="font-size:11px;text-transform:uppercase;letter-spacing:0.06em;color:var(--text-muted);font-weight:700;display:block;margin-bottom:6px;">Select Bank</label>
              <select id="bank-name" style="width:100%;background:rgba(7,11,20,0.7);border:1px solid rgba(29,78,216,0.3);border-radius:10px;padding:11px 14px;color:#fff;font-size:14px;outline:none;font-family:inherit;appearance:none;">
                <option value="">-- Choose Your Bank --</option>
                <option>Nepal Bank Limited</option>
                <option>Rastriya Banijya Bank</option>
                <option>Agriculture Development Bank</option>
                <option>Nabil Bank</option>
                <option>Nepal Investment Mega Bank</option>
                <option>Standard Chartered Bank Nepal</option>
                <option>Himalayan Bank</option>
                <option>Nepal SBI Bank</option>
                <option>Everest Bank</option>
                <option>Global IME Bank</option>
                <option>Machhapuchchhre Bank</option>
                <option>Laxmi Sunrise Bank</option>
                <option>Prabhu Bank</option>
                <option>NMB Bank</option>
                <option>Siddhartha Bank</option>
                <option>Citizens Bank International</option>
              </select>
            </div>
            <div class="form-group" style="margin-bottom:12px;">
              <label style="font-size:11px;text-transform:uppercase;letter-spacing:0.06em;color:var(--text-muted);font-weight:700;display:block;margin-bottom:6px;">Account Number / Mobile Number</label>
              <input type="text" id="bank-account" placeholder="Account or registered mobile number" style="width:100%;background:rgba(7,11,20,0.7);border:1px solid rgba(29,78,216,0.3);border-radius:10px;padding:11px 14px;color:#fff;font-size:14px;outline:none;font-family:inherit;">
            </div>
            <div style="font-size:12px;color:var(--text-muted);padding:10px;background:rgba(29,78,216,0.06);border-radius:8px;">
              💡 You will receive a payment notification on your bank's mobile app. Approve it to complete your booking.
            </div>
          </div>
        </div>

        <!-- Pay Now Button -->
        <button type="button" class="booking-submit-btn" id="pay-confirm-btn" disabled onclick="KoshiApp.confirmPayment()" style="opacity:0.5;">
          🔒 Pay &amp; Confirm Booking
        </button>
        <button type="button" onclick="KoshiApp.goToStep(1)" style="width:100%;background:none;border:none;color:var(--text-muted);cursor:pointer;font-size:13px;padding:10px;font-family:inherit;margin-top:6px;">
          ← Back to Booking Details
        </button>

      </div>

    </div>
  </div>
</div>

<!-- Toast Notification -->
<div class="toast-notification" id="toast-notif">
  <div class="toast-icon">✓</div>
  <div class="toast-msg">Action completed!</div>
</div>

<script src="{{ asset('js/app.js') }}"></script>
@yield('scripts')

</body>
</html>
