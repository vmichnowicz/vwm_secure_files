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
 * VWM Secure Files control panel
 */
class Vwm_secure_files_mcp {
	
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

		// Make damn sure module path is defined
		$this->EE->load->add_package_path(PATH_THIRD . 'vwm_polls/');

		// Load model
		$this->EE->load->model('vwm_secure_files_m');
	}

	/**
	 * Module CP page
	 * 
	 * @access public
	 * @return string
	 */
	public function index()
	{
		// Page title
		$this->EE->cp->set_variable('cp_page_title', $this->EE->lang->line('vwm_secure_files_module_name'));
		
		// CP URL
		$data['action_url'] = 'C=addons_modules' . AMP . 'M=show_module_cp' . AMP . 'module=vwm_secure_files' . AMP . 'method=add_file';
		
		// Add JavaScript
		$this->EE->cp->add_to_head('<script type="text/javascript">EE.CP_URL = "' . $this->EE->config->item('cp_url') . '";</script>');
		$this->EE->cp->load_package_js('mcp');
		
		// All members
		$data['members'] = $this->EE->vwm_secure_files_m->all_members();
		
		// All groups
		$data['groups'] = $this->EE->vwm_secure_files_m->all_groups();
		
		// Get all secure files
		$data['files'] = $this->EE->vwm_secure_files_m->all_files();
		
		return $this->EE->load->view('mcp', $data, TRUE);
	}
	
	/**
	 * Update a file
	 * 
	 * @access public
	 * @return void
	 */
	public function update_file()
	{
		if ($this->EE->vwm_secure_files_m->update_file($_POST))
		{
			$this->EE->output->send_ajax_response(array('result' => 'success'));
		}
		else
		{
			$this->EE->output->send_ajax_response(array('result' => 'failure'));
		}
	}
	
	/**
	 * Remove a file
	 * 
	 * @access public
	 * @return void
	 */
	public function remove_file()
	{
		if ($this->EE->vwm_secure_files_m->remove_file($this->EE->input->post('id')))
		{
			$this->EE->output->send_ajax_response(array('result' => 'success'));
		}
		else
		{
			$this->EE->output->send_ajax_response(array('result' => 'failure'));
		}
	}
	
	/**
	 * Add a file
	 * 
	 * @access public
	 * @return void
	 */
	public function add_file()
	{
		// Redirect user back to this page after adding file
		$redirect_to = $this->EE->input->post('redirect_to');
		
		// if file addition was successful
		if ($this->EE->vwm_secure_files_m->add_file($_POST))
		{
			// Great success!
			$this->EE->session->set_flashdata('message_success', lang('vwm_secure_files_file_success'));
		}
		// If file addition failed
		else
		{
			$this->EE->session->set_flashdata('message_failure', lang('vwm_secure_files_file_failure'));
		}
		
		$this->EE->functions->redirect(BASE . AMP . $redirect_to);
	}

}

// END CLASS