<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

 // include base class
if ( ! class_exists('Remotes_base'))
{
	require_once(PATH_THIRD.'remotes/base.remotes.php');
}

// ------------------------------------------------------------------------

class Remotes_model extends Remotes_base 
{
	/**
	 * Constructor
	 *
	 * @access     public
	 * @return     void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	public function get_remotes()
	{
		ee()->db->select('id, site_id, last_updated, url, file_name, lifetime')
				->where('site_id', $this->site_id)
				->order_by('order');
		return ee()->db->get('remotes')->result_array();
	}


	public function save_remotes($remotes)
	{
		$ids = array();

		foreach($remotes as $remote)
		{
			// we updating
			if(isset($remote['id']) && $remote['id'] != '')
			{
				ee()->db->where('id', $remote['id']);
				ee()->db->update('remotes', $remote);
				$ids[] = $remote['id'];
			}
			// we creating new ones
			else
			{
				unset($remote['id']);
				ee()->db->insert('remotes', $remote); 
				$ids[] = ee()->db->insert_id();
			}
		}

		// nuke any not supplied
		ee()->db->where_not_in('id', $ids);
		ee()->db->delete('remotes'); 
		
	}

	public function update_remote($id, $remote_data, $mime)
	{

		$data = array(
			'data' =>  $remote_data,
			'last_updated' => ee()->localize->now,
			'type' => $mime
		);
		ee()->db->where('id', $id);
		ee()->db->update('remotes', $data);
	}

	public function clear_remote_data($id)
	{
		$data = array(
			'last_updated' => '',
			'data' => ''
		);
		ee()->db->where('id', $id);
		ee()->db->update('remotes', $data);
	}

}