<?php

if ( !function_exists( 'fm_trend_tags' ) )
{
	function fm_trend_tags()
	{
		$ci =& get_instance();
		$tags = array();
		$ids = array();
		
		$sql = "SELECT c.cat_id, c.cat_name, c.cat_slug, COUNT(*) AS cat_total
			FROM feed_category c, feed_relationship r, feed_item i, feed_site s  
			WHERE c.cat_id=r.rel_attribute 
			AND r.rel_type=1  
			AND i.item_id=r.rel_value 
			AND s.site_id=i.item_site 
			AND s.site_status=1 
			AND DATEDIFF(SYSDATE(), r.rel_date)<=30 
			GROUP BY c.cat_id, c.cat_name HAVING cat_total > 3 
			ORDER BY c.cat_name ASC";
		
		$query = $ci->db->query($sql);
		
		foreach ($query->result_array() as $row) 
		{
			$tags[trim($row['cat_name'])] = $row['cat_total'];
			$ids[trim($row['cat_name'])] = $row['cat_slug'];
		}
		
		$output = dom('No popular tag at for the last 30 days', 'p');
		
		if ($query->num_rows() > 0)
		{
			$output = '';
			$min_size = 9;
			$max_size = 25;
			
			$max_qty = max(array_values($tags));
			$min_qty = min(array_values($tags));
			
			$spread = $max_qty - $min_qty;
			
	        if ($spread == 0) 
			{ 
				// we don't want to divide by zero
				$spread = 1;
	        }
			
			// set the font-size increment
			$step = ($max_size - $min_size) / ($spread);
			
			foreach ($tags as $key => $value) 
			{
				
				$size = round($min_size + (($value - $min_qty) * $step));
				$output .= ' ' . anchor( 'category/' . $ids[$key], $key, 'style="font-size:' . $size . 'px;"' );
			}
		}
		
		
		return $output;
	}
}

if ( !function_exists( 'fm_trend_site' ) )
{
	function fm_trend_site()
	{
		$ci =& get_instance();
		$tags = array();
		$ids = array();
		
		$sql = "SELECT s.site_id, s.site_name, COUNT(*) AS site_total
			FROM feed_relationship r, feed_item i, feed_site s  
			WHERE r.rel_type=2  
			AND i.item_id=r.rel_value 
			AND s.site_id=i.item_site 
			AND s.site_status=1 
			GROUP BY s.site_id, s.site_name 
			ORDER BY s.site_name ASC";
		
		$query = $ci->db->query($sql);
		
		foreach ($query->result_array() as $row) 
		{
			$tags[trim($row['site_name'])] = $row['site_total'];
			$ids[trim($row['site_name'])] = $row['site_id'];
		}
		
		$output = dom('No popular site at for the last 120 days', 'p');
		
		if ($query->num_rows() > 0)
		{
			$output = '';
			$min_size = 9;
			$max_size = 40;
			
			$max_qty = max(array_values($tags));
			$min_qty = min(array_values($tags));
			
			$spread = $max_qty - $min_qty;
			
	        if ($spread == 0) 
			{ 
				// we don't want to divide by zero
				$spread = 1;
	        }
			
			// set the font-size increment
			$step = ($max_size - $min_size) / ($spread);
			
			foreach ($tags as $key => $value) 
			{
				
				$size = round($min_size + (($value - $min_qty) * $step));
				$output .= ' ' . anchor( 'site/' . $ids[$key], $key, 'style="font-size:' . $size . 'px;"' );
			}
		}
		
		
		return $output;
	}
}
?>
