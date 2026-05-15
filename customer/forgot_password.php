<?php
require_once(__DIR__ . '/../database/Database.php');
$db = new Database();
$db->Disconnect();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Forgot Password — Laundry Shop</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'Inter', sans-serif; min-height: 100vh; display: flex; flex-direction: column; align-items: center; justify-content: center; background: #0f172a; background-image: radial-gradient(ellipse at 30% 60%, rgba(245,158,11,0.1), transparent 50%); color: #f1f5f9; -webkit-font-smoothing: antialiased; padding: 40px 20px; overflow-y: auto; }
    .wrapper { width: 100%; max-width: 420px; padding: 20px; }
    .brand { text-align: center; margin-bottom: 28px; }
    .brand-icon { width: 60px; height: 60px; background: linear-gradient(135deg, #f59e0b, #d97706); border-radius: 16px; display: inline-flex; align-items: center; justify-content: center; font-size: 26px; margin-bottom: 14px; box-shadow: 0 8px 30px rgba(245,158,11,0.3); }
    .brand h1 { font-size: 22px; font-weight: 800; color: #fbbf24; margin-bottom: 4px; }
    .brand p { color: #64748b; font-size: 13px; }
    .card { background: #1e293b; border: 1px solid rgba(148,163,184,0.1); border-radius: 16px; padding: 32px 28px; box-shadow: 0 20px 50px rgba(0,0,0,0.3); position: relative; }
    .card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px; background: linear-gradient(90deg, #f59e0b, #ef4444); border-radius: 16px 16px 0 0; }
    .form-group { margin-bottom: 18px; }
    .form-group label { display: block; font-size: 12px; font-weight: 600; color: #94a3b8; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.5px; }
    .form-input { width: 100%; padding: 11px 14px; background: #0f172a; border: 1px solid rgba(148,163,184,0.15); border-radius: 10px; color: #f1f5f9; font-size: 14px; font-family: inherit; outline: none; transition: all 0.3s; }
    .form-input:focus { border-color: #f59e0b; box-shadow: 0 0 0 3px rgba(245,158,11,0.15); }
    .form-input::placeholder { color: #475569; }
    .btn { width: 100%; padding: 12px; border: none; border-radius: 10px; font-size: 15px; font-weight: 600; font-family: inherit; cursor: pointer; transition: all 0.3s; }
    .btn-primary { background: linear-gradient(135deg, #f59e0b, #d97706); color: #fff; box-shadow: 0 4px 15px rgba(245,158,11,0.35); }
    .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(245,158,11,0.45); }
    .footer { text-align: center; margin-top: 20px; font-size: 12.5px; color: #475569; }
    .footer a { color: #fbbf24; text-decoration: none; }
    .success-box { display: none; text-align: center; padding: 20px 0; }
    .success-box .icon { width: 64px; height: 64px; border-radius: 50%; background: rgba(16,185,129,0.15); display: inline-flex; align-items: center; justify-content: center; font-size: 28px; margin-bottom: 16px; }
    .success-box h3 { color: #10b981; font-size: 18px; margin-bottom: 8px; }
    .success-box p { color: #94a3b8; font-size: 13.5px; line-height: 1.5; }
    .toast { position: fixed; top: 20px; right: 20px; padding: 12px 20px; border-radius: 10px; font-size: 13px; font-weight: 500; z-index: 9999; transform: translateX(calc(100% + 30px)); transition: transform 0.4s; }
    .toast.error { background: #dc2626; color: #fff; }
    .toast.show { transform: translateX(0); }
    .info-text { font-size: 13px; color: #64748b; line-height: 1.6; margin-bottom: 20px; }
  </style>
</head>
<body>
  <div id="toast" class="toast"></div>
  <div class="wrapper">
    <div class="brand">
      <div class="brand-icon">🔑</div>
      <h1>Forgot Password</h1>
      <p>We'll help you reset it</p>
    </div>
    <div class="card">
      <div id="form-section">
        <p class="info-text">Enter the email address linked to your account. We'll generate a reset token you can use to create a new password.</p>
        <form id="form-forgot">
          <div class="form-group">
            <label>Email Address</label>
            <input type="email" id="forgot-email" class="form-input" placeholder="you@example.com" required>
          </div>
          <button type="submit" class="btn btn-primary" id="btn-forgot">Send Reset Link</button>
        </form>
      </div>
      <div class="success-box" id="success-section">
        <div class="icon">✅</div>
        <h3>Reset Token Generated!</h3>
        <p>Your reset token is:</p>
        <p style="background:#0f172a; padding:10px; border-radius:8px; margin:12px 0; font-family:monospace; font-size:13px; color:#fbbf24; word-break:break-all;" id="token-display"></p>
        <p>Use this token on the <a href="reset_password.php" style="color:#38bdf8;">Reset Password</a> page.</p>
      </div>
    </div>
    <div class="footer"><p>Remember your password? <a href="login.php">Sign in</a></p></div>
  </div>

  <script src="../assets/js/jquery-3.1.1.min.js"></script>
  <script>
  function showToast(msg, type) {
    var t = document.getElementById('toast');
    t.textContent = msg;
    t.className = 'toast ' + type + ' show';
    setTimeout(function(){ t.classList.remove('show'); }, 3500);
  }
  $('#form-forgot').on('submit', function(e) {
    e.preventDefault();
    var btn = $('#btn-forgot');
    btn.prop('disabled', true).text('Processing...');
    $.ajax({
      url: '../data/customer_forgot_password.php',
      type: 'post',
      dataType: 'json',
      data: { email: $('#forgot-email').val() },
      success: function(data) {
        if(data.valid) {
          $('#form-section').hide();
          $('#token-display').text(data.token);
          $('#success-section').show();
        } else {
          btn.prop('disabled', false).text('Send Reset Link');
          showToast(data.msg || 'Email not found.', 'error');
        }
      },
      error: function() {
        btn.prop('disabled', false).text('Send Reset Link');
        showToast('Connection error.', 'error');
      }
    });
  });
  </script>
</body>
</html>
