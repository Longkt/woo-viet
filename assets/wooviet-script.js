(function($) {
	$('#billing_city_field').on('change', function() {
		// Find value customer choose
		$customer_city = $(this).find('#select2-billing_city-container').attr('title');
		console.log($customer_city);

		if($customer_city != '') {
			$.ajax({
				url: woovietAjax.ajaxurl,
				type: 'POST',
				data: {
					action: 'get_customer_city_choose',
					customer_city: $customer_city,
				},
			})
			.done(function(response) {
				console.log(response);
			});
			
		}
	})
})(jQuery)