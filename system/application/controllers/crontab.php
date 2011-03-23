<?php

class Crontab extends Controller
{
	function __construct()
	{
		parent::Controller();
		$this->load->model( 'Crontab_model', 'model' );
		$this->load->library( 'simplepie' );
		$this->load->helper( array(
			'string',
			'text'
		) );
	}
	
	public $FORCE = FALSE;
	
	public function index()
	{
		$this->FORCE = TRUE;
		$this->_fetch();
		$this->output->enable_profiler(TRUE);
	}
	public function pop()
	{
		$this->option->update('fetch_timestamp', 0);
	}
	public function image()
	{
		//$this->_fetch();
		echo file_get_contents( './public/images/g.gif');
	}
	
	private function _fetch()
	{
		$timestamp = (int)$this->option->get('fetch_timestamp');
		$interval = (int)$this->option->get('fetch_interval');
		$site = $this->model->get_site_data();
		
		$interval = round( 86400 / ( $site['total'] * (int)$this->option->get('fetch_daily_count') ) );
		
		$this->option->update( 'fetch_interval', $interval );
		
		$now = time();
		
		if ( ( $timestamp + $interval ) < $now OR $this->FORCE === TRUE ) :
			
			if ( $site['id'] > 0 ) :
				
				$this->simplepie->set_feed_url( $site['feed'] );
				$this->simplepie->set_autodiscovery_level( SIMPLEPIE_LOCATOR_ALL );
				$this->simplepie->force_feed( TRUE );
				$this->simplepie->set_cache_location( BASEPATH . 'cache' );
				$this->simplepie->set_item_limit( 5 );
				$this->simplepie->set_cache_duration( 1800 );
				$this->simplepie->init();
				$this->simplepie->handle_content_type();
				
				$this->model->update_site_data( $site['id'], $this->simplepie->subscribe_url(), $this->simplepie->get_title() );
				
				$items = $this->simplepie->get_items(0, 5);
				$this->model->save_items( $items, $site['id'] );
			endif;
			
			$this->option->update( 'fetch_timestamp', $now );
		endif;
	}
}

?>