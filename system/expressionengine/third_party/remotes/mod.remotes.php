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
 * Remotes Module Front End File
 *
 * @package		ExpressionEngine
 * @subpackage	Addons
 * @category	Module
 * @author		Iain Urquhart
 * @link		http://iain.co.nz
 */

 // include base class
if ( ! class_exists('Remotes_base'))
{
	require_once(PATH_THIRD.'remotes/base.remotes.php');
}

class Remotes extends Remotes_base {
	
	public $return_data;
	
	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct();
	}

	public function fetch_remote()
	{

		$short_name = ee()->input->get('sn');
		$cache_time = ee()->input->get('ct');

		if(!$short_name)
			show_error('You must select a remote source!');

		if(!$cache_time)
		{
			$cache_time = 999999999999;
		}

		ee()->db->where('file_name', $short_name);
		$r = ee()->db->get('remotes')->row_array();

		$now = ee()->localize->now;

		if($r['data'] == '' || $r['last_updated'] == '' || $now > ($r['last_updated']+$cache_time) )
		{

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $r['url']);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$r['data'] = curl_exec($ch);
			$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			$r['type'] = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);

			curl_close ($ch);

			ee()->load->model('remotes_model', 'remotes');
			ee()->remotes->update_remote( $r['id'], $r['data'], $r['type'] );

		}

		header("Content-Type: ".$r['type']);
		echo $r['data']; exit();
		
	}
	
	// ----------------------------------------------------------------

}
/* End of file mod.remotes.php */
/* Location: /system/expressionengine/third_party/remotes/mod.remotes.php */