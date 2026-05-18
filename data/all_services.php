<?php 
require_once('../database/Database.php');
$db = new Database();
$services = $db->getRows("SELECT * FROM services ORDER BY service_name ASC");
?>

<style>
    #services-table th, #services-table td {
        padding: 12px 16px !important;
        vertical-align: middle !important;
    }
    #services-table th {
        color: #94a3b8;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    .text-center { text-align: center !important; }
    .text-right { text-align: right !important; }
    .text-left { text-align: left !important; }
    
    /* Style for stacked buttons in action column */
    .action-buttons {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 6px;
    }
    .action-buttons .btn {
        width: 80px;
        justify-content: center;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
</style>

<div class="table-responsive">
    <table id="services-table" class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>Service Name</th>
                <th class="text-right">Price</th>
                <th style="width:120px;" class="text-center">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($services as $s): ?>
            <tr>
                <td style="font-weight:500;"><?= htmlspecialchars($s['service_name']); ?></td>
                <td class="text-right" style="font-weight:600; color:#10b981;">₱<?= number_format($s['price'], 2); ?></td>
                <td class="text-center">
                    <div class="action-buttons">
                        <button type="button" class="btn btn-warning btn-xs" onclick="editService('<?= $s['id']; ?>', '<?= htmlspecialchars(addslashes($s['service_name'])); ?>', '<?= $s['price']; ?>')">
                            <i class="fa fa-edit"></i> Edit
                        </button>
                        <button type="button" class="btn btn-danger btn-xs" onclick="deleteService('<?= $s['id']; ?>')">
                            <i class="fa fa-trash"></i> Delete
                        </button>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if(empty($services)): ?>
            <tr><td colspan="3" class="text-center" style="padding:20px; color:#64748b;">No services added yet.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php $db->Disconnect(); ?>
