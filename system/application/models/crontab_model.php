<?php

class Crontab_model extends Model
{
	public function get_site_data()
	{
		$data = array( 'id' => 0, 'feed' => '', 'total' => 0 );
		
		$sql = "SELECT s.site_id, s.site_url, s.site_feed 
			FROM feed_site s 
			WHERE s.site_query=1 
			ORDER BY s.site_lastquery ASC";
		
		$query = $this->db->query( $sql );
		$data['total'] = $query->num_rows();
		
		if ( $query->num_rows() > 0 ) :
			$row = $query->row();
			
			$data['id'] = $row->site_id;
			
			if ( trim( $row->site_feed ) !== '' ) :
				$data['feed'] = prep_url( $row->site_feed );
			else :
				$data['feed'] = prep_url( $row->site_url );
			endif;
		endif;
		
		
		return $data;
	}
	public function save_items( $items, $site )
	{
		// save everything
		
		$timestamp = $this->_detect_last_timestamp( $site );
		
		$id = 0;
		$sql_item = "INSERT INTO feed_item (item_url, item_title, item_excerpt, item_content, item_slug, item_site, item_author, item_datetime) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
		$sql_cat = "INSERT INTO feed_relationship (rel_type, rel_attribute, rel_value, rel_date) VALUES (1, ?, ?, SYSDATE())";
		 
		foreach ( $items as $item ) :
			$item_timestamp = strtotime( $item->get_date() );
			
			if ( $item_timestamp > $timestamp AND $item_timestamp < time() ) :
				$author = $this->_get_author( $item->get_author()->get_name(), $site );
				
				$contents = trim( quotes_to_entities( ascii_to_entities( $item->get_description() ) ) );
				$excerpt = character_limiter( strip_tags( $contents ), 200 );
				
				$this->db->query( $sql_item, array(
					trim( prep_url( $item->get_link() ) ),
					trim( quotes_to_entities( ascii_to_entities( $item->get_title() ) ) ),
					$excerpt,
					$contents,
					trim( strtolower( url_title( $item->get_title() ) ) ),
					$site,
					$author,
					date( 'Y-m-d H:i:s', $item_timestamp )
				) );
				
				$id = $this->db->insert_id();
				
				foreach( $item->get_categories() as $category ) :
					$cat = $this->_get_category( $category->get_term() );
					
					$this->db->query( $sql_cat, array(
						$cat,
						$id
					) );
				endforeach;
			endif;
			
		endforeach;
		
		$this->_update_timestamp( $site );
	}
	private function _detect_last_timestamp( $site = 0 )
	{
		// detect the last inserted item based on timestamp
		
		$sql = "SELECT i.item_datetime FROM feed_item i WHERE i.item_site=? ORDER BY i.item_datetime DESC LIMIT 1";
		$query = $this->db->query( $sql, array( $site ) );
		
		if ( $query->num_rows() > 0 ) :
			$row = $query->row();
			return strtotime( $row->item_datetime );
		else :
			return 0;
		endif;
	}
	private function _update_timestamp( $site )
	{
		$sql = "UPDATE feed_site SET site_lastquery=SYSDATE() WHERE site_id=?";
		$this->db->query( $sql, array(
			$site
		) );
	}
	private function _get_author( $name = '', $site = 0 )
	{
		// try to get the author id, but if author not in the db add it up
		
		$name = trim( $name );
		$slug = trim( strtolower( url_title($name) ) );
		$id = 0;
		
		$sql = "SELECT a.author_id FROM feed_author a WHERE a.author_slug=? AND a.author_site=?";
		$query = $this->db->query( $sql, array( $slug, $site ) );
		
		if ( $query->num_rows() > 0 ) :
			$row = $query->row();
			$id = $row->author_id;
		else :
			$sql_insert = "INSERT INTO feed_author (author_name, author_slug, author_site) VALUES (?, ?, ?)";
			$this->db->query( $sql_insert, array(
				$name,
				$slug,
				$site
			) );
			
			$id = $this->db->insert_id();
		endif;
		
		return $id;
	}
	public function update_site_data( $id, $feed, $name = '' )
	{
		$sql = "UPDATE feed_site SET site_feed=? WHERE site_id=?";
		$this->db->query( $sql, array( $feed, $id ) );
		
		$site_name = quotes_to_entities( ascii_to_entities( $name ) );
		
		if ( trim($site_name) !== '' ) :
			$sql = "UPDATE feed_site SET site_name=? WHERE site_id=?";
			$this->db->query( $sql, array( $site_name, $id ) );
		endif;
	} 
	private function _get_category( $name = '' )
	{
		$name = trim( $name );
		$slug = trim( strtolower( url_title( $name ) ) );
		$id = 0;
		
		$sql = "SELECT c.cat_id FROM feed_category c WHERE c.cat_slug=?";
		$query = $this->db->query( $sql, array( $slug ) );
		
		if ( $query->num_rows() > 0 ) :
			$row = $query->row();
			$id = $row->cat_id;
		else :
			$sql_insert = "INSERT INTO feed_category (cat_name, cat_slug) VALUES (?, ?)";
			$this->db->query( $sql_insert, array( quotes_to_entities( ascii_to_entities( $name ) ), $slug ) );
			$id = $this->db->insert_id();
		endif;
		
		return $id;
	}
}

?>