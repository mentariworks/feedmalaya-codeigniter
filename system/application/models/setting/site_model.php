<?php

class Site_model extends Model
{
	public $reference = array(
		'query' => array( 'No', 'Yes', 'No' ),
		'status' => array( 'Pending', 'Active', 'Not Active' )
	);
	function __construct()
	{
		parent::Model();
		$this->ci =& get_instance();
	}
	
	public $ci = NULL;
	
	public function index()
	{
		$data = array();
		
		$table_name = "feed_site";
		
		//Build contents query
		$this->db->select( "site_id, site_url, site_feed, site_name, site_query, site_lastquery, site_status" )->from( $table_name );
		$this->ci->flexigrid->build_query();
		
		//Get contents
		$query = $this->db->get();
		
		foreach ( $query->result_array() as $row ) :
			$schema = array(
				$row['site_id'],
				$row['site_id'],
				$row['site_feed'],
				anchor( $row['site_url'], $row['site_name'] ),
				$this->reference['query'][$row['site_query']],
				$row['site_lastquery'],
				$this->reference['status'][$row['site_status']]
			);
			
			array_push( $data, $schema );
		endforeach;
		
		//Build count query
		$this->ci->flexigrid->build_query( FALSE );
		$query_all = $this->db->get( $table_name );
		$count = $query_all->num_rows();
	
		//Return all
		return array(
			'items' => $data,
			'count' => $count
		);
	}
	public function update()
	{
		$id = $this->input->post('site_id');
		$feed = $this->input->post('site_feed');
		$name = $this->input->post('site_name');
		
		$code = 0;
		
		$sql = "SELECT s.* FROM feed_site s WHERE s.site_id=?";
		$sql_update = "UPDATE feed_site SET site_feed=?, site_name=? WHERE site_id=?";
		
		if ( $this->auth['role'] == 1 ) :
			$query = $this->db->query( $sql, array( $id ) );
					
			if ( $query->num_rows() > 0 ) :
				$this->db->query( $sql_update, array( $feed, $name, $id ) );
			endif;
			$code = 1;
		else :
			$code = 2;
		endif;
		
		return array( 'code' => $code );
	}
	public function add()
	{
		$item = $this->input->post('items');
		
		$sql = "SELECT s.* FROM feed_site s WHERE s.site_id=?";
		$sql_update = "UPDATE feed_site SET site_status=1, site_query=1 WHERE site_id=?";
		
		$query = $this->db->query( $sql, array( $item ) );
		if ( $query->num_rows() > 0 ) :
			$row = $query->row();
			$this->db->query( $sql_update, array( $row->site_id ) );
		endif;
		
		return "Add";
	}
	public function remove()
	{
		$item = $this->input->post('items');
		
		$sql = "SELECT s.* FROM feed_site s WHERE s.site_id=?";
		$sql_update = "UPDATE feed_site SET site_query=0 WHERE site_id=?";
		
		$query = $this->db->query( $sql, array( $item ) );
		if ( $query->num_rows() > 0 ) :
			$row = $query->row();
			$this->db->query( $sql_update, array( $row->site_id ) );
		endif;
		
		return "Removed";
	}
	public function activate()
	{
		$item = $this->input->post('items');
		
		$sql = "SELECT s.* FROM feed_site s WHERE s.site_id=?";
		$sql_update = "UPDATE feed_site SET site_status=1 WHERE site_id=?";
		
		$query = $this->db->query( $sql, array( $item ) );
		if ( $query->num_rows() > 0 ) :
			$row = $query->row();
			$this->db->query( $sql_update, array( $row->site_id ) );
		endif;
		
		return "Activate";
	}
	public function deactivate()
	{
		$item = $this->input->post('items');
		
		$sql = "SELECT s.* FROM feed_site s WHERE s.site_id=?";
		$sql_update = "UPDATE feed_site SET site_status=2 WHERE site_id=?";
		
		$query = $this->db->query( $sql, array( $item ) );
		if ( $query->num_rows() > 0 ) :
			$row = $query->row();
			$this->db->query( $sql_update, array( $row->site_id ) );
		endif;
		
		return "Deactivate";
	}
}

?>