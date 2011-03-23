<?php

class Suggest extends Controller
{
	function __construct()
	{
		parent::Controller();
		$this->load->model( 'Suggest_model', 'model' );
	}
	public function index()
	{
		$this->ui->set_output('text');
		
		$file = ( $this->auth['id'] > 0 ? 'form' : 'forbidden' );
		
		$this->ui->data( $this->load->view( "suggest/$file", NULL, TRUE ) );
		$this->ui->render();
	}
	
	public function save()
	{
		$this->ui->set_output('json');
		$this->ui->data( $this->model->save() );
		$this->ui->render();
	}
}

?>