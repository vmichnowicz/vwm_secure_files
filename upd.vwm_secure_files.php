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
 * Lets install, uninstall, or update this bad boy
 */
class Vwm_secure_files_upd {

	public $version = '0.2';
	
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
	}

	/**
	 * Module Installer
	 *
	 * @access public
	 * @return bool
	 */	
	public function install()
	{
		// VWM Polls module information
		$data = array(
			'module_name' => 'Vwm_secure_files' ,
			'module_version' => $this->version,
			'has_cp_backend' => 'y',
			'has_publish_fields' => 'n'
		);

		$this->EE->db->insert('modules', $data);

		// Add download_file action to exp_actions
		$download_file = array('class' => 'Vwm_secure_files', 'method' => 'download_file');
		$this->EE->db->insert('actions', $download_file);

		// Get database prefix
		$prefix = $this->EE->db->dbprefix;

		// Table to record poll votes
		$this->EE->db->query("	
			CREATE TABLE IF NOT EXISTS `{$prefix}vwm_secure_files_files` (
				`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
				`hash` varchar(32) NOT NULL,
				`file_path` varchar(256) NOT NULL,
				`allowed_groups` varchar(265) DEFAULT NULL,
				`allowed_members` varchar(256) DEFAULT NULL,
				`denied_groups` varchar(256) DEFAULT NULL,
				`denied_members` varchar(256) DEFAULT NULL,
				`download_limit` mediumint(16) unsigned NOT NULL DEFAULT '0',
				`downloads` mediumint(16) unsigned NOT NULL DEFAULT '0',
				`created` int(10) unsigned NOT NULL,
				`updated` int(10) unsigned DEFAULT NULL,
				PRIMARY KEY (`id`),
				UNIQUE KEY `hash` (`hash`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;	
		");

		return TRUE;
	}

	/**
	 * Module Uninstaller
	 *
	 * @access public
	 * @return bool
	 */	
	public function uninstall()
	{
		// Get database prefix
		$prefix = $this->EE->db->dbprefix;
		
		// Get module ID
		$module_id = $this->EE->db
			->select('module_id')
			->where('module_name', 'Vwm_secure_files')
			->limit(1)
			->get('modules')
			->row('module_id');
		
		// Delete from modules
		$this->EE->db
			->where('module_id', $module_id)
			->delete('modules');

		// Delete from module_member_groups
		$this->EE->db
			->where('module_id', $module_id)
			->delete('module_member_groups');

		// Delete from actions
		$this->EE->db
			->where('class', 'Vwm_secure_files')
			->delete('actions');

		// Delete all extra tables
		$this->EE->db->query("DROP TABLE {$prefix}vwm_secure_files_files");

		return TRUE;
	}

	/**
	 * Module Updater
	 *
	 * @access	public
	 * @return	bool
	 */	
	public function update($current = '')
	{
		// Get database prefix
		$prefix = $this->EE->db->dbprefix;

		// If user is on the first release of VWM Secure Files - version 0.1
		if ($current == '0.1')
		{
			// Allow download limit to be NULL
			$this->EE->db->query("ALTER TABLE  `{$prefix}vwm_secure_files_files` CHANGE  `download_limit`  `download_limit` MEDIUMINT( 16 ) UNSIGNED NULL DEFAULT NULL");
		}
		return TRUE;
	}
	
}

// END CLASS