(function (window, $) {

	$(function () {
		setDefaultPassword();
		handleChangeElement();

		$("#save_password").click(function (evt) {
			evt.preventDefault();
			const passwords = $('.ppwp_multiple_password').val().split("\n").map(pass => pass.trim()).filter(pass => pass !== "");
			const rolePassword = $(".post-protection-password").val().trim();
			const roleSelected = $("#is_role_selected").val();
			if (isErrorPassword(passwords)) {
				return;
			}

			savePassword({
				save_password: rolePassword,
				is_role_selected: roleSelected,
				id_page_post: $("#id_page_post").val(),
				ppwp_multiple_password: passwords.length <= 0 ? '' : passwords,
			}, function (result, error) {
				$('#save_password').text('Submit');
				$('#save_password').prop("disabled", false);
				const pluginName = 'Password Protect WordPress';
				if (result) {
					changeValueRoles(result);
					if (roleSelected === 'global') {
						$('#ppwp_multiple_password').val(passwords.join('\n'));
					} else {
						$('#post-protection-password').val(rolePassword);
					}
					toastr.success('Passwords updated successfully!', pluginName);
				}

				if (error) {
					if (400 === error.status) {
						toastr.error(error.responseJSON.message, pluginName);
					} else {
						toastr.error('Passwords update fail!', pluginName);
					}
					console.log('Data error', error);
				}
			});
		});
	});

	function checkPasswordNoSpace(password) {
		return password.indexOf(" ") === -1
	}

	function handleChangeElement() {
		$("#post-protection-password").change(function () {
			if ($(this).val().trim().indexOf(" ") !== -1) {
				toastr.error(save_password_data.error_message.space_password, 'Password Protect WordPress');
				$('#save_password').prop("disabled", true);
			} else {
				$('#save_password').prop("disabled", false);
			}
		});

		$(".ppwp_multiple_password").change(function () {
			const passwords = $(this).val().split("\n").map(pass => pass.trim()).filter(pass => pass !== "");

			if ((new Set(passwords)).size !== passwords.length) {
				toastr.error(save_password_data.error_message.duplicate_password, 'Password Protect WordPress');
				$('#save_password').prop("disabled", true);
				return;
			} else {
				$('#save_password').prop("disabled", false);
			}

			if (!passwords.every(checkPasswordNoSpace)) {
				toastr.error(save_password_data.error_message.space_password, 'Password Protect WordPress');
				$('#save_password').prop("disabled", true);
				return;
			} else {
				$('#save_password').prop("disabled", false);
			}
		});

		let arrayPassword = {};
		$('.pda-selected-role-select2').on('focus', function () {
			let role = this.value;
			// Add Value to MAP
			arrayPassword[role] = $('#post-protection-password').val();
			$('#' + role).val($('#post-protection-password').val());
		}).change(function () {
			let role = $('.pda-selected-role-select2').val();

			let value;
			if (typeof (arrayPassword[role]) !== 'undefined') {
				value = arrayPassword[role];
			} else {
				value = $('#post-protection-password-' + role).text() === "" ? "" : $('#post-protection-password-' + role).text();
			}

			if (role === 'global') {
				$('#post-protection-password').hide();
				$('#ppwp_multiple_password').show();
			} else {
				$('#post-protection-password').show();
				$('#ppwp_multiple_password').hide();
			}

			$('#post-protection-password').val(value);

			if ($("#is_role_selected").val() === 'global') {
				$("#label-password-post").text("Passwords")
			} else {
				$("#label-password-post").text("Password")
			}
		});
		$('.pda-selected-role-select2').trigger('change');


		$('.edit-post-protection').click(function () {
			$('#post-protection').show();
			$(this).hide();
		});

		$('.button-cancel').click(function () {
			$('#post-protection').hide();
			$('.edit-post-protection').show();
		});
	}

	function setDefaultPassword() {
		const role = $('.pda-selected-role-select2').val();
		if (role) {
			const value = $('#post-protection-password-' + role).text() === "" ? "" : $('#post-protection-password-' + role).text();
			$('#post-protection-password').val(value);
		}
	}

	function isErrorPassword(passwords) {
		const roleSelected = $('.pda-selected-role-select2').val();
		if (roleSelected !== 'global') {
			return false;
		}

		if (!passwords.every(checkPasswordNoSpace)) {
			toastr.error(save_password_data.error_message.space_password, 'Password Protect WordPress');
			$('#save_password').prop("disabled", true);
			return true;
		}

		if ((new Set(passwords)).size !== passwords.length) {
			toastr.error(save_password_data.error_message.duplicate_password, 'Password Protect WordPress');
			$('#save_password').prop("disabled", true);
			return true;
		}

	}

	function savePassword(settings, cb) {
		const _data = {
			action: 'ppw_free_set_password',
			settings: settings,
			security_check: $('#ppw_meta_box_nonce').val(),
		}
		$('#save_password').text('Submitting');
		$('#save_password').prop("disabled", true);
		$.ajax({
			url: save_password_data.ajax_url,
			type: 'POST',
			data: _data,
			success: function (data) {
				cb(data, null);
			},
			error: function (error) {
				cb(null, error);
			},
			timeout: 5000
		});
	}

	function changeValueRoles(data) {
		const rolesFiltered = Object.keys(data)
			.filter(function (role) {
				return '' !== data[role];
			});
		const roles = rolesFiltered.map(function (role) {
			return "<span>" + role + "</span>";
		}).join(' ');
		const rolesText = rolesFiltered.length > 1 ? rolesFiltered.length + ' roles' : rolesFiltered.length + ' role';
		$("#all_roles_select").html(roles);
		$("#number_roles").text(rolesText);
	}

})(window, jQuery);
