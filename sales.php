<?php require_once('session.php'); 
require_once('database/Database.php');
$db = new Database();
$products = $db->getRows("SELECT i.*, c.category_name, b.brand_name 
    FROM inventory i 
    LEFT JOIN inventory_category c ON i.category_id = c.id 
    LEFT JOIN inventory_brand b ON i.brand_id = b.id 
    WHERE i.quantity > 0 
    ORDER BY i.item_name ASC");
$categories = $db->getRows("SELECT * FROM inventory_category ORDER BY category_name ASC");
$services = $db->getRows("SELECT * FROM services ORDER BY service_name ASC");
$db->Disconnect();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Point of Sale — HypeLaundry</title>
    <meta name="description" content="HypeLaundry Sales & Inventory - Point of Sale">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="assets/css/font-awesome.css">
    <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">
    <link href="assets/css/dataTables.bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/modern-theme.css">
    <style>
      .pos-layout { display: flex; gap: 20px; min-height: calc(100vh - 200px); }
      .pos-products { flex: 1; min-width: 0; }
      .pos-cart { width: 380px; flex-shrink: 0; }
      
      .product-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 14px; }
      .product-card {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 16px;
        cursor: pointer;
        transition: all 0.25s ease;
        position: relative;
        overflow: hidden;
      }
      .product-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.3);
        border-color: rgba(99,102,241,0.4);
      }
      .product-card.out-of-stock {
        opacity: 0.4;
        pointer-events: none;
      }
      .product-card .p-name {
        font-size: 13.5px;
        font-weight: 600;
        color: #f1f5f9;
        margin-bottom: 6px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
      }
      .product-card .p-category {
        font-size: 11px;
        color: #64748b;
        margin-bottom: 8px;
      }
      .product-card .p-price {
        font-size: 16px;
        font-weight: 700;
        color: #10b981;
      }
      .product-card .p-stock {
        font-size: 11px;
        color: #94a3b8;
        margin-top: 4px;
      }
      .product-card .p-stock.low { color: #f87171; }

      .cart-box {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 12px;
        display: flex;
        flex-direction: column;
        height: 100%;
        overflow: hidden;
      }
      .cart-header {
        padding: 18px 20px;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        align-items: center;
        justify-content: space-between;
      }
      .cart-header h4 {
        font-size: 16px;
        font-weight: 600;
        color: #f1f5f9;
        margin: 0;
      }
      .cart-header .item-count {
        background: rgba(99,102,241,0.15);
        color: #818cf8;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
      }
      .cart-items {
        flex: 1;
        overflow-y: auto;
        padding: 12px 0;
        max-height: 340px;
      }
      .cart-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 20px;
        border-bottom: 1px solid rgba(148,163,184,0.06);
        transition: background 0.15s;
      }
      .cart-item:hover { background: rgba(99,102,241,0.04); }
      .cart-item .ci-info { flex: 1; min-width: 0; }
      .cart-item .ci-name {
        font-size: 13px;
        font-weight: 500;
        color: #f1f5f9;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
      }
      .cart-item .ci-price { font-size: 11.5px; color: #64748b; }
      .cart-item .ci-qty {
        display: flex;
        align-items: center;
        gap: 6px;
      }
      .cart-item .ci-qty button {
        width: 26px;
        height: 26px;
        border-radius: 6px;
        border: 1px solid rgba(148,163,184,0.2);
        background: rgba(99,102,241,0.1);
        color: #818cf8;
        cursor: pointer;
        font-size: 14px;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.15s;
      }
      .cart-item .ci-qty button:hover {
        background: rgba(99,102,241,0.25);
      }
      .cart-item .ci-qty span {
        font-size: 13px;
        font-weight: 600;
        color: #f1f5f9;
        min-width: 20px;
        text-align: center;
      }
      .cart-item .ci-subtotal {
        font-size: 13px;
        font-weight: 600;
        color: #10b981;
        min-width: 70px;
        text-align: right;
      }
      .cart-item .ci-remove {
        cursor: pointer;
        color: #64748b;
        font-size: 14px;
        transition: color 0.15s;
      }
      .cart-item .ci-remove:hover { color: #ef4444; }

      .cart-footer {
        border-top: 1px solid var(--border-color);
        padding: 16px 20px;
      }
      .cart-total-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
      }
      .cart-total-row.grand {
        font-size: 18px;
        font-weight: 700;
        color: #f1f5f9;
        padding-top: 10px;
        border-top: 1px solid var(--border-color);
        margin-top: 6px;
      }
      .cart-total-row .label { color: #94a3b8; font-size: 13px; }
      .cart-total-row .value { color: #f1f5f9; font-weight: 600; }
      .cart-total-row.grand .value { color: #10b981; }

      .cart-actions { margin-top: 14px; display: flex; flex-direction: column; gap: 8px; }
      .cart-actions .btn-checkout {
        width: 100%;
        padding: 14px;
        background: linear-gradient(135deg, #10b981, #059669);
        color: #fff;
        border: none;
        border-radius: 10px;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
      }
      .cart-actions .btn-checkout:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 15px rgba(16,185,129,0.4);
      }
      .cart-actions .btn-checkout:disabled {
        opacity: 0.4;
        cursor: not-allowed;
        transform: none;
      }
      .cart-actions .btn-clear {
        width: 100%;
        padding: 10px;
        background: transparent;
        color: #94a3b8;
        border: 1px solid rgba(148,163,184,0.2);
        border-radius: 10px;
        font-size: 13px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
      }
      .cart-actions .btn-clear:hover { border-color: #ef4444; color: #ef4444; }

      .search-bar {
        display: flex;
        gap: 10px;
        margin-bottom: 18px;
        flex-wrap: wrap;
      }
      .search-bar input {
        flex: 1;
        min-width: 200px;
        padding: 10px 16px;
        background: var(--bg-surface);
        border: 1px solid var(--border-color);
        border-radius: 10px;
        color: #f1f5f9;
        font-size: 13.5px;
        outline: none;
        transition: border 0.2s;
      }
      .search-bar input:focus { border-color: #6366f1; }
      .search-bar input::placeholder { color: #64748b; }
      .search-bar select {
        padding: 10px 14px;
        background: var(--bg-surface);
        border: 1px solid var(--border-color);
        border-radius: 10px;
        color: #f1f5f9;
        font-size: 13px;
        outline: none;
        cursor: pointer;
      }

      .cart-empty {
        text-align: center;
        padding: 40px 20px;
        color: #64748b;
      }
      .cart-empty i { font-size: 36px; margin-bottom: 12px; display: block; opacity: 0.4; }
      .cart-empty p { font-size: 13px; }

      .checkout-field { margin-bottom: 12px; }
      .checkout-field label { display: block; font-size: 12px; font-weight: 600; color: #94a3b8; margin-bottom: 4px; }
      .checkout-field input, .checkout-field select {
        width: 100%;
        padding: 10px 14px;
        background: var(--bg-surface);
        border: 1px solid var(--border-color);
        border-radius: 8px;
        color: #f1f5f9;
        font-size: 13.5px;
        outline: none;
      }
      .checkout-field input:focus, .checkout-field select:focus { border-color: #6366f1; }

      @media (max-width: 1000px) {
        .pos-layout { flex-direction: column; }
        .pos-cart { width: 100%; }
      }
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
        <section class="content-header">
          <div class="welcome-section">
            <div class="greeting">Sales Terminal</div>
            <div class="page-title">Point of Sale</div>
          </div>
        </section>

        <section class="content">
          <div class="pos-layout">
            <!-- Products Grid -->
            <div class="pos-products">
              <div class="search-bar">
                <input type="text" id="pos-search" placeholder="🔍 Search products...">
                <select id="pos-category-filter">
                  <option value="">All Categories</option>
                  <?php foreach($categories as $cat): ?>
                  <option value="<?= $cat['id']; ?>"><?= htmlspecialchars($cat['category_name']); ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              
              <h4 style="color:#f1f5f9; font-weight:600; margin-bottom:12px;"><i class="fa fa-tags" style="margin-right:8px; color:#818cf8;"></i>Laundry Services</h4>
              <div class="product-grid" style="margin-bottom:25px;">
                <?php foreach($services as $s): ?>
                <div class="product-card" 
                     data-id="<?= $s['id']; ?>"
                     data-name="<?= htmlspecialchars($s['service_name']); ?>"
                     data-price="<?= $s['price']; ?>"
                     data-type="service"
                     onclick="addToCart(this)">
                  <div class="p-name"><?= htmlspecialchars($s['service_name']); ?></div>
                  <div class="p-category">Service</div>
                  <div class="p-price">₱<?= number_format($s['price'], 2); ?></div>
                  <div class="p-stock" style="color:#10b981;">Available</div>
                </div>
                <?php endforeach; ?>
              </div>

              <h4 style="color:#f1f5f9; font-weight:600; margin-bottom:12px;"><i class="fa fa-cubes" style="margin-right:8px; color:#818cf8;"></i>Products</h4>
              <div class="product-grid" id="product-grid">
                <?php foreach($products as $p): 
                  $isLow = $p['quantity'] <= $p['low_stock_threshold'];
                ?>
                <div class="product-card" 
                     data-id="<?= $p['id']; ?>"
                     data-name="<?= htmlspecialchars($p['item_name']); ?>"
                     data-price="<?= $p['price']; ?>"
                     data-stock="<?= $p['quantity']; ?>"
                     data-unit="<?= htmlspecialchars($p['unit']); ?>"
                     data-category="<?= $p['category_id']; ?>"
                     onclick="addToCart(this)">
                  <div class="p-name"><?= htmlspecialchars($p['item_name']); ?></div>
                  <div class="p-category"><?= $p['category_name'] ?? 'Uncategorized'; ?> · <?= $p['brand_name'] ?? 'No brand'; ?></div>
                  <div class="p-price">₱<?= number_format($p['price'], 2); ?></div>
                  <div class="p-stock <?= $isLow ? 'low' : ''; ?>">
                    <?= $isLow ? '⚠ ' : ''; ?><?= number_format($p['quantity'], 2); ?> <?= $p['unit']; ?> in stock
                  </div>
                </div>
                <?php endforeach; ?>
                <?php if(empty($products)): ?>
                <div style="grid-column: 1/-1; text-align:center; padding:60px 20px; color:#64748b;">
                  <i class="fa fa-archive" style="font-size:40px; display:block; margin-bottom:12px; opacity:0.3;"></i>
                  <p>No products in inventory. <a href="inventory.php" style="color:#818cf8;">Add products first →</a></p>
                </div>
                <?php endif; ?>
              </div>
            </div>

            <!-- Cart -->
            <div class="pos-cart">
              <div class="cart-box">
                <div class="cart-header">
                  <h4><i class="fa fa-shopping-cart" style="margin-right:8px;"></i>Cart</h4>
                  <span class="item-count" id="cart-count">0 items</span>
                </div>
                <div class="cart-items" id="cart-items">
                  <div class="cart-empty" id="cart-empty">
                    <i class="fa fa-shopping-basket"></i>
                    <p>Cart is empty<br><small>Click products to add them</small></p>
                  </div>
                </div>
                <div class="cart-footer">
                  <div class="checkout-field">
                    <label>Customer Name</label>
                    <input type="text" id="customer-name" placeholder="Walk-in customer" value="Walk-in">
                  </div>
                  <div class="checkout-field">
                    <label>Payment Method</label>
                    <select id="payment-method">
                      <option value="Cash">Cash</option>
                    </select>
                  </div>
                  <div class="checkout-field" id="amount-paid-field">
                    <label>Amount Paid</label>
                    <input type="number" step="0.01" id="amount-paid" placeholder="0.00" oninput="updateChange()">
                  </div>

                  <div class="cart-total-row">
                    <span class="label">Subtotal</span>
                    <span class="value" id="cart-subtotal">₱0.00</span>
                  </div>
                  <div class="cart-total-row">
                    <span class="label">Discount</span>
                    <span class="value"><input type="number" step="0.01" id="cart-discount" value="0" style="width:80px;padding:4px 8px;background:var(--bg-surface);border:1px solid var(--border-color);border-radius:6px;color:#f1f5f9;font-size:12px;text-align:right;outline:none;" oninput="updateCartTotals()"></span>
                  </div>
                  <div class="cart-total-row grand">
                    <span>TOTAL</span>
                    <span class="value" id="cart-total">₱0.00</span>
                  </div>
                  <div class="cart-total-row" id="change-row" style="display:none;">
                    <span class="label">Change</span>
                    <span class="value" id="cart-change" style="color:#f59e0b;">₱0.00</span>
                  </div>

                  <div class="cart-actions">
                    <button class="btn-checkout" id="btn-checkout" disabled onclick="processCheckout()">
                      <i class="fa fa-check-circle" style="margin-right:6px;"></i>Complete Sale
                    </button>
                    <button class="btn-clear" onclick="clearCart()">
                      <i class="fa fa-trash" style="margin-right:5px;"></i>Clear Cart
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
      </div>

      <footer class="main-footer">
        <div class="pull-right hidden-xs">
          <b>Version</b> 4.0
        </div>
        <strong>Copyright &copy; 2026 <a href="#">HypeLaundry</a>.</strong> Sales & Inventory Management System.
      </footer>
    </div>

    <?php include_once('modal/change_password.php'); ?>
    <?php include_once('modal/msg.php'); ?>
    <?php include_once('modal/confirm.php'); ?>
    <?php include_once('script.php'); ?>

    <script>
    var cart = [];

    // Search & Filter
    $('#pos-search').on('input', filterProducts);
    $('#pos-category-filter').on('change', filterProducts);

    function filterProducts() {
      var search = $('#pos-search').val().toLowerCase();
      var catId = $('#pos-category-filter').val();
      
      $('.product-card').each(function() {
        var name = $(this).data('name').toLowerCase();
        var category = $(this).data('category').toString();
        var matchSearch = name.indexOf(search) >= 0;
        var matchCat = !catId || category === catId;
        $(this).toggle(matchSearch && matchCat);
      });
    }

    function addToCart(el) {
      var id = $(el).data('id');
      var name = $(el).data('name');
      var price = parseFloat($(el).data('price'));
      var type = $(el).data('type') || 'product';
      
      if (type === 'service') {
        var existingService = cart.find(function(c) { return c.type === 'service'; });
        if (existingService) {
          alert('Only one laundry service can be added to the cart at a time.');
          return;
        }
      }
      
      var stock = 0;
      var unit = '';
      if(type === 'product'){
        stock = parseFloat($(el).data('stock'));
        unit = $(el).data('unit');
      }

      var existing = cart.find(function(c) { return c.id === id && c.type === type; });
      if (existing) {
        if (type === 'product' && existing.qty >= stock) {
          alert('Cannot exceed available stock (' + stock + ' ' + unit + ')');
          return;
        }
        existing.qty++;
      } else {
        cart.push({ id: id, name: name, price: price, stock: stock, unit: unit, qty: 1, type: type });
      }
      renderCart();
    }

    function removeFromCart(id, type) {
      cart = cart.filter(function(c) { return !(c.id === id && c.type === type); });
      renderCart();
    }

    function changeQty(id, type, delta) {
      var item = cart.find(function(c) { return c.id === id && c.type === type; });
      if (!item) return;
      
      if (type === 'service' && delta > 0) {
        alert('Quantity for services is limited to 1.');
        return;
      }
      
      var newQty = item.qty + delta;
      if (newQty <= 0) {
        removeFromCart(id, type);
        return;
      }
      if (type === 'product' && newQty > item.stock) {
        alert('Cannot exceed available stock (' + item.stock + ' ' + item.unit + ')');
        return;
      }
      item.qty = newQty;
      renderCart();
    }

    function renderCart() {
      var html = '';
      if (cart.length === 0) {
        $('#cart-empty').show();
        $('#btn-checkout').prop('disabled', true);
      } else {
        $('#cart-empty').hide();
        $('#btn-checkout').prop('disabled', false);
        cart.forEach(function(item) {
          var subtotal = item.price * item.qty;
          var qtyControls = '';
          var priceText = '₱' + item.price.toFixed(2);
          
          if (item.type === 'product') {
            priceText += ' × ' + item.qty;
            qtyControls = '<div class="ci-qty">' +
              '<button onclick="changeQty(' + item.id + ', \'' + item.type + '\', -1)">−</button>' +
              '<span>' + item.qty + '</span>' +
              '<button onclick="changeQty(' + item.id + ', \'' + item.type + '\', 1)">+</button>' +
              '</div>';
          } else {
            qtyControls = '<div class="ci-qty"></div>';
          }

          html += '<div class="cart-item">' +
            '<div class="ci-info"><div class="ci-name">' + item.name + '</div><div class="ci-price">' + priceText + '</div></div>' +
            qtyControls +
            '<div class="ci-subtotal">₱' + subtotal.toFixed(2) + '</div>' +
            '<span class="ci-remove" onclick="removeFromCart(' + item.id + ', \'' + item.type + '\')"><i class="fa fa-times"></i></span>' +
            '</div>';
        });
      }
      $('#cart-items').html(cart.length === 0 ? '<div class="cart-empty" id="cart-empty"><i class="fa fa-shopping-basket"></i><p>Cart is empty<br><small>Click products to add them</small></p></div>' : html);
      $('#cart-count').text(cart.length + ' item' + (cart.length !== 1 ? 's' : ''));
      updateCartTotals();
    }

    function updateCartTotals() {
      var subtotal = 0;
      cart.forEach(function(item) { subtotal += item.price * item.qty; });
      var discount = parseFloat($('#cart-discount').val()) || 0;
      var total = Math.max(0, subtotal - discount);

      $('#cart-subtotal').text('₱' + subtotal.toFixed(2));
      $('#cart-total').text('₱' + total.toFixed(2));
      updateChange();
    }

    function updateChange() {
      var total = parseFloat($('#cart-total').text().replace('₱', '').replace(',', '')) || 0;
      var paid = parseFloat($('#amount-paid').val()) || 0;
      var change = paid - total;
      if (paid > 0) {
        $('#change-row').show();
        $('#cart-change').text('₱' + Math.max(0, change).toFixed(2));
      } else {
        $('#change-row').hide();
      }
    }

    function clearCart() {
      if (cart.length === 0) return;
      if (confirm('Clear all items from cart?')) {
        cart = [];
        $('#cart-discount').val(0);
        $('#amount-paid').val('');
        renderCart();
      }
    }

    function processCheckout() {
      if (cart.length === 0) return;

      var subtotal = 0;
      cart.forEach(function(item) { subtotal += item.price * item.qty; });
      var discount = parseFloat($('#cart-discount').val()) || 0;
      var total = Math.max(0, subtotal - discount);
      var paid = parseFloat($('#amount-paid').val()) || 0;

      if (paid < total && $('#payment-method').val() === 'Cash') {
        alert('Amount paid cannot be less than total for cash payments.');
        return;
      }

      var saleData = {
        customer_name: $('#customer-name').val() || 'Walk-in',
        payment_method: $('#payment-method').val(),
        discount: discount,
        amount_paid: paid > 0 ? paid : total,
        items: JSON.stringify(cart)
      };

      $('#btn-checkout').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Processing...');

      $.ajax({
        url: 'data/process_sale.php',
        type: 'post',
        dataType: 'json',
        data: saleData,
        success: function(data) {
          if (data.valid) {
            cart = [];
            $('#cart-discount').val(0);
            $('#amount-paid').val('');
            $('#customer-name').val('Walk-in');
            renderCart();

            // Show success
            $('#modal-msg').find('#msg-body').text('Sale completed! Ref: ' + data.reference);
            $('#modal-msg').modal('show');

            // Auto print receipt
            if (data.sale_id) {
              window.open('data/print_receipt.php?id=' + data.sale_id, '_blank', 'width=400,height=600');
            }

            // Refresh product stock
            setTimeout(function() { location.reload(); }, 1500);
          } else {
            alert(data.msg || 'Failed to process sale.');
          }
          $('#btn-checkout').prop('disabled', false).html('<i class="fa fa-check-circle" style="margin-right:6px;"></i>Complete Sale');
        },
        error: function() {
          alert('Connection error. Please try again.');
          $('#btn-checkout').prop('disabled', false).html('<i class="fa fa-check-circle" style="margin-right:6px;"></i>Complete Sale');
        }
      });
    }
    </script>
  </body>
</html>
