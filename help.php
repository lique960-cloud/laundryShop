<?php require_once('session.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Help Center — Laundry Shop</title>
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
    .help-card-icon { width: 44px; height: 44px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 18px; flex-shrink: 0; }
    .help-card-icon.green,
    .help-card-icon.blue,
    .help-card-icon.yellow,
    .help-card-icon.purple,
    .help-card-icon.cyan,
    .help-card-icon.pink,
    .help-card-icon.orange { 
      background: rgba(99, 102, 241, 0.15); 
      color: #818cf8; 
    }
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
    .help-card-content .prompt-box { background: #0f172a; border: 1px solid rgba(99,102,241,0.2); border-radius: 8px; padding: 14px 16px; margin: 12px 0; font-size: 13px; color: #cbd5e1; line-height: 1.6; position: relative; }
    .copy-btn { position: absolute; top: 8px; right: 8px; background: rgba(99,102,241,0.2); border: none; color: #818cf8; padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 600; cursor: pointer; font-family: inherit; transition: all 0.2s; }
    .copy-btn:hover { background: rgba(99,102,241,0.4); }
    .copy-btn.copied { background: rgba(16,185,129,0.3); color: #10b981; }
    .tips-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 16px; }
    .tip-card { background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 10px; padding: 18px 20px; }
    .tip-card h5 { font-size: 14px; font-weight: 600; color: #f1f5f9; margin-bottom: 6px; display: flex; align-items: center; gap: 8px; }
    .tip-card p { font-size: 13px; color: #64748b; line-height: 1.5; margin: 0; }
    @media (max-width: 768px) { .help-grid { grid-template-columns: 1fr; } .tips-grid { grid-template-columns: 1fr; } }
  </style>
</head>
<body class="hold-transition skin-blue sidebar-mini modern-theme">
  <div class="wrapper">
    <header class="main-header">
      <a href="home.php" class="logo">
        <span class="logo-mini"><b>H</b>L</span>
        <span class="logo-lg">🧺 <b>Hype</b>Laundry</span>
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

        <!-- STAFF SECTION -->
        <div class="help-section-title"><span class="section-dot" style="background:#818cf8;"></span> For Staff / Employees</div>
        <div class="help-grid">

          <!-- Add New Order -->
          <div class="help-card" onclick="toggleCard(this)">
            <div class="help-card-header">
              <div class="help-card-icon green"><i class="fa fa-plus-circle"></i></div>
              <div class="help-card-info"><h4>Add New Order</h4><p>Walk through encoding a new laundry order</p></div>
              <i class="fa fa-chevron-down help-card-chevron"></i>
            </div>
            <div class="help-card-body"><div class="help-card-content">
              <p>Follow these steps to add a new laundry order:</p>
              <ol>
                <li>Click the <strong>"New Laundry"</strong> button on the Dashboard</li>
                <li>Enter the <strong>Customer Name</strong> (full name)</li>
                <li>Set the <strong>Priority Number</strong> (lower = more urgent)</li>
                <li>Enter the <strong>Weight in kilograms</strong></li>
                <li>Select the <strong>Laundry Type</strong> (e.g., Clothes, Blanket)</li>
                <li>Click <strong>"Save"</strong> — the total is auto-computed (Weight × Price/kg)</li>
              </ol>
              <div class="prompt-box">
                <button class="copy-btn" onclick="copyPrompt(event, this)">Copy prompt</button>
                I need to add a new laundry order. The customer's name is [NAME], they have [WEIGHT] kg of [TYPE]. Please compute the total and confirm the order details before saving.
              </div>
            </div></div>
          </div>

          <!-- Claim & Pay -->
          <div class="help-card" onclick="toggleCard(this)">
            <div class="help-card-header">
              <div class="help-card-icon blue"><i class="fa fa-check-circle"></i></div>
              <div class="help-card-info"><h4>Claim & Pay</h4><p>Process pickup and payment confirmation</p></div>
              <i class="fa fa-chevron-down help-card-chevron"></i>
            </div>
            <div class="help-card-body"><div class="help-card-content">
              <p>When a customer comes to pick up their laundry:</p>
              <ol>
                <li>Go to the <strong>Dashboard</strong> (Home page)</li>
                <li><strong>Check the box</strong> next to the customer's order(s)</li>
                <li>Click the <strong>"Claim & Pay"</strong> button</li>
                <li>Confirm the action in the popup dialog</li>
                <li>The order moves to <strong>Sales Report</strong> automatically</li>
              </ol>
              <div class="prompt-box">
                <button class="copy-btn" onclick="copyPrompt(event, this)">Copy prompt</button>
                Customer [NAME] is here to pick up their laundry. Please find their order, confirm the total amount due, and process the claim so it moves to the sales report.
              </div>
            </div></div>
          </div>

          <!-- Edit an Order -->
          <div class="help-card" onclick="toggleCard(this)">
            <div class="help-card-header">
              <div class="help-card-icon yellow"><i class="fa fa-pencil"></i></div>
              <div class="help-card-info"><h4>Edit an Order</h4><p>Correct order details with change summary</p></div>
              <i class="fa fa-chevron-down help-card-chevron"></i>
            </div>
            <div class="help-card-body"><div class="help-card-content">
              <p>To correct an existing order:</p>
              <ol>
                <li>Find the order in the <strong>Dashboard table</strong></li>
                <li>Click the <strong>"Edit"</strong> button on that row</li>
                <li>Update the fields that need correction (name, weight, type, priority)</li>
                <li>Click <strong>"Save"</strong> to apply changes</li>
                <li>A success message confirms the update</li>
              </ol>
              <div class="prompt-box">
                <button class="copy-btn" onclick="copyPrompt(event, this)">Copy prompt</button>
                I need to edit order for customer [NAME]. Change the weight from [OLD] kg to [NEW] kg and update the laundry type to [TYPE]. Please show me a summary of changes before saving.
              </div>
            </div></div>
          </div>

          <!-- Daily Sales Report -->
          <div class="help-card" onclick="toggleCard(this)">
            <div class="help-card-header">
              <div class="help-card-icon purple"><i class="fa fa-bar-chart"></i></div>
              <div class="help-card-info"><h4>Daily Sales Report</h4><p>Read, summarize, and print sales data</p></div>
              <i class="fa fa-chevron-down help-card-chevron"></i>
            </div>
            <div class="help-card-body"><div class="help-card-content">
              <p>To view and print sales reports:</p>
              <ol>
                <li>Go to <strong>"Sales Report"</strong> in the sidebar</li>
                <li>Select a <strong>date</strong> using the date picker</li>
                <li>The table loads all transactions for that day</li>
                <li><strong>Check the receipts</strong> you want to include</li>
                <li>Click <strong>"Print Report"</strong> to generate a printable receipt</li>
              </ol>
              <div class="prompt-box">
                <button class="copy-btn" onclick="copyPrompt(event, this)">Copy prompt</button>
                Show me today's sales report. How many transactions were completed? What is the total revenue? Summarize the report and prepare it for printing.
              </div>
            </div></div>
          </div>
        </div>

        <!-- CUSTOMER SECTION -->
        <div class="help-section-title" style="margin-top:12px;"><span class="section-dot" style="background:#818cf8;"></span> For Customers</div>
        <div class="help-grid">

          <!-- Service Inquiry -->
          <div class="help-card" onclick="toggleCard(this)">
            <div class="help-card-header">
              <div class="help-card-icon cyan"><i class="fa fa-info-circle"></i></div>
              <div class="help-card-info"><h4>Service Inquiry</h4><p>Services and pricing explained clearly</p></div>
              <i class="fa fa-chevron-down help-card-chevron"></i>
            </div>
            <div class="help-card-body"><div class="help-card-content">
              <p>Common customer questions about our services:</p>
              <ul>
                <li><strong>What services do you offer?</strong> — We offer laundry services for Clothes and Blankets, priced per kilogram</li>
                <li><strong>How is pricing calculated?</strong> — Total = Weight (kg) × Price per kg for that type</li>
                <li><strong>How long does it take?</strong> — Processing time varies by priority level</li>
              </ul>
              <div class="prompt-box">
                <button class="copy-btn" onclick="copyPrompt(event, this)">Copy prompt</button>
                Hi! I'd like to know what laundry services you offer, how much they cost per kilo, and how long it takes to get my laundry back. Can you explain your pricing?
              </div>
            </div></div>
          </div>

          <!-- Order Status -->
          <div class="help-card" onclick="toggleCard(this)">
            <div class="help-card-header">
              <div class="help-card-icon pink"><i class="fa fa-search"></i></div>
              <div class="help-card-info"><h4>Order Status</h4><p>Check if laundry is ready for pickup</p></div>
              <i class="fa fa-chevron-down help-card-chevron"></i>
            </div>
            <div class="help-card-body"><div class="help-card-content">
              <p>Help customers check their order status:</p>
              <ul>
                <li>Orders visible on the Dashboard are <strong>still being processed</strong></li>
                <li>Once claimed, they move to <strong>Sales Report</strong> (completed)</li>
                <li>Staff can look up orders by customer name in the table</li>
              </ul>
              <div class="prompt-box">
                <button class="copy-btn" onclick="copyPrompt(event, this)">Copy prompt</button>
                Hi, my name is [NAME]. I dropped off my laundry on [DATE]. Can you check if it's ready for pickup? What's the total amount I need to pay?
              </div>
            </div></div>
          </div>

          <!-- Laundry Types Info -->
          <div class="help-card" onclick="toggleCard(this)">
            <div class="help-card-header">
              <div class="help-card-icon orange"><i class="fa fa-tags"></i></div>
              <div class="help-card-info"><h4>Laundry Types Info</h4><p>Clothes vs Blanket pricing explained</p></div>
              <i class="fa fa-chevron-down help-card-chevron"></i>
            </div>
            <div class="help-card-body"><div class="help-card-content">
              <p>Our laundry types and pricing:</p>
              <ul>
                <li><strong>Clothes</strong> — Regular clothing items, priced per kilogram</li>
                <li><strong>Blankets</strong> — Heavier items like comforters, priced per kilogram</li>
                <li>Prices may vary — check <strong>Laundry Types</strong> page for current rates</li>
                <li>Total is always <strong>Weight × Price/kg</strong></li>
              </ul>
              <div class="prompt-box">
                <button class="copy-btn" onclick="copyPrompt(event, this)">Copy prompt</button>
                What's the difference between your Clothes and Blanket service? How much does each cost per kilo? I have about [WEIGHT] kg of [TYPE] — how much would that cost?
              </div>
            </div></div>
          </div>
        </div>



      </section>
    </div>

    <footer class="main-footer">
      <div class="pull-right hidden-xs"><b>Version</b> 3.0</div>
      <strong>Copyright &copy; 2026 <a href="#">Laundry Shop</a>.</strong> All rights reserved.
    </footer>
  </div>

  <?php include_once('modal/change_password.php'); ?>
  <?php include_once('modal/msg.php'); ?>
  <?php include_once('script.php'); ?>

  <script>
  function toggleCard(card) {
    // Close other cards
    document.querySelectorAll('.help-card.active').forEach(function(c) {
      if (c !== card) c.classList.remove('active');
    });
    card.classList.toggle('active');
  }

  function copyPrompt(e, btn) {
    e.stopPropagation();
    var box = btn.parentElement;
    var text = box.textContent.replace('Copy prompt', '').replace('Copied!', '').trim();
    navigator.clipboard.writeText(text).then(function() {
      btn.textContent = 'Copied!';
      btn.classList.add('copied');
      setTimeout(function() {
        btn.textContent = 'Copy prompt';
        btn.classList.remove('copied');
      }, 2000);
    });
  }
  </script>
</body>
</html>
