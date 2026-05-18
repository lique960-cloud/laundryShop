<?php 
require_once('../database/Database.php');
$db = new Database();
$userRole = isset($_SESSION['user_role']) ? strtolower($_SESSION['user_role']) : 'admin';

$date = isset($_POST['date']) ? $_POST['date'] : '';

if(empty($date)){
    $sql = "SELECT ps.*, u.user_fullname 
            FROM product_sales ps 
            LEFT JOIN user u ON ps.processed_by = u.user_id 
            ORDER BY ps.sale_date DESC";
    $rows = $db->getRows($sql);
} else {
    $sql = "SELECT ps.*, u.user_fullname 
            FROM product_sales ps 
            LEFT JOIN user u ON ps.processed_by = u.user_id 
            WHERE DATE(ps.sale_date) = ? 
            ORDER BY ps.sale_date DESC";
    $rows = $db->getRows($sql, [$date]);
}

$total = 0;
foreach($rows as $r) { $total += $r['sale_total']; }
?>

<style>
    #myTable-txn th, #myTable-txn td {
        padding: 12px 16px !important;
        vertical-align: middle !important;
    }
    #myTable-txn th {
        color: #94a3b8;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    .text-center { text-align: center !important; }
    .text-right { text-align: right !important; }
    .text-left { text-align: left !important; }
</style>

<div id="txn-summary" style="margin-bottom:12px; background:rgba(99,102,241,0.08); border:1px solid rgba(99,102,241,0.15); border-radius:8px; padding:10px 16px; color:#818cf8; font-size:13.5px;">
    <?php if(empty($date)): ?>
        Viewing: <strong>All Transactions</strong> &middot; Total: <strong style="color:#10b981;">₱<?= number_format($total, 2); ?></strong> &middot; <strong><?= count($rows); ?></strong> record(s)
    <?php else: ?>
        Viewing: <strong><?= $date; ?></strong> &middot; Total: <strong style="color:#10b981;">₱<?= number_format($total, 2); ?></strong> &middot; <strong><?= count($rows); ?></strong> record(s)
    <?php endif; ?>
</div>
<div class="table-responsive">
    <table id="myTable-txn" class="table table-bordered table-hover">
        <thead>
            <tr>
                <?php if($userRole == 'admin'): ?>
                <th style="width:40px;" class="text-center"><input type="checkbox" id="select-all-txn"></th>
                <?php endif; ?>
                <th>Reference</th>
                <th>Customer</th>
                <th class="text-center">Payment</th>
                <th class="text-right">Amount</th>
                <th class="text-center">Date</th>
                <th class="text-center">Processed By</th>
                <th style="width:140px;" class="text-center">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($rows as $r): ?>
            <tr>
                <?php if($userRole == 'admin'): ?>
                <td class="text-center"><input type="checkbox" class="select-txn" value="<?= $r['sale_id']; ?>" data-amount="<?= $r['sale_total']; ?>"></td>
                <?php endif; ?>
                <td><span style="font-family:monospace; color:#818cf8; font-weight:600; font-size:12px;"><?= $r['sale_reference']; ?></span></td>
                <td style="font-weight:500;"><?= htmlspecialchars($r['sale_customer_name']); ?></td>
                <td class="text-center"><span style="background:rgba(99,102,241,0.15);color:#818cf8;padding:4px 10px;border-radius:20px;font-size:11px;font-weight:600;"><?= $r['sale_payment_method']; ?></span></td>
                <td class="text-right"><span style="font-weight:600; color:#10b981;">₱<?= number_format($r['sale_total'], 2); ?></span></td>
                <td class="text-center"><span style="font-size:12px; color:#94a3b8;"><?= date('M d, Y h:i A', strtotime($r['sale_date'])); ?></span></td>
                <td class="text-center"><span style="font-size:12px; color:#94a3b8;">
                    <?php 
                        $processedBy = $r['user_fullname'] ?? 'System';
                        echo ($processedBy == 'Staff Member') ? 'Cashier' : $processedBy;
                    ?>
                </span></td>
                <td class="text-center">
                    <button type="button" class="btn btn-info btn-xs view-txn" data-id="<?= $r['sale_id']; ?>">
                       <i class="fa fa-eye"></i>
                    </button>
                    <button type="button" class="btn btn-primary btn-xs print-txn" data-id="<?= $r['sale_id']; ?>">
                       <i class="fa fa-print"></i>
                    </button>
                    <?php if($userRole == 'admin'): ?>
                    <button type="button" class="btn btn-danger btn-xs delete-txn" data-id="<?= $r['sale_id']; ?>">
                       <i class="fa fa-trash"></i>
                    </button>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="<?= ($userRole == 'admin') ? 3 : 2; ?>"></td>
                <td align="right"><strong style="color:#94a3b8;">TOTAL:</strong></td>
                <td class="text-right"><strong style="color:#10b981; font-size:15px;">₱<?= number_format($total, 2); ?></strong></td>
                <td colspan="3"></td>
            </tr>
        </tfoot>
    </table>
</div>
<?php $db->Disconnect(); ?>
