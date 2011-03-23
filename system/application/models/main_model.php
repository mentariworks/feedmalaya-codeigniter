<?php

class Main_model extends Model
{
	public $API = FALSE;
	public $caller = '';
	
	public function index()
	{
		$start = $this->uri->rsegment(3, 0);
		$segment = "browse";
		
		return $this->fetch( $start, $segment );
	}
	public function api()
	{
		$id = $this->uri->segment(3, 0);
		$start = $this->uri->segment(4, 0);
		$segment = "api/$id";
		
		$this->API = TRUE;
		
		return $this->fetch( $start, $segment );
	}
	public function single()
	{
		$id = $this->uri->rsegment(3, 0);
		
		$filter = ( intval( $id ) ? " i.item_id=$id " : " 1=1 " );
		$segment = "item/$id";
		
		return $this->fetch( 0, $segment, $filter , FALSE);
	}
	public function author()
	{
		$id = $this->uri->rsegment(3, 0);
		$start = $this->uri->rsegment(4, 0);
		
		$filter = ( intval( $id ) ? " a.author_id=$id " : " 1=1 " );
		$segment = "author/$id";
		
		return $this->fetch( $start, $segment, $filter );
	}
	public function site()
	{
		$id = $this->uri->rsegment(3, 0);
		$start = $this->uri->rsegment(4, 0);
		
		$filter = ( intval( $id ) ? " i.item_site=$id "  : " 1=1 " );
		$segment = "site/$id";
		
		return $this->fetch( $start, $segment, $filter, FALSE );
	}
	public function category()
	{
		$id = $this->uri->rsegment(3, 0);
		$start = $this->uri->rsegment(4, 0);
		
		$filter = ( trim( $id ) !== '' ? " i.item_id IN ( SELECT r.rel_value  
			FROM feed_category c, feed_relationship r
			WHERE c.cat_slug='$id' AND c.cat_id=r.rel_attribute AND r.rel_type=1 ) "  : NULL );
		$segment = "category/$id";
		
		return $this->fetch( $start, $segment, $filter );
	}
	public function fetch( $start = 0, $segment = 'index', $filter = " 1=1 ", $hide = TRUE )
	{
		$data = array();
		$limit = $this->option->get('table_list');
		
		$filter_user = $this->_filter_hide_user($hide);
		
		$sql_all = "SELECT i.*, a.author_name, s.site_name   
			FROM feed_item i 
			LEFT JOIN feed_author a 
				ON (a.author_id=i.item_author) 
			LEFT JOIN feed_site s 
				ON (s.site_id=i.item_site ) 
			WHERE $filter $filter_user 
			AND s.site_status=1 
			AND i.item_datetime<SYSDATE() 
			AND i.item_status=1 
			ORDER BY i.item_datetime DESC";
		
		$sql = "$sql_all LIMIT $start, $limit";
		$query = $this->db->query( $sql );
		
		foreach ( $query->result_array() as $row ) :
			$cat = $this->_get_category( $row['item_id'] );
			
			$schema = array(
				'id' => $row['item_id'],
				'title' => $row['item_title'] ,
				'slug' => $row['item_slug'] ,
				'excerpt' => $row['item_excerpt'],
				'author' => array(
					'id' => $row['item_author'],
					'name' => $row['author_name']
				),
				'site' => array(
					'id' => $row['item_site'],
					'name' => $row['site_name']
				),
				'datetime' => strtotime( $row['item_datetime'] ),
				'category' => array(
					'id' => $cat['id'],
					'name' => $cat['name']
				)
			);
			
			if ( $this->API === FALSE ) :
				$schema['category']['anchor'] = $cat['anchor'];
			endif;
			
			array_push( $data, $schema );
		endforeach;
		
		$query_all = $this->db->query( $sql_all );
		$total = $query_all->num_rows();
		
		$caller = $this->_get_caller();
		
		$config = array(
			'base_url' => site_url( $caller . $segment ),
			'total_rows' => $total,
			'per_page' => $limit,
			'cur_page' => $start,
			'uri_segment' => FALSE,
			'num_links' => 4
		);
		
		$this->pagination->initialize( $config );
		
		$output = array(
			'items' => $data
		);
		
		if ( $this->API === FALSE ) :
			$output['pagination'] = $this->pagination->create_links();
		endif;
		
		return $output;
		
	}
	private function _get_category( $id = 0 )
	{
		$data = array(
			'id' => array(),
			'name' => array()
		);
		
		
		if ( $this->API === FALSE ) :
			$data['anchor'] = array();
		endif;
		
		$sql = "SELECT c.* 
			FROM feed_category c, feed_relationship r
			WHERE (c.cat_id=r.rel_attribute AND r.rel_type=1) 
			AND r.rel_value=?";
		
		$query = $this->db->query( $sql, array( $id ) );
		
		foreach ( $query->result_array() as $row ) :
			array_push( $data['id'], $row['cat_id'] );
			array_push( $data['name'], $row['cat_name'] );
			
			if ( $this->API === FALSE ) :
				$caller = $this->_get_caller();
				array_push( $data['anchor'], anchor( $caller . 'category/' . $row['cat_slug'], $row['cat_name'] ) );
			endif;
		endforeach;
		
		return $data;
	}
	private function _filter_hide_user( $hide = TRUE )
	{
		$filter = "";
		$id = $this->auth['id'];
		
		if ( $id > 0 AND $hide === TRUE ) :
			$filter = " AND i.item_site NOT IN (SELECT h.rel_value FROM feed_relationship h WHERE h.rel_attribute=$id AND h.rel_type=3) ";
		endif;
		
		return $filter;
	}
	private function _get_caller()
	{
		return (trim($this->caller) !== '' ? $this->caller . '/' : '' );
	}
}

?>