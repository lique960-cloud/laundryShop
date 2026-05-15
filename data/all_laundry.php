<br />
<?php 
require_once('../class/Laundry.php');
$laundries = $laundry->all_laundry();
 ?>

<div class="table-responsive">
        <table id="myTable-laundry" class="table table-bordered table-hover table-striped">
            <thead>
                <tr>
                    <th style="width:40px;"><center>Select</center></th>
                    <th>Customer Name</th>
                    <th><center>Priority #</center></th>
                    <th><center>Weight (kg)</center></th>
                    <th><center>Type</center></th>
                    <th><center>Date Received</center></th>
                    <th><center>Amount</center></th>
                    <th style="width:80px;"><center>Action</center></th>
                </tr>
            </thead>
            <tbody>
            	<?php
                    foreach($laundries as $l): 
                    $amount = $l['laun_weight'] * $l['laun_type_price'];
                ?>
                <tr align="center">
                    <td><input type="checkbox" name="imSlepy" value="<?= $l['laun_id']; ?>"></td>
                    <td align="left" style="font-weight:500;"><?= ucwords($l['customer_name']); ?></td>
                    <td><span style="background:rgba(99,102,241,0.15); color:#818cf8; padding:3px 10px; border-radius:20px; font-size:12px; font-weight:600;"><?= $l['laun_priority']; ?></span></td>
                    <td><?= $l['laun_weight']; ?> kg</td>
                    <td><span style="background:rgba(99,102,241,0.15); color:#818cf8; padding:3px 10px; border-radius:20px; font-size:12px; font-weight:600;"><?= $l['laun_type_desc']; ?></span></td>
                    <td style="font-size:12.5px; color:#94a3b8;"><?= $l['laun_date_received']; ?></td>
                    <td style="font-weight:600; color:#818cf8;"><?= '₱ '.number_format($amount, 2); ?></td>
                    <td>
                        <button onclick="editLaundry('<?= $l['laun_id']; ?>')" type="button" class="btn btn-warning btn-xs">
                           <i class="fa fa-edit"></i> Edit
                        </button>
                        <button type="button" class="btn btn-danger btn-xs delete-laundry-row" data-laun-id="<?= $l['laun_id']; ?>">
                           <i class="fa fa-trash"></i>
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
        $('#myTable-laundry').DataTable();
    });
</script>

<?php $laundry->Disconnect(); ?>