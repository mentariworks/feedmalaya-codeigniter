<?php if (!defined('BASEPATH')) exit ('No direct script access allowed');

class MY_URI extends CI_URI
{
    var $auto_segments = array ();
    var $filters = array (
	    'id' => 0,
	    'user' => '',
	    'offset' => 0,
	    'sort' => 'desc'
    );
	function MY_URI() 
	{
		parent::CI_URI();
	}
	/**
	 * Get the URI String
	 *
	 * @access	private
	 * @return	string
	 */
	function _fetch_uri_string()
	{
		if (strtoupper($this->config->item('uri_protocol')) == 'AUTO')
		{
			// If the URL has a question mark then it's simplest to just
			// build the URI string from the zero index of the $_GET array.
			// This avoids having to deal with $_SERVER variables, which
			// can be unreliable in some environments
			if (is_array($_GET) && count($_GET) == 1 && trim(key($_GET), '/') != '')
			{
				$this->uri_string = key($_GET);
				return;
			}

			// Is there a PATH_INFO variable?
			// Note: some servers seem to have trouble with getenv() so we'll test it two ways
			$path = (isset($_SERVER['PATH_INFO'])) ? $_SERVER['PATH_INFO'] : @getenv('PATH_INFO');
			if (trim($path, '/') != '' && $path != "/".SELF)
			{
				$this->uri_string = $path;
				return;
			}

			// No PATH_INFO?... What about QUERY_STRING?
			$path =  (isset($_SERVER['QUERY_STRING'])) ? $_SERVER['QUERY_STRING'] : @getenv('QUERY_STRING');
			if (trim($path, '/') != '')
			{
				$this->uri_string = $path;
				return;
			}

			// No QUERY_STRING?... Maybe the ORIG_PATH_INFO variable exists?
			$path = (isset($_SERVER['ORIG_PATH_INFO'])) ? $_SERVER['ORIG_PATH_INFO'] : @getenv('ORIG_PATH_INFO');
			if (trim($path, '/') != '' && $path != "/".SELF)
			{
				// remove path and script information so we have good URI data
				$this->uri_string = str_replace($_SERVER['SCRIPT_NAME'], '', $path);
				return;
			}

			// We've exhausted all our options...
			$this->uri_string = '';
		}
		else
		{
			$uri = strtoupper($this->config->item('uri_protocol'));

			if (is_array($_GET) && isset($_GET['p']) && trim($_GET['p'], '/') != '')
			{
				$this->uri_string = $_GET['p'];
				return;
			}
			if ($uri == 'REQUEST_URI')
			{
				$this->uri_string = $this->_parse_request_uri();
				return;
			}

			$this->uri_string = (isset($_SERVER[$uri])) ? $_SERVER[$uri] : @getenv($uri);
		}

		// If the URI contains only a slash we'll kill it
		if ($this->uri_string == '/')
		{
			$this->uri_string = '';
		}
	}
    function auto_segment($input = array ())
    {
        if (is_array($input) and count($input) > 0)
        {
            $this->filters = array_merge($input, $this->filters);
        }

        $data = array ();
        $not = array ();

        foreach ($this->segments as $key=>$value)
        {
            $is = elements(trim($value), $this->filters, FALSE);

            if ($is !== FALSE)
            {
                if ( isset ($this->segments[($key+1)]))
                {
                    $data[$value] = $this->segments[($key+1)];
                    array_push($not, ($key+1));
                }
                else
                {
                    $data[$value] = $this->filters[$value];
                }
            }
            elseif (!in_array($key, $not))
            {
                $data[$key] = $value;

            }
        }

        $this->auto_segments = $data;
    }
    function smart($key = 'id')
    {
        return ( isset ($this->auto_segments[$key]) ? $this->auto_segments[$key] : $this->filters[$key]);
    }
}
?>
