<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 
 * @return 
 * @param object $item
 * @param object $array
 * @param object $default[optional]
 */
if ( ! function_exists('elements'))
{
	function elements( $item, $array, $default = FALSE )
	{
		if ( ! isset( $array[$item] ) )
		{
			return $default;
		}

		return $array[$item];
	}	
}
?>