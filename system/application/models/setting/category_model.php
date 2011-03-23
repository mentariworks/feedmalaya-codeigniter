<?php

class Category_model extends Model
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
		
		$table_name = "feed_category";
		
		//Build contents query
		$this->db->select( "cat_id, cat_name, cat_slug" )->from( $table_name );
		$this->ci->flexigrid->build_query();
		
		//Get contents
		$query = $this->db->get();
		
		foreach ( $query->result_array() as $row ) :
			$schema = array(
				$row['cat_id'],
				$row['cat_id'],
				$row['cat_name'],
				$row['cat_slug']
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
}

?>