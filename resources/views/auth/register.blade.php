<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Create Traveler Account | Koshi Province Tourism</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap">
  <link rel="stylesheet" href="{{ asset('css/variables.css') }}">
  <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
  <link rel="stylesheet" href="{{ asset('css/components.css') }}">
  <style>
    body {
      background: radial-gradient(circle at top right, #111e38 0%, #030712 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 32px 20px;
      font-family: 'Plus Jakarta Sans', sans-serif;
    }
    .auth-container {
      width: 100%;
      max-width: 480px;
    }
    .auth-card {
      background: rgba(17, 26, 46, 0.78);
      border: 1px solid rgba(255, 255, 255, 0.1);
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(20px);
      border-radius: 24px;
      padding: 40px 32px;
      box-shadow: 0 25px 60px rgba(0, 0, 0, 0.8), 0 0 30px rgba(232, 184, 75, 0.08);
    }
    .auth-header {
      text-align: center;
      margin-bottom: 28px;
    }
    .auth-logo {
      font-family: 'Outfit', sans-serif;
      font-size: 26px;
      font-weight: 800;
      color: #fff;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      margin-bottom: 8px;
      text-decoration: none;
    }
    .auth-logo span {
      color: var(--primary);
    }
    .auth-header p {
      font-size: 14px;
      color: var(--text-secondary);
      margin: 0;
    }
    .auth-alert-error {
      background: rgba(239, 68, 68, 0.12);
      border: 1px solid rgba(239, 68, 68, 0.3);
      color: #fca5a5;
      padding: 12px 16px;
      border-radius: 10px;
      font-size: 13px;
      margin-bottom: 20px;
    }
    .auth-alert-error ul {
      margin: 0;
      padding-left: 18px;
    }
    .auth-field {
      margin-bottom: 18px;
    }
    .auth-field label {
      display: block;
      font-size: 12px;
      text-transform: uppercase;
      letter-spacing: 0.06em;
      color: var(--text-secondary);
      font-weight: 700;
      margin-bottom: 8px;
    }
    .auth-field input {
      width: 100%;
      background: rgba(7, 11, 20, 0.7);
      border: 1px solid rgba(255, 255, 255, 0.1);
      border-radius: 10px;
      padding: 13px 16px;
      color: #fff;
      font-size: 14px;
      outline: none;
      transition: all 0.2s ease;
      font-family: inherit;
    }
    .auth-field input:focus {
      border-color: var(--primary);
      background: rgba(7, 11, 20, 0.95);
      box-shadow: 0 0 0 3px rgba(232, 184, 75, 0.15);
    }
    .auth-row {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 14px;
    }
    .auth-submit-btn {
      width: 100%;
      background: linear-gradient(135deg, var(--primary), var(--primary-dark));
      color: #030712;
      border: none;
      border-radius: 10px;
      padding: 14px;
      font-size: 15px;
      font-weight: 800;
      cursor: pointer;
      font-family: inherit;
      transition: all 0.25s ease;
      margin-top: 10px;
    }
    .auth-submit-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 24px rgba(232, 184, 75, 0.35);
    }
    .auth-footer {
      text-align: center;
      margin-top: 24px;
      font-size: 13px;
      color: var(--text-secondary);
    }
    .auth-footer a {
      color: var(--primary);
      text-decoration: none;
      font-weight: 700;
      transition: color 0.2s;
    }
    .auth-footer a:hover {
      color: #fff;
    }
    @media (max-width: 500px) {
      .auth-row { grid-template-columns: 1fr; gap: 0; }
    }
  </style>
</head>
<body>

<div class="auth-container">
  
  <div class="auth-card">
    <div class="auth-header">
      <a href="{{ route('home') }}" class="auth-logo">
        <span style="font-size:30px;">⛰️</span>
        <div><span>Koshi</span>Hub</div>
      </a>
      <p>Create your personal traveler account to book hotels, track journeys, and explore Eastern Nepal.</p>
    </div>

    @if ($errors->any())
      <div class="auth-alert-error">
        <ul>
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form method="POST" action="{{ route('register.submit') }}">
      @csrf

      <div class="auth-field">
        <label for="reg_name">Full Name</label>
        <input type="text" id="reg_name" name="name" value="{{ old('name') }}" required placeholder="e.g. Ramesh Karki" autocomplete="name" autofocus>
      </div>

      <div class="auth-field">
        <label for="reg_email">Email Address</label>
        <input type="email" id="reg_email" name="email" value="{{ old('email') }}" required placeholder="ramesh@example.com" autocomplete="email">
      </div>

      <div class="auth-field">
        <label for="reg_phone">Phone Number (Optional)</label>
        <input type="text" id="reg_phone" name="phone" value="{{ old('phone') }}" placeholder="+977 98XXXXXXXX" autocomplete="tel">
      </div>

      <div class="auth-row">
        <div class="auth-field">
          <label for="reg_password">Password</label>
          <input type="password" id="reg_password" name="password" required placeholder="••••••••" autocomplete="new-password">
        </div>

        <div class="auth-field">
          <label for="reg_password_confirmation">Confirm Password</label>
          <input type="password" id="reg_password_confirmation" name="password_confirmation" required placeholder="••••••••" autocomplete="new-password">
        </div>
      </div>

      <button type="submit" class="auth-submit-btn">
        Create Traveler Account →
      </button>
    </form>

    <div class="auth-footer">
      Already have an account? <a href="{{ route('login') }}">Sign In here</a>
      <div style="margin-top: 14px;">
        <a href="{{ route('home') }}" style="color:var(--text-muted);font-weight:400;font-size:12px;">← Back to Home Page</a>
      </div>
    </div>
  </div>

</div>

</body>
</html>
