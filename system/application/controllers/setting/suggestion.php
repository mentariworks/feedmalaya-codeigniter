<?php

class Suggestion extends Controller
{
	function __construct()
	{
		parent::Controller();
		$this->load->model( 'setting/Suggestion_model', 'model' );
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
			'suggest_id' => array( 'ID', 40, TRUE, 'right', 1),
			'suggest_url' => array( 'URL', 250, TRUE, 'left', 2),
			'suggest_by' => array( 'Who Suggest', 225, TRUE, 'left', 0),
			'suggest_desc' => array( 'Reason', 225, TRUE, 'left', 0)
		);
		$params = array(
			'width' => 'auto',
			'height' => 300,
			'rp' => 10,
			'rpOptions' => '[10,15,20,25,40]',
			'pagestat' => 'Displaying: {from} to {to} of {total} items.',
			'blockOpacity' => 0.5,
			'title' => 'Suggested Site Management',
			'showTableToggleBtn' => FALSE
		);
		
		$buttons = array(
			array( 'Approve', 'add', 'Js.data.setting.suggestion.add' ),
			array( 'Reject', 'delete', 'Js.data.setting.suggestion.remove' ),
			array( 'separator' ),
			array( 'Select All', 'add', 'Js.data.setting.suggestion.tick' ),
			array( 'DeSelect All', 'delete','Js.data.setting.suggestion.tick' )
		);
		
		$identity = 'flex-setup-suggest';
		$grid = build_grid_js(
			$identity,
			site_url("setting/suggestion/ajax"),
			$cols,
			'suggest_id',
			'ASC',
			$params,
			$buttons,
			$this->_fetch()
		);
		
		$data = array(
			'grid' => $grid,
			'identity' => $identity
		);
		
		$this->ui->set_title( 'Suggested Site Management' );
		$this->ui->view( 'setting/flexigrid', $data );
		$this->ui->view( 'setting/suggestion', $data );
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
		
		$valid_fields = array( 'suggest_id', 'suggest_url', 'suggest_desc' );
		$this->flexigrid->validate_post( 'suggest_id', 'ASC', $valid_fields );
		$data = $this->model->index();
		
		return $this->flexigrid->json_build( $data['count'], $data['items'] );
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
}

?>