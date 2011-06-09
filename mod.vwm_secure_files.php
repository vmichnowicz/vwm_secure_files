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

/**
 * VWM Secure Files module
 */
class Vwm_secure_files {

	/**
	 * Constructor
	 *
	 * @access public
	 * @return void
	 */
	public function __construct()
	{
		// Make a local reference to the ExpressionEngine super object
		$this->EE =& get_instance();

		// Load lang, helper, and model
		$this->EE->load->model('vwm_secure_files_m');
		$this->EE->lang->loadfile('vwm_secure_files');
	}
	
	/**
	 * Download a file
	 *
	 * @access public
	 * @return void
	 */
	public function download_file()
	{
		// Grab file ID
		$hash = (int)$this->EE->input->get('ID');
		
		// If this file is in the secure files database
		if ( $file = $this->EE->vwm_secure_files_m->get_file($hash) )
		{
			// Yo, are we good?
			$we_good = FALSE; // We are not good by default
			
			// Default error message
			$error = lang('vwm_secure_files_no_permission');
			
			// Is this current group allowed?
			if (in_array($this->EE->session->userdata('group_id'), $file['allowed_groups']) )
			{
				$we_good = TRUE;
			}
			
			// Is this current member allowed?
			if (in_array($this->EE->session->userdata('member_id'), $file['allowed_members']) )
			{
				$we_good = TRUE;
			}
			
			// Is this current group denied?
			if (in_array($this->EE->session->userdata('group_id'), $file['denied_groups']) )
			{
				$we_good = FALSE;
				$error = lang('vwm_secure_files_denied_group');
			}
			
			// Is this current member denied?
			if (in_array($this->EE->session->userdata('member_id'), $file['denied_members']) )
			{
				$we_good = FALSE;
				$error = lang('vwm_secure_files_denied_member');
			}
			
			// If this file has a download limit?
			if ($file['download_limit'] > 0)
			{
				// If we are all good so far and the download limit been met?
				if ($we_good AND $file['download_limit'] >= $file['downloads'])
				{
					$we_good = FALSE;
					$error = lang('vwm_secure_files_limit_reached');
				}
			}
			
			// If the user is allowed to download this file
			if ($we_good)
			{
				/**
				 * Make sure this file exists
				 * 
				 * fopen() can check both local (secure/file.txt) and remote
				 * (http://example.com/secure/file.txt) files. 
				 */
				if ( $handle = fopen($file['file_path'], 'r') )
				{
					// Close file handle
					fclose($handle);

					// Add one to the downloads counter
					$this->EE->vwm_secure_files_m->record_download($file['id']);

					/**
					 * Get file size (only for local files)
					 * @todo get file size for remote files
					 */
					$file['size'] = file_exists($file['file_path']) ? filesize($file['file_path']) : NULL;

					// Get file name
					$file_name = basename($file['file_path']);

					// Set headers so user is prompted to download file
					header('Content-Description: File Transfer');
					header('Content-Type: application/octet-stream');
					header('Content-Disposition: attachment; filename="' . $file_name . '"');
					header('Content-Transfer-Encoding: binary');
					header('Expires: 0');
					header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
					header('Pragma: public');

					// If we have a file size then this will help provide a progress meter for our download
					if($file['size'])
					{
						header('Content-Length: ' . filesize($file));
					}

					ob_clean();
					flush();

					// Return file data
					readfile($file['file_path']);
					exit;
				}
				else
				{
					show_error(lang('vwm_secure_files_no_file'));
				}
			}
			// If the user is not allowed to download this file
			else
			{
				show_error($error);
			}
		}
		// If this file does not exist
		else
		{
			show_404();
		}
	}
	
}

// END CLASS