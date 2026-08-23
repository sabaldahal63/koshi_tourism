<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Admin Dashboard | Koshi Province Tourism')</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap">
  <link rel="stylesheet" href="{{ asset('css/variables.css') }}">
  <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
  <link rel="stylesheet" href="{{ asset('css/components.css') }}">
  <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
  <script>
    window.APP_URL = "{{ url('/') }}";
    window.CSRF_TOKEN = "{{ csrf_token() }}";
  </script>
</head>
<body class="admin-body">

<div class="admin-wrapper">

  <!-- Sidebar Navigation -->
  <aside class="admin-sidebar" id="adminSidebar">
    <div class="sidebar-header">
      <a href="{{ route('admin.dashboard') }}" class="sidebar-logo">
        <span style="font-size:22px;">⛰️</span>
        <div><span>Koshi</span>Admin</div>
      </a>
      <span class="sidebar-badge">Portal</span>
    </div>

    <div class="sidebar-nav">
      <span class="sidebar-nav-title">Management</span>
      <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <span class="icon">📊</span>
        <span>Overview & Bookings</span>
      </a>
      <a href="{{ route('admin.destinations') }}" class="sidebar-link {{ request()->routeIs('admin.destinations') ? 'active' : '' }}">
        <span class="icon">🏔️</span>
        <span>Destinations</span>
      </a>
      <a href="{{ route('admin.hotels') }}" class="sidebar-link {{ request()->routeIs('admin.hotels') ? 'active' : '' }}">
        <span class="icon">🏨</span>
        <span>Hotels & Resorts</span>
      </a>
      <a href="{{ route('admin.guides') }}" class="sidebar-link {{ request()->routeIs('admin.guides') ? 'active' : '' }}">
        <span class="icon">🧭</span>
        <span>Certified Guides</span>
      </a>

      <span class="sidebar-nav-title">Website</span>
      <a href="{{ route('home') }}" class="sidebar-link" target="_blank">
        <span class="icon">🌐</span>
        <span>Visit Main Website</span>
      </a>
      <a href="{{ route('bookings') }}" class="sidebar-link" target="_blank">
        <span class="icon">📋</span>
        <span>Customer Bookings</span>
      </a>
    </div>

    <div class="sidebar-footer">
      <div class="admin-user">
        <div class="admin-avatar">
          {{ strtoupper(substr(session('admin_user.name', 'Admin'), 0, 2)) }}
        </div>
        <div class="admin-user-info">
          <h4>{{ session('admin_user.name', 'Administrator') }}</h4>
          <p>{{ session('admin_user.role', 'Staff Member') }}</p>
        </div>
      </div>
      <form method="POST" action="{{ route('admin.logout') }}" style="margin:0;">
        @csrf
        <button type="submit" class="btn-icon-danger" title="Sign Out / Logout" style="width:30px;height:30px;font-size:14px;">
          🚪
        </button>
      </form>
    </div>
  </aside>

  <!-- Main Content Body -->
  <div class="admin-main">
    
    <!-- Topbar -->
    <header class="admin-topbar">
      <div class="topbar-left">
        <button class="menu-toggle-btn" id="menuToggleBtn">☰</button>
        <div class="topbar-title">
          <h1>@yield('header_title', 'Admin Dashboard')</h1>
          <p>@yield('header_subtitle', 'Monitor revenue, manage customer reservations, and oversee travel inventory.')</p>
        </div>
      </div>
      <div class="topbar-right">
        <a href="{{ route('home') }}" class="site-preview-btn" target="_blank">
          <span>↗</span> View Live Site
        </a>
      </div>
    </header>

    <!-- Main Content Body -->
    <main class="admin-content">
      @yield('content')
    </main>

  </div>

</div>

<!-- Admin Toast Notification -->
<div class="toast-notification" id="toast-notif">
  <div class="toast-icon">✓</div>
  <div class="toast-msg">Operation successful!</div>
</div>

<script>
  // Mobile sidebar toggle
  const toggleBtn = document.getElementById('menuToggleBtn');
  const sidebar = document.getElementById('adminSidebar');
  if (toggleBtn && sidebar) {
    toggleBtn.addEventListener('click', () => {
      sidebar.classList.toggle('open');
    });
  }

  // Toast Helper
  function showAdminToast(message, isError = false) {
    const toast = document.getElementById('toast-notif');
    if (!toast) return;
    const msgEl = toast.querySelector('.toast-msg');
    const iconEl = toast.querySelector('.toast-icon');
    if (msgEl) msgEl.textContent = message;
    if (iconEl) iconEl.textContent = isError ? '✕' : '✓';
    toast.style.borderColor = isError ? 'var(--admin-danger)' : 'rgba(16,185,129,0.4)';
    toast.classList.add('show');
    setTimeout(() => toast.classList.remove('show'), 3500);
  }
</script>
@yield('scripts')

</body>
</html>
