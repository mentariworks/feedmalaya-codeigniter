<?php

class Suggest_model extends Model
{
	public function save()
	{
		$code = 0;
		$sql = "INSERT INTO feed_suggest (suggest_url, suggest_desc, suggest_userid, suggest_status) VALUES (?, ?, ?, 0)";
		
		if ( $this->auth['id'] > 0 ) :
			
			try {
				$this->db->query( $sql, array(
					prep_url( $this->input->post( 'site_url', TRUE ) ),
					strip_tags( $this->input->post( 'site_desc', TRUE ) ),
					$this->auth['id']
				) );
				
				$code = 1;
			} catch( Exception $e ) {
				$code = 0;
			}
			
			$code = 1;
		else :
			$code = 2;
		endif;
		
		return array(
			'code' => $code
		);
	}
}

?>