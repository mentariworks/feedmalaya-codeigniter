<?php

class Item extends Controller
{
	function __construct()
	{
		parent::Controller();
		$this->load->model( 'Item_model', 'model' );
		$this->load->scaffolding('feed_site');
	}
	public function visit()
	{
		$id = $this->uri->rsegment(3, 0);
		
		if ( intval( $id ) AND (int)$id > 0 ) :
			$location = $this->model->visit( $id );
			redirect( $location, 'location', 301 );
		else :
			show_404();
		endif;
	}
	public function like()
	{
		$id = $this->uri->segment(3, 0);
		
		$this->ui->set_output( 'json' );
		$this->ui->data( $this->model->like( $id ) );
		$this->ui->render();
	}
	public function unlike()
	{
		$id = $this->uri->segment(3, 0);
		
		$this->ui->set_output( 'json' );
		$this->ui->data( $this->model->unlike( $id ) );
		$this->ui->render();
	}
}

?>