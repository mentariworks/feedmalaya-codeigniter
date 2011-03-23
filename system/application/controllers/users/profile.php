<?php

class Profile extends Controller
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
		$data = $this->model->single();
		
		if ( !( is_array( $data['users'] ) AND count( $data['users'] ) > 0 ) ) :
			show_404();
		else :
			$latest = $this->model->latest( $data['users'][0]['id'] );
			$data = array_merge( $data, $latest );
			
			$this->ui->set_title( $data['users'][0]['name'] );
			$this->ui->view( 'users/single', $data );
			$this->ui->view( 'sidebar', NULL, 'sidebar' );
			$this->ui->render();
		endif;
		
	}
}

?>