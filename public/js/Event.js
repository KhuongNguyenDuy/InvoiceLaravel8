$(document).ready(function(){
	//get project of customer
	$('.customer-option').change(function(){
        var idc = $(this).val();                            
        $.ajax({
                type:'get',
                url:'/get-info-customer',
                data:{id:idc},
                success:function(data){
                    if(data.success == true){               
						$('#project').empty();
						$('#estimate').empty();
						$('#order').empty();												
						$('#project').append($('<option>').val("").text("Chọn project...").attr("selected","selected").attr("disabled","disabled"));
						$('#estimate').append($('<option>').val("").text("Chọn estimate...").attr("selected","selected").attr("disabled","disabled"));
						$('#order').append($('<option>').val("").text("Chọn order...").attr("selected","selected").attr("disabled","disabled"));
						//$('#table_tr').html("");
						//reset_total();
						for (let i = 0; i < data.projects.length; i++) {
							$('#project').append($('<option>').val(data.projects[i].id).text(data.projects[i].name));			
						}
                     }                                                                                
                    else{
                        alert("Fail");
                     }                                        
                }
            });
    });
	//get item when change project
	$('#project').change(function(){
	 	var projectid = $(this).val();                       
	 	$.ajax({
	 		type:'get',
	 		url:'/ajax-get-item',
	 		data:{id:projectid},
	 		success:function(data){
				//if(data.data == ""){
				//	reset_total();
				//}			
	 			//$('#table_tr').html(data.data);
				$('#estimate').empty();
				$('#order').empty();
				$('#estimate').append($('<option>').val("").text("Chọn estimate...").attr("selected","selected").attr("disabled","disabled"));
				$('#order').append($('<option>').val("").text("Chọn order...").attr("selected","selected").attr("disabled","disabled"));
				if(data.estimates.length > 0){
					for (let i = 0; i < data.estimates.length; i++) {
						$('#estimate').append($('<option>').val(data.estimates[i].id).text(data.estimates[i].name));			
					}
				}
				if(data.estimates.length > 0){
					for (let i = 0; i < data.orders.length; i++) {
						$('#order').append($('<option>').val(data.orders[i].id).text(data.orders[i].name));			
					}   
				}				         
	 		}
	 	});
	});
	// add new row or delete row
	var i=1;
    $("#add_row").click(function(){
		b=i-1;
      	$('#addr'+i).html($('#addr'+b).html()).find('td:first-child').html(i+1);
      	$('#tab_logic').append('<tr id="addr'+(i+1)+'"></tr>');
      	i++; 
  	});
    $("#delete_row").click(function(){
    	if(i>1){
		$("#addr"+(i-1)).html('');
		i--;
		}
		calc();
	});	
	$('#tab_logic tbody').on('keyup change',function(){		
		calc();
	});
	$('#tax').on('keyup change',function(){
		calc_total();
	});
	/**
	 * change project -> load item of project
	 */
	// $('#project').change(function(){
	// 	var projectid = $(this).val();
	// 	location.href = '/get-item'+projectid;
	// });
	/**
	 * js keyup change add to cart
	 */
	// $('select').selectize({
	// 	sortField: 'text'
	// });

	//event when DO
	// $("#table_tr").bind("DOMSubtreeModified", function() {
	// 	 (function(i, element) {
	// 		var html = $(this).html();
	// 		if(html!=''){
	// 			var qty = $(this).find('.qty').val();
	// 			var price = parseInt($(this).find('.price').val().replace(new RegExp(',', 'g'),""));
	// 			var total_item = qty*price;
	// 			$(this).find('.total').val(numberFormat(total_item,0,"",","));
	// 			calc_total();
	// 		}
	// 	});
	// });

	// $('#tab_logic tbody').on('keyup change',function(){
	// 	$('#tab_logic tbody tr').each(function(i, element) {
	// 		var html = $(this).html();
	// 		if(html!=''){
	// 			var qty = $(this).find('.qty').val();
	// 			var price = parseInt($(this).find('.price').val().replace(new RegExp(',', 'g'),""));
	// 			var total_item = qty*price;
	// 			$(this).find('.total').val(numberFormat(total_item,0,"",","));
	// 			calc_total();
	// 		}
	// 	});
	// });

	/**
	 * function get total invoice
	 */
	// function calc_total(){
	// 	total=0;
	// 	$('.total').each(function() {
	// 		if($(this).val() == 0 || $(this).val() == ""){
	// 		}else{
	// 			total += parseInt($(this).val().replace(new RegExp(',', 'g'),""));
	// 		}
	// 	});
	// 	$('#sub_total').val(numberFormat(total,0,"",","));
	// 	tax_sum=total/100*$('#tax').val();
	// 	$('#tax_amount').val(numberFormat(Math.round(tax_sum),0,"",","));
	// 	$('#total_amount').val(numberFormat(Math.round(tax_sum+total),0,"",","));
	// }
	//function number format 
	/**
	 * input: int 123456
	 * output: string 123,456
	 */
});
function numberFormatCommas(number) {
    return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}
function numberFormat(number, decimals, dec_point, thousands_point) {
	if (number == null || !isFinite(number)) {
		throw new TypeError("Số không hợp lệ");
	}

	if (!decimals) {
		var len = number.toString().split('.').length;
		decimals = len > 1 ? len : 0;
	}

	if (!dec_point) {
		dec_point = '.';
	}

	if (!thousands_point) {
		thousands_point = ',';
	}

	number = parseFloat(number).toFixed(decimals);

	number = number.replace(".", dec_point);

	var splitNum = number.split(dec_point);
	splitNum[0] = splitNum[0].replace(/\B(?=(\d{3})+(?!\d))/g, thousands_point);
	number = splitNum.join(dec_point);

	return number;
}

/**
 * reset total
 */
function reset_total(){
	$('#sub_total').val(0);
	$('#tax_amount').val(0);
	$('#tax').val(10);
	$('#total_amount').val(0);
}
function calc(){
	$('#tab_logic tbody tr').each(function(i, element) {			
		var html = $(this).html();
		if(html != ''){
			if($(this).find('.price').val() != ''){				
				var price = parseInt($(this).find('.price').val().replace(new RegExp(',', 'g'),""));
				var valu = numberFormatCommas(price);
				$(this).find('.price').val(valu);		
				var qty = $(this).find('.qty').val();
				var total_item = qty*price;
				$(this).find('.total').val(numberFormat(total_item,0,"",","));
				calc_total();
				//var qty = $(this).find('.qty').val();
				//var price = $(this).find('.price').val();
				//$(this).find('.total').val(qty*price);			
				//calc_total();
			}
		}
    });
}

function calc_total(){
	total=0;
	$('.total').each(function() {
		if($(this).val() == 0 || $(this).val() == ""){
		}else{
			total += parseInt($(this).val().replace(new RegExp(',', 'g'),""));
		}
	});
	$('#sub_total').val(numberFormat(total,0,"",","));
	tax_sum=total/100*$('#tax').val();
	$('#tax_amount').val(numberFormat(Math.round(tax_sum),0,"",","));
	$('#total_amount').val(numberFormat(Math.round(tax_sum+total),0,"",","));
	// $('.total').each(function() {
    //    total += parseInt($(this).val());
    // });
	// $('#sub_total').val(total.toFixed(2));
	// tax_sum=(total/100)*$('#tax').val();
	// $('#tax_amount').val(tax_sum.toFixed(2));
	// $('#total_amount').val((tax_sum+total).toFixed(2));
}

	