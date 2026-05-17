<?php 
require_once('../database/Database.php');
$db = new Database();

if(isset($_GET['date'])){
    $date = $_GET['date'];
    $ids = isset($_GET['ids']) ? trim($_GET['ids']) : '';

    if($ids !== ''){
        $idList = array_filter(array_map('intval', explode(',', $ids)), function($id){ return $id > 0; });
        if(count($idList) > 0){
            $placeholders = implode(',', array_fill(0, count($idList), '?'));
            $sql = "SELECT * FROM product_sales WHERE sale_id IN ($placeholders) ORDER BY sale_date DESC";
            $reports = $db->getRows($sql, $idList);
        } else {
            if(empty($date)){
                $reports = $db->getRows("SELECT * FROM product_sales ORDER BY sale_date DESC");
            } else {
                $reports = $db->getRows("SELECT * FROM product_sales WHERE DATE(sale_date) = ? ORDER BY sale_date DESC", [$date]);
            }
        }
    } else {
        if(empty($date)){
            $reports = $db->getRows("SELECT * FROM product_sales ORDER BY sale_date DESC");
        } else {
            $reports = $db->getRows("SELECT * FROM product_sales WHERE DATE(sale_date) = ? ORDER BY sale_date DESC", [$date]);
        }
    }
    $db->Disconnect();
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Sales Report — <?= empty($date) ? 'All Time' : $date; ?></title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <style>
      @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
      
      * { box-sizing: border-box; margin: 0; padding: 0; }
      
      body {
        font-family: 'Inter', sans-serif;
        background: #fff;
        color: #1e293b;
        padding: 30px 40px;
        -webkit-font-smoothing: antialiased;
      }

      .report-header {
        text-align: center;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 2px solid #e2e8f0;
      }

      .report-header h1 {
        font-size: 22px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 4px;
      }

      .report-header .subtitle {
        font-size: 13px;
        color: #64748b;
        margin-bottom: 4px;
      }

      .report-header .date {
        font-size: 16px;
        font-weight: 600;
        color: #6366f1;
      }

      table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
      }

      thead th {
        background: #f1f5f9;
        color: #475569;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 10px 14px;
        border-bottom: 2px solid #e2e8f0;
        text-align: left;
      }

      tbody td {
        padding: 10px 14px;
        border-bottom: 1px solid #f1f5f9;
        font-size: 13px;
        color: #334155;
      }

      tbody tr:hover { background: #f8fafc; }

      .text-center { text-align: center; }
      .text-right { text-align: right; }

      .total-row td {
        border-top: 2px solid #e2e8f0;
        font-weight: 700;
        font-size: 14px;
        padding: 12px 14px;
        color: #0f172a;
      }

      .amount { color: #059669; font-weight: 600; }

      .report-footer {
        text-align: center;
        margin-top: 30px;
        padding-top: 15px;
        border-top: 1px solid #e2e8f0;
        font-size: 11px;
        color: #94a3b8;
      }

      @media print {
        body { padding: 20px; }
        .no-print { display: none; }
      }
    </style>
  </head>
  <body>

  <div class="report-header">
    <h1>📦 HypeLaundry</h1>
    <div class="subtitle"><?= empty($date) ? 'Complete Sales Report' : 'Daily Sales Report'; ?></div>
    <div class="date"><?= empty($date) ? 'All Recorded Transactions' : $date; ?></div>
  </div>

  <table>
    <thead>
      <tr>
        <th>Reference</th>
        <th>Customer</th>
        <th class="text-center">Payment</th>
        <th class="text-center">Date</th>
        <th class="text-right">Amount</th>
      </tr>
    </thead>
    <tbody>
      <?php 
        $total = 0;
        foreach($reports as $r): 
        $total += $r['sale_total'];
      ?>
      <tr>
        <td style="font-family:monospace;"><?= $r['sale_reference']; ?></td>
        <td><?= htmlspecialchars($r['sale_customer_name']); ?></td>
        <td class="text-center"><?= $r['sale_payment_method']; ?></td>
        <td class="text-center"><?= date('M d, Y h:i A', strtotime($r['sale_date'])); ?></td>
        <td class="text-right amount"><?= '₱ '.number_format($r['sale_total'], 2); ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
    <tfoot>
      <tr class="total-row">
        <td colspan="4" class="text-right">TOTAL:</td>
        <td class="text-right amount" style="font-size:16px;"><?= '₱ '.number_format($total,2); ?></td>
      </tr>
    </tfoot>
  </table>

  <div class="report-footer">
    <p>Generated on <?= date('F j, Y g:i A'); ?> &bull; HypeLaundry Sales & Inventory System</p>
  </div>

  <script type="text/javascript">
    print();
  </script>

</body>
</html>

<?php
}//end isset
