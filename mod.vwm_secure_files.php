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
		$hash = $this->EE->input->get('ID');
		
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

					// Get file size
					$file['size'] = $this->file_size($file['file_path']);

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
						header('Content-Length: ' . $file['size']);
					}

					ob_clean();
					flush();

					// Return file data
					readfile($file['file_path']);
					
					// Add one to the downloads counter
					$this->EE->vwm_secure_files_m->record_download($file['id']);
					
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
	
	/**
	 * Get the file size of a file
	 * 
	 * @param string		File path
	 */
	private function file_size($file_name)
	{
		// Let's play it safe and assume we don't know the file size
		$file_size = NULL;
		
		/**
		 *  If this file exists on the local server
		 *  secure/secure.txt
		 */
		if ( file_exists($file_name) )
		{
			$file_size = filesize($file_name);
		}
		/**
		 * If this file exists on a remote server
		 * http://example.com/secure/secure.txt
		 * 
		 * @link http://us.php.net/manual/en/function.filesize.php#92462
		 */
		else
		{
			// Make sure CURL is enabled
			if ( in_array('curl', get_loaded_extensions()) )
			{
				// Initialize
				$curl = curl_init($file_name);
				
				// Set options
				curl_setopt($curl, CURLOPT_NOBODY, TRUE);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
				curl_setopt($curl, CURLOPT_HEADER, TRUE);
				curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
				
				// Exexute & close
				$data = curl_exec($curl);
				curl_close($curl);
				
				if ($data != FALSE)
				{
					if ( preg_match('/Content-Length: (\d+)/', $data, $matches) )
					{
						$file_size = (int)$matches[1];
					}
				}
			}
		}
		
		return $file_size;
	}
	
}

// END CLASS