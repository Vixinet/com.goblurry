$(document).ready(function(){
	$('.video').tubular({
		videoId: 'edVKTpKCiZY'
	});
	$('form').on('submit', evFormSubmit);
});

var evFormSubmit = function(event) {
	event.preventDefault();
	console.log($(this).find('.email').val());
	console.log($(this));
	$.ajax({
		url: "register.php",
		method: 'POST',
		context:this,
		data: { 
			email: $(this).find('.email').val(),
			phone: $(this).find('.phone').val()
		}
	})
	.done(function(data) {
		if(data < 10) {
			$('.modal .modal-body').text('An error occurred, please try later.');
		} else if(data == 200) {
			$(this).find('.email').val('');
			$(this).find('.phone').val('');
			$('.modal .modal-body').html('<strong>Thank you</strong> for your subscription. As soon as Blurry is released, we send you an email or a text message. ');
		} else if(data == 10) {
			$('.modal .modal-body').html('<strong>Oops...</strong> It seems your email contains an error. Please check.');
		}else if(data == 11) {
			$('.modal .modal-body').html('<strong>Oops...</strong> You didn\'t fill any information. We need at least your email or your phone number.');
		}
		$('.modal').modal('show');
	});
};