<?php

class Mobile extends Controller
{
	function __construct()
	{
		parent::Controller();
		$this->load->model( 'Main_model', 'model' );
		$this->load->model( 'Lookup_model', 'lookup_model' );
		$this->load->library( array(
			'pagination'
		) );
		$this->ui->set_template( 'mobile/' );
		$this->model->caller = 'mobile';
	}
	public function index()
	{
		$data = $this->model->index();
		
		$start = $this->uri->segment(3, 0);
		$limit = (int)$this->option->get('table_list');
		
		if ( $start > 0 ) :
			$this->ui->set_title("Page ". ( floor( $start / $limit ) + 1 ) . " &mdash; Latest" );
		endif;
		
		$this->ui->view( 'mobile/index', $data );
		$this->ui->render();
		
	}
	public function author()
	{
		$data = $this->model->author();
		
		$id = $this->uri->segment(3, 0);
		$start = $this->uri->segment(4, 0);
		$limit = (int)$this->option->get('table_list');
		
		$title = "";
		if ( $start > 0 ) :
			$title = "Page ". ( floor( $start / $limit ) + 1 ) . " &mdash; ";
		endif;
		$title .= "By " . $this->lookup_model->author_name( $id );
		
		$this->ui->set_title( $title );
		$this->ui->view( 'mobile/index', $data );
		$this->ui->render();
	}
	public function site()
	{
		$data = $this->model->site();
		
		$id = $this->uri->segment(3, 0);
		$start = $this->uri->segment(4, 0);
		$limit = (int)$this->option->get('table_list');
		
		$title = "";
		if ( $start > 0 ) :
			$title = "Page ". ( floor( $start / $limit ) + 1 ) . " &mdash; ";
		endif;
		$title .= "From " . $this->lookup_model->site_name( $id );
		
		$this->ui->set_title( $title );
		$this->ui->view( 'mobile/index', $data );
		$this->ui->render();
	}
	public function category()
	{
		$data = $this->model->category();
		
		$id = $this->uri->segment(3, 0);
		$start = $this->uri->segment(4, 0);
		$limit = (int)$this->option->get('table_list');
		
		$title = "";
		if ( $start > 0 ) :
			$title = "Page ". ( floor( $start / $limit ) + 1 ) . " &mdash; ";
		endif;
		$title .= "Tag with " . $this->lookup_model->category_name( $id );
		
		$this->ui->set_title( $title );
		$this->ui->view( 'mobile/index', $data );
		$this->ui->render();
	}
}

?>