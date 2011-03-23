<?php

class Main extends Controller
{
	function __construct()
	{
		parent::Controller();
	}
	public function index()
	{
		show_404();
	}
	public function sync()
	{
		$sql = "SELECT * FROM feed_item";
		$query = $this->db->query($sql);
		
		foreach( $query->result_array() as $row ) :
			$this->db->query("UPDATE feed_relationship SET rel_date=? WHERE rel_value=? AND rel_type=1", array(
				$row['item_datetime'],
				$row['item_id']
			));
			
		endforeach;
	}
}

?>