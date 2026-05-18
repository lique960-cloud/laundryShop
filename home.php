<?php require_once('session.php'); 
require_once('database/Database.php');
$db = new Database();

// Sales Stats
$todaySales = $db->getRow("SELECT COALESCE(SUM(sale_total),0) as total, COUNT(*) as cnt FROM product_sales WHERE DATE(sale_date) = CURDATE()");
$totalIncome = $db->getRow("SELECT COALESCE(SUM(sale_total),0) as total FROM product_sales");
$totalTransactions = $db->getRow("SELECT COUNT(*) as cnt FROM product_sales");
$monthlySales = $db->getRow("SELECT COALESCE(SUM(sale_total),0) as total FROM product_sales WHERE MONTH(sale_date) = MONTH(CURDATE()) AND YEAR(sale_date) = YEAR(CURDATE())");

// Inventory Stats
$totalInvItems = $db->getRow("SELECT COUNT(*) as cnt FROM inventory");
$lowStockCount = $db->getRow("SELECT COUNT(*) as cnt FROM inventory WHERE quantity <= low_stock_threshold");
$totalInvValue = $db->getRow("SELECT COALESCE(SUM(quantity * price),0) as total FROM inventory");
$outOfStockCount = $db->getRow("SELECT COUNT(*) as cnt FROM inventory WHERE quantity <= 0");

// Low Stock Items
$lowStockItems = $db->getRows("SELECT i.item_name, i.quantity, i.unit, i.low_stock_threshold, c.category_name 
    FROM inventory i 
    LEFT JOIN inventory_category c ON i.category_id = c.id 
    WHERE i.quantity <= i.low_stock_threshold 
    ORDER BY i.quantity ASC LIMIT 6");

// Recent Transactions
$recentSales = $db->getRows("SELECT * FROM product_sales ORDER BY sale_date DESC LIMIT 5");

// Top Selling Items (this month)
$topItems = $db->getRows("SELECT si.item_name, SUM(si.quantity) as total_sold, SUM(si.subtotal) as total_revenue
    FROM sale_items si
    JOIN product_sales ps ON si.sale_id = ps.sale_id
    WHERE MONTH(ps.sale_date) = MONTH(CURDATE()) AND YEAR(ps.sale_date) = YEAR(CURDATE())
    GROUP BY si.item_id, si.item_name
    ORDER BY total_sold DESC LIMIT 5");

// Daily sales for the last 7 days (for chart)
$dailySalesData = $db->getRows("SELECT DATE(sale_date) as sale_day, COALESCE(SUM(sale_total),0) as total 
    FROM product_sales 
    WHERE sale_date >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
    GROUP BY DATE(sale_date) 
    ORDER BY sale_day ASC");

$userName = isset($_SESSION['user_fullname']) ? $_SESSION['user_fullname'] : 'Administrator';
$userRole = isset($_SESSION['user_role']) ? ucfirst($_SESSION['user_role']) : 'Admin';

$db->Disconnect();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Dashboard — HypeLaundry Sales & Inventory</title>
    <meta name="description" content="HypeLaundry Sales & Inventory Management Dashboard - Comprehensive Overview">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="assets/css/font-awesome.css">
    <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">
    <link href="assets/css/dataTables.bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/modern-theme.css">
    <style>
      .hero-section {
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.1), rgba(139, 92, 246, 0.05));
        border-radius: 20px;
        padding: 30px;
        margin-bottom: 30px;
        border: 1px solid rgba(99, 102, 241, 0.15);
        position: relative;
        overflow: hidden;
      }
      .hero-section::after {
        content: '';
        position: absolute;
        top: -50px;
        right: -50px;
        width: 200px;
        height: 200px;
        background: radial-gradient(circle, rgba(99, 102, 241, 0.15), transparent 70%);
        border-radius: 50%;
      }
      .hero-content h1 {
        font-size: 32px;
        font-weight: 800;
        margin: 0 0 8px;
        background: linear-gradient(135deg, #f1f5f9, #94a3b8);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
      }
      .hero-content p {
        color: #94a3b8;
        font-size: 15px;
        margin: 0;
      }
      .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
      }
      .mini-stat-card {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 16px;
        padding: 24px;
        transition: var(--transition);
        display: flex;
        align-items: center;
        gap: 18px;
      }
      .mini-stat-card:hover {
        transform: translateY(-4px);
        border-color: rgba(99, 102, 241, 0.3);
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
      }
      .mini-stat-icon {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        flex-shrink: 0;
      }
      .mini-stat-info .value {
        font-size: 24px;
        font-weight: 800;
        color: #f1f5f9;
        line-height: 1.2;
      }
      .mini-stat-info .label {
        font-size: 13px;
        color: #64748b;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
      }
      .dashboard-row {
        display: flex;
        gap: 25px;
        flex-wrap: wrap;
      }
      .dashboard-col-main { flex: 1; min-width: 0; }
      .dashboard-col-side { width: 360px; flex-shrink: 0; }
      
      @media (max-width: 1200px) {
        .dashboard-col-side { width: 100%; }
      }
    </style>
  </head>
  <body class="hold-transition skin-blue sidebar-mini modern-theme">
    <div class="wrapper">

      <header class="main-header">
        <a href="home.php" class="logo">
          <span class="logo-mini"><b>H</b>L</span>
          <span class="logo-lg"><i class="fa fa-cube" style="margin-right:5px;"></i><b>Hype</b>Laundry</span>
        </a>
        <nav class="navbar navbar-static-top" role="navigation">
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
        </nav>
      </header>

      <aside class="main-sidebar">
        <section class="sidebar">
          <ul class="sidebar-menu">
          <?php include_once('navigation.php'); ?>
          </ul>
        </section>
      </aside>

      <div class="content-wrapper">
        <section class="content">
          
          <div class="hero-section">
            <div class="hero-content">
              <h1>Good Day, <?= htmlspecialchars($userRole); ?>!</h1>
              <p>Here's what's happening with HypeLaundry today. You have <span style="color:#818cf8; font-weight:600;"><?= $lowStockCount['cnt']; ?> low stock alerts</span> that need your attention.</p>
            </div>
            <div style="position:absolute; bottom:20px; right:30px;">
              <span style="background:rgba(99,102,241,0.2); color:#818cf8; padding:6px 15px; border-radius:20px; font-size:12px; font-weight:700; border:1px solid rgba(99,102,241,0.3);">
                <i class="fa fa-shield" style="margin-right:6px;"></i><?= $userRole; ?> Access
              </span>
            </div>
          </div>

          <div class="stats-grid">
            <a href="transactions.php" class="mini-stat-card" style="text-decoration:none; color:inherit; display:flex; align-items:center; gap:18px;">
              <div class="mini-stat-icon" style="background: rgba(16, 185, 129, 0.1); color: #10b981;"><i class="fa fa-shopping-cart"></i></div>
              <div class="mini-stat-info">
                <div class="value">₱<?= number_format($todaySales['total'] ?? 0, 2); ?></div>
                <div class="label">Today's Sales</div>
              </div>
            </a>
            <a href="transactions.php" class="mini-stat-card" style="text-decoration:none; color:inherit; display:flex; align-items:center; gap:18px;">
              <div class="mini-stat-icon" style="background: rgba(14, 165, 233, 0.1); color: #0ea5e9;"><i class="fa fa-list-alt"></i></div>
              <div class="mini-stat-info">
                <div class="value"><?= $totalTransactions['cnt'] ?? 0; ?></div>
                <div class="label">Transactions</div>
              </div>
            </a>
            <a href="inventory.php" class="mini-stat-card" style="text-decoration:none; color:inherit; display:flex; align-items:center; gap:18px;">
              <div class="mini-stat-icon" style="background: rgba(167, 139, 250, 0.1); color: #a78bfa;"><i class="fa fa-archive"></i></div>
              <div class="mini-stat-info">
                <div class="value"><?= $totalInvItems['cnt'] ?? 0; ?></div>
                <div class="label">Inventory Items</div>
              </div>
            </a>
            <a href="inventory.php" class="mini-stat-card" style="text-decoration:none; color:inherit; display:flex; align-items:center; gap:18px;">
              <div class="mini-stat-icon" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b;"><i class="fa fa-warning"></i></div>
              <div class="mini-stat-info">
                <div class="value"><?= $lowStockCount['cnt'] ?? 0; ?></div>
                <div class="label">Low Stock</div>
              </div>
            </a>
          </div>

          <div class="dashboard-row">
            <div class="dashboard-col-main">
              <!-- Sales Chart -->
              <div class="box">
                <div class="box-header with-border">
                  <h3 class="box-title"><i class="fa fa-line-chart" style="margin-right:8px; color:#818cf8;"></i>Revenue Overview</h3>
                </div>
                <div class="box-body">
                  <canvas id="salesChart" style="width:100%; height:320px;"></canvas>
                </div>
              </div>

              <!-- Recent Transactions -->
              <div class="box">
                <div class="box-header with-border">
                  <h3 class="box-title"><i class="fa fa-clock-o" style="margin-right:8px; color:#818cf8;"></i>Recent Transactions</h3>
                  <div class="box-tools pull-right">
                    <a href="transactions.php" class="btn btn-default btn-xs">View All</a>
                  </div>
                </div>
                <div class="box-body no-padding">
                  <div class="table-responsive">
                    <table class="table table-hover">
                      <thead>
                        <tr>
                          <th>Reference</th>
                          <th>Customer</th>
                          <th>Total</th>
                          <th>Method</th>
                          <th>Date</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php foreach($recentSales as $s): ?>
                        <tr>
                          <td><span style="font-family:monospace; color:#818cf8; font-weight:600;"><?= $s['sale_reference']; ?></span></td>
                          <td style="font-weight:500;"><?= htmlspecialchars($s['sale_customer_name']); ?></td>
                          <td style="font-weight:700; color:#10b981;">₱<?= number_format($s['sale_total'], 2); ?></td>
                          <td><span style="background:rgba(99,102,241,0.1);color:#818cf8;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;"><?= $s['sale_payment_method']; ?></span></td>
                          <td style="font-size:12px; color:#94a3b8;"><?= date('M d, h:i A', strtotime($s['sale_date'])); ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if(empty($recentSales)): ?>
                        <tr><td colspan="5" style="text-align:center; padding:30px; color:#64748b;">No transactions recorded.</td></tr>
                        <?php endif; ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>

            <div class="dashboard-col-side">
              <!-- Top Items -->
              <div class="box">
                <div class="box-header with-border">
                  <h3 class="box-title"><i class="fa fa-trophy" style="margin-right:8px; color:#f59e0b;"></i>Top Products</h3>
                </div>
                <div class="box-body">
                  <?php if(empty($topItems)): ?>
                    <p style="color:#64748b; text-align:center; padding:20px 0;">No sales data this month</p>
                  <?php else: ?>
                    <?php foreach($topItems as $idx => $item): ?>
                    <div style="display:flex; align-items:center; gap:12px; padding:12px 0; <?= $idx < count($topItems)-1 ? 'border-bottom:1px solid rgba(148,163,184,0.1);' : ''; ?>">
                      <div style="width:32px;height:32px;border-radius:10px;background:rgba(99,102,241,0.15);display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;color:#818cf8;flex-shrink:0;"><?= $idx + 1; ?></div>
                      <div style="flex:1; min-width:0;">
                        <div style="font-size:14px; font-weight:600; color:#f1f5f9; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;"><?= htmlspecialchars($item['item_name']); ?></div>
                        <div style="font-size:12px; color:#64748b;"><?= number_format($item['total_sold'], 0); ?> units sold</div>
                      </div>
                      <div style="font-size:14px; font-weight:700; color:#10b981;">₱<?= number_format($item['total_revenue'], 0); ?></div>
                    </div>
                    <?php endforeach; ?>
                  <?php endif; ?>
                </div>
              </div>

              <!-- Inventory Status -->
              <div class="box">
                <div class="box-header with-border">
                  <h3 class="box-title"><i class="fa fa-archive" style="margin-right:8px; color:#818cf8;"></i>Inventory Status</h3>
                </div>
                <div class="box-body" style="padding:10px 24px !important;">
                  <div style="margin-bottom:20px;">
                    <div style="display:flex; justify-content:space-between; margin-bottom:6px;">
                      <span style="font-size:13px; color:#94a3b8;">Total Value</span>
                      <span style="font-size:15px; font-weight:700; color:#f1f5f9;">₱<?= number_format($totalInvValue['total'], 2); ?></span>
                    </div>
                    <div style="height:6px; background:rgba(148,163,184,0.1); border-radius:3px; overflow:hidden;">
                      <div style="height:100%; width:100%; background:linear-gradient(to right, #6366f1, #a78bfa);"></div>
                    </div>
                  </div>
                  
                  <h4 style="font-size:12px; text-transform:uppercase; color:#64748b; letter-spacing:1px; margin:20px 0 12px; font-weight:700;">Critical Stock</h4>
                  <?php if(empty($lowStockItems)): ?>
                    <p style="color:#10b981; font-size:13px; font-weight:500;"><i class="fa fa-check-circle" style="margin-right:6px;"></i>All items are in stock</p>
                  <?php else: ?>
                    <?php foreach($lowStockItems as $item): ?>
                    <div style="margin-bottom:12px;">
                      <div style="display:flex; justify-content:space-between; margin-bottom:4px;">
                        <span style="font-size:13px; color:#f1f5f9; font-weight:500;"><?= htmlspecialchars($item['item_name']); ?></span>
                        <span style="font-size:12px; color:#f87171; font-weight:600;"><?= number_format($item['quantity'], 0); ?> <?= $item['unit']; ?> left</span>
                      </div>
                      <div style="height:4px; background:rgba(248,113,113,0.1); border-radius:2px; overflow:hidden;">
                        <?php 
                          $pct = min(100, ($item['quantity'] / ($item['low_stock_threshold'] * 2)) * 100);
                        ?>
                        <div style="height:100%; width:<?= $pct; ?>%; background:#f87171;"></div>
                      </div>
                    </div>
                    <?php endforeach; ?>
                    <a href="inventory.php" class="btn btn-default btn-xs btn-block" style="margin-top:10px;">Restock Inventory</a>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          </div>

        </section>
      </div>

      <footer class="main-footer">
        <div class="pull-right hidden-xs">
          <b>Version</b> 4.5
        </div>
        <strong>Copyright &copy; 2026 <a href="#">HypeLaundry</a>.</strong> Sales & Inventory Management System.
      </footer>

      <div class="control-sidebar-bg"></div>
    </div>

    <?php include_once('modal/change_password.php'); ?>
    <?php include_once('modal/msg.php'); ?>
    <?php include_once('modal/confirm.php'); ?>
    <?php include_once('script.php'); ?>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
      // Sales Chart
      var ctx = document.getElementById('salesChart').getContext('2d');
      var labels = [];
      var data = [];
      
      <?php 
      // Build chart data - fill in missing days
      $chartData = [];
      foreach($dailySalesData as $d){
        $chartData[$d['sale_day']] = $d['total'];
      }
      for($i = 6; $i >= 0; $i--){
        $day = date('Y-m-d', strtotime("-$i days"));
        $label = date('M d', strtotime($day));
        $val = isset($chartData[$day]) ? $chartData[$day] : 0;
        echo "labels.push('$label');\n";
        echo "data.push($val);\n";
      }
      ?>

      new Chart(ctx, {
        type: 'line',
        data: {
          labels: labels,
          datasets: [{
            label: 'Daily Revenue',
            data: data,
            borderColor: '#818cf8',
            backgroundColor: function(context) {
              const chart = context.chart;
              const {ctx, chartArea} = chart;
              if (!chartArea) return null;
              const gradient = ctx.createLinearGradient(0, chartArea.bottom, 0, chartArea.top);
              gradient.addColorStop(0, 'rgba(99, 102, 241, 0)');
              gradient.addColorStop(1, 'rgba(99, 102, 241, 0.15)');
              return gradient;
            },
            borderWidth: 3,
            pointBackgroundColor: '#6366f1',
            pointBorderColor: '#fff',
            pointBorderWidth: 2,
            pointRadius: 4,
            pointHoverRadius: 6,
            tension: 0.4,
            fill: true
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: { display: false },
            tooltip: {
              backgroundColor: '#1e293b',
              titleColor: '#f1f5f9',
              bodyColor: '#94a3b8',
              borderColor: 'rgba(148,163,184,0.1)',
              borderWidth: 1,
              padding: 12,
              cornerRadius: 10,
              displayColors: false,
              callbacks: {
                label: function(context) {
                  return '₱ ' + context.parsed.y.toLocaleString(undefined, {minimumFractionDigits: 2});
                }
              }
            }
          },
          scales: {
            x: {
              grid: { display: false },
              ticks: { color: '#64748b', font: { size: 11, weight: '500' } }
            },
            y: {
              grid: { color: 'rgba(148,163,184,0.05)' },
              border: { dash: [4, 4] },
              ticks: { 
                color: '#64748b', 
                font: { size: 11 },
                callback: function(value) { return '₱' + value.toLocaleString(); }
              }
            }
          }
        }
      });
    </script>
  </body>
</html>

