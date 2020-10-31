(function ($) {
  $(document).ready(function () {
    $('form.ppw-post-password-form.post-password-form').bind('submit', handleSubmitBtn);
  });

  function handleSubmitBtn(evt) {
	evt.preventDefault();
	$form = $(this);
	var post_id = $form.find('input[name="post_id"]').val();
	var password = $form.find('input[name="post_password"]').val();
	$submitBtn = $form.find('input[type=submit]');
	$submitBtn.prop("disabled", true);
	sendRequestToValidatePassword(
	  {
		post_id,
		password
	  },
	  function(data, error) {
		$submitBtn.prop("disabled", false);
		var $message = $form.find('div.ppw-ppf-error-msg');
		if (error) {
		  if ($message.length === 0) {
			var message = error.responseJSON && error.responseJSON.message ? error.responseJSON.message : 'Please enter the correct password!';
			$form.append('<div class="ppwp-wrong-pw-error ppw-ppf-error-msg">' + message + '</div>');
		  }

		  return;
		}

		$message.remove();
		$form.parent().replaceWith(data.post_content)
	  }
	);
  }

  function sendRequestToValidatePassword(_data, cb) {
	$.ajax({
	  beforeSend: function (xhrObj) {
		xhrObj.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		xhrObj.setRequestHeader("X-WP-Nonce", ppw_data.nonce);
	  },
	  url: ppw_data.restUrl + 'wppp/v1/validate-password',
	  type: 'POST',
	  data: _data,
	  success: function (data) {
		cb(data, null);
	  },
	  error: function (error) {
		cb(null, error);
	  },
	  timeout: 10000
	})
  }
})(jQuery);
