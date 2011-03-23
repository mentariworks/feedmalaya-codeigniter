<?php

class Main extends Controller
{
	function __construct()
	{
		parent::Controller();
		$this->load->model( 'users/Main_model', 'model' );
		$this->load->library( 'pagination' );
	}
	public function _remap()
	{
		$this->index();
	}
	public function index()
	{
		$data = $this->model->index();
		
		$this->ui->set_title( 'List of Members' );
		$this->ui->view( 'users/index', $data );
		$this->ui->view( 'sidebar', NULL, 'sidebar' );
		$this->ui->render();
	}
}

?>