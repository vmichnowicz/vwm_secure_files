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
 * Model used for database interactions with secure file module and fieldtype
 */
class Vwm_secure_files_m extends CI_Model {

	/**
	 * Model construct
	 * 
	 * @access public
	 * @return void
	 */
	public function __construct()
	{
		// Let's get it started in here
	}

	/**
	 * Get all secure files
	 *
	 * @access public
	 * @return array
	 */
	public function all_files()
	{
		$query = $this->db->get('vwm_secure_files_files');
		
		if ($query->num_rows() > 0)
		{
			$action_id = $this->cp->fetch_action_id('Vwm_secure_files', 'download_file');
			
			foreach ($query->result() as $row)
			{
				$data[$row->id] = array(
					'id' => (int)$row->id,
					'hash' => $row->hash,
					'file_path' => $row->file_path,
					'file_url' => site_url('?ACT=' . $action_id . '&amp;ID=' . $row->hash),
					'allowed_groups' => explode(',', $row->allowed_groups),
					'allowed_members' => explode(',', $row->allowed_members),
					'denied_groups' => explode(',', $row->denied_groups),
					'denied_members' => explode(',', $row->denied_members),
					'download_limit' => (int)$row->download_limit,
					'downloads' => (int)$row->downloads,
					'created' => (int)$row->created,
					'updated' => (int)$row->updated
				);
			}
			
			return $data;
		}
		else
		{
			return array();
		}
		
		return $query->num_rows() > 0 ? $query->result_array() : array();
	}
	
	/**
	 * Get all members
	 *
	 * @access public
	 * @return array
	 */
	public function all_members()
	{
		$query = $this->db->select('member_id, username, screen_name')->get('members');
		
		foreach ($query->result() as $row)
		{
			$data[$row->member_id] = $row->screen_name;
		}
		
		return $data;
	}
	
	/**
	 * Get all member groups
	 *
	 * @access public
	 * @return array
	 */
	public function all_groups()
	{
		$query = $this->db->select('group_id, group_title')->get('member_groups');
		
		foreach ($query->result() as $row)
		{
			$data[$row->group_id] = $row->group_title;
		}
		
		return $data;
	}
	
	/**
	 * Get a file
	 *
	 * @access public
	 * @param int			Download hash
	 * @return array
	 */
	public function get_file($hash)
	{
		$query = $this->db
			->where('hash', $hash)
			->limit(1)
			->get('vwm_secure_files_files');
		
		$data = array();
		
		if ($query->num_rows() > 0)
		{
			$row = $query->row(); 
			
			$data = array(
				'id' => (int)$row->id,
				'hash' => $row->hash,
				'file_path' => $row->file_path,
				'allowed_groups' => explode(',', $row->allowed_groups),
				'allowed_members' => explode(',', $row->allowed_members),
				'denied_groups' => explode(',', $row->denied_groups),
				'denied_members' => explode(',', $row->denied_members),
				'download_limit' => (int)$row->download_limit,
				'downloads' => (int)$row->downloads,
				'created' => (int)$row->created,
				'updated' => (int)$row->updated
			);
		}

		return $data;
	}
	
	/**
	 * Update a file
	 *
	 * @access public
	 * @param array			Array of download info
	 * @return bool
	 */
	public function update_file($file)
	{
		$data = array(
			'file_path'			=> trim($file['file_path']),
			'allowed_groups'	=> $file['allowed_groups'] == 'null' ? NULL : implode(',', $file['allowed_groups']),
			'allowed_members'	=> $file['allowed_members'] == 'null' ? NULL : implode(',', $file['allowed_members']),
			'denied_groups'		=> $file['denied_groups'] == 'null' ? NULL : implode(',', $file['denied_groups']),
			'denied_members'	=> $file['denied_members'] == 'null' ? NULL : implode(',', $file['denied_members']),
			'download_limit'	=> (int)$file['download_limit'],
			'updated'			=> time()
		);

		$query = $this->db
			->where('id', $file['id'])
			->update('vwm_secure_files_files', $data);
		
		return $this->db->affected_rows() > 0 ? TRUE : FALSE;
	}
	
	/**
	 * Add a file
	 *
	 * @access public
	 * @param array			Array of download info
	 * @return bool
	 */
	public function add_file($file)
	{
		if (trim($file['file_path']))
		{
			$data = array(
				'file_path'			=> trim($file['file_path']),
				'hash'				=> md5( trim($file['file_path']) . time() ),
				'allowed_groups'	=> implode(',', $file['allowed_groups']),
				'allowed_members'	=> implode(',', $file['allowed_members']),
				'denied_groups'		=> implode(',', $file['denied_groups']),
				'denied_members'	=> implode(',', $file['denied_members']),
				'download_limit'	=> (int)$file['download_limit'],
				'created'			=> time()
			);

			$query = $this->db->insert('vwm_secure_files_files', $data);

			return $this->db->affected_rows() > 0 ? TRUE : FALSE;
		}
		else
		{
			return FALSE;
		}
		
	}
	
	/**
	 * Remove a file
	 *
	 * @access public
	 * @param array			ID of file
	 * @return bool
	 */
	public function remove_file($id)
	{
		$this->db->where('id', $id)->delete('vwm_secure_files_files');
		return $this->db->affected_rows() > 0 ? TRUE : FALSE;
	}
	
	/**
	 * Record a download (Add +1 to the downloads counter)
	 *
	 * @access public
	 * @param int			Download ID
	 * @return void
	 */
	public function record_download($id)
	{
		$this->db
			->where('id', $id)
			->set('downloads', 'downloads + 1', FALSE)
			->update('vwm_secure_files_files');
	}
}

// END CLASS