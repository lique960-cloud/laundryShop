var valid = true;
function eMsg(message){
	alert('Error: L'+message+'+');
}//end eMsg

//laundry type
$('#newType').click(function(event) {
	/* Act on the event */
	$('#type-type').val('insert');
	$('#type').val('');
	$('#price').val('');
	$('#modal-lau-type').find('.modal-title').text('New Laundry Type');
	$('#modal-lau-type').modal('show');
});

//inset new type
$(document).on('submit', '#form-type', function(event) {
	event.preventDefault();
	/* Act on the event */
    var type_id = $('#type-id').val();
    var type = $('#type').val();
    var price = $('#price').val();
    var type_type = $('#type-type').val();
 	if(type_type == 'insert'){
	   $.ajax({
			url: 'data/insert_type.php',
			type: 'post',
			dataType: 'json',
			data: {
				type:type,
				price:price
			},
			success: function (data) {
				// console.log(data);
				if(data.valid == valid){
					$('#modal-lau-type').modal('hide');
					all_type();
					$('#type').val('');
					$('#price').val('');
					$('#modal-msg').find('#msg-body').text(data.msg);
					$('#modal-msg').modal('show');
				}
			},
			error: function(){
				eMsg(26);
			}
		});
 	}else if(type_type == 'edit'){
 		$.ajax({
 				url: 'data/edit_type.php',
 				type: 'post',
 				dataType: 'json',
 				data: {
 					type_id:type_id,
 					type:type,
 					price:price
 				},
 				success: function (data) {
 					// console.log(data);
 					if(data.valid == valid){
 						all_type();
 						$('#modal-lau-type').modal('hide');
 						$('#modal-msg').find('#msg-body').text(data.msg);
 						$('#modal-msg').modal('show');
 					}
 				},
 				error: function(){
 					eMsg(58);
 				}
 			});
 	}else{
 		//where magic begins .wahaha
 	}
});

//delete type
$('#delType').click(function(event) {
	/* Act on the event */
  var haveCheck = $('#table-type input[type=checkbox]:checked').length > 0;

  if(!haveCheck){
	 alert('Please check the row(s) that you want to delete.');
  } else {
	$('#confirm-type').val('delete-type');
	$('#modal-confirm').modal('show');
  }
});

//display type table
function all_type(){
	$.ajax({
			url: 'data/all_types.php',
			type: 'post',
			success: function (data) {
				$('#table-type').html(data);
			},
			error:function(){
				eMsg(45);
			}
		});
}//end all_type
all_type();

//edit type
function editType(type_id){
	$.ajax({
			url: 'data/get_type.php',
			type: 'post',
			dataType: 'json',
			data: {
				type_id:type_id
			},
			success: function (data) {
				// console.log(data);
				$('#type-type').val('edit');
				$('#type-id').val(data.laun_type_id);
				$('#type').val(data.laun_type_desc);
				$('#price').val(data.laun_type_price);
				$('#modal-lau-type').find('.modal-title').text('Edit Laundry Type');
				$('#modal-lau-type').modal('show');
			},
			error: function(){
				eMsg(72);
			}
		});
}//end editType


//all laundry
function all_laundry(){
	$.ajax({
			url: 'data/all_laundry.php',
			type: 'post',
			data: {

			},
			success: function (data) {
				$('#table-laundry').html(data);
			},
			error: function(){
				eMsg(128);
			}
		});
}//end all_laundry
all_laundry();

//open modal
$('#newLaun').click(function(event) {
	/* Act on the event */
	$('#laun-type').val('insert');
	$('#modal-laun').find('.modal-title').text('New Laundry');
	$('#modal-laun').modal('show');
});

$(document).on('submit', '#form-new-laun', function(event) {
	event.preventDefault();
	/* Act on the event */
	var modal_type = $('#laun-type').val();//insert/update
	var laun_id = $('#laun-id').val();//pk
	var customer = $('#customer').val();
	var priority = $('#priority').val();
	var weight = $('#weight').val();
	var type = $('#newlaun-type').val();
	if(modal_type == 'insert'){
		$.ajax({
				url: 'data/insert_laundry.php',
				type: 'post',
				dataType: 'json',
				data: {
					customer:customer,
					priority:priority,
					weight:weight,
					type:type
				},
				success: function (data) {
					// console.log(data);
					all_laundry();
					$('#modal-laun').modal('hide');
				},
				error: function(){
					eMsg(163);
				}
			});
	}else if(modal_type == 'edit'){
		$.ajax({
				url: 'data/edit_laundry.php',
				type: 'post',
				dataType: 'json',
				data: {
					customer:customer,
					priority:priority,
					weight:weight,
					type:type,
					laun_id: laun_id
				},
				success: function (data) {
					// console.log(data);
					if(data.valid == valid){
						all_laundry();
						$('#modal-laun').modal('hide');
						$('#modal-msg').find('#msg-body').text(data.msg);
						$('#modal-msg').modal('show');
					}
				},
				error: function(){
					eMsg(183);
				}
			});
	}else{
		//where the magic begins .mhuahwahwahwah
		//soo sleepy. programmer sucks
	}

});//end submit form

//delete laundry
$('#delLaun').click(function(event) {
	/* Act on the event */
  var haveCheck = $('#table-laundry input[type=checkbox]:checked').length > 0;

  if(!haveCheck){
	 alert('Please check the row(s) that you want to delete.');
  } else {
	$('#confirm-type').val('delete-laundry');
	$('#modal-confirm').modal('show');
  }
});

