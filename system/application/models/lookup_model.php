<?php

class Lookup_model extends Model
{
	public function site( $id = 0 )
	{
		$data = array( 'id' => 0, 'feed' => '', 'url' => '', 'name' => '' );
		
		$sql = "SELECT s.site_id, s.site_url, s.site_feed, s.site_name 
			FROM feed_site s 
			WHERE s.site_id=?
			ORDER BY s.site_lastquery ASC";
		
		$query = $this->db->query( $sql, array( $id ) );
		
		if ( $query->num_rows() > 0 ) :
			$row = $query->row();
			
			$data['id'] = $row->site_id;
			$data['feed'] = prep_url( $row->site_feed );
			$data['url'] = prep_url( $row->site_url );
			$data['name'] = $row->site_name;
		endif;
		
		
		return $data;
	}
	public function site_name( $id )
	{
		$sql = "SELECT s.site_name FROM feed_site s WHERE s.site_id=? LIMIT 1";
		$query = $this->db->query( $sql, array( $id ) );
		
		if ( $query->num_rows() > 0 ) :
			$row = $query->row();
			
			return $row->site_name;
		else :
			return FALSE;
		endif;
	}
	public function category_name( $id, $filter = 'cat_id' )
	{
		$sql = "SELECT c.cat_name FROM feed_category c WHERE c.$filter=? LIMIT 1";
		$query = $this->db->query( $sql, array( $id ) );
		
		if ( $query->num_rows() > 0 ) :
			$row = $query->row();
			
			return $row->cat_name;
		else :
			return FALSE;
		endif;
	}
	public function author_name( $id )
	{
		$sql = "SELECT a.author_name FROM feed_author a WHERE a.author_id=? LIMIT 1";
		$query = $this->db->query( $sql, array( $id ) );
		
		if ( $query->num_rows() > 0 ) :
			$row = $query->row();
			
			return $row->author_name;
		else :
			return FALSE;
		endif;
	}
}

?>