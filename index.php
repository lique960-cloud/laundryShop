<?php
require_once('database/Database.php');
$db = new Database();

if (isset($_SESSION['user_logged'])) {
  header('location: home.php');
  exit;
} elseif (isset($_SESSION['customer_logged'])) {
  header('location: customer/order.php');
  exit;
}

$remUser = isset($_COOKIE['remember_user']) ? $_COOKIE['remember_user'] : '';
$remPass = isset($_COOKIE['remember_pass']) ? $_COOKIE['remember_pass'] : '';
$remCheck = isset($_COOKIE['remember_user']) ? 'checked' : '';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login — HypeLaundry</title>
  <meta name="description" content="Laundry Shop Management System - Secure Login Portal">
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

    /* ============ LEFT: LOGIN PANEL ============ */
    .login-panel {
      flex: 0 0 440px;
      padding: 60px 50px;
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
      margin-bottom: 40px;
    }

    .form-header-logo img {
      width: 40px;
      height: 40px;
      object-fit: contain;
    }

    .form-header-logo span {
      font-size: 22px;
      font-weight: 700;
      letter-spacing: -0.5px;
      color: #f8fafc;
    }

    .login-header {
      margin-bottom: 32px;
    }

    .login-header h2 {
      font-size: 28px;
      font-weight: 800;
      margin-bottom: 8px;
      color: #e2e8f0;
    }

    .login-header p {
      font-size: 15px;
      color: #cbd5e1;
    }

    .form-group {
      margin-bottom: 20px;
    }

    .form-group label {
      display: block;
      font-size: 13px;
      font-weight: 600;
      color: #cbd5e1;
      margin-bottom: 8px;
    }

    .input-wrapper {
      position: relative;
    }

    .form-input {
      width: 100%;
      padding: 14px 16px;
      background: #111827;
      border: 1px solid rgba(148, 163, 184, 0.25);
      border-radius: 12px;
      font-size: 15px;
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

    .toggle-password {
      position: absolute;
      right: 14px;
      top: 50%;
      transform: translateY(-50%);
      cursor: pointer;
      font-size: 16px;
      color: #94a3b8;
    }

    .remember-row {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-top: -8px;
      margin-bottom: 24px;
    }

    .remember-row label {
      display: flex;
      align-items: center;
      gap: 8px;
      font-size: 14px;
      color: #cbd5e1;
      cursor: pointer;
    }

    .remember-row a {
      font-size: 14px;
      color: #818cf8;
      text-decoration: none;
      font-weight: 600;
    }

    .btn-login {
      width: 100%;
      padding: 16px;
      background: #6366f1;
      color: #ffffff;
      border: none;
      border-radius: 14px;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.2s;
      margin-bottom: 20px;
    }

    .btn-login:hover {
      background: #818cf8;
      transform: translateY(-1px);
    }

    .create-account-link {
      text-align: center;
      font-size: 14px;
      color: #cbd5e1;
    }

    .create-account-link a {
      color: #ffffff;
      font-weight: 700;
      text-decoration: none;
    }

    /* ============ RIGHT: BRANDING PANEL ============ */
    .branding-panel {
      flex: 1;
      position: relative;
      background-image: linear-gradient(135deg, rgba(15, 23, 42, 0.6), rgba(30, 41, 59, 0.6)), url('dist/img/wavy_bg.png');
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

    .branding-desc {
      display: flex;
      gap: 20px;
    }

    .branding-desc .line {
      width: 2px;
      background: #f8fafc;
      flex-shrink: 0;
    }

    .branding-desc p {
      font-size: 18px;
      line-height: 1.6;
      font-weight: 500;
      color: #f1f5f9;
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
    .toast-error {
      position: fixed;
      top: 24px;
      right: 24px;
      background: #0f172a;
      color: #fff;
      padding: 16px 24px;
      border-radius: 12px;
      font-size: 14px;
      font-weight: 500;
      transform: translateX(calc(100% + 40px));
      transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
      z-index: 9999;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    }

    .toast-error.show {
      transform: translateX(0);
    }

    @media (max-width: 900px) {
      .app-card {
        flex-direction: column-reverse;
        height: auto;
      }
      .branding-panel {
        height: 300px;
        padding: 40px;
      }
      .login-panel {
        flex: 1;
        width: 100%;
        padding: 40px 30px;
      }
      .branding-content h1 {
        font-size: 30px;
      }
      .btn-top-right {
        display: none;
      }
    }
  </style>
</head>

<body>
  <div id="toast-error" class="toast-error">
    Invalid credentials. Please try again.
  </div>

  <div class="app-card">
    <!-- LEFT: LOGIN PANEL -->
    <div class="login-panel">
      <div class="form-header-logo">
        <img src="dist/img/logobrand.png" alt="Logo">
        <span>HypeLaundry</span>
      </div>

      <div class="login-header">
        <h2>Welcome Back!</h2>
        <p>Login to your account</p>
      </div>

      <form method="post" id="form-login">
        <div class="form-group">
          <label for="credential">Email</label>
          <input autofocus type="text" id="credential" name="credential" class="form-input" placeholder="Enter your email" value="<?php echo $remUser; ?>" required>
        </div>

        <div class="form-group">
          <label for="password">Password</label>
          <div class="input-wrapper">
            <input type="password" id="password" name="pw" class="form-input" placeholder="Enter your password" value="<?php echo $remPass; ?>" required>
            <span class="toggle-password" id="toggle-password">👁️</span>
          </div>
        </div>

        <div class="remember-row">
          <label>
            <input type="checkbox" name="remember_me" id="remember_me" <?php echo $remCheck; ?>>
            Remember me
          </label>
          <a href="customer/forgot_password.php">Forgot password?</a>
        </div>

        <button type="submit" name="commit" class="btn-login" id="btn-login">Sign In</button>
      </form>

      <div class="create-account-link">
        <p>New here? <a href="customer/register.php">Create Account</a></p>
      </div>
    </div>

    <!-- RIGHT: BRANDING PANEL -->
    <div class="branding-panel">
      <a href="#" class="btn-top-right">Get to know us ↗</a>
      
      <div class="branding-content">
        <img src="dist/img/logobrand.png" alt="Logo" class="logo-large">
        <h1>Join HypeLaundry now and experience the best laundry service.</h1>
      </div>
    </div>

  <script src="assets/js/jquery-3.1.1.min.js"></script>
  <script src="assets/js/bootstrap.min.js"></script>
  <script type="text/javascript">
    $(document).on('submit', '#form-login', function (event) {
      event.preventDefault();
      var un = $('#credential').val();
      var pw = $('#password').val();
      var rm = $('#remember_me').is(':checked') ? 1 : 0;

      $('#btn-login').prop('disabled', true).text('Signing in...');

      $.ajax({
        url: 'data/unified_login.php',
        type: 'post',
        dataType: 'json',
        data: {
          credential: un,
          pw: pw,
          remember_me: rm
        },
        success: function (data) {
          if (data.valid) {
            window.location = data.url;
          } else {
            showToast(data.msg || "Invalid credentials.");
            $('#btn-login').prop('disabled', false).text('Sign In');
          }
        },
        error: function () {
          showToast("Connection error. Please try again.");
          $('#btn-login').prop('disabled', false).text('Sign In');
        }
      });
    });

    function showToast(msg) {
      const toast = $('#toast-error');
      toast.text(msg).addClass('show');
      setTimeout(() => {
        toast.removeClass('show');
      }, 4000);
    }

    $('#toggle-password').on('click', function () {
      const input = $('#password');
      const type = input.attr('type') === 'password' ? 'text' : 'password';
      input.attr('type', type);
      $(this).text(type === 'password' ? '👁️' : '🔒');
    });
  </script>
</body>

</html>