$(document).on('click', '.delete-laundry-row', function(event) {
	event.preventDefault();
	var id = $(this).attr('data-laun-id');
	$('#confirm-type').val('delete-laundry-single');
	$('#confirm-id').val(id);
	$('#modal-confirm').modal('show');
});

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

$(document).on('click', '#confirm-yes', function(event) {
	event.preventDefault();
	var confirmType = $('#confirm-type').val();
	var requests = [];
	var selectedIds = $('#table-laundry input[type=checkbox]:checked').map(function() {
		return $(this).val();
	}).get();
	var selectedTypeIds = $('#table-type input[type=checkbox]:checked').map(function() {
		return $(this).val();
	}).get();

	if(confirmType == 'delete-sale'){
		var id = $('#confirm-id').val();
		if(id){
			requests.push($.ajax({
				url: 'data/delete_sale.php',
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
				url: 'data/delete_sale.php',
				type: 'post',
				dataType: 'json',
				data: { id: id }
			}));
		});
	}else if(confirmType == 'delete-laundry'){
		selectedIds.forEach(function(id) {
			requests.push($.ajax({
				url: 'data/delete_laundry.php',
				type: 'post',
				dataType: 'json',
				data: { id: id }
			}));
		});
	}else if(confirmType == 'delete-laundry-single'){
		var id = $('#confirm-id').val();
		if(id){
			requests.push($.ajax({
				url: 'data/delete_laundry.php',
				type: 'post',
				dataType: 'json',
				data: { id: id }
			}));
		}
	}else if(confirmType == 'delete-type'){
		selectedTypeIds.forEach(function(id) {
			requests.push($.ajax({
				url: 'data/delete_type.php',
				type: 'post',
				dataType: 'json',
				data: { id: id }
			}));
		});
	}else if(confirmType == 'claim-laundry'){
		selectedIds.forEach(function(id) {
			requests.push($.ajax({
				url: 'data/claim_laundry.php',
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
			} else if(confirmType == 'delete-laundry' || confirmType == 'delete-laundry-single'){
				$('#modal-confirm').modal('hide');
				$('#modal-msg').find('#msg-body').text('Deleted Successfully!');
				$('#modal-msg').modal('show');
				all_laundry();
			} else if(confirmType == 'delete-type'){
				var responses = Array.isArray(arguments[0]) ? arguments : [arguments];
				var success = true;
				var msg = 'Deleted Successfully!';
				
				for(var i=0; i<responses.length; i++){
					var data = responses[i][0];
					if(data && data.valid === false){
						success = false;
						msg = data.msg || 'One or more types could not be deleted.';
						break;
					}
				}
				
				$('#modal-confirm').modal('hide');
				$('#modal-msg').find('#msg-body').text(msg);
				$('#modal-msg').modal('show');
				all_type();
			} else if(confirmType == 'claim-laundry'){
				$('#modal-confirm').modal('hide');
				$('#modal-msg').find('#msg-body').text('Claim and paid Successfully!');
				$('#modal-msg').modal('show');
				all_laundry();
				if(typeof loadSale === 'function'){
					loadSale();
				}
			} else if(confirmType == 'delete-inventory'){
				$('#modal-confirm').modal('hide');
				$('#modal-msg').find('#msg-body').text('Inventory item(s) deleted successfully!');
				$('#modal-msg').modal('show');
				if(typeof all_inventory === 'function'){
					all_inventory();
				}
			}
		});
	}
});

function editLaundry(laun_id){
	$('#laun-type').val('edit');
	//fill
	$.ajax({
			url: 'data/get_laundry.php',
			type: 'post',
			dataType: 'json',
			data: {
				laun_id:laun_id
			},
			success: function (data) {
				// console.log(data);
				$('#laun-id').val(data.laun_id);
				$('#customer').val(data.customer_name);
				$('#priority').val(data.laun_priority);
				$('#weight').val(data.laun_weight);
				$('#newlaun-type').val(data.laun_type_id);
			},
			error: function(){
				eMsg(237);
			}
		});
	$('#modal-laun').find('.modal-title').text('Edit Laundry');
	$('#modal-laun').modal('show');
}//end editLaundry

//claim laundry
$('#claim').click(function(event) {
	/* Act on the event */
  var haveCheck = $('#table-laundry input[type=checkbox]:checked').length > 0;

  if(!haveCheck){
	 alert('Please check the row(s) that you want to claim.');
  } else {
	$('#confirm-type').val('claim-laundry');
	$('#modal-confirm').modal('show');
  }
});

//date choice sa report
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

$('#print-button').click(function(event) {
	/* Act on the event */
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
	var date = $('#dailySale').val();
	var url = 'data/print.php?date=' + encodeURIComponent(date) + '&ids=' + saleId;
	window.open(url, '_blank', 'width=600,height=400');
});

$('#changePass').click(function(event) {
	/* Act on the event */
	$('#modal-pass').find('.modal-title').text('Change Password');
	$('#modal-pass').modal('show');
});

$(document).on('submit', '#form-change', function(event) {
	event.preventDefault();
	/* Act on the event */
	var pwd = $('#pwd').val();
	var pwd2 = $('#pwd2').val();
	if(pwd != pwd2){
		alert("Password Not Match!");
	}else{
		//pass is match
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