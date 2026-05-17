<?php 
  $currentPage = basename($_SERVER['PHP_SELF']);
  $userRole = isset($_SESSION['user_role']) ? strtolower($_SESSION['user_role']) : 'admin';
?>
  <li class="header" style="background:transparent; color:#64748b; font-size:10px; font-weight:700; letter-spacing:1px; padding:20px 20px 10px;">CORE MODULES</li>
         
    <li class="treeview <?= in_array($currentPage, ['home.php', 'cashier_home.php']) ? 'active' : ''; ?>">
      <a href="<?= $userRole == 'cashier' ? 'cashier_home.php' : 'home.php'; ?>">
        <i class="fa fa-th-large" style="color: #818cf8;"></i>
        <span>Dashboard</span>
      </a>
    </li>

    <?php if($userRole == 'cashier'): ?>
    <li class="treeview <?= $currentPage == 'sales.php' ? 'active' : ''; ?>">
      <a href="sales.php">
        <i class="fa fa-shopping-cart" style="color: #10b981;"></i>
        <span>Point of Sale</span>
      </a>
    </li>
    <?php endif; ?>

    <li class="treeview <?= $currentPage == 'transactions.php' ? 'active' : ''; ?>">
      <a href="transactions.php">
        <i class="fa fa-receipt" style="color: #0ea5e9;"></i>
        <span>Transactions</span>
      </a>
    </li>

    <li class="treeview <?= in_array($currentPage, ['inventory.php', 'inventory_logs.php', 'inventory_categories.php', 'services.php']) ? 'active' : ''; ?>">
      <a href="#">
        <i class="fa fa-archive" style="color: #a78bfa;"></i>
        <span>Inventory</span>
        <i class="fa fa-angle-left pull-right"></i>
      </a>
      <ul class="treeview-menu" style="background: rgba(15, 23, 42, 0.4);">
        <li class="<?= $currentPage == 'inventory.php' ? 'active' : ''; ?>"><a href="inventory.php"><i class="fa fa-circle-o"></i> Stock Management</a></li>
        <?php if($userRole == 'admin'): ?>
        <li class="<?= $currentPage == 'inventory_logs.php' ? 'active' : ''; ?>"><a href="inventory_logs.php"><i class="fa fa-circle-o"></i> Audit Logs</a></li>
        <li class="<?= $currentPage == 'inventory_categories.php' ? 'active' : ''; ?>"><a href="inventory_categories.php"><i class="fa fa-circle-o"></i> Categories & Brands</a></li>
        <li class="<?= $currentPage == 'services.php' ? 'active' : ''; ?>"><a href="services.php"><i class="fa fa-circle-o"></i> Manage Services</a></li>
        <?php endif; ?>
      </ul>
    </li>

    <?php if($userRole == 'admin'): ?>
    <li class="treeview <?= $currentPage == 'report.php' ? 'active' : ''; ?>">
      <a href="report.php">
        <i class="fa fa-bar-chart" style="color: #f59e0b;"></i>
        <span>Analytics & Reports</span>
      </a>
    </li>
    <?php endif; ?>

    <li class="treeview <?= $currentPage == 'help.php' ? 'active' : ''; ?>">
      <a href="help.php">
        <i class="fa fa-question-circle" style="color: #94a3b8;"></i>
        <span>Help Center</span>
      </a>
    </li>

<li class="header" style="background:transparent; color:#64748b; font-size:10px; font-weight:700; letter-spacing:1px; padding:20px 20px 10px;">USER SETTINGS</li>
	<li><a id="changePass" href="#"><i class="fa fa-shield"></i> 
		<span>Privacy & Security</span></a>
	</li>

	<li><a href="logout.php"><i class="fa fa-sign-out" style="color: #ef4444;"></i> 
		<span style="color: #ef4444;">Sign Out</span></a>
	</li>