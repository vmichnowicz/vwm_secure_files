<h3><?php echo lang('vwm_secure_files_all_files'); ?></h3>

<table id="vwm_secure_files_files" class="mainTable" border="0px" cellpadding="0px" cellspacing="0px">
	<thead>
		<tr>
			<th><?php echo lang('vwm_secure_files_id'); ?></th>
			<th><?php echo lang('vwm_secure_files_file_path'); ?></th>
			<th><?php echo lang('vwm_secure_files_allowed_groups'); ?></th>
			<th><?php echo lang('vwm_secure_files_allowed_members'); ?></th>
			<th><?php echo lang('vwm_secure_files_denied_groups'); ?></th>
			<th><?php echo lang('vwm_secure_files_denied_members'); ?></th>
			<th><?php echo lang('vwm_secure_files_download_limit'); ?></th>
			<th><?php echo lang('vwm_secure_files_downloads'); ?></th>
			<th>&nbsp;</th>
		</tr>
	</thead>
	<tbody>
		<?php if ($files): ?>
			<?php foreach ($files as $file): ?>
				<tr>
					<td><a href="<?php echo $file['file_url']; ?>"><?php echo $file['id']; ?></a><input type="hidden" name="id" value="<?php echo $file['id']; ?>" /></td>
					<td><?php echo form_input('file_path', $file['file_path']); ?></td>
					<td><?php echo form_multiselect('allowed_groups', $groups, $file['allowed_groups']); ?></td>
					<td><?php echo form_multiselect('allowed_members', $members, $file['allowed_members']); ?></td>
					<td><?php echo form_multiselect('denied_groups', $groups, $file['denied_groups']); ?></td>
					<td><?php echo form_multiselect('denied_members', $members, $file['denied_members']); ?></td>
					<td><?php echo form_input('download_limit', $file['download_limit'], 'style="width: 25%;"'); ?></td>
					<td><?php echo $file['downloads']; ?></td>
					<td><input type="button" value="<?php echo lang('vwm_secure_files_remove'); ?>" class="submit remove" />&nbsp;<input type="button" value="<?php echo lang('vwm_secure_files_update'); ?>" class="submit update" /></td>
				</tr>
			<?php endforeach; ?>
		<?php else: ?>
			<td	colspan="9"><?php echo lang('vwm_secure_files_no_files'); ?></td>
		<?php endif; ?>
	</tbody>
</table>

<h3><?php echo lang('vwm_secure_files_add_file'); ?></h3>

<?php echo form_open($action_url, '', array('redirect_to' => $this->cp->get_safe_refresh()) ) ?>
	<fieldset style="border: 0px none; margin: 0px; padding: 0px;">
		<table class="mainTable" border="0px" cellpadding="0px" cellspacing="0px">
			<thead>
				<th><?php echo lang('vwm_secure_files_file_path'); ?></th>
				<th><?php echo lang('vwm_secure_files_allowed_groups'); ?></th>
				<th><?php echo lang('vwm_secure_files_allowed_members'); ?></th>
				<th><?php echo lang('vwm_secure_files_denied_groups'); ?></th>
				<th><?php echo lang('vwm_secure_files_denied_members'); ?></th>
				<th><?php echo lang('vwm_secure_files_download_limit'); ?></th>
				<th>&nbsp;</th>
			</thead>
			<tbody>
				<tr>
					<td><input type="text" name="file_path" /></td>
					<td><?php echo form_multiselect('allowed_groups[]', $groups); ?></td>
					<td><?php echo form_multiselect('allowed_members[]', $members); ?></td>
					<td><?php echo form_multiselect('denied_groups[]', $groups); ?></td>
					<td><?php echo form_multiselect('denied_members[]', $members); ?></td>
					<td><input type="text" name="download_limit" /></td>
					<td><input type="submit" class="submit" value="<?php echo lang('vwm_secure_files_add'); ?>" /></td>
				</tr>
			</tbody>
		</table>
	</fieldset>
<?php echo form_close(); ?>