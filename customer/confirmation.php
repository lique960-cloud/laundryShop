<?php
require_once('session.php');
require_once(__DIR__ . '/../database/Database.php');
$db = new Database();

// Get order details from session or URL parameter
$priority = isset($_GET['priority']) ? intval($_GET['priority']) : 0;
$weight = isset($_GET['weight']) ? floatval($_GET['weight']) : 0;
$type_id = isset($_GET['type_id']) ? intval($_GET['type_id']) : 0;

if($priority && $weight && $type_id) {
    $type = $db->getRow("SELECT * FROM laundry_type WHERE laun_type_id = ?", [$type_id]);
    $amount = $weight * $type['laun_type_price'];
} else {
    // Redirect back if no order data
    header('location: order.php');
    exit;
}

$db->Disconnect();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Order Confirmation — Laundry Shop</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'Inter', sans-serif; min-height: 100vh; display: flex; align-items: center; justify-content: center; background: #0f172a; background-image: radial-gradient(ellipse at 20% 50%, rgba(16,185,129,0.15), transparent 50%), radial-gradient(ellipse at 80% 20%, rgba(5,150,105,0.1), transparent 50%); color: #f1f5f9; -webkit-font-smoothing: antialiased; padding: 20px 0; }
    .wrapper { width: 100%; max-width: 480px; padding: 20px; }
    .brand { text-align: center; margin-bottom: 32px; }
    .brand-icon { width: 64px; height: 64px; background: linear-gradient(135deg, #10b981, #059669); border-radius: 16px; display: inline-flex; align-items: center; justify-content: center; font-size: 28px; margin-bottom: 16px; box-shadow: 0 8px 30px rgba(16,185,129,0.35); }
    .brand h1 { font-size: 24px; font-weight: 800; color: #34d399; margin-bottom: 4px; }
    .brand p { color: #64748b; font-size: 13px; }
    .card { background: #1e293b; border: 1px solid rgba(148,163,184,0.1); border-radius: 16px; padding: 32px 28px; box-shadow: 0 20px 50px rgba(0,0,0,0.3); position: relative; overflow: hidden; text-align: center; }
    .card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px; background: linear-gradient(90deg, #10b981, #0ea5e9); border-radius: 16px 16px 0 0; }
    .success-icon { width: 80px; height: 80px; border-radius: 50%; background: rgba(16,185,129,0.15); display: inline-flex; align-items: center; justify-content: center; font-size: 32px; margin-bottom: 20px; }
    .card-title { font-size: 20px; font-weight: 700; color: #10b981; margin-bottom: 8px; }
    .card-subtitle { color: #94a3b8; font-size: 14px; margin-bottom: 24px; }
    .order-details { background: #0f172a; border: 1px solid rgba(148,163,184,0.08); border-radius: 12px; padding: 20px; margin-bottom: 24px; }
    .detail-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; }
    .detail-row:last-child { margin-bottom: 0; }
    .detail-label { color: #94a3b8; font-size: 13px; font-weight: 500; }
    .detail-value { color: #f1f5f9; font-size: 14px; font-weight: 600; }
    .priority-badge { background: rgba(99,102,241,0.15); color: #818cf8; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; }
    .amount-due { background: rgba(16,185,129,0.08); border: 1px solid rgba(16,185,129,0.2); border-radius: 10px; padding: 16px; margin-bottom: 24px; }
    .amount-label { color: #94a3b8; font-size: 13px; margin-bottom: 4px; }
    .amount-value { color: #10b981; font-size: 28px; font-weight: 800; }
    .notice { background: rgba(245,158,11,0.08); border: 1px solid rgba(245,158,11,0.2); border-radius: 8px; padding: 12px 16px; font-size: 12.5px; color: #fbbf24; margin-bottom: 20px; line-height: 1.5; }
    .btn { width: 100%; padding: 14px; border: none; border-radius: 10px; font-size: 15px; font-weight: 600; font-family: inherit; cursor: pointer; transition: all 0.3s; }
    .btn-primary { background: linear-gradient(135deg, #10b981, #059669); color: #fff; box-shadow: 0 4px 15px rgba(16,185,129,0.35); }
    .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(16,185,129,0.45); }
    .footer { text-align: center; margin-top: 20px; font-size: 12.5px; color: #475569; }
    .footer a { color: #34d399; text-decoration: none; }
    @media (max-width: 480px) { .wrapper { padding: 14px; } .card { padding: 28px 20px; } }
  </style>
</head>
<body>
  <div class="wrapper">
    <div class="brand">
      <div class="brand-icon">✅</div>
      <h1>Order Confirmed!</h1>
      <p>Your laundry order has been submitted</p>
    </div>
    <div class="card">
      <div class="success-icon">🧺</div>
      <div class="card-title">Order Submitted Successfully</div>
      <div class="card-subtitle">Your order is now in the queue for processing</div>

      <div class="order-details">
        <div class="detail-row">
          <span class="detail-label">Priority Number</span>
          <span class="detail-value priority-badge">#<?= $priority; ?></span>
        </div>
        <div class="detail-row">
          <span class="detail-label">Laundry Type</span>
          <span class="detail-value"><?= htmlspecialchars($type['laun_type_desc']); ?></span>
        </div>
        <div class="detail-row">
          <span class="detail-label">Weight</span>
          <span class="detail-value"><?= number_format($weight, 2); ?> kg</span>
        </div>
        <div class="detail-row">
          <span class="detail-label">Date Submitted</span>
          <span class="detail-value"><?= date('M j, Y g:i A'); ?></span>
        </div>
      </div>

      <div class="amount-due">
        <div class="amount-label">Amount Due (Pay at Pickup)</div>
        <div class="amount-value">₱ <?= number_format($amount, 2); ?></div>
      </div>

      <div class="notice">💰 Payment will be collected when you pick up your laundry. Please bring this priority number with you.</div>

      <a href="order.php" class="btn btn-primary">View My Orders</a>
    </div>
    <div class="footer"><p>&copy; 2026 <a href="#">Laundry Shop</a>. All rights reserved.</p></div>
  </div>
</body>
</html>