<?php

class Hidden extends Controller
{
	function __construct()
	{
		parent::Controller();
		$this->load->model( 'users/Hidden_model', 'model' );
	}
	function _remap()
	{
		$map = $this->uri->segment(3, 'index');
		
		if ( $this->auth['id'] > 0 ) :
			$this->$map();
		else :
			show_404();
		endif;
	}
	public function index()
	{
		$this->load->helper( 'flexigrid' );
		
		$cols = array(
			'site_id' => array( 'ID', 40, TRUE, 'right', 1),
			'site_name' => array( 'Name', 350, TRUE, 'left', 2)
		);
		$params = array(
			'width' => 'auto',
			'height' => 300,
			'rp' => 10,
			'rpOptions' => '[10,15,20,25,40]',
			'pagestat' => 'Displaying: {from} to {to} of {total} items.',
			'blockOpacity' => 0.5,
			'title' => 'Hidden Site',
			'showTableToggleBtn' => FALSE
		);
		
		$buttons = array(
			array( 'Show', 'add', 'Js.data.users.hidden.remove' ),
			array( 'separator' ),
			array( 'Select All', 'add', 'Js.data.users.hidden.tick' ),
			array( 'DeSelect All', 'delete','Js.data.users.hidden.tick' )
		);
		
		$identity = 'flex-hidden-site';
		$grid = build_grid_js(
			$identity,
			site_url("users/hidden/ajax"),
			$cols,
			'site_id',
			'ASC',
			$params,
			$buttons,
			$this->_fetch()
		);
		
		$data = array(
			'grid' => $grid,
			'identity' => $identity
		);
		
		$this->ui->set_title( 'Hidden Site' );
		$this->ui->view( 'setting/flexigrid', $data );
		$this->ui->view( 'users/hidden', $data );
		$this->ui->view( 'sidebar', NULL, 'sidebar' );
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
		
		$valid_fields = array( 'site_id', 'site_name' );
		$this->flexigrid->validate_post( 'site_id', 'ASC', $valid_fields );
		$data = $this->model->index();
		
		return $this->flexigrid->json_build( $data['count'], $data['items'] );
	}
	public function add()
	{
		$this->ui->set_output( 'json' );
		$this->ui->data( $this->model->add() );
		$this->ui->render();
	}
	public function remove()
	{
		$this->ui->set_output( 'text' );
		$this->ui->data( $this->model->remove() );
		$this->ui->render();
	}
	
}

?>