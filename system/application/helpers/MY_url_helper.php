<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Show base url (added index_page information if available)
 * @alias index_url
 * @return string
 */
if (!function_exists('index_url'))
{
    function index_url()
    {
        return site_url();
    }
}

/**
 * 
 * @return 
 * @param object $filters[optional]
 */
if (!function_exists('assist_url'))
{
    function assist_url( $filters = array () )
    {
        $CI = & get_instance();
        $CI->uri->auto_segment();

        $uri = "";

        $keys = array ();

        foreach ( $CI->uri->auto_segments as $key => $value )
        {
            if ( !is_int( $key ) )
            {
                $filter = elements( $key, $filters );

                if ($filter !== FALSE)
                {
                    array_push( $keys, $key );
                    $uri .= $key . "/" . $filter . "/";
                }
                else
                {
                    $uri .= $key . "/" . $value . "/";
                }
            }
            else
            {
                $uri .= $value."/";
            }
        }

        foreach ( $filters as $fkey => $fvalue )
        {
            if ( !in_array( $fkey, $keys ) )
			{
				if ( !is_int( $fkey ) )
				{
					$uri .= $fkey . "/" . $fvalue . "/";
				}
				else {
					$uri .= $fvalue . "/";
				}
                
			}
		}

        return site_url( $uri );
    }
}
?>