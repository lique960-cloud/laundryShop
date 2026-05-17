<?php 
require_once('../database/Database.php');
$db = new Database();
$services = $db->getRows("SELECT * FROM services ORDER BY service_name ASC");
?>
<div class="table-responsive">
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>Service Name</th>
                <th>Price</th>
                <th style="width:150px;"><center>Action</center></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($services as $s): ?>
            <tr>
                <td style="font-weight:500;"><?= htmlspecialchars($s['service_name']); ?></td>
                <td style="font-weight:600; color:#10b981;">₱<?= number_format($s['price'], 2); ?></td>
                <td>
                    <center>
                        <button type="button" class="btn btn-warning btn-xs" onclick="editService('<?= $s['id']; ?>', '<?= htmlspecialchars(addslashes($s['service_name'])); ?>', '<?= $s['price']; ?>')">
                            <i class="fa fa-edit"></i> Edit
                        </button>
                        <button type="button" class="btn btn-danger btn-xs" onclick="deleteService('<?= $s['id']; ?>')">
                            <i class="fa fa-trash"></i> Delete
                        </button>
                    </center>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if(empty($services)): ?>
            <tr><td colspan="3" style="text-align:center; padding:20px; color:#64748b;">No services added yet.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php $db->Disconnect(); ?>
