(function($) {
	/*$('#billing_city_field').on('change', function() {
		// Find value customer choose
		var customer_city = $(this).find('#select2-billing_city-container').attr('title');

		if(customer_city != '') {
			$.ajax({
				url: woovietAjax.ajaxurl,
				type: 'POST',
				data: {
					action: 'get_customer_city_choose',
					customer_city: customer_city,
				},
			})
			.done(function(response) {
				$('body').trigger('update_checkout');
				console.log(response);
			});
			
		}
	})*/

	$('body').on('change', function() {
		var country = $(this).find('#select2-billing_country-container').attr('title');
		console.log(country);

		if(country == 'Vietnam') {
			$('.woocommerce-billing-fields__field-wrapper').addClass('active');
		} else {
			$('.woocommerce-billing-fields__field-wrapper').removeClass('active');
		}
	})
})(jQuery)