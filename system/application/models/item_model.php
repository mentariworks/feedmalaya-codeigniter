<?php

class Item_model extends Model
{
	public function visit( $id )
	{
		$location = site_url();
		
		$sql = "SELECT i.item_url FROM feed_item i WHERE i.item_id=? AND i.item_status=1";
		$query = $this->db->query( $sql, array( $id ) );
		
		if ( $query->num_rows() > 0 ) :
			$row = $query->row();
			
			$location = $row->item_url;	
		else :
			show_404();
		endif;
		
		return $location;
		
	}
	public function like( $id )
	{
		$sql = "SELECT r.rel_id FROM feed_relationship r WHERE r.rel_type=2 AND r.rel_attribute=? AND r.rel_value=?";
		$sql_insert = "INSERT INTO feed_relationship (rel_type, rel_attribute, rel_value) VALUES (2, ?, ?)";
		
		if ( intval( $id ) AND $id > 0 ) :
			$query = $this->db->query( $sql, array( $this->auth['id'], $id ) );
			
			if ( $query->num_rows() < 1 ) :
				$this->db->query( $sql_insert, array( $this->auth['id'], $id ) );
				return array(
					'SUIXHR' => TRUE,
					'callback' => 'Js.data.page.like.reply',
					'text' => array( $id, 'You like this.' )
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
	
	public function unlike( $id )
	{
		$sql = "SELECT r.rel_id FROM feed_relationship r WHERE r.rel_type=2 AND r.rel_attribute=? AND r.rel_value=?";
		$sql_remove = "DELETE FROM feed_relationship WHERE rel_type=2 AND rel_attribute=? AND rel_value=?";
		
		if ( intval( $id ) AND $id > 0 ) :
			$query = $this->db->query( $sql, array( $this->auth['id'], $id ) );
			
			if ( $query->num_rows() > 0 ) :
				$this->db->query( $sql_remove, array( $this->auth['id'], $id ) );
				return array(
					'SUIXHR' => TRUE,
					'callback' => 'Js.data.page.like.reply',
					'text' => array( $id, 'You unlike this.' )
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
}

?>