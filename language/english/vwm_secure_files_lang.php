<?php if ( ! defined('EXT')) { exit('Invalid file request'); }

/**
 * VWM Secure Files
 *
 * @package		VWM Secure Files
 * @author		Victor Michnowicz
 * @copyright	Copyright (c) 2011 Victor Michnowicz
 * @license		http://www.apache.org/licenses/LICENSE-2.0.html
 * @link		http://github.com/vmichnowicz/vwm_secure_files
 */

// -----------------------------------------------------------------------------

$lang = array(
	// Module info
	'vwm_secure_files_module_name'			=> 'VWM Secure Files',
	'vwm_secure_files_module_description'	=> 'Lock it down!',
	
	// File download errors
	'vwm_secure_files_no_permission'		=> 'You have not been given permission to download this file.',
	'vwm_secure_files_denied_member'		=> 'You are not allowed to download this file.',
	'vwm_secure_files_denied_group'			=> 'Your member group is not allowed to download this file.',
	'vwm_secure_files_no_file'				=> 'This file does not exist.',
	'vwm_secure_files_limit_reached'		=> 'This download limit for this file has been met.',
	
	// CP form notifications
	'vwm_secure_files_file_success'			=> 'File added successfully.',
	'vwm_secure_files_file_failure'			=> 'File addition failed.',
	
	// Control panel headers
	'vwm_secure_files_all_files'			=> 'All Files',
	'vwm_secure_files_add_file'				=> 'Add File',
	
	// Control panel table headers
	'vwm_secure_files_id'					=> 'ID',
	'vwm_secure_files_file_path'			=> 'File Path',
	'vwm_secure_files_allowed_groups'		=> 'Allowed Groups',
	'vwm_secure_files_allowed_members'		=> 'Allowed Members',
	'vwm_secure_files_denied_groups'		=> 'Denied Groups',
	'vwm_secure_files_denied_members'		=> 'Denied Members',
	'vwm_secure_files_download_limit'		=> 'Download Limit',
	'vwm_secure_files_downloads'			=> 'Downloads',
	
	// Control panel buttons
	'vwm_secure_files_remove'				=> 'Remove',
	'vwm_secure_files_update'				=> 'Update',
	'vwm_secure_files_add'					=> 'Add Secure File',
	
	// Display field
	'vwm_secure_files_no_files'				=> 'No Files',
	
);

// END FILE