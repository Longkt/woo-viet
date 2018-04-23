(function($) {
	//var cities = woo_viet_cities.cities;
	//console.log( woo_viet_cities );


	/*$('._billing_state_field').on('change', function() {

		var order_billing_state_val = $(this).find('#_billing_state').val();

		var order_billing_city = $('#_billing_city');

		var order_billing_city_val = order_billing_city.val();

		order_billing_city.empty();

		$.each(woo_viet_cities, function(index, value) {

			if(index == order_billing_state_val) {
				$.each(value, function(index2, value2) {
					var selected = '';
					var html = '';
					if(order_billing_city_val == value2) {
						selected = 'selected';
					}

					html += '<option selected="' + selected + '" value="' + value2 + '">' + value2 + '</option>';
					order_billing_city.append(html);
					console.log(value2);
				})
			}

		});

	});*/

	$('._billing_state_field').on('change', function() {

		var order_billing_state_val = $(this).find('#_billing_state').val();

		$.ajax({
			url: ajaxurl,
			type: 'POST',
			data: {
				action: 'add_select_cities',
				param: order_billing_state_val,
			}
		}).done(function(response){
			console.log(response);
			//$( '.js_field-country' ).trigger( 'change', [ true ] );
		});		

	});
	
})(jQuery)