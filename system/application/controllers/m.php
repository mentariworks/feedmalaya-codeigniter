<?php

class M extends Controller
{
	function __construct()
	{
		parent::Controller();
		redirect('mobile', 'location', 301 );
	}
}

?>