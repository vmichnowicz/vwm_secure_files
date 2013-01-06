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
 * VWM Secure Files fieldtype
 */
class Vwm_secure_files_ft extends EE_Fieldtype {
	
	public $info = array(
		'name' => 'VWM Secure Files',
		'version' => '0.1'
	);
	
	/**
	 * Constructor
	 *
	 * @access public
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();

		// Make damn sure module path is defined
		$this->EE->load->add_package_path(PATH_THIRD . 'vwm_secure_files/');

		// Load model
		$this->EE->lang->loadfile('vwm_secure_files');
		$this->EE->load->model('vwm_secure_files_m');
	}
	
	/**
	 * Display field in publish field
	 *
	 * @access public
	 * @param string		Field text
	 * @return string
	 */
	public function display_field($data)
	{
		/**
		 * Grab the selected file
		 * If this is a new entry, there will be no currently selected file
		 */
		$current_file = $data == '' ? NULL : $data;
		
		$data = array(
			'files' => $this->EE->vwm_secure_files_m->all_files(),
			'field_name' => $this->field_name,
			'current_file' => $current_file
		);
		
		return $this->EE->load->view('display_field', $data, TRUE);
	}
}

// END CLASS