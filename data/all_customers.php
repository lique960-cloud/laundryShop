<?php
require_once(__DIR__ . '/../database/Database.php');
$db = new Database();

try {
    $customers = $db->getRows("SELECT cust_id, cust_fullname, cust_email, cust_mobile, cust_address, cust_created_at FROM customers ORDER BY cust_created_at DESC");
    
    if($customers && count($customers) > 0){
?>
<table id="customer-table" class="table table-striped table-hover">
    <thead>
        <tr>
            <th>#</th>
            <th>Customer Name</th>
            <th>Email</th>
            <th>Contact Number</th>
            <th>Address</th>
            <th>Registered</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
    <?php 
    $cnt = 1;
    foreach($customers as $row): 
    ?>
        <tr>
            <td><?= $cnt++; ?></td>
            <td>
                <span style="font-weight:600; color:#e2e8f0;"><?= htmlspecialchars($row['cust_fullname']); ?></span>
            </td>
            <td><?= htmlspecialchars($row['cust_email']); ?></td>
            <td><?= htmlspecialchars($row['cust_mobile']); ?></td>
            <td>
                <span style="font-size:12.5px; color:#94a3b8; max-width:200px; display:inline-block; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;" title="<?= htmlspecialchars($row['cust_address'] ?? ''); ?>">
                    <?= htmlspecialchars($row['cust_address'] ?? 'N/A'); ?>
                </span>
            </td>
            <td style="font-size:12.5px; color:#94a3b8;">
                <?= date('M d, Y', strtotime($row['cust_created_at'])); ?>
            </td>
            <td>
                <button class="btn btn-xs btn-primary view-customer" 
                    data-id="<?= $row['cust_id']; ?>"
                    data-name="<?= htmlspecialchars($row['cust_fullname']); ?>"
                    data-email="<?= htmlspecialchars($row['cust_email']); ?>"
                    data-mobile="<?= htmlspecialchars($row['cust_mobile']); ?>"
                    data-address="<?= htmlspecialchars($row['cust_address'] ?? ''); ?>"
                    data-date="<?= date('F d, Y g:i A', strtotime($row['cust_created_at'])); ?>"
                    title="View Details">
                    <i class="fa fa-eye"></i>
                </button>
                <button class="btn btn-xs btn-danger delete-customer" data-id="<?= $row['cust_id']; ?>" data-name="<?= htmlspecialchars($row['cust_fullname']); ?>" title="Delete">
                    <i class="fa fa-trash"></i>
                </button>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<script>
$(function(){
    if($.fn.DataTable){
        $('#customer-table').DataTable({
            "order": [],
            "pageLength": 10,
            "language": {
                "emptyTable": "No customers found",
                "zeroRecords": "No matching customers found"
            }
        });
    }
});
</script>
<?php
    } else {
?>
<div style="text-align:center; padding:40px 20px; color:#64748b;">
    <div style="font-size:48px; margin-bottom:12px;">👥</div>
    <p style="font-size:15px; font-weight:500; margin-bottom:4px;">No customers yet</p>
    <p style="font-size:13px;">Customer accounts will appear here once they register.</p>
</div>
<?php
    }
} catch (Exception $e) {
    echo '<div style="padding:20px; color:#ef4444;">Error: ' . htmlspecialchars($e->getMessage()) . '</div>';
}

$db->Disconnect();
?>
