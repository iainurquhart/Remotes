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
 * Remotes Module Control Panel File
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

class Remotes_mcp extends Remotes_base {
	
	public $return_data;
	

	function __construct() 
	{
		parent::__construct();
		// ee()->load->model('taxonomy_model', 'taxonomy');

		ee()->cp->set_right_nav(array(
			'module_home'	=> $this->base_url,
			'edit_remotes'	=> $this->base_url.AMP.'method=edit_remotes',
			// Add more right nav items here.
		));

		ee()->load->model('remotes_model', 'remotes');

	}
	
	// ----------------------------------------------------------------

	/**
	 * Index Function
	 *
	 * @return 	void
	 */
	public function index()
	{
		ee()->load->library('table');
		ee()->load->helper('url');
		ee()->load->helper('date');
		ee()->view->cp_page_title = lang('remotes_module_name');

		// add our css
		ee()->cp->add_to_head('
			<link type="text/css" href="'.$this->theme_base_url.'/css/roland.css" rel="stylesheet" />
		');

		$vars = array();
		// post values in example form
		$vars['base_url'] = $this->base_url;
		$vars['remotes'] = ee()->remotes->get_remotes();
		foreach($vars['remotes'] as &$remote)
		{
			$remote['nice_date'] = ee()->localize->human_time($remote['last_updated'], TRUE);
		}

		$act_id = ee()->cp->fetch_action_id('Remotes', 'fetch_remote');
		$vars['act_base']= ee()->functions->fetch_site_index(0, 0).QUERY_MARKER.'ACT='.$act_id;

		return ee()->load->view('index', $vars, TRUE);

	}

	/**
	 * Index Function
	 *
	 * @return 	void
	 */
	public function edit_remotes()
	{

		ee()->view->cp_page_title = lang('remotes_module_name');

		// prep our js
		ee()->load->library('table');
		ee()->cp->add_js_script('ui', 'sortable'); 
		ee()->cp->load_package_js('jquery.roland');
		ee()->javascript->compile();

		// add our css
		ee()->cp->add_to_head('
			<link type="text/css" href="'.$this->theme_base_url.'/css/roland.css" rel="stylesheet" />
		');

		$vars = array();
		$vars['_form_base_url'] = $this->form_base_url;
		$vars['remotes'] = ee()->remotes->get_remotes();
		
		// misc assets/classes required
		$vars['drag_handle'] = '&nbsp;';
		$vars['nav'] = '<a class="remove_row" href="#">-</a> <a class="add_row" href="#">+</a>';
		$vars['roland_template'] = array(
				'table_open'		=> '<table class="mainTable roland_table" border="0" cellspacing="0" cellpadding="0">',
				'row_start'			=> '<tr class="row">',
				'row_alt_start'     => '<tr class="row">'
		);

		$vars['cache_times'] = array(
			'0' => 'Cache Forever',
			'600' => '10 Minutes',
			'1800' => '30 Minutes',
			'3600' => '1 Hour',
			'86400' => '24 Hours'
		);

		return ee()->load->view('edit_remotes', $vars, TRUE);

	}

	public function save_remotes()
	{

		$data = $remotes = array();
		$data['urls'] = ee()->input->post('urls');
		$data['file_names'] = ee()->input->post('cache_file_names');
		$data['ids'] = ee()->input->post('ids');
		$data['lifetime'] = ee()->input->post('lifetime');

		foreach($data['urls'] as $key => $url)
		{
			$remotes[] = array(
				'site_id' => $this->site_id,
				'url' => $data['urls'][$key],
				'file_name' =>  $data['file_names'][$key],
				'id' => $data['ids'][$key],
				'order' => $key,
				'lifetime' => $data['lifetime'][$key]
			);
		}

		ee()->remotes->save_remotes($remotes);
		ee()->session->set_flashdata('message_success', lang('remotes_updated'));
		ee()->functions->redirect($this->base_url);
		
	}

	public function generate_url()
	{
		$act_id = ee()->cp->fetch_action_id('Remotes', 'fetch_remote');
		$url = ee()->functions->fetch_site_index(0, 0).QUERY_MARKER.'ACT='.$act_id;
		$params = ee()->input->post('params');
		$url .= AMP.'sn='.$params['short_name'].AMP.'ct='.$params['cache_time'];
		ee()->functions->redirect($url);
	}


	public function clear_remote_data()
	{
		$id = ee()->input->get_post('id');
		ee()->remotes->clear_remote_data($id);
		ee()->session->set_flashdata('message_success', lang('remote_cleared'));
		ee()->functions->redirect($this->base_url);

	}

	
}
/* End of file mcp.remotes.php */
/* Location: /system/expressionengine/third_party/remotes/mcp.remotes.php */