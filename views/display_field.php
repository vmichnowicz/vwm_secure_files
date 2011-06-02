<select name="<?php echo $field_name; ?>">
	<?php if ($files): ?>
		<?php foreach ($files as $file): ?>
				<?php if ($file['file_url'] == $current_file): ?>
					<option value="<?php echo $file['file_url']; ?>" selected="selected"><?php echo $file['file_path']; ?></option>
				<?php else: ?>
					<option value="<?php echo $file['file_url']; ?>"><?php echo $file['file_path']; ?></option>
				<?php endif; ?>
		<?php endforeach; ?>
	<?php else: ?>
			<option disbled="disabled"><?php echo lang('vwm_secure_files_no_files'); ?></option>
	<?php endif; ?>
</select>