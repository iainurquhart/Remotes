<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// ------------------------------------------------------------------------

if( !function_exists('ee') )
{
	function ee()
	{
		static $EE;
		if ( ! $EE) $EE = get_instance();
		return $EE;
	}
}

/**
 * Taxonomy Base Class
 *
 * @package		ExpressionEngine
 * @subpackage	Addons
 * @category	Module
 * @author		Iain Urquhart
 * @link		http://iain.co.nz
 * @copyright 	Copyright (c) 2012 Iain Urquhart
 * @license   	Commercial, All Rights Reserved: http://devot-ee.com/add-ons/license/taxonomy/
 */

// ------------------------------------------------------------------------

// Include our config
include(PATH_THIRD.'remotes/config.php');

class Remotes_base
{


	// --------------------------------------------------------------------
	// PROPERTIES
	// --------------------------------------------------------------------

	/**
	 * Add-on name
	 *
	 * @var        string
	 * @access     public
	 */
	public $name = REMOTES_NAME;

	/**
	 * Add-on version
	 *
	 * @var        string
	 * @access     public
	 */
	public $version = REMOTES_VERSION;

	/**
	 * URL to module docs
	 *
	 * @var        string
	 * @access     public
	 */
	public $docs_url = REMOTES_URL;

	/**
	 * Settings array
	 *
	 * @var        array
	 * @access     public
	 */
	public $settings = array();

	// --------------------------------------------------------------------

	/**
	 * EE object
	 *
	 * @var        object
	 * @access     protected
	 */
	protected $EE;

	/**
	 * Package name
	 *
	 * @var        string
	 * @access     protected
	 */
	protected $package = REMOTES_SHORT_NAME;

	/**
	 * Site id shortcut
	 *
	 * @var        int
	 * @access     protected
	 */
	protected $site_id;

	/**
	 * Form base url for module
	 *
	 * @var        string
	 * @access     protected
	 */
	protected $form_base_url;

	/**
	 * Base url for module
	 *
	 * @var        string
	 * @access     protected
	 */
	protected $base_url;

	/**
	 * Site url for module
	 *
	 * @var        string
	 * @access     protected
	 */
	protected $site_url;

	/**
	 * Theme base url for module
	 *
	 * @var        string
	 * @access     protected
	 */
	protected $theme_base_url;

	/**
	 * Data array for views
	 *
	 * @var        array
	 * @access     protected
	 */
	protected $data = array();



	public function __construct()
	{
		
		$this->site_id = ee()->config->item('site_id');
		$this->site_url = ee()->functions->fetch_site_index();
		$this->form_base_url 	= 'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module='.$this->package;
		$this->base_url		= BASE.AMP.$this->form_base_url;

		$this->theme_base_url  = defined('URL_THIRD_THEMES') ? URL_THIRD_THEMES . '/'.$this->package : $ee()->config->item('theme_folder_url') . '/third_party/'.$this->package;

		if (! isset(ee()->session->cache['remotes']))
		{
			ee()->session->cache['remotes'] = array();
		}
		
		$this->cache =& ee()->session->cache['remotes'];

	}
}