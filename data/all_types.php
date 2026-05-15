<br />
<?php 
require_once('../class/Laundry.php');
$types = $laundry->get_all_laundry();
 ?>

<div class="table-responsive">
        <table id="myTable-type" class="table table-bordered table-hover table-striped">
            <thead>
                <tr>
                    <th style="width:40px;"><center>Select</center></th>
                    <th>Laundry Type Description</th>
                    <th><center>Price / Kg</center></th>
                    <th style="width:100px;"><center>Action</center></th>
                </tr>
            </thead>
            <tbody>
            	<?php foreach($types as $t): ?>
	                <tr align="center">
                        <td><input type="checkbox" name="typeCheck" value="<?= $t['laun_type_id']; ?>"></td>
	                    <td align="left" style="font-weight:500;">
                            <span style="display:inline-flex;align-items:center;gap:8px;">
                                <span style="width:8px;height:8px;border-radius:50%;background:#f59e0b;display:inline-block;"></span>
                                <?= $t['laun_type_desc']; ?>
                            </span>
                        </td>
	                    <td style="font-weight:600; color:#10b981;"><?= '₱ '.number_format($t['laun_type_price'], 2); ?></td>
	                    <td>
                            <button onclick="editType('<?= $t['laun_type_id']; ?>');" type="button" class="btn btn-warning btn-xs">
                                <i class="fa fa-edit"></i> Edit
                            </button>   
                        </td>
	                </tr>
	            <?php endforeach; ?>
            </tbody>
        </table>
</div>


<!-- for the datatable of employee -->
<script type="text/javascript">
    $(document).ready(function() {
        $('#myTable-type').DataTable();
    });
</script>

<?php $laundry->Disconnect(); ?>