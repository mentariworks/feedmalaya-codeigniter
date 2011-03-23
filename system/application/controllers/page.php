<?php

class Page extends Controller
{
	public $page = array(
		'login' => 'How to Login or Register?',
		'faq' => 'Frequently Asked Questions',
		'our-mission' => 'Our Mission'
	);
	function __construct()
	{
		parent::Controller();
		
	}
	private function _fetch( $id )
	{
		$title = url_title( $id );
		
		if ( isset( $this->page[$title] ) ) :
			$this->ui->set_title( $this->page[$title] );
		endif;
		
		$this->ui->view( 'page/' . $title  );
		$this->ui->view( 'sidebar', NULL, 'sidebar' );
		$this->ui->render();
	}
	public function _remap()
	{
		$page_id = $this->uri->segment( 2, '' );
		
		if ( trim( $page_id ) !== '' ) :
			$this->_fetch( $page_id );
		else :
			show_404();
		endif;
	}
}

?>