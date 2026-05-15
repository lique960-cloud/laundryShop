<?php
require_once(__DIR__ . '/../database/Database.php');
$db = new Database();
if (isset($_SESSION['customer_logged'])) {
  header('location: order.php');
  exit;
}
$db->Disconnect();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Create Account — HypeLaundry</title>
  <meta name="description" content="Create your Laundry Shop customer account for on-demand pickup & delivery">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    *,
    *::before,
    *::after {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Inter', -apple-system, sans-serif;
      min-height: 100vh;
      display: flex;
      align-items: flex-start;
      justify-content: center;
      background: linear-gradient(135deg, #0f172a, #111827);
      color: #e2e8f0;
      -webkit-font-smoothing: antialiased;
      padding: 0;
      overflow-y: auto;
    }

    .app-card {
      width: 100vw;
      min-height: 100vh;
      background: #0f172a;
      border-radius: 0;
      display: flex;
      overflow: visible;
      position: relative;
      box-shadow: 0 40px 120px rgba(0, 0, 0, 0.35);
    }

    /* ============ LEFT: REGISTER PANEL ============ */
    .register-panel {
      flex: 0 0 500px;
      padding: 50px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      min-height: 100vh;
      background: rgba(15, 23, 42, 0.95);
      border-right: 1px solid rgba(148, 163, 184, 0.12);
    }

    .form-header-logo {
      display: flex;
      align-items: center;
      gap: 12px;
      margin-bottom: 30px;
    }

    .form-header-logo img {
      width: 36px;
      height: 36px;
      object-fit: contain;
    }

    .form-header-logo span {
      font-size: 20px;
      font-weight: 700;
      letter-spacing: -0.5px;
      color: #f8fafc;
    }

    .register-header {
      margin-bottom: 24px;
    }

    .register-header h2 {
      font-size: 26px;
      font-weight: 800;
      margin-bottom: 6px;
      color: #e2e8f0;
    }

    .register-header p {
      font-size: 14px;
      color: #cbd5e1;
    }

    .form-group {
      margin-bottom: 16px;
    }

    .form-group label {
      display: block;
      font-size: 12px;
      font-weight: 700;
      color: #cbd5e1;
      margin-bottom: 6px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .input-wrapper {
      position: relative;
    }

    .form-input {
      width: 100%;
      padding: 12px 14px;
      background: #111827;
      border: 1px solid rgba(148, 163, 184, 0.25);
      border-radius: 10px;
      font-size: 14px;
      color: #e2e8f0;
      outline: none;
      transition: all 0.2s;
    }

    .form-input::placeholder {
      color: #94a3b8;
    }

    .form-input:focus {
      border-color: #6366f1;
      box-shadow: 0 0 0 1px rgba(99, 102, 241, 0.4);
    }

    textarea.form-input {
      resize: none;
      min-height: 60px;
    }

    .form-row {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 12px;
    }

    .toggle-password {
      position: absolute;
      right: 12px;
      top: 50%;
      transform: translateY(-50%);
      cursor: pointer;
      font-size: 15px;
      color: #94a3b8;
    }

    .pw-hint {
      font-size: 11px;
      color: #94a3b8;
      margin-top: -8px;
      margin-bottom: 16px;
    }

    .btn-register {
      width: 100%;
      padding: 14px;
      background: #6366f1;
      color: #ffffff;
      border: none;
      border-radius: 12px;
      font-size: 15px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.2s;
      margin-top: 10px;
      margin-bottom: 20px;
    }

    .btn-register:hover {
      background: #818cf8;
      transform: translateY(-1px);
    }

    .login-footer {
      text-align: center;
      font-size: 14px;
      color: #cbd5e1;
    }

    .login-footer a {
      color: #ffffff;
      font-weight: 700;
      text-decoration: none;
    }

    /* ============ RIGHT: BRANDING PANEL ============ */
    .branding-panel {
      flex: 1;
      position: relative;
      background-image: linear-gradient(135deg, rgba(15, 23, 42, 0.6), rgba(30, 41, 59, 0.6)), url('../dist/img/wavy_bg.png');
      background-blend-mode: multiply;
      background-size: cover;
      background-position: center;
      padding: 60px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      color: #f8fafc;
    }



    .branding-content {
      position: relative;
      z-index: 1;
      max-width: 500px;
      margin: 0 auto;
      text-align: center;
    }

    .branding-content .logo-large {
      width: 200px;
      height: 200px;
      margin: 0 auto 24px;
      object-fit: contain;
      display: block;
    }

    .branding-content h1 {
      font-size: 42px;
      font-weight: 800;
      line-height: 1.1;
      margin-bottom: 24px;
      letter-spacing: -1.5px;
    }

    .btn-top-right {
      position: absolute;
      top: 40px;
      right: 40px;
      padding: 10px 20px;
      background: rgba(255, 255, 255, 0.8);
      border: 1px solid #e2e8f0;
      border-radius: 12px;
      font-size: 14px;
      font-weight: 600;
      color: #0f172a;
      text-decoration: none;
      display: flex;
      align-items: center;
      gap: 8px;
      transition: all 0.2s;
    }

    .btn-top-right:hover {
      background: #ffffff;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    /* Toast */
    .toast {
      position: fixed;
      top: 24px;
      right: 24px;
      padding: 16px 24px;
      border-radius: 12px;
      font-size: 14px;
      font-weight: 500;
      transform: translateX(calc(100% + 40px));
      transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
      z-index: 9999;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    }

    .toast.error { background: #0f172a; color: #fff; }
    .toast.success { background: #059669; color: #fff; }
    .toast.show { transform: translateX(0); }

    @media (max-width: 900px) {
      .app-card {
        flex-direction: column;
        height: auto;
      }
      .branding-panel {
        height: 250px;
        padding: 30px;
      }
      .register-panel {
        flex: 1;
        width: 100%;
        padding: 40px 25px;
      }
      .branding-content h1 {
        font-size: 24px;
      }
    }
  </style>
</head>

<body>
  <div id="toast" class="toast">
    <span id="toast-msg"></span>
  </div>

  <div class="app-card">
    <!-- LEFT: REGISTER PANEL -->
    <div class="register-panel">
      <div class="form-header-logo">
        <img src="../dist/img/logobrand.png" alt="Logo">
        <span>HypeLaundry</span>
      </div>

      <div class="register-header">
        <h2>Create Account</h2>
        <p>Join HypeLaundry for the best experience</p>
      </div>

      <form id="form-register">
        <div class="form-group">
          <label for="fullname">Complete Name</label>
          <input autofocus type="text" id="fullname" name="fullname" class="form-input" placeholder="Juan Dela Cruz" required maxlength="50">
        </div>

        <div class="form-group">
          <label for="reg-email">Email Address</label>
          <input type="email" id="reg-email" name="email" class="form-input" placeholder="you@example.com" required maxlength="50">
        </div>

        <div class="form-group">
          <label for="mobile">Contact Number</label>
          <input type="text" id="mobile" name="mobile" class="form-input" placeholder="09XX XXX XXXX" required maxlength="11">
        </div>

        <div class="form-group">
          <label for="address">Complete Address</label>
          <textarea id="address" name="address" class="form-input" placeholder="House/Unit No., Street, Barangay, City..." required maxlength="70"></textarea>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label for="reg-password">Password</label>
            <div class="input-wrapper">
              <input type="password" id="reg-password" name="password" class="form-input" placeholder="••••••••" required>
              <span class="toggle-password" data-target="#reg-password">👁️</span>
            </div>
          </div>
          <div class="form-group">
            <label for="reg-password2">Confirm</label>
            <div class="input-wrapper">
              <input type="password" id="reg-password2" name="password2" class="form-input" placeholder="••••••••" required>
              <span class="toggle-password" data-target="#reg-password2">👁️</span>
            </div>
          </div>
        </div>
        <p class="pw-hint">At least 6 characters.</p>

        <button type="submit" class="btn-register" id="btn-register">Create Account</button>
      </form>

      <div class="login-footer">
        <p>Already have an account? <a href="../index.php">Sign in</a></p>
      </div>
    </div>

    <!-- RIGHT: BRANDING PANEL -->
    <div class="branding-panel">
      <a href="#" class="btn-top-right">Get to know us ↗</a>
      <div class="branding-content">
        <img src="../dist/img/logobrand.png" alt="Logo" class="logo-large">
        <h1>Join HypeLaundry now and experience the best laundry service.</h1>
      </div>
    </div>
  </div>

  <script src="../assets/js/jquery-3.1.1.min.js"></script>
  <script>
    $('.toggle-password').on('click', function () {
      var target = $(this).data('target');
      var pwInput = $(target);
      var type = pwInput.attr('type') === 'password' ? 'text' : 'password';
      pwInput.attr('type', type);
      $(this).text(type === 'password' ? '👁️' : '🔒');
    });

    function showToast(msg, type) {
      var t = $('#toast');
      $('#toast-msg').text(msg);
      t.removeClass('error success').addClass(type).addClass('show');
      setTimeout(function () { t.removeClass('show'); }, 3500);
    }

    $('#form-register').on('submit', function (e) {
      e.preventDefault();
      var fullname = $('#fullname').val().trim();
      var email = $('#reg-email').val().trim();
      var mobile = $('#mobile').val().trim();
      var address = $('#address').val().trim();
      var pw = $('#reg-password').val();
      var pw2 = $('#reg-password2').val();

      if (pw !== pw2) { showToast('Passwords do not match!', 'error'); return; }
      if (pw.length < 6) { showToast('Password must be at least 6 characters.', 'error'); return; }

      var btn = $('#btn-register');
      btn.prop('disabled', true).text('Creating account...');

      $.ajax({
        url: '../data/customer_register.php',
        type: 'post',
        dataType: 'json',
        data: {
          fullname: fullname,
          email: email,
          mobile: mobile,
          address: address,
          password: pw
        },
        success: function (data) {
          if (data.valid) {
            showToast('Account created! Redirecting...', 'success');
            setTimeout(function () { window.location = '../index.php'; }, 2000);
          } else {
            btn.prop('disabled', false).text('Create Account');
            showToast(data.msg || 'Registration failed.', 'error');
          }
        },
        error: function () {
          btn.prop('disabled', false).text('Create Account');
          showToast('Connection error.', 'error');
        }
      });
    });
  </script>
</body>

</html>