<?php
require_once('session.php');
require_once(__DIR__ . '/../database/Database.php');
$db = new Database();
$customer = $db->getRow("SELECT * FROM customers WHERE cust_id = ?", [$_SESSION['customer_logged']]);
$types = $db->getRows("SELECT * FROM laundry_type ORDER BY laun_type_desc ASC");
$myOrders = $db->getRows("SELECT l.*, lt.laun_type_desc, lt.laun_type_price FROM laundry l INNER JOIN laundry_type lt ON l.laun_type_id = lt.laun_type_id WHERE l.customer_name = ? ORDER BY l.laun_date_received DESC", [$customer['cust_fullname']]);
$db->Disconnect();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Place Order — Laundry Shop</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
    rel="stylesheet">
  <style>
    *,
    *::before,
    *::after {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Inter', sans-serif;
      background: #0f172a;
      color: #f1f5f9;
      -webkit-font-smoothing: antialiased;
      min-height: 100vh;
    }

    .topbar {
      background: #1e293b;
      border-bottom: 1px solid rgba(148, 163, 184, 0.1);
      padding: 14px 24px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      position: sticky;
      top: 0;
      z-index: 100;
    }

    .topbar .logo {
      font-size: 17px;
      font-weight: 700;
      color: #f1f5f9;
      text-decoration: none;
    }

    .topbar .logo span {
      color: #38bdf8;
    }

    .topbar-right {
      display: flex;
      align-items: center;
      gap: 16px;
    }

    .user-badge {
      background: rgba(14, 165, 233, 0.1);
      color: #38bdf8;
      padding: 6px 14px;
      border-radius: 20px;
      font-size: 12.5px;
      font-weight: 600;
    }

    .btn-logout {
      background: rgba(239, 68, 68, 0.1);
      color: #f87171;
      border: none;
      padding: 6px 14px;
      border-radius: 20px;
      font-size: 12.5px;
      font-weight: 600;
      cursor: pointer;
      font-family: inherit;
      transition: all 0.2s;
    }

    .btn-logout:hover {
      background: rgba(239, 68, 68, 0.2);
    }

    .container {
      max-width: 900px;
      margin: 0 auto;
      padding: 30px 20px;
    }

    .welcome-bar {
      margin-bottom: 28px;
    }

    .welcome-bar .greeting {
      font-size: 13px;
      color: #64748b;
      margin-bottom: 2px;
    }

    .welcome-bar .title {
      font-size: 26px;
      font-weight: 800;
      background: linear-gradient(135deg, #38bdf8, #818cf8);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }

    .grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 24px;
    }

    .card {
      background: #1e293b;
      border: 1px solid rgba(148, 163, 184, 0.1);
      border-radius: 14px;
      padding: 24px;
      position: relative;
      overflow: hidden;
    }

    .card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 3px;
    }

    .card.order-card::before {
      background: linear-gradient(90deg, #0ea5e9, #6366f1);
    }

    .card.history-card::before {
      background: linear-gradient(90deg, #8b5cf6, #ec4899);
    }

    .card-title {
      font-size: 16px;
      font-weight: 600;
      margin-bottom: 20px;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .card-title .icon {
      width: 32px;
      height: 32px;
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 15px;
    }

    .card-title .icon.blue {
      background: rgba(14, 165, 233, 0.15);
      color: #38bdf8;
    }

    .card-title .icon.purple {
      background: rgba(139, 92, 246, 0.15);
      color: #a78bfa;
    }

    .form-group {
      margin-bottom: 16px;
    }

    .form-group label {
      display: block;
      font-size: 12px;
      font-weight: 600;
      color: #94a3b8;
      margin-bottom: 6px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .form-input,
    .form-select {
      width: 100%;
      padding: 11px 14px;
      background: #0f172a;
      border: 1px solid rgba(148, 163, 184, 0.15);
      border-radius: 10px;
      color: #f1f5f9;
      font-size: 14px;
      font-family: inherit;
      outline: none;
      transition: all 0.3s;
    }

    .form-input:focus,
    .form-select:focus {
      border-color: #0ea5e9;
      box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.15);
    }

    .form-select option {
      background: #1e293b;
      color: #f1f5f9;
    }

    .service-cards {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 10px;
      margin-bottom: 16px;
    }

    .service-opt {
      background: #0f172a;
      border: 2px solid rgba(148, 163, 184, 0.1);
      border-radius: 10px;
      padding: 14px;
      text-align: center;
      cursor: pointer;
      transition: all 0.3s;
    }

    .service-opt:hover {
      border-color: rgba(14, 165, 233, 0.3);
    }

    .service-opt.selected {
      border-color: #0ea5e9;
      background: rgba(14, 165, 233, 0.08);
    }

    .service-opt .svc-icon {
      font-size: 24px;
      margin-bottom: 6px;
    }

    .service-opt .svc-name {
      font-size: 13.5px;
      font-weight: 600;
      color: #f1f5f9;
    }

    .service-opt .svc-price {
      font-size: 12px;
      color: #38bdf8;
      font-weight: 500;
    }

    .total-box {
      background: rgba(16, 185, 129, 0.08);
      border: 1px solid rgba(16, 185, 129, 0.2);
      border-radius: 10px;
      padding: 14px 16px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 18px;
    }

    .total-box .lbl {
      color: #94a3b8;
      font-size: 13px;
      font-weight: 500;
    }

    .total-box .val {
      color: #10b981;
      font-size: 22px;
      font-weight: 800;
    }

    .notice {
      background: rgba(245, 158, 11, 0.08);
      border: 1px solid rgba(245, 158, 11, 0.2);
      border-radius: 8px;
      padding: 10px 14px;
      font-size: 12.5px;
      color: #fbbf24;
      margin-bottom: 16px;
      line-height: 1.5;
    }

    .btn {
      width: 100%;
      padding: 12px;
      border: none;
      border-radius: 10px;
      font-size: 15px;
      font-weight: 600;
      font-family: inherit;
      cursor: pointer;
      transition: all 0.3s;
    }

    .btn-primary {
      background: linear-gradient(135deg, #0ea5e9, #0284c7);
      color: #fff;
      box-shadow: 0 4px 15px rgba(14, 165, 233, 0.35);
    }

    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(14, 165, 233, 0.45);
    }

    .btn-primary:disabled {
      opacity: 0.5;
      cursor: not-allowed;
      transform: none;
    }

    .order-list {
      max-height: 400px;
      overflow-y: auto;
    }

    .order-item {
      background: #0f172a;
      border: 1px solid rgba(148, 163, 184, 0.08);
      border-radius: 10px;
      padding: 14px;
      margin-bottom: 10px;
      position: relative;
      transition: all 0.3s;
    }

    .order-item:hover {
      border-color: rgba(148, 163, 184, 0.15);
    }

    .order-item .oi-top {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 6px;
    }

    .order-item .oi-type {
      font-size: 12px;
      font-weight: 600;
      background: rgba(245, 158, 11, 0.15);
      color: #f59e0b;
      padding: 2px 8px;
      border-radius: 12px;
    }

    .order-item .oi-status {
      font-size: 11px;
      font-weight: 600;
      padding: 2px 8px;
      border-radius: 12px;
    }

    .oi-status.pending {
      background: rgba(99, 102, 241, 0.15);
      color: #818cf8;
    }

    .oi-status.claimed {
      background: rgba(16, 185, 129, 0.15);
      color: #10b981;
    }

    .order-item .oi-name {
      font-size: 14px;
      font-weight: 600;
      color: #f1f5f9;
      margin-bottom: 4px;
    }

    .order-item .oi-details {
      font-size: 12.5px;
      color: #64748b;
    }

    .order-item .oi-bottom {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-top: 8px;
    }

    .order-item .oi-amount {
      font-size: 15px;
      font-weight: 700;
      color: #10b981;
    }

    .btn-cancel-order {
      background: rgba(239, 68, 68, 0.1);
      color: #f87171;
      border: 1px solid rgba(239, 68, 68, 0.2);
      padding: 5px 12px;
      border-radius: 8px;
      font-size: 11.5px;
      font-weight: 600;
      font-family: inherit;
      cursor: pointer;
      transition: all 0.2s;
      display: inline-flex;
      align-items: center;
      gap: 4px;
    }

    .btn-cancel-order:hover {
      background: rgba(239, 68, 68, 0.2);
      border-color: rgba(239, 68, 68, 0.4);
      color: #fca5a5;
    }

    .empty-state {
      text-align: center;
      padding: 30px 0;
      color: #475569;
    }

    .empty-state .empty-icon {
      font-size: 40px;
      margin-bottom: 10px;
      opacity: 0.5;
    }

    .empty-state p {
      font-size: 13.5px;
    }

    .toast {
      position: fixed;
      top: 20px;
      right: 20px;
      padding: 12px 20px;
      border-radius: 10px;
      font-size: 13px;
      font-weight: 500;
      z-index: 9999;
      transform: translateX(calc(100% + 30px));
      transition: transform 0.4s;
    }

    .toast.error {
      background: #dc2626;
      color: #fff;
    }

    .toast.success {
      background: #059669;
      color: #fff;
    }

    .toast.show {
      transform: translateX(0);
    }

    /* Cancel confirmation overlay */
    .confirm-overlay {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(0, 0, 0, 0.6);
      backdrop-filter: blur(4px);
      z-index: 10000;
      align-items: center;
      justify-content: center;
    }

    .confirm-overlay.active {
      display: flex;
    }

    .confirm-box {
      background: #1e293b;
      border: 1px solid rgba(148, 163, 184, 0.15);
      border-radius: 16px;
      padding: 28px;
      max-width: 380px;
      width: 90%;
      box-shadow: 0 25px 60px rgba(0, 0, 0, 0.5);
      text-align: center;
      animation: confirmIn 0.25s ease;
    }

    @keyframes confirmIn {
      from {
        opacity: 0;
        transform: scale(0.9) translateY(10px);
      }

      to {
        opacity: 1;
        transform: scale(1) translateY(0);
      }
    }

    .confirm-box .confirm-icon {
      font-size: 40px;
      margin-bottom: 12px;
    }

    .confirm-box h3 {
      font-size: 17px;
      font-weight: 700;
      color: #f1f5f9;
      margin-bottom: 8px;
    }

    .confirm-box p {
      font-size: 13.5px;
      color: #94a3b8;
      line-height: 1.5;
      margin-bottom: 20px;
    }

    .confirm-actions {
      display: flex;
      gap: 10px;
    }

    .confirm-actions button {
      flex: 1;
      padding: 10px;
      border: none;
      border-radius: 10px;
      font-size: 13.5px;
      font-weight: 600;
      font-family: inherit;
      cursor: pointer;
      transition: all 0.2s;
    }

    .btn-confirm-no {
      background: #334155;
      color: #94a3b8;
    }

    .btn-confirm-no:hover {
      background: #475569;
      color: #f1f5f9;
    }

    .btn-confirm-yes {
      background: linear-gradient(135deg, #ef4444, #dc2626);
      color: #fff;
      box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);
    }

    .btn-confirm-yes:hover {
      transform: translateY(-1px);
      box-shadow: 0 6px 20px rgba(239, 68, 68, 0.4);
    }

    @media (max-width: 768px) {
      .grid {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>

<body>
  <div id="toast" class="toast"></div>
  <div class="topbar">
    <a href="order.php" class="logo">🧺 <span>Hype</span> Laundry</a>
    <div class="topbar-right">
      <div class="user-badge">👋 <?= htmlspecialchars($customer['cust_fullname']); ?></div>
      <button class="btn-logout" onclick="window.location='logout.php'">Sign Out</button>
    </div>
  </div>

  <div class="container">
    <div class="welcome-bar">
      <div class="greeting">Welcome back, <?= htmlspecialchars(explode(' ', $customer['cust_fullname'])[0]); ?>!</div>
      <div class="title">Place Your Order</div>
    </div>

    <div class="grid">
      <!-- Order Form -->
      <div class="card order-card">
        <div class="card-title">
          <div class="icon blue">📦</div> Select Service
        </div>
        <form id="form-order">
          <div class="form-group">
            <label>Laundry Type</label>
            <div class="service-cards" id="service-cards">
              <?php foreach ($types as $i => $t): ?>
                <div class="service-opt <?= $i === 0 ? 'selected' : ''; ?>" data-id="<?= $t['laun_type_id']; ?>"
                  data-price="<?= $t['laun_type_price']; ?>" onclick="selectService(this)">
                  <div class="svc-icon"><?= strtolower($t['laun_type_desc']) === 'blanket' ? '🛏️' : '👕'; ?></div>
                  <div class="svc-name"><?= $t['laun_type_desc']; ?></div>
                  <div class="svc-price">₱<?= number_format($t['laun_type_price'], 2); ?>/kg</div>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
          <div class="form-group">
            <label>Weight (kg)</label>
            <input type="number" id="weight" class="form-input" placeholder="Enter weight in kg" min="1" step="any"
              required oninput="computeTotal()">
          </div>
          <div class="total-box">
            <span class="lbl">Estimated Total</span>
            <span class="val" id="total-display">₱ 0.00</span>
          </div>
          <div class="notice">⚠️ Your order will be sent directly to the admin. Payment is collected upon pickup.</div>
          <button type="submit" class="btn btn-primary" id="btn-order">Submit Order</button>
        </form>
      </div>

      <!-- Order History -->
      <div class="card history-card">
        <div class="card-title">
          <div class="icon purple">📋</div> My Orders
        </div>
        <div class="order-list">
          <?php if (empty($myOrders)): ?>
            <div class="empty-state">
              <div class="empty-icon">📭</div>
              <p>No orders yet. Place your first order!</p>
            </div>
          <?php else: ?>
            <?php foreach ($myOrders as $o):
              $amt = $o['laun_weight'] * $o['laun_type_price'];
              ?>
              <div class="order-item">
                <div class="oi-top">
                  <span class="oi-type"><?= $o['laun_type_desc']; ?></span>
                  <span
                    class="oi-status <?= $o['laun_claimed'] ? 'claimed' : 'pending'; ?>"><?= $o['laun_claimed'] ? '✓ Completed' : '⏳ Processing'; ?></span>
                </div>
                <div class="oi-details"><?= $o['laun_weight']; ?> kg · Priority #<?= $o['laun_priority']; ?> ·
                  <?= date('M j, Y', strtotime($o['laun_date_received'])); ?></div>
                <div class="oi-bottom">
                  <div class="oi-amount">₱ <?= number_format($amt, 2); ?></div>
                  <?php if (!$o['laun_claimed']): ?>
                    <button class="btn-cancel-order" data-id="<?= $o['laun_id']; ?>"
                      data-type="<?= htmlspecialchars($o['laun_type_desc']); ?>" data-weight="<?= $o['laun_weight']; ?>">
                      ✕ Cancel
                    </button>
                  <?php endif; ?>
                </div>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>

  <!-- Cancel Confirmation Overlay -->
  <div class="confirm-overlay" id="confirm-overlay">
    <div class="confirm-box">
      <div class="confirm-icon">⚠️</div>
      <h3>Cancel Order?</h3>
      <p id="confirm-text">Are you sure you want to cancel this order? This action cannot be undone.</p>
      <input type="hidden" id="cancel-order-id" value="">
      <div class="confirm-actions">
        <button class="btn-confirm-no" id="cancel-no">Keep Order</button>
        <button class="btn-confirm-yes" id="cancel-yes">Yes, Cancel</button>
      </div>
    </div>
  </div>

  <script src="../assets/js/jquery-3.1.1.min.js"></script>
  <script>
    var selectedTypeId = document.querySelector('.service-opt.selected') ? document.querySelector('.service-opt.selected').dataset.id : null;
    var selectedPrice = document.querySelector('.service-opt.selected') ? parseFloat(document.querySelector('.service-opt.selected').dataset.price) : 0;

    function selectService(el) {
      document.querySelectorAll('.service-opt').forEach(function (s) { s.classList.remove('selected'); });
      el.classList.add('selected');
      selectedTypeId = el.dataset.id;
      selectedPrice = parseFloat(el.dataset.price);
      computeTotal();
    }

    function computeTotal() {
      var w = parseFloat($('#weight').val()) || 0;
      var total = w * selectedPrice;
      $('#total-display').text('₱ ' + total.toFixed(2));
    }

    function showToast(msg, type) {
      var t = document.getElementById('toast');
      t.textContent = msg;
      t.className = 'toast ' + type + ' show';
      setTimeout(function () { t.classList.remove('show'); }, 3500);
    }

    $('#form-order').on('submit', function (e) {
      e.preventDefault();
      var weight = parseFloat($('#weight').val());
      if (!weight || weight <= 0) { showToast('Please enter a valid weight.', 'error'); return; }
      if (!selectedTypeId) { showToast('Please select a laundry type.', 'error'); return; }

      var btn = $('#btn-order');
      btn.prop('disabled', true).text('Submitting...');
      $.ajax({
        url: '../data/customer_order.php',
        type: 'post',
        dataType: 'json',
        data: { weight: weight, type_id: selectedTypeId },
        success: function (data) {
          if (data.valid) {
            // Redirect to confirmation page with order details
            window.location = 'confirmation.php?priority=' + data.priority + '&weight=' + data.weight + '&type_id=' + data.type_id;
          } else {
            btn.prop('disabled', false).text('Submit Order');
            showToast(data.msg || 'Failed to submit order.', 'error');
          }
        },
        error: function () {
          btn.prop('disabled', false).text('Submit Order');
          showToast('Connection error.', 'error');
        }
      });
    });

    // Cancel order
    $(document).on('click', '.btn-cancel-order', function () {
      var btn = $(this);
      var id = btn.data('id');
      var typeName = btn.data('type');
      var weight = btn.data('weight');
      $('#cancel-order-id').val(id);
      $('#confirm-text').html('Are you sure you want to cancel your <strong>' + typeName + '</strong> order (' + weight + ' kg)? This action cannot be undone.');
      $('#confirm-overlay').addClass('active');
    });

    $('#cancel-no').on('click', function () {
      $('#confirm-overlay').removeClass('active');
    });

    $('#confirm-overlay').on('click', function (e) {
      if (e.target === this) $('#confirm-overlay').removeClass('active');
    });

    $('#cancel-yes').on('click', function () {
      var id = $('#cancel-order-id').val();
      var btn = $(this);
      btn.prop('disabled', true).text('Cancelling...');
      $.ajax({
        url: '../data/customer_cancel_order.php',
        type: 'post',
        dataType: 'json',
        data: { laun_id: id },
        success: function (data) {
          $('#confirm-overlay').removeClass('active');
          btn.prop('disabled', false).text('Yes, Cancel');
          if (data.valid) {
            showToast(data.msg, 'success');
            setTimeout(function () { window.location.reload(); }, 1200);
          } else {
            showToast(data.msg || 'Failed to cancel order.', 'error');
          }
        },
        error: function () {
          $('#confirm-overlay').removeClass('active');
          btn.prop('disabled', false).text('Yes, Cancel');
          showToast('Connection error.', 'error');
        }
      });
    });
  </script>
</body>

</html>