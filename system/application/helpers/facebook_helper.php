<?php

if ( !function_exists( 'fb_inits' ) )
{
	function fb_inits()
	{
		$ci =& get_instance();
		$ci->ui->module['FACEBOOK-APIKEY'] = $ci->option->get('facebook_api_key');
	}
}

if ( !function_exists( 'fb_user_login' ) ) 
{
	function fb_user_login()
	{
		$ci =& get_instance();
		$output = '';
		
		if ( !$ci->fb_user ) 
		{
			// Not login, so show a login button
			//$output = '<fb:login-button onlogin="window.location=\'' . base_url() . '\'"></fb:login-button>';
			$output .= '<p><a href="#" id="fb-login-button" title="Log in using your existing facebook account">';
			$output .= '<img src="' . base_url() . 'public/images/connect_white_medium_long.gif" alt="" />';
			$output .= '</a></p>';
			
		}
		else
		{
			
			$output = img($ci->fb_user['pic_square']);
			$output .= '<p>Hi ' . $ci->fb_user['name'] . '</p>';
			$output .= '<p><a href="#" onclick="FB.Connect.logout(function() { window.location=\'' .current_url() . '\' }); return false;" >Logout</a></p>';
		}
		
		/*
		 * <a href="#" onclick="FB.Connect.logout(function() { window.location='current_url()' }); return false;" >(Logout)</a>
		 */
		return $output;
	}	
}
?>