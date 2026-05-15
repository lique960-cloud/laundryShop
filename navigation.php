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