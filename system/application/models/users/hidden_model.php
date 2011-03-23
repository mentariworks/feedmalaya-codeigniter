<?php

class Hidden_model extends Model
{
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
		$table_join = "feed_relationship";
		
		//Build contents query
		$this->db
			->select( "$table_join.rel_id, $table_name.site_id, $table_name.site_name" )
			->from( $table_name )
			->join( $table_join, "$table_join.rel_value=$table_name.site_id", "inner" );
		
		$this->ci->flexigrid->build_query();
		
		$this->db->where( "$table_join.rel_attribute", $this->auth['id'] );
		$this->db->where( "$table_join.rel_type", 3 );
		
		//Get contents
		$query = $this->db->get();
		
		foreach ( $query->result_array() as $row ) :
			$schema = array(
				$row['rel_id'],
				$row['site_id'],
				anchor( 'main/site/' . $row['site_id'], $row['site_name'] )
			);
			
			array_push( $data, $schema );
		endforeach;
		
		//Build count query
		$this->ci->flexigrid->build_query( FALSE );
		$query_all = $this->db
			->select( "$table_join.rel_id, $table_name.site_id, $table_name.site_name" )
			->from( $table_name )
			->join( $table_join, "$table_join.rel_value=$table_name.site_id", "inner" )
			->where( "$table_join.rel_attribute", $this->auth['id'] )
			->where( "$table_join.rel_type", 3 )
			->get();
		
		$count = $query_all->num_rows();
	
		//Return all
		return array(
			'items' => $data,
			'count' => $count
		);
	}
	public function add()
	{
		$item = $this->uri->segment(4, 0);
		$sql = "SELECT * FROM feed_relationship WHERE rel_type=3 AND rel_attribute=? AND rel_value=?";
		$sql_insert = "INSERT INTO feed_relationship (rel_type, rel_attribute, rel_value) VALUES (3, ?, ?)";
		
		$schema = array( $this->auth['id'], $item );
		$query = $this->db->query( $sql, $schema );
		
		if ( $query->num_rows() < 1 ) :
			$this->db->query( $sql_insert, $schema );
			
			return array( 
				'SUIXHR' => TRUE,
				'callback' => 'Js.plugin.showDialog',
				'text' => array(
					'title' => 'Server Response',
					'content' => 'This site will no longer be shown while you logged in'
				)
			);
		else :
			return array( 
				'SUIXHR' => TRUE,
				'callback' => 'Js.plugin.showDialog',
				'text' => array(
					'title' => 'Server Response',
					'content' => 'This is an invalid request, We are unable to process your request'
				)
			);
		endif;
	}
	public function remove()
	{
		$itemsList = $this->input->post('items');
		$items = explode( ',', $itemsList );
		
		$sql_delete = "DELETE FROM feed_relationship WHERE rel_type=3 AND rel_id=? AND rel_attribute=?";
		
		if ( is_array( $items ) AND count( $items ) > 0 ) :
			foreach ( $items as $item ) :
				$this->db->query( $sql_delete, array( 
					$item,
					$this->auth['id']
				) );
			endforeach;
		endif;
		
		return "Removed";
	}
}

?>