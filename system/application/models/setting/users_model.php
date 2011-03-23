<?php

class Users_model extends Model
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
		
		$table_name = "feed_user";
		$table_like = "feed_relationship l";
		$table_hide = "feed_relationship h";
		//Build contents query
		$this->db
			->select( "$table_name.user_id, $table_name.user_name, COUNT( DISTINCT l.rel_id ) AS user_like, COUNT( DISTINCT h.rel_id ) AS user_hide", FALSE )
			->from( $table_name )
			->join( $table_like, "$table_name.user_id=l.rel_attribute AND l.rel_type=2", "left" )
			->join( $table_hide, "$table_name.user_id=h.rel_attribute AND h.rel_type=3", "left" )
			->group_by( "$table_name.user_id" );
		
		$this->ci->flexigrid->build_query();
		
		//Get contents
		$query = $this->db->get();
		
		foreach ( $query->result_array() as $row ) :
			$schema = array(
				$row['user_id'],
				$row['user_id'],
				anchor('users/profile/'. $row['user_id'], $row['user_name']),
				$row['user_like'],
				$row['user_hide']
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