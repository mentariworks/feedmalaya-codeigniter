<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Apphook
{
	function pre()
	{
		$ci =& get_instance();
		fb_inits();
		$ci->ui->module['fbconnect'] = fb_user_login();
	}
	function post()
	{
		$ci =& get_instance();
		
	}
}

?>