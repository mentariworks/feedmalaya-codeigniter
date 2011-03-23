<?php

class Main extends Controller
{
	function __construct()
	{
		parent::Controller();
		$this->load->model( 'Main_model', 'model' );
		$this->load->model( 'Lookup_model', 'lookup_model' );
		$this->load->library( array(
			'pagination'
		) );
	}
	public function api()
	{
		$app = array(
			'site_name' => $this->config->item('site_name', 'application'),
			'description' => 'Feedmalaya is a local blog aggregator that pull out the best content from bloggers.',
			'current_link' => current_url(),
			'link' => site_url(),
			'pubDate' => date('r'),
			'generator' => 'http://feedmalaya.com',
			'language' => 'en'
		);
		
		$data = $this->model->api();
		$data = array_merge( $app, $data );
		$output = $this->uri->segment(3, 0);
		
		if ( $output === 'rss' ) :
			echo '<?xml version="1.0" encoding="UTF-8"?>';
			$this->load->view( 'main/feed', $data );
		elseif ( $output === 'json' ) :
			$this->ui->set_output('json');
			$this->ui->data( $data );
			$this->ui->render();
		endif;
	}
	public function index()
	{
		$data = $this->model->index();
		
		$start = $this->uri->rsegment(3, 0);
		$limit = (int)$this->option->get('table_list');
		
		if ( $start > 0 ) :
			$this->ui->set_title("Page ". ( floor( $start / $limit ) + 1 ) . " &mdash; Latest" );
		endif;
		
		$this->ui->view( 'main/index', $data );
		$this->ui->view( 'main/js' );
		$this->ui->view( 'sidebar', NULL, 'sidebar' );
		$this->ui->render();
		
	}
	public function single()
	{
		$data = $this->model->single();
		
		if ( !( is_array( $data['items'] ) AND count( $data['items'] ) > 0 ) ) :
			show_404();
		else :
			$this->ui->set_title( $data['items'][0]['title'] );
			$this->ui->view( 'main/single', $data );
			$this->ui->view( 'main/js' );
			$this->ui->view( 'sidebar', NULL, 'sidebar' );
			$this->ui->render();
		endif;
		
	}
	public function author()
	{
		$data = $this->model->author();
		
		$id = $this->uri->rsegment(3, 0);
		$start = $this->uri->rsegment(4, 0);
		$limit = (int)$this->option->get('table_list');
		$detail = $this->lookup_model->author_name( $id );
		
		
		if ( $detail === FALSE ) :
			show_404();
		else :
			$title = "";
			if ( $start > 0 ) :
				$title = "Page ". ( floor( $start / $limit ) + 1 ) . " &mdash; ";
			endif;
			$title .= "By " . $detail;
			
			$this->ui->set_title( $title );
			$this->ui->view( 'main/index', $data );
			$this->ui->view( 'main/js' );
			$this->ui->view( 'sidebar', NULL, 'sidebar' );
			$this->ui->render();
		endif;
	}
	public function site()
	{
		$data = $this->model->site();
		
		$id = $this->uri->rsegment(3, 0);
		$start = $this->uri->rsegment(4, 0);
		$limit = (int)$this->option->get('table_list');
		$detail = $this->lookup_model->site_name( $id );
		
		if ( $detail === FALSE ) :
			show_404();
		else :
			$title = "";
			if ( $start > 0 ) :
				$title = "Page ". ( floor( $start / $limit ) + 1 ) . " &mdash; ";
			endif;
			$title .= "From " . $detail;
			
			$this->ui->set_title( $title );
			$this->ui->view( 'main/index', $data );
			$this->ui->view( 'main/js' );
			$this->ui->view( 'sidebar', NULL, 'sidebar' );
			$this->ui->render();
		endif;
	}
	public function category()
	{
		$data = $this->model->category();
		
		$id = $this->uri->rsegment(3, 0);
		$start = $this->uri->rsegment(4, 0);
		$limit = (int)$this->option->get('table_list');
		$detail = $this->lookup_model->category_name( $id, 'cat_slug' );
		
		if ( $detail === FALSE ) :
			show_404();
		else :
			$title = "";
			if ( $start > 0 ) :
				$title = "Page ". ( floor( $start / $limit ) + 1 ) . " &mdash; ";
			endif;
			$title .= "Tag with " . $detail;
			
			$this->ui->set_title( $title );
			$this->ui->view( 'main/index', $data );
			$this->ui->view( 'main/js' );
			$this->ui->view( 'sidebar', NULL, 'sidebar' );
			$this->ui->render();
		endif;
	}
}

?>