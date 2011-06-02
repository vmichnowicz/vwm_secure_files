/**
 * Update secure file
 */
$('#vwm_secure_files_files input[type="button"].update').click(function() {
	var tr = $(this).closest('tr');
	$.post(EE.CP_URL + '?D=cp&C=addons_modules&M=show_module_cp&module=vwm_secure_files&method=update_file', {
		XID: EE.XID,
		id: $(tr).find('input[name="id"]').val(),
		file_path: $(tr).find('input[name="file_path"]').val(),
		allowed_groups: $(tr).find('select[name="allowed_groups"]').val(),
		allowed_members: $(tr).find('select[name="allowed_members"]').val(),
		denied_groups: $(tr).find('select[name="denied_groups"]').val(),
		denied_members: $(tr).find('select[name="denied_members"]').val(),
		download_limit: $(tr).find('input[name="download_limit"]').val()
	}, function(data) {
		if (data.result == 'success') {
			$.ee_notice('Update successful', {open: true, type: 'success'});
		}
		else {
			$.ee_notice('Update failed', {open: true, type: 'error'});
		}
	}, 'json');
});

/**
 * Delete secure file
 */
$('#vwm_secure_files_files input[type="button"].remove').click(function() {
	var remove = confirm('Are you sure you want to remove this link to this file');
	if (remove) {
		var tr = $(this).closest('tr');
		$.post(EE.CP_URL + '?D=cp&C=addons_modules&M=show_module_cp&module=vwm_secure_files&method=remove_file', {
			XID: EE.XID,
			id: $(tr).find('input[name="id"]').val()
		}, function(data) {
			if (data.result == 'success') {
				$(tr).remove();
				$.ee_notice('File removal successful', {type: 'success'});
			}
			else {
				$.ee_notice('File removal failed', {open: true, type: 'error'});
			}
		}, 'json');
	}
});