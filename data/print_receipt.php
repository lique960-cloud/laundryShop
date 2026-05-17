<?php 
require_once('../database/Database.php');
$db = new Database();

$sale = null;
$items = [];

if(isset($_GET['id'])){
    $saleId = intval($_GET['id']);
    $sale = $db->getRow("SELECT * FROM product_sales WHERE sale_id = ?", [$saleId]);
    if($sale){
        $items = $db->getRows("SELECT * FROM sale_items WHERE sale_id = ?", [$saleId]);
    }
}
$db->Disconnect();

if(!$sale){
    echo '<h3>Receipt not found.</h3>';
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Receipt — <?= $sale['sale_reference']; ?></title>
    <meta content="width=device-width, initial-scale=1" name="viewport">
    <style>
      @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
      
      * { box-sizing: border-box; margin: 0; padding: 0; }
      
      body {
        font-family: 'Inter', sans-serif;
        background: #fff;
        color: #1e293b;
        padding: 24px 30px;
        -webkit-font-smoothing: antialiased;
        max-width: 400px;
        margin: 0 auto;
      }

      .receipt-header {
        text-align: center;
        margin-bottom: 20px;
        padding-bottom: 16px;
        border-bottom: 2px dashed #cbd5e1;
      }

      .receipt-header h1 {
        font-size: 20px;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 2px;
      }

      .receipt-header .subtitle {
        font-size: 11px;
        color: #64748b;
        margin-bottom: 8px;
      }

      .receipt-header .ref {
        font-family: monospace;
        font-size: 13px;
        font-weight: 600;
        color: #6366f1;
        background: #f1f5f9;
        padding: 4px 12px;
        border-radius: 6px;
        display: inline-block;
      }

      .receipt-meta {
        margin-bottom: 16px;
        padding-bottom: 12px;
        border-bottom: 1px solid #e2e8f0;
        font-size: 12px;
        color: #64748b;
      }

      .receipt-meta div {
        display: flex;
        justify-content: space-between;
        margin-bottom: 3px;
      }

      .receipt-meta strong { color: #334155; }

      table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 16px;
      }

      thead th {
        font-size: 10px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #64748b;
        padding: 6px 0;
        border-bottom: 1px solid #e2e8f0;
        text-align: left;
      }

      thead th:last-child { text-align: right; }
      thead th.text-center { text-align: center; }

      tbody td {
        padding: 6px 0;
        font-size: 12px;
        color: #334155;
        border-bottom: 1px solid #f1f5f9;
      }

      tbody td:last-child { text-align: right; }
      tbody td.text-center { text-align: center; }

      .totals {
        border-top: 2px dashed #cbd5e1;
        padding-top: 12px;
        margin-bottom: 16px;
      }

      .totals div {
        display: flex;
        justify-content: space-between;
        margin-bottom: 4px;
        font-size: 12px;
        color: #64748b;
      }

      .totals div strong { color: #1e293b; }

      .totals .grand-total {
        font-size: 16px;
        font-weight: 700;
        color: #0f172a;
        padding-top: 8px;
        margin-top: 8px;
        border-top: 1px solid #e2e8f0;
      }

      .totals .grand-total span { color: #059669; }

      .receipt-footer {
        text-align: center;
        padding-top: 16px;
        border-top: 2px dashed #cbd5e1;
        font-size: 11px;
        color: #94a3b8;
      }

      .receipt-footer p { margin-bottom: 4px; }

      @media print {
        body { padding: 10px; }
        .no-print { display: none; }
      }
    </style>
  </head>
  <body>

  <div class="receipt-header">
    <h1>📦 HypeLaundry</h1>
    <div class="subtitle">Sales & Inventory Management</div>
    <div class="ref"><?= $sale['sale_reference']; ?></div>
  </div>

  <div class="receipt-meta">
    <div><span>Date:</span><strong><?= date('M d, Y h:i A', strtotime($sale['sale_date'])); ?></strong></div>
    <div><span>Customer:</span><strong><?= htmlspecialchars($sale['sale_customer_name']); ?></strong></div>
    <div><span>Payment:</span><strong><?= $sale['sale_payment_method']; ?></strong></div>
  </div>

  <table>
    <thead>
      <tr>
        <th>Item</th>
        <th class="text-center">Qty</th>
        <th class="text-center">Price</th>
        <th>Subtotal</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($items as $item): ?>
      <tr>
        <td><?= htmlspecialchars($item['item_name']); ?></td>
        <td class="text-center"><?= number_format($item['quantity'], 0); ?></td>
        <td class="text-center">₱<?= number_format($item['unit_price'], 2); ?></td>
        <td>₱<?= number_format($item['subtotal'], 2); ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <div class="totals">
    <div><span>Subtotal:</span><strong>₱<?= number_format($sale['sale_total'] + $sale['sale_discount'], 2); ?></strong></div>
    <?php if($sale['sale_discount'] > 0): ?>
    <div><span>Discount:</span><strong>-₱<?= number_format($sale['sale_discount'], 2); ?></strong></div>
    <?php endif; ?>
    <div class="grand-total"><span>TOTAL:</span><span>₱<?= number_format($sale['sale_total'], 2); ?></span></div>
    <div><span>Amount Paid:</span><strong>₱<?= number_format($sale['sale_amount_paid'], 2); ?></strong></div>
    <?php if($sale['sale_change'] > 0): ?>
    <div><span>Change:</span><strong>₱<?= number_format($sale['sale_change'], 2); ?></strong></div>
    <?php endif; ?>
  </div>

  <div class="receipt-footer">
    <p><strong>Thank you for your purchase!</strong></p>
    <p>Generated on <?= date('F j, Y g:i A'); ?></p>
    <p>HypeLaundry Sales & Inventory System</p>
  </div>

  <script>
    window.onload = function() { window.print(); };
  </script>

  </body>
</html>
