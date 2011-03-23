<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( !function_exists( 'prettylist' ) ) :
	function prettylist( $data = array(), $between = '', $last = 0 ) {
		$count = count( $data );
		$output = '';
		
		if($count > 1) :
			for ( $loop = 0; $loop <($count-1); $loop++ ) :
				$output .= ($loop == 0 ? '' : $between) . $data[$loop];
			endfor;
			
			$output .= $last . $data[($count-1)];
			
		elseif ( $count == 1 ) :
			$output = $data[0];
		endif;
		
		return $output;
	}
endif;

if ( !function_exists( 'querystring' ) ) :
	function querystring($data = array(), $start = '?')
	{
		$ci =& get_instance();
		
		$query = "";
		$count = 0;
		
		foreach($data as $value) :
			$val = ( $ci->input->get($value) != FALSE ? $ci->input->get($value) : '' );
			
			if(trim($val) != '') :
				$query .= ( $count == 0 ? $start : '&' ) . $value . '=' . $val;
				$count++;
			endif;
		endforeach;
		
		return $query;
	}
endif;

if ( !function_exists( 'is_mobile_browser' ) ) :
	function is_mobile_browser()
	{
		$mobile_browser = 0;
		
		if ( preg_match( '/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone)/i', strtolower( $_SERVER['HTTP_USER_AGENT'] ) ) ) :
    		$mobile_browser++;
		endif;
		
		if( ( strpos( strtolower( $_SERVER['HTTP_ACCEPT'] ), 'application/vnd.wap.xhtml+xml' ) > 0 ) OR ( ( isset( $_SERVER['HTTP_X_WAP_PROFILE'] ) OR isset( $_SERVER['HTTP_PROFILE'] ) ) ) ) :
			$mobile_browser++;
		endif;
		
		$mobile_ua = strtolower( substr( $_SERVER['HTTP_USER_AGENT'], 0, 4 ) );
		$mobile_agents = array(
		'w3c ','acs-','alav','alca','amoi','audi','avan','benq','bird','blac',
		'blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno',
		'ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-',
		'maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-',
		'newt','noki','oper','palm','pana','pant','phil','play','port','prox',
		'qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar',
		'sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-',
		'tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp',
		'wapr','webc','winw','winw','xda','xda-');
		
		if ( in_array( $mobile_ua, $mobile_agents ) ) :
			$mobile_browser++;
		endif;
		
		if ( isset($_SERVER['ALL_HTTP']) AND strpos( strtolower( $_SERVER['ALL_HTTP'] ), 'OperaMini' ) > 0 ) :
			$mobile_browser++;
		endif;
		
		if ( strpos( strtolower( $_SERVER['HTTP_USER_AGENT'] ), 'windows' ) > 0 ) :
			$mobile_browser = 0;
		endif;
		
		return ( $mobile_browser > 0 ? TRUE : FALSE );
	}
endif;
?>