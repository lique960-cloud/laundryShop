var valid = true;
function eMsg(message){
	alert('Error: L'+message+'+');
}//end eMsg

// ============================================
// SALES REPORT
// ============================================

//date choice for report
$('#dailySale').change(function (e) {
	e.preventDefault();
	var date = $('#dailySale').val();
	$('#reportActions').show();

	$.ajax({
		url: 'data/daily_report.php',
		type: 'post',
		data: {
			date:date
		},
		success: function (data) {
			$('#table-sales').html(data);
		},
		error: function(){
			eMsg(330);
		}
	});	
});

function loadSale(){
	var date = $('#dailySale').val();
	$('#reportActions').show();
	$.ajax({
			url: 'data/daily_report.php',
			type: 'post',
			data: {
				date:date
			},
			success: function (data) {
				$('#table-sales').html(data);
			},
			error: function(){
				eMsg(348);
			}
		});
}//end loadSale
loadSale();

//delete sale from report
$(document).on('click', '.delete-sale', function(event){
	event.preventDefault();
	var saleId = $(this).attr('data-sale-id');
	if(!saleId){
		return;
	}
	$('#confirm-type').val('delete-sale');
	$('#confirm-id').val(saleId);
	$('#modal-confirm').modal('show');
});

// Bulk delete sales from report
$(document).on('click', '#delSelectedSales', function(event) {
	event.preventDefault();
	var selectedIds = $('#myTable-report tbody input.select-report:checked').map(function() {
		return $(this).val();
	}).get();

	if(selectedIds.length === 0){
		alert('Please select the record(s) that you want to delete.');
		return;
	}

	$('#confirm-type').val('delete-sale-bulk');
	$('#modal-confirm').modal('show');
});

$('#print-button').click(function(event) {
	var date = $('#dailySale').val();
	var ids = Array.isArray(window.selectedReportIds) ? window.selectedReportIds : [];

	if(ids.length === 0){
		alert('Please select at least one receipt before printing.');
		return;
	}

	var url = 'data/print.php?date=' + encodeURIComponent(date);
	url += '&ids=' + encodeURIComponent(ids.join(','));
	window.open(url, '_blank', 'width=600,height=400');
});

$(document).on('click', '.print-receipt', function(event) {
	event.preventDefault();
	var saleId = $(this).attr('data-sale-id');
	window.open('data/print_receipt.php?id=' + saleId, '_blank', 'width=400,height=600');
});

// ============================================
// CONFIRM DIALOG HANDLER
// ============================================

$(document).on('click', '#confirm-yes', function(event) {
	event.preventDefault();
	var confirmType = $('#confirm-type').val();
	var requests = [];

	if(confirmType == 'delete-sale'){
		var id = $('#confirm-id').val();
		if(id){
			requests.push($.ajax({
				url: 'data/delete_transaction.php',
				type: 'post',
				dataType: 'json',
				data: { id: id }
			}));
		}
	}else if(confirmType == 'delete-sale-bulk'){
		var selectedSaleIds = $('#myTable-report tbody input.select-report:checked').map(function() {
			return $(this).val();
		}).get();
		selectedSaleIds.forEach(function(id) {
			requests.push($.ajax({
				url: 'data/delete_transaction.php',
				type: 'post',
				dataType: 'json',
				data: { id: id }
			}));
		});
	}else if(confirmType == 'delete-transaction'){
		var id = $('#confirm-id').val();
		if(id){
			requests.push($.ajax({
				url: 'data/delete_transaction.php',
				type: 'post',
				dataType: 'json',
				data: { id: id }
			}));
		}
	}else if(confirmType == 'delete-transaction-bulk'){
		var selectedTxnIds = $('#table-transactions input.select-txn:checked').map(function() {
			return $(this).val();
		}).get();
		selectedTxnIds.forEach(function(id) {
			requests.push($.ajax({
				url: 'data/delete_transaction.php',
				type: 'post',
				dataType: 'json',
				data: { id: id }
			}));
		});
	}else if(confirmType == 'delete-inventory'){
		var selectedInvIds = $('#table-inventory input[type=checkbox]:checked').map(function() {
			return $(this).val();
		}).get();
		selectedInvIds.forEach(function(id) {
			requests.push($.ajax({
				url: 'data/delete_inventory.php',
				type: 'post',
				dataType: 'json',
				data: { id: id }
			}));
		});
	}

	if(requests.length > 0){
		$.when.apply($, requests).always(function(){
			$('#modal-confirm').modal('hide');
			if(confirmType == 'delete-sale' || confirmType == 'delete-sale-bulk'){
				$('#modal-msg').find('#msg-body').text('Sales record(s) deleted successfully!');
				$('#modal-msg').modal('show');
				if(typeof loadSale === 'function'){
					loadSale();
				}
			} else if(confirmType == 'delete-transaction' || confirmType == 'delete-transaction-bulk'){
				$('#modal-msg').find('#msg-body').text('Transaction(s) deleted successfully!');
				$('#modal-msg').modal('show');
				if(typeof loadTransactions === 'function'){
					loadTransactions();
				}
			} else if(confirmType == 'delete-inventory'){
				$('#modal-msg').find('#msg-body').text('Inventory item(s) deleted successfully!');
				$('#modal-msg').modal('show');
				if(typeof all_inventory === 'function'){
					all_inventory();
				}
			}
		});
	}
});

// ============================================
// CHANGE PASSWORD
// ============================================

$('#changePass').click(function(event) {
	$('#modal-pass').find('.modal-title').text('Change Password');
	$('#modal-pass').modal('show');
});

$(document).on('submit', '#form-change', function(event) {
	event.preventDefault();
	var pwd = $('#pwd').val();
	var pwd2 = $('#pwd2').val();
	if(pwd != pwd2){
		alert("Password Not Match!");
	}else{
		$.ajax({
				url: 'data/change_pass.php',
				type: 'post',
				dataType: 'json',
				data: {
					pwd:pwd
				},
				success: function (data) {
					if(data.valid == valid){
						$('#modal-pass').modal('hide');
						$('#modal-msg').find('#msg-body').text(data.msg);
						$('#modal-msg').modal('show');
					}
				},
				error: function(){
					eMsg(387);
				}
			});
	}
});

// Toggle password visibility
$(document).on('click', '.toggle-password', function() {
	var target = $(this).data('target');
	var pwInput = $(target);
	var type = pwInput.attr('type') === 'password' ? 'text' : 'password';
	pwInput.attr('type', type);
	$(this).text(type === 'password' ? '👁️' : '🔒');
});