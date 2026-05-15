<?php 
require_once('../class/Sales.php');
if(isset($_POST['date'])){
	$date = $_POST['date'];

	$reports = $sales->daily_sales($date);
?>
<?php 
    $total = 0;
    foreach($reports as $r) {
        $total += $r['sale_amount'];
    }
?>
<br />
<div id="selected-summary" style="margin-bottom:10px; <?= empty($date) ? '' : 'display:none;' ?>">
    <?php if(empty($date)): ?>
        Viewing: <strong>All Recorded Sales</strong> &middot; Total: <strong style="color:#818cf8;">₱ <?= number_format($total, 2); ?></strong>
    <?php else: ?>
        Selected <strong id="selected-count">0</strong> receipt(s) &middot; Total: <strong id="selected-total">₱ 0.00</strong>
    <?php endif; ?>
</div>
<div class="table-responsive">
        <table id="myTable-report" class="table table-bordered table-hover table-striped">
            <thead>
                <tr>
                    <th style="width:40px;"><input type="checkbox" id="select-all-report"></th>
                    <th>Customer Name</th>
                    <th><center>Type</center></th>
                    <th><center>Laundry Received</center></th>
                    <th><center>Date Paid</center></th>
                    <th><center>Amount</center></th>
                    <th style="width:100px;"><center>Action</center></th>
                </tr>
            </thead>
            <tbody>
            	<?php 
				foreach($reports as $r): 
			?>
                <tr align="center">
                    <td><input type="checkbox" class="select-report" value="<?= $r['sale_id']; ?>" data-amount="<?= $r['sale_amount']; ?>"></td>
                    <td align="left" style="font-weight:500;"><?= $r['sale_customer_name']; ?></td>
                    <td><span style="background:rgba(99,102,241,0.15); color:#818cf8; padding:3px 10px; border-radius:20px; font-size:12px; font-weight:600;"><?= $r['sale_type_desc']; ?></span></td>
                    <td style="font-size:12.5px; color:#94a3b8;"><?= $r['sale_laundry_received']; ?></td>
                    <td style="font-size:12.5px; color:#94a3b8;"><?= $r['sale_date_paid']; ?></td>
                    <td style="font-weight:600; color:#818cf8;"><?= '₱ '.number_format($r['sale_amount'], 2); ?></td>
                    <td>
                        <button type="button" class="btn btn-info btn-xs print-receipt" data-sale-id="<?= $r['sale_id']; ?>">
                           <i class="fa fa-print"></i> Print
                        </button>
                        <button type="button" class="btn btn-danger btn-xs delete-sale" data-sale-id="<?= $r['sale_id']; ?>">
                           <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
	            <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4"></td>
                    <td align="right"><strong style="color:#94a3b8;">TOTAL:</strong></td>
                    <td align="center"><strong style="color:#818cf8; font-size:15px;"><?= '₱ '.number_format($total,2); ?></strong></td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
</div>


<!-- for the datatable of employee -->
<script type="text/javascript">
    window.selectedReportIds = [];
    $(document).ready(function() {
        var table = $('#myTable-report').DataTable({
            columnDefs: [
                { orderable: false, targets: [0] }
            ]
        });

        function updateSelectedReport() {
            var selectedCount = 0;
            var selectedTotal = 0;
            var selectedIds = [];
            $('#myTable-report tbody input.select-report:checked').each(function() {
                selectedCount++;
                selectedTotal += parseFloat($(this).data('amount')) || 0;
                selectedIds.push($(this).val());
            });

            window.selectedReportIds = selectedIds;

            if(selectedCount > 0) {
                $('#selected-summary').show();
            } else {
                $('#selected-summary').hide();
            }

            $('#selected-count').text(selectedCount);
            $('#selected-total').text('₱ ' + selectedTotal.toFixed(2));
        }

        $('#select-all-report').on('change', function(){
            var checked = $(this).prop('checked');
            $('#myTable-report tbody input.select-report').prop('checked', checked);
            updateSelectedReport();
        });

        $(document).on('change', '#myTable-report tbody input.select-report', function(){
            var allBoxes = $('#myTable-report tbody input.select-report');
            var checkedBoxes = $('#myTable-report tbody input.select-report:checked');
            $('#select-all-report').prop('checked', allBoxes.length === checkedBoxes.length);
            updateSelectedReport();
        });

        updateSelectedReport();
    });
</script>



<?php
}//end isset


