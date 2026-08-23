<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Staff Login | Koshi Province Tourism Hub</title>
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
      padding: 24px;
      font-family: 'Plus Jakarta Sans', sans-serif;
    }
    .login-container {
      width: 100%;
      max-width: 440px;
    }
    .login-card {
      background: rgba(17, 26, 46, 0.75);
      border: 1px solid rgba(255, 255, 255, 0.1);
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(20px);
      border-radius: 20px;
      padding: 40px 32px;
      box-shadow: 0 25px 60px rgba(0, 0, 0, 0.8), 0 0 30px rgba(232, 184, 75, 0.06);
    }
    .login-header {
      text-align: center;
      margin-bottom: 28px;
    }
    .login-logo {
      font-family: 'Outfit', sans-serif;
      font-size: 26px;
      font-weight: 800;
      color: #fff;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      margin-bottom: 8px;
    }
    .login-logo span {
      color: var(--primary);
    }
    .login-header p {
      font-size: 13px;
      color: var(--text-secondary);
      margin: 0;
    }
    .login-alert-error {
      background: rgba(239, 68, 68, 0.12);
      border: 1px solid rgba(239, 68, 68, 0.3);
      color: #fca5a5;
      padding: 12px 16px;
      border-radius: 10px;
      font-size: 13px;
      margin-bottom: 20px;
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .login-alert-info {
      background: rgba(59, 130, 246, 0.12);
      border: 1px solid rgba(59, 130, 246, 0.3);
      color: #93c5fd;
      padding: 12px 16px;
      border-radius: 10px;
      font-size: 13px;
      margin-bottom: 20px;
    }
    .login-field {
      margin-bottom: 20px;
    }
    .login-field label {
      display: block;
      font-size: 12px;
      text-transform: uppercase;
      letter-spacing: 0.06em;
      color: var(--text-secondary);
      font-weight: 700;
      margin-bottom: 8px;
    }
    .login-field input {
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
    .login-field input:focus {
      border-color: var(--primary);
      background: rgba(7, 11, 20, 0.95);
      box-shadow: 0 0 0 3px rgba(232, 184, 75, 0.15);
    }
    .login-btn {
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
    .login-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 24px rgba(232, 184, 75, 0.35);
    }
    .login-demo-box {
      margin-top: 24px;
      background: rgba(255, 255, 255, 0.04);
      border: 1px dashed rgba(255, 255, 255, 0.12);
      border-radius: 10px;
      padding: 12px 16px;
      font-size: 12px;
      color: var(--text-secondary);
      line-height: 1.6;
    }
    .login-demo-box strong {
      color: var(--primary);
    }
    .login-footer-links {
      text-align: center;
      margin-top: 24px;
    }
    .login-footer-links a {
      color: var(--text-secondary);
      text-decoration: none;
      font-size: 13px;
      transition: color 0.2s;
    }
    .login-footer-links a:hover {
      color: #fff;
    }
  </style>
</head>
<body>

<div class="login-container">
  
  <div class="login-card">
    <div class="login-header">
      <div class="login-logo">
        <span style="font-size:30px;">⛰️</span>
        <div><span>Koshi</span>Hub</div>
      </div>
      <p>Staff & Management Administrative Sign In</p>
    </div>

    @if($errors->has('error'))
      <div class="login-alert-error">
        <span>⚠️</span>
        <div>{{ $errors->first('error') }}</div>
      </div>
    @endif

    @if(session('info'))
      <div class="login-alert-info">
        {{ session('info') }}
      </div>
    @endif

    <form method="POST" action="{{ route('admin.login.submit') }}">
      @csrf

      <div class="login-field">
        <label for="admin_email">Email Address</label>
        <input type="email" id="admin_email" name="email" value="{{ old('email', 'admin@koshi.gov.np') }}" required placeholder="admin@koshi.gov.np" autocomplete="email" autofocus>
      </div>

      <div class="login-field">
        <label for="admin_password">Password</label>
        <input type="password" id="admin_password" name="password" required placeholder="••••••••" autocomplete="current-password">
      </div>

      <button type="submit" class="login-btn">
        Sign In to Dashboard →
      </button>
    </form>

    

    <div class="login-footer-links">
      <a href="{{ route('home') }}">← Return to Public Website</a>
    </div>
  </div>

</div>

</body>
</html>
