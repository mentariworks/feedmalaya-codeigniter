<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 
 * @return 
 * @param object $text
 * @param object $attributes
 */
if(!function_exists('dom'))
{
	function dom( $text = '', $type = 'strong', $attributes = '' )
	{
		$type = strtolower( $type );
		// Were any attributes submitted?  If so generate a string
		if (is_array($attributes))
		{
			$atts = '';
			foreach ($attributes as $key => $val)
			{
				$atts .= ' ' . $key . '="' . $val . '"';
			}
			
			$attributes = $atts;
		}
		
		$output = '<' . $type . ' ' . $attributes . '>';
		$output .= $text;
		$output .= '</'. $type .'>';
		
		return $output;
	}
}
?>