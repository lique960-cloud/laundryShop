<?php require_once('session.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Help Center — HypeLaundry Sales & Inventory</title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="assets/css/bootstrap-theme.min.css">
  <link rel="stylesheet" href="assets/css/font-awesome.css">
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
  <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">
  <link rel="stylesheet" href="assets/css/modern-theme.css">
  <style>
    .help-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px; margin-bottom: 28px; }
    .help-section-title { font-size: 13px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 1.2px; margin-bottom: 16px; display: flex; align-items: center; gap: 8px; }
    .help-section-title .section-dot { width: 8px; height: 8px; border-radius: 50%; }
    .help-card { background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 12px; overflow: hidden; transition: all 0.3s ease; cursor: pointer; position: relative; }
    .help-card:hover { transform: translateY(-3px); box-shadow: 0 8px 30px rgba(0,0,0,0.3); border-color: rgba(99,102,241,0.3); }
    .help-card-header { padding: 20px 22px; display: flex; align-items: center; gap: 14px; }
    .help-card-icon { width: 44px; height: 44px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 18px; flex-shrink: 0; background: rgba(99, 102, 241, 0.15); color: #818cf8; }
    .help-card-info h4 { font-size: 15px; font-weight: 600; color: #f1f5f9; margin: 0 0 3px; }
    .help-card-info p { font-size: 12.5px; color: #64748b; margin: 0; line-height: 1.4; }
    .help-card-chevron { margin-left: auto; color: #475569; font-size: 14px; transition: transform 0.3s; }
    .help-card.active .help-card-chevron { transform: rotate(180deg); }
    .help-card-body { max-height: 0; overflow: hidden; transition: max-height 0.4s ease; background: rgba(15,23,42,0.4); }
    .help-card.active .help-card-body { max-height: 600px; }
    .help-card-content { padding: 20px 22px; border-top: 1px solid var(--border-color); }
    .help-card-content p { color: #94a3b8; font-size: 13.5px; line-height: 1.7; margin-bottom: 10px; }
    .help-card-content ol, .help-card-content ul { color: #94a3b8; font-size: 13.5px; line-height: 1.8; padding-left: 20px; margin-bottom: 12px; }
    .help-card-content li { margin-bottom: 4px; }
    .help-card-content strong { color: #f1f5f9; }
    @media (max-width: 768px) { .help-grid { grid-template-columns: 1fr; } }
  </style>
</head>
<body class="hold-transition skin-blue sidebar-mini modern-theme">
  <div class="wrapper">
    <header class="main-header">
      <a href="home.php" class="logo">
        <span class="logo-mini"><b>H</b>L</span>
        <span class="logo-lg">📦 <b>Hype</b>Laundry</span>
      </a>
      <nav class="navbar navbar-static-top" role="navigation">
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
          <span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span>
        </a>
      </nav>
    </header>
    <aside class="main-sidebar"><section class="sidebar"><ul class="sidebar-menu"><?php include_once('navigation.php'); ?></ul></section></aside>

    <div class="content-wrapper">
      <section class="content-header">
        <div class="welcome-section">
          <div class="greeting">Interactive Guide</div>
          <div class="page-title">Help Center</div>
        </div>
      </section>
      <section class="content">

        <!-- SALES OPERATIONS -->
        <div class="help-section-title"><span class="section-dot" style="background:#10b981;"></span> Sales Operations</div>
        <div class="help-grid">

          <!-- Point of Sale -->
          <div class="help-card" onclick="toggleCard(this)">
            <div class="help-card-header">
              <div class="help-card-icon" style="background:rgba(16,185,129,0.15);color:#10b981;"><i class="fa fa-shopping-cart"></i></div>
              <div class="help-card-info"><h4>Create a New Sale</h4><p>Process product sales using the POS terminal</p></div>
              <i class="fa fa-chevron-down help-card-chevron"></i>
            </div>
            <div class="help-card-body"><div class="help-card-content">
              <p>Follow these steps to process a sale:</p>
              <ol>
                <li>Navigate to <strong>"Point of Sale"</strong> in the sidebar</li>
                <li>Browse or <strong>search</strong> for products in the product grid</li>
                <li><strong>Click a product</strong> to add it to the cart</li>
                <li>Use the <strong>+/−</strong> buttons to adjust quantity</li>
                <li>Enter the <strong>customer name</strong> (optional, defaults to "Walk-in")</li>
                <li>Select the <strong>payment method</strong> (Cash, GCash, Bank Transfer)</li>
                <li>Enter the <strong>amount paid</strong> to calculate change</li>
                <li>Click <strong>"Complete Sale"</strong> to finalize the transaction</li>
              </ol>
              <p><strong>Note:</strong> Stock is automatically deducted from inventory when a sale is completed.</p>
            </div></div>
          </div>

          <!-- View Transactions -->
          <div class="help-card" onclick="toggleCard(this)">
            <div class="help-card-header">
              <div class="help-card-icon" style="background:rgba(14,165,233,0.15);color:#0ea5e9;"><i class="fa fa-list-alt"></i></div>
              <div class="help-card-info"><h4>View Transactions</h4><p>Browse and manage all sales transactions</p></div>
              <i class="fa fa-chevron-down help-card-chevron"></i>
            </div>
            <div class="help-card-body"><div class="help-card-content">
              <p>To view and manage transactions:</p>
              <ol>
                <li>Go to <strong>"Transactions"</strong> in the sidebar</li>
                <li>Use the <strong>date filter</strong> to view specific dates or click <strong>"Show All"</strong></li>
                <li>Click the <strong>eye icon</strong> to view transaction details and line items</li>
                <li>Click the <strong>print icon</strong> to generate a receipt</li>
                <li>Select transactions and click <strong>"Delete"</strong> to remove records</li>
              </ol>
            </div></div>
          </div>

          <!-- Sales Report -->
          <div class="help-card" onclick="toggleCard(this)">
            <div class="help-card-header">
              <div class="help-card-icon" style="background:rgba(139,92,246,0.15);color:#a78bfa;"><i class="fa fa-bar-chart"></i></div>
              <div class="help-card-info"><h4>Sales Report</h4><p>View and print sales reports by date</p></div>
              <i class="fa fa-chevron-down help-card-chevron"></i>
            </div>
            <div class="help-card-body"><div class="help-card-content">
              <p>To generate sales reports:</p>
              <ol>
                <li>Go to <strong>"Sales Report"</strong> in the sidebar</li>
                <li>Select a <strong>date</strong> to filter, or leave empty for all-time data</li>
                <li><strong>Select receipts</strong> using the checkboxes</li>
                <li>Click <strong>"Print Report"</strong> to generate a printable summary</li>
              </ol>
            </div></div>
          </div>
        </div>

        <!-- INVENTORY MANAGEMENT -->
        <div class="help-section-title" style="margin-top:12px;"><span class="section-dot" style="background:#818cf8;"></span> Inventory Management</div>
        <div class="help-grid">

          <!-- Add Products -->
          <div class="help-card" onclick="toggleCard(this)">
            <div class="help-card-header">
              <div class="help-card-icon"><i class="fa fa-plus-circle"></i></div>
              <div class="help-card-info"><h4>Add Products</h4><p>Add new products to your inventory</p></div>
              <i class="fa fa-chevron-down help-card-chevron"></i>
            </div>
            <div class="help-card-body"><div class="help-card-content">
              <p>To add a new product to inventory:</p>
              <ol>
                <li>Go to <strong>Inventory → Stock Management</strong></li>
                <li>Click <strong>"Add Product"</strong></li>
                <li>Fill in: <strong>Item Name, Category, Brand, Quantity, Unit, Price, Low Stock Threshold</strong></li>
                <li>Click <strong>"Save Item"</strong></li>
              </ol>
              <p><strong>Product examples:</strong> Liquid Detergent, Fabric Conditioner, Bleach, Washing Machine Parts, Accessories</p>
            </div></div>
          </div>

          <!-- Stock Management -->
          <div class="help-card" onclick="toggleCard(this)">
            <div class="help-card-header">
              <div class="help-card-icon" style="background:rgba(245,158,11,0.15);color:#f59e0b;"><i class="fa fa-archive"></i></div>
              <div class="help-card-info"><h4>Stock Management</h4><p>Monitor stock levels and restock products</p></div>
              <i class="fa fa-chevron-down help-card-chevron"></i>
            </div>
            <div class="help-card-body"><div class="help-card-content">
              <p>Key stock management features:</p>
              <ul>
                <li><strong>Low Stock Alerts:</strong> Dashboard shows items below threshold</li>
                <li><strong>Auto Deduction:</strong> Stock is automatically deducted when sales are processed</li>
                <li><strong>Edit Stock:</strong> Click "Edit" on any item to update quantity or details</li>
                <li><strong>Inventory Logs:</strong> View complete history of all stock movements</li>
              </ul>
            </div></div>
          </div>

          <!-- Categories & Brands -->
          <div class="help-card" onclick="toggleCard(this)">
            <div class="help-card-header">
              <div class="help-card-icon" style="background:rgba(6,182,212,0.15);color:#06b6d4;"><i class="fa fa-tags"></i></div>
              <div class="help-card-info"><h4>Categories & Brands</h4><p>Organize products by category and brand</p></div>
              <i class="fa fa-chevron-down help-card-chevron"></i>
            </div>
            <div class="help-card-body"><div class="help-card-content">
              <p>To organize your inventory:</p>
              <ol>
                <li>Go to <strong>Inventory → Categories & Brands</strong></li>
                <li>Add categories like: <strong>Detergents, Conditioners, Bleach, Machines, Accessories</strong></li>
                <li>Add brands like: <strong>Tide, Downy, Zonrox, Samsung, LG</strong></li>
                <li>When adding products, select from your created categories and brands</li>
              </ol>
            </div></div>
          </div>

        </div>

        <!-- SYSTEM SETTINGS -->
        <div class="help-section-title" style="margin-top:12px;"><span class="section-dot" style="background:#f59e0b;"></span> System Settings</div>
        <div class="help-grid">
          <div class="help-card" onclick="toggleCard(this)">
            <div class="help-card-header">
              <div class="help-card-icon" style="background:rgba(239,68,68,0.15);color:#ef4444;"><i class="fa fa-lock"></i></div>
              <div class="help-card-info"><h4>Change Password</h4><p>Update your account password</p></div>
              <i class="fa fa-chevron-down help-card-chevron"></i>
            </div>
            <div class="help-card-body"><div class="help-card-content">
              <ol>
                <li>Click <strong>"Change Password"</strong> in the sidebar under Settings</li>
                <li>Enter and confirm your new password</li>
                <li>Click <strong>"Save"</strong> to update</li>
              </ol>
            </div></div>
          </div>
        </div>

      </section>
    </div>

    <footer class="main-footer">
      <div class="pull-right hidden-xs"><b>Version</b> 4.0</div>
      <strong>Copyright &copy; 2026 <a href="#">HypeLaundry</a>.</strong> Sales & Inventory Management System.
    </footer>
  </div>

  <?php include_once('modal/change_password.php'); ?>
  <?php include_once('modal/msg.php'); ?>
  <?php include_once('script.php'); ?>

  <script>
  function toggleCard(card) {
    document.querySelectorAll('.help-card.active').forEach(function(c) {
      if (c !== card) c.classList.remove('active');
    });
    card.classList.toggle('active');
  }
  </script>
</body>
</html>
