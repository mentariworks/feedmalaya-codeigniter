<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Input extends CI_Input
{
	var $input		= array( 'GET' => array() );
	
	function MY_Input()
	{
		log_message('debug', "Input Class Initialized");

		$CFG =& load_class('Config');
		$this->use_xss_clean	= ($CFG->item('global_xss_filtering') === TRUE) ? TRUE : FALSE;
		$this->allow_get_array	= ($CFG->item('enable_query_strings') === TRUE) ? TRUE : FALSE;
		$this->_sanitize_get();
		$this->_sanitize_globals();
	}
	
	function _sanitize_get()
	{
		$this->input['GET']	= $this->_clean_input_data( $_GET );
	}
	
	function get( $index = '', $xss_clean = FALSE )
	{
		$get = $this->_fetch_from_array( $_GET, $index, $xss_clean );
		
		if ( $get === FALSE ) 
		{
			$get = $this->_fetch_from_array( $this->input['GET'], $index, $xss_clean );
		}
		
		return $get;
	}
}

?>