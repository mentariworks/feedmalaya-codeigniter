<?php

class Option extends Controller
{
	function __construct()
	{
		parent::Controller();
		$this->load->model( 'setting/Option_model', 'model' );
		$this->load->model( 'Lookup_model', 'lookup' );
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
			'option_id' => array( 'ID', 40, TRUE, 'right', 1 ),
			'option_name' => array( 'Name', 250, TRUE, 'left', 0 ),
			'option_value' => array( 'Value', 250, TRUE, 'left', 0 )
			
		);
		$params = array(
			'width' => 'auto',
			'height' => 300,
			'rp' => 10,
			'rpOptions' => '[10,15,20,25,40]',
			'pagestat' => 'Displaying: {from} to {to} of {total} items.',
			'blockOpacity' => 0.5,
			'title' => 'Option Management',
			'showTableToggleBtn' => FALSE,
			'singleSelect' => TRUE
		);
		
		$buttons = array(
			array( 'Edit', 'add', 'Js.data.setting.site.edit' ),
			array( 'separator' ),
			array( 'Fetch', 'add', 'Js.data.setting.site.addQueeue' ),
			array( 'Stop', 'delete', 'Js.data.setting.site.removeQueeue' ),
			array( 'separator' ),
			array( 'Activate', 'add', 'Js.data.setting.site.activate' ),
			array( 'Deactivate', 'delete', 'Js.data.setting.site.deActivate' )
		);
		
		$identity = 'flex-setup-site';
		$grid = build_grid_js(
			$identity,
			site_url("setting/site/ajax"),
			$cols,
			'site_id',
			'ASC',
			$params,
			$buttons
		);
		
		$data = array(
			'grid' => $grid,
			'identity' => $identity
		);
		
		$this->ui->set_title( 'Site Management' );
		$this->ui->view( 'setting/flexigrid', $data );
		$this->ui->view( 'setting/site', $data );
		$this->ui->render();
	}
	public function ajax()
	{
		$this->load->library( 'flexigrid' );
		
		$valid_fields = array( 'site_id', 'site_feed', 'site_name', 'site_fetch', 'site_lastquery', 'site_status' );
		$this->flexigrid->validate_post( 'site_id', 'ASC', $valid_fields );
		$data = $this->model->index();
		
		$this->output->set_header( $this->config->item('json_header') );
		
		$this->output->set_output( 
			$this->flexigrid->json_build( $data['count'], $data['items'] )
		);
	}
	public function edit()
	{
		$id = $this->uri->segment(4, 0);
		
		if ( intval( $id ) AND $id > 0 ) :
			$data = $this->lookup->site( $id );
			$this->ui->set_output('text');
			$this->ui->data( $this->load->view( "setting/site-edit", $data, TRUE ) );
			$this->ui->render();	
		endif;
	}
	public function update()
	{
		$this->ui->set_output( 'json' );
		$this->ui->data( $this->model->update() );
		$this->ui->render();
	}
	public function add()
	{
		$this->ui->set_output( 'text' );
		$this->ui->data( $this->model->add() );
		$this->ui->render();
	}
	public function remove()
	{
		$this->ui->set_output( 'text' );
		$this->ui->data( $this->model->remove() );
		$this->ui->render();
	}
	public function activate()
	{
		$this->ui->set_output( 'text' );
		$this->ui->data( $this->model->activate() );
		$this->ui->render();
	}
	public function deactivate()
	{
		$this->ui->set_output( 'text' );
		$this->ui->data( $this->model->deactivate() );
		$this->ui->render();
	}
}

?>