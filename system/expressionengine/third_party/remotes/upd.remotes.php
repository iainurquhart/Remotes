<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * ExpressionEngine - by EllisLab
 *
 * @package		ExpressionEngine
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2003 - 2011, EllisLab, Inc.
 * @license		http://expressionengine.com/user_guide/license.html
 * @link		http://expressionengine.com
 * @since		Version 2.0
 * @filesource
 */
 
// ------------------------------------------------------------------------

/**
 * Remotes Module Install/Update File
 *
 * @package		ExpressionEngine
 * @subpackage	Addons
 * @category	Module
 * @author		Iain Urquhart
 * @link		http://iain.co.nz
 */

if( !function_exists('ee') )
{
	function ee()
	{
		static $EE;
		if ( ! $EE) $EE = get_instance();
		return $EE;
	}
}

class Remotes_upd {
	
	public $version = '1.0';
	
	private $EE;
	
	/**
	 * Constructor
	 */
	public function __construct()
	{
		
	}
	
	// ----------------------------------------------------------------
	
	/**
	 * Installation Method
	 *
	 * @return 	boolean 	TRUE
	 */
	public function install()
	{
		$mod_data = array(
			'module_name'			=> 'Remotes',
			'module_version'		=> $this->version,
			'has_cp_backend'		=> "y",
			'has_publish_fields'	=> 'n'
		);
		
		ee()->db->insert('modules', $mod_data);
		
		$fields = array(

			'id' => array(
				'type' => 'int',
				'constraint' => '10',
				'unsigned' => TRUE,
				'auto_increment' => TRUE
			),

			'site_id' => array(
				'type' => 'int', 
				'constraint' => '10'
			),

			'last_updated' => array(
				'type' => 'int', 
				'constraint' => '10'
			),

			'url'	=> array(
				'type' => 'varchar',
				'constraint' => '250'
			),

			'file_name'	=> array(
				'type' => 'varchar',
				'constraint' => '250'
			),

			'order' => array(
				'type' => 'int',
				'constraint' => '3'
			),

			'data' => array(
				'type' => 'longblob'
			),

			'type' => array(
				'type' => 'varchar',
				'constraint' => '250'
			),

			'lifetime' => array(
				'type' => 'int', 
				'constraint' => '10'
			),
		);

		ee()->load->dbforge();
		ee()->dbforge->add_field($fields);
		ee()->dbforge->add_key('id', TRUE);
		ee()->dbforge->create_table('remotes');
		unset($fields);

		$data = array(
			'class'     => 'Remotes' ,
			'method'    => 'fetch_remote'
		);

		ee()->db->insert('actions', $data);
		
		return TRUE;
	}

	// ----------------------------------------------------------------
	
	/**
	 * Uninstall
	 *
	 * @return 	boolean 	TRUE
	 */	
	public function uninstall()
	{
		$mod_id = ee()->db->select('module_id')
								->get_where('modules', array(
									'module_name'	=> 'Remotes'
								))->row('module_id');
		
		ee()->db->where('module_id', $mod_id)
					 ->delete('module_member_groups');
		
		ee()->db->where('module_name', 'Remotes')
					 ->delete('modules');
		
		ee()->load->dbforge();
		ee()->dbforge->drop_table('remotes');

		ee()->db->where('class', 'Remotes');
		ee()->db->delete('actions');
		
		return TRUE;
	}
	
	// ----------------------------------------------------------------
	
	/**
	 * Module Updater
	 *
	 * @return 	boolean 	TRUE
	 */	
	public function update($current = '')
	{
		// If you have updates, drop 'em in here.
		return TRUE;
	}
	
}
/* End of file upd.remotes.php */
/* Location: /system/expressionengine/third_party/remotes/upd.remotes.php */