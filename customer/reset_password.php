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
  <title>Reset Password — Laundry Shop</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'Inter', sans-serif; min-height: 100vh; display: flex; flex-direction: column; align-items: center; justify-content: center; background: #0f172a; background-image: radial-gradient(ellipse at 50% 50%, rgba(16,185,129,0.1), transparent 50%); color: #f1f5f9; -webkit-font-smoothing: antialiased; padding: 40px 20px; overflow-y: auto; }
    .wrapper { width: 100%; max-width: 420px; padding: 20px; }
    .brand { text-align: center; margin-bottom: 28px; }
    .brand-icon { width: 60px; height: 60px; background: linear-gradient(135deg, #10b981, #059669); border-radius: 16px; display: inline-flex; align-items: center; justify-content: center; font-size: 26px; margin-bottom: 14px; box-shadow: 0 8px 30px rgba(16,185,129,0.3); }
    .brand h1 { font-size: 22px; font-weight: 800; color: #34d399; margin-bottom: 4px; }
    .brand p { color: #64748b; font-size: 13px; }
    .card { background: #1e293b; border: 1px solid rgba(148,163,184,0.1); border-radius: 16px; padding: 32px 28px; box-shadow: 0 20px 50px rgba(0,0,0,0.3); position: relative; }
    .card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px; background: linear-gradient(90deg, #10b981, #0ea5e9); border-radius: 16px 16px 0 0; }
    .form-group { margin-bottom: 18px; }
    .form-group label { display: block; font-size: 12px; font-weight: 600; color: #94a3b8; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.5px; }
    .form-input { width: 100%; padding: 11px 40px; background: #0f172a; border: 1px solid rgba(148,163,184,0.15); border-radius: 10px; color: #f1f5f9; font-size: 14px; font-family: inherit; outline: none; transition: all 0.3s; }
    .form-input:focus { border-color: #10b981; box-shadow: 0 0 0 3px rgba(16,185,129,0.15); }
    .form-input::placeholder { color: #475569; }
    .input-wrapper { position: relative; }
    .input-wrapper .input-icon {
      position: absolute;
      left: 14px;
      top: 50%;
      transform: translateY(-50%);
      font-size: 16px;
      pointer-events: none;
      transition: all 0.3s;
      opacity: 0.5;
    }
    .toggle-password {
      position: absolute;
      right: 14px;
      top: 50%;
      transform: translateY(-50%);
      cursor: pointer;
      font-size: 15px;
      opacity: 0.5;
      transition: all 0.3s;
      user-select: none;
      z-index: 10;
    }
    .toggle-password:hover {
      opacity: 1;
      color: #10b981;
    }
    .btn { width: 100%; padding: 12px; border: none; border-radius: 10px; font-size: 15px; font-weight: 600; font-family: inherit; cursor: pointer; transition: all 0.3s; }
    .btn-primary { background: linear-gradient(135deg, #10b981, #059669); color: #fff; box-shadow: 0 4px 15px rgba(16,185,129,0.35); }
    .btn-primary:hover { transform: translateY(-2px); }
    .footer { text-align: center; margin-top: 20px; font-size: 12.5px; color: #475569; }
    .footer a { color: #34d399; text-decoration: none; }
    .toast { position: fixed; top: 20px; right: 20px; padding: 12px 20px; border-radius: 10px; font-size: 13px; font-weight: 500; z-index: 9999; transform: translateX(calc(100% + 30px)); transition: transform 0.4s; }
    .toast.error { background: #dc2626; color: #fff; }
    .toast.success { background: #059669; color: #fff; }
    .toast.show { transform: translateX(0); }
    .success-box { display:none; text-align:center; padding:20px 0; }
    .success-box .icon { width:64px;height:64px;border-radius:50%;background:rgba(16,185,129,0.15);display:inline-flex;align-items:center;justify-content:center;font-size:28px;margin-bottom:16px; }
    .success-box h3 { color:#10b981; font-size:18px; margin-bottom:8px; }
    .success-box p { color:#94a3b8; font-size:13.5px; }

  </style>
</head>
<body>
  <div id="toast" class="toast"></div>
  <div class="wrapper">
    <div class="brand">
      <div class="brand-icon">🔐</div>
      <h1>Reset Password</h1>
      <p>Enter your reset token and new password</p>
    </div>
    <div class="card">
      <div id="form-section">
        <form id="form-reset">
          <div class="form-group">
            <label>Reset Token</label>
            <input type="text" id="reset-token" class="form-input" placeholder="Paste your reset token here" required>
          </div>
          <div class="form-group">
            <label>New Password</label>
            <div class="input-wrapper">
              <input type="password" id="new-password" class="form-input" placeholder="Enter new password" required>
              <span class="input-icon">🔒</span>
              <span class="toggle-password" data-target="#new-password">👁️</span>
            </div>
          </div>
          <div class="form-group">
            <label>Confirm New Password</label>
            <div class="input-wrapper">
              <input type="password" id="confirm-password" class="form-input" placeholder="Confirm new password" required>
              <span class="input-icon">🔒</span>
              <span class="toggle-password" data-target="#confirm-password">👁️</span>
            </div>
          </div>
          <button type="submit" class="btn btn-primary" id="btn-reset">Reset Password</button>
        </form>
      </div>
      <div class="success-box" id="success-section">
        <div class="icon">✅</div>
        <h3>Password Reset Successful!</h3>
        <p>You can now <a href="login.php" style="color:#38bdf8;">sign in</a> with your new password.</p>
      </div>
    </div>
    <div class="footer"><p>Remember your password? <a href="login.php">Sign in</a></p></div>
  </div>

  <script src="../assets/js/jquery-3.1.1.min.js"></script>
  <script>
  $('.toggle-password').on('click', function() {
    var target = $(this).data('target');
    var pwInput = $(target);
    var type = pwInput.attr('type') === 'password' ? 'text' : 'password';
    pwInput.attr('type', type);
    $(this).text(type === 'password' ? '👁️' : '🔒');
  });

  function showToast(msg, type) {
    var t = document.getElementById('toast');
    t.textContent = msg;
    t.className = 'toast ' + type + ' show';
    setTimeout(function(){ t.classList.remove('show'); }, 3500);
  }
  $('#form-reset').on('submit', function(e) {
    e.preventDefault();
    var pw = $('#new-password').val();
    if(pw !== $('#confirm-password').val()) { showToast('Passwords do not match!', 'error'); return; }
    if(pw.length < 6) { showToast('Password must be at least 6 characters.', 'error'); return; }
    var btn = $('#btn-reset');
    btn.prop('disabled', true).text('Resetting...');
    $.ajax({
      url: '../data/customer_reset_password.php',
      type: 'post',
      dataType: 'json',
      data: { token: $('#reset-token').val(), password: pw },
      success: function(data) {
        if(data.valid) {
          $('#form-section').hide();
          $('#success-section').show();
        } else {
          btn.prop('disabled', false).text('Reset Password');
          showToast(data.msg || 'Invalid or expired token.', 'error');
        }
      },
      error: function() {
        btn.prop('disabled', false).text('Reset Password');
        showToast('Connection error.', 'error');
      }
    });
  });
  </script>
</body>
</html>
