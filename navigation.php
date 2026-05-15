<?php 
  $currentPage = basename($_SERVER['PHP_SELF']);
?>
  <li class="header">MAIN NAVIGATION</li>
         
    <li class="treeview <?= $currentPage == 'home.php' ? 'active' : ''; ?>">
      <a href="home.php">
        <i class="fa fa-dashboard"></i>
        <span>Dashboard</span>
      </a>
    </li>

    <li class="treeview <?= $currentPage == 'laundrytype.php' ? 'active' : ''; ?>">
      <a href="laundrytype.php">
        <i class="fa fa-tags"></i>
        <span>Laundry Types</span>
      </a>
    </li>

    <li class="treeview <?= $currentPage == 'customers.php' ? 'active' : ''; ?>">
      <a href="customers.php">
        <i class="fa fa-users"></i>
        <span>Customers</span>
      </a>
    </li>

    <li class="treeview <?= $currentPage == 'report.php' ? 'active' : ''; ?>">
      <a href="report.php">
        <i class="fa fa-bar-chart"></i>
        <span>Sales Report</span>
      </a>
    </li>

    <li class="treeview <?= in_array($currentPage, ['inventory.php', 'inventory_logs.php', 'inventory_categories.php', 'supply_mapping.php']) ? 'active' : ''; ?>">
      <a href="#">
        <i class="fa fa-archive"></i>
        <span>Inventory</span>
        <i class="fa fa-angle-left pull-right"></i>
      </a>
      <ul class="treeview-menu">
        <li class="<?= $currentPage == 'inventory.php' ? 'active' : ''; ?>"><a href="inventory.php"><i class="fa fa-circle-o"></i> Stock Management</a></li>
        <li class="<?= $currentPage == 'inventory_logs.php' ? 'active' : ''; ?>"><a href="inventory_logs.php"><i class="fa fa-circle-o"></i> Inventory Logs</a></li>
        <li class="<?= $currentPage == 'inventory_categories.php' ? 'active' : ''; ?>"><a href="inventory_categories.php"><i class="fa fa-circle-o"></i> Categories & Brands</a></li>
        <li class="<?= $currentPage == 'supply_mapping.php' ? 'active' : ''; ?>"><a href="supply_mapping.php"><i class="fa fa-circle-o"></i> Supply Mapping</a></li>
      </ul>
    </li>

    <li class="treeview <?= $currentPage == 'help.php' ? 'active' : ''; ?>">
      <a href="help.php">
        <i class="fa fa-question-circle"></i>
        <span>Help Center</span>
      </a>
    </li>

<li class="header">SETTINGS</li>
	<li><a id="changePass" href="#"><i class="fa fa-lock"></i> 
		<span>Change Password</span></a>
	</li>

	<li><a href="logout.php"><i class="fa fa-sign-out"></i> 
		<span>Logout</span></a>
	</li>