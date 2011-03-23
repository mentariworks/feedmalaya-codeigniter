<?php

class Category extends Controller
{
	function __construct()
	{
		parent::Controller();
		$this->load->model( 'setting/Category_model', 'model' );
		$this->ui->set_file( 'page' );
	}
	function _remap()
	{
		$map = $this->uri->segment(3, 'index');
		
		if ( $this->auth['role'] == 1 ) :
			$this->$map();
		else :
			show_404();
		endif;
	}
	public function index()
	{
		$this->load->helper( 'flexigrid' );
		
		$cols = array(
			'cat_id' => array( 'ID', 40, TRUE, 'right', 1),
			'cat_name' => array( 'Name', 250, TRUE, 'left', 2),
			'cat_slug' => array( 'Slug', 150, TRUE, 'left', 0)
		);
		$params = array(
			'width' => 'auto',
			'height' => 300,
			'rp' => 10,
			'rpOptions' => '[10,15,20,25,40]',
			'pagestat' => 'Displaying: {from} to {to} of {total} items.',
			'blockOpacity' => 0.5,
			'title' => 'List of Categories',
			'showTableToggleBtn' => FALSE,
			'singleSelect' => TRUE
		);
		
		$buttons = NULL;
		
		$identity = 'flex-setup-suggest';
		$grid = build_grid_js(
			$identity,
			site_url("setting/category/ajax"),
			$cols,
			'cat_id',
			'ASC',
			$params,
			$buttons,
			$this->_fetch()
		);
		
		$data = array(
			'grid' => $grid,
			'identity' => $identity
		);
		
		$this->ui->set_title( 'List of Categories' );
		$this->ui->view( 'setting/flexigrid', $data );
		$this->ui->render();
	}
	public function ajax()
	{
		$this->output->set_header( $this->config->item('json_header') );
		$this->output->set_output( $this->_fetch() );
	}
	private function _fetch()
	{
		$this->load->library( 'flexigrid' );
		
		$valid_fields = array( 'cat_id', 'cat_name' );
		$this->flexigrid->validate_post( 'cat_id', 'ASC', $valid_fields );
		$data = $this->model->index();
		
		$this->output->set_header( $this->config->item('json_header') );
		
		return $this->flexigrid->json_build( $data['count'], $data['items'] );
	}
}

?>