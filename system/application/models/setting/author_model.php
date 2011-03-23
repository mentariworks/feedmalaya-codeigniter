<?php

class Author_model extends Model
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
		
		$table_name = "feed_author";
		$table_join = "feed_site";
		
		//Build contents query
		$this->db->select( "$table_name.author_id, $table_name.author_name, $table_join.site_name" )->from( $table_name );
		$this->db->join( $table_join, "$table_name.author_site=$table_join.site_id", "left" );
		$this->ci->flexigrid->build_query();
		
		//Get contents
		$query = $this->db->get();
		
		foreach ( $query->result_array() as $row ) :
			$schema = array(
				$row['author_id'],
				$row['author_id'],
				$row['author_name'],
				$row['site_name']
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