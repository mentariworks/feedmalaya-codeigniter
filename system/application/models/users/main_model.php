<?php

class Main_model extends Model
{
	public function index()
	{
		$start = $this->uri->segment( 3, 0 );
		return $this->fetch( $start, " 1=1 " );
	}
	public function single()
	{
		$id = $this->uri->segment( 3, 0 );
		$filter = ( intval( $id ) ? " u.user_id=$id "  : " 1=1 " );
		
		return $this->fetch( 0, $filter );
	}
	public function fetch( $start = 0, $filter = " 1=1 " )
	{
		$data = array();
		$limit = (int)$this->option->get( 'table_list' );
		
		$sql_all = "SELECT u.user_id, u.* , COUNT( DISTINCT r.rel_id ) AS like_total, COUNT( DISTINCT s.rel_id ) AS hide_total 
		FROM feed_user u 
		LEFT JOIN feed_relationship r 
			ON ( r.rel_type=2 AND r.rel_attribute = u.user_id ) 
		LEFT JOIN feed_relationship s 
			ON ( s.rel_type=3 AND s.rel_attribute = u.user_id ) 
		WHERE $filter 
		GROUP BY u.user_id
		ORDER BY u.user_name ASC";
		$sql = "$sql_all LIMIT $start, $limit";
		
		$query = $this->db->query( $sql );
		
		foreach ( $query->result_array() as $row ) :
			$schema = array(
				'id' => $row['user_id'],
				'name' => $row['user_name'],
				'first' => $row['user_firstname'],
				'last' => $row['user_lastname'],
				'image' => $row['user_image'],
				'url' => $row['user_profile_url'],
				'like' => $row['like_total'],
				'hide' => $row['hide_total']
			);
			
			array_push( $data, $schema );
		endforeach;
		
		$query_all = $this->db->query( $sql_all );
		$total = $query_all->num_rows();
		
		$config = array(
			'base_url' => site_url( 'users/main' ),
			'total_rows' => $total,
			'per_page' => $limit,
			'cur_page' => $start,
			'num_links' => 4
		);
		
		$this->pagination->initialize( $config );
		
		$output = array(
			'users' => $data,
			'pagination' => $this->pagination->create_links()
		);
		
		return $output;
	}
	
	public function latest( $id = 0 )
	{
		$data = array(
			'likes' => array(),
			'hides' => array()
		);
		
		$sql_like = "SELECT r.*, i.* 
		FROM feed_item i, feed_relationship r 
		WHERE i.item_id=r.rel_value 
		AND r.rel_type=2 
		AND r.rel_attribute=? 
		ORDER BY r.rel_date DESC 
		LIMIT 20";
		
		$sql_hide = "SELECT r.*, s.* 
		FROM feed_site s, feed_relationship r 
		WHERE s.site_id=r.rel_value 
		AND r.rel_type=3 
		AND r.rel_attribute=? 
		ORDER BY r.rel_date DESC 
		LIMIT 20";
		
		$query_like = $this->db->query( $sql_like, array( $id ) );
		$query_hide = $this->db->query( $sql_hide, array( $id ) );
		
		foreach ( $query_like->result_array() as $row ) :
			$schema = array(
				'id' => $row['item_id'],
				'slug' => $row['item_slug'],
				'title' => $row['item_title'],
				'anchor' => anchor( 'view/' . $row['item_slug'] . '/' . $row['item_id'], $row['item_title'] )
			);
			
			array_push( $data['likes'], $schema );
		endforeach;
		
		foreach ( $query_hide->result_array() as $row ) :
			$schema = array(
				'id' => $row['site_id'],
				'title' => $row['site_name'],
				'anchor' => anchor( 'site/' . $row['site_id'], $row['site_name'] )
			);
			
			array_push( $data['hides'], $schema );
		endforeach;
		
		return $data;
	}
	
}

?>