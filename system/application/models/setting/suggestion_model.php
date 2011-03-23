<?php

class Suggestion_model extends Model
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
		
		$table_name = "feed_suggest";
		$table_join = "feed_user";
		
		//Build contents query
		$this->db
			->select( "$table_name.suggest_id, $table_name.suggest_url, $table_name.suggest_desc, $table_join.user_name AS suggest_by" )
			->from( $table_name )
			->where( "$table_name.suggest_status", 0 )
			->join( $table_join, "$table_name.suggest_userid=$table_join.user_id", "left" );
		$this->ci->flexigrid->build_query();
		
		//Get contents
		$query = $this->db->get();
		
		foreach ( $query->result_array() as $row ) :
			$schema = array(
				$row['suggest_id'],
				$row['suggest_id'],
				anchor( $row['suggest_url'], $row['suggest_url'], 'target="_blank"' ),
				$row['suggest_by'],
				$row['suggest_desc'],
				''
			);
			
			array_push( $data, $schema );
		endforeach;
		
		//Build count query
		$this->ci->flexigrid->build_query( FALSE );
		$query_all = $this->db->from( $table_name )->where( "suggest_status", 0 )->get();
		$count = $query_all->num_rows();
	
		//Return all
		return array(
			'items' => $data,
			'count' => $count
		);
	}
	
	public function add()
	{
		$itemsList = $this->input->post('items');
		$items = explode( ',', $itemsList );
		
		$sql = "SELECT s.* FROM feed_suggest s WHERE s.suggest_id=?";
		$sql_update = "UPDATE feed_suggest SET suggest_status=1 WHERE suggest_id=?";
		$sql_insert = "INSERT INTO feed_site (site_url, site_query, site_status, site_lastquery) VALUES (?, 0, 0, 0)";
		
		if ( is_array( $items ) AND count( $items ) > 0 ) :
			foreach ( $items as $item ) :
				$query = $this->db->query( $sql, array( $item ) );
				
				if ( $query->num_rows() > 0 ) :
					$row = $query->row();
					
					$this->db->query( $sql_insert, array( $row->suggest_url ) );
					$this->db->query( $sql_update, array( $row->suggest_id ) );
				endif;
			endforeach;
		endif;
		
		return "Added";
	}
	
	public function remove()
	{
		$itemsList = $this->input->post('items');
		$items = explode( ',', $itemsList );
		
		$sql = "SELECT s.* FROM feed_suggest s WHERE s.suggest_id=?";
		$sql_update = "UPDATE feed_suggest SET suggest_status=2 WHERE suggest_id=?";
		
		if ( is_array( $items ) AND count( $items ) > 0 ) :
			foreach ( $items as $item ) :
				$query = $this->db->query( $sql, array( $item ) );
				
				if ( $query->num_rows() > 0 ) :
					$row = $query->row();
					
					$this->db->query( $sql_update, array( $row->suggest_id ) );
				endif;
			endforeach;
		endif;
		
		return "Removed";
	}
}

?>