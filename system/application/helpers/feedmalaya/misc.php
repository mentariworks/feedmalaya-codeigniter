<?php

if (!function_exists('fm_show_404'))
{
	function fm_show_404($print = FALSE)
	{
		$FM =& get_instance();
		
		$output = $FM->option->get('no_data_message');
		
		if ( $print === FALSE )
		{
			return $output;
		}
		else 
		{
			print $output;
		}
	}
	
}

?>