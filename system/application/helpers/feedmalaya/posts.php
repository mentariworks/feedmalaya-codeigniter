<?php

if ( !function_exists('fm_post_permalink')) {
	function fm_post_permalink($item)
	{
		return anchor( 'visit/' . $item['slug'] .'/' . $item['id'], $item['title']);
	}
}
if ( !function_exists('fm_post_datetime')) {
	function fm_post_datetime($item, $format = 'd-m-Y')
	{
		return date( 'd-m-Y', $item['datetime'] );
	}
}
if ( !function_exists('fm_post_author')) {
	function fm_post_author($item)
	{
		return anchor( 'author/' . $item['author']['id'], $item['author']['name'] );
	}
}
if ( !function_exists('fm_post_site')) {
	function fm_post_site($item)
	{
		return anchor( 'site/' . $item['site']['id'], $item['site']['name'] );
	}
}

if ( !function_exists('fm_post_tags') )
{
	function fm_post_tags($item, $before = '<div>', $after = '</div>')
	{
		$id = $item['id'];
		$tags = $item['category'];
		$output = '';
		
		if ( is_array( $tags['id'] ) AND count( $tags['id'] ) > 0 ) 
		{
			$count = count( $tags['id'] );
			$output = $before;
			
			if ( $count <= 3 )
			{
				$output .= prettylist( $tags['anchor'], ', ', ' &amp; ' );
			}
			else
			{
				$sliced = array_slice( $tags['anchor'], 0, 3 );
				$info = array(
					'id' => 'tag_more_' . $id,
					'class' => 'tag_more'
				);
				$output .= dom( implode( ', ', $sliced ) . ' . ', 'span', 'id="tag_snippet_' . $id .'"' ) . anchor( '#', '+ more', $info );
				
				$output .= dom( prettylist( $tags['anchor'], ', ', ' &amp; ' ), 'div', 'id="tag_full_' . $id .'" style="display: none;"' ); 
			}
			
			$output .= $after;
		}
		
		return $output;
	}
}

if ( !function_exists( 'fm_post_fav' ) )
{
	function fm_post_fav( $item )
	{
		$FM =& get_instance();
		$id = $item['id'];
		
		$sql_all = "SELECT r.rel_id, u.user_id, u.user_name 
		FROM feed_relationship r, feed_user u 
		WHERE r.rel_value=? 
		AND r.rel_attribute=u.user_id 
		AND r.rel_attribute<>? 
		AND r.rel_type=2";
		$query_all = $FM->db->query( $sql_all, array( $id, $FM->auth['id'] ) );
		$all = $query_all->num_rows();
		
		$sql_personal = "SELECT r.rel_id 
		FROM feed_relationship r 
		WHERE r.rel_value=? 
		AND r.rel_attribute=? 
		AND r.rel_type=2 LIMIT 1";
		$query_personal = $FM->db->query( $sql_personal, array( $id, $FM->auth['id'] ) );
		$personal = $query_personal->num_rows();
		
		$output = '<p class="item_people" id="item_like_' . $id . '">';
		
		if ( $FM->auth['id'] > 0 AND $personal > 0 ) 
		{
			$output .= 'You';	
		}
		
		if ( $all > 3 )
		{
			$output .= ( $personal > 0 ? ' and ' : '' );
			$output .= $all . ( $personal > 0 ? ' other ' : ' ' ) .'people' . ( $all > 1 ? 's' : '');
		} 
		elseif ( $all > 0 ) 
		{
			$other = array();
			foreach( $query_all->result_array() as $row )
			{
				array_push($other, anchor('users/profile/' . $row['user_id'], $row['user_name']));
			}
			
			if ( $all > 1 )
			{
				$output .= ( $personal > 0 ? ', ' : '' ) . prettylist($other, ', ', ' and ');
			} 
			else
			{
				$output .= ( $personal > 0 ? ' and ' : '' ) . $other[0];
			}
			
		}
		if ( $personal > 0 OR $all > 0 ) 
		{
			$output .= ' like this.';
		}
		$output .= '</p>';
		
		return $output;
	}
}


if ( !function_exists( 'fm_post_meta' ) )
{
	function fm_post_meta( $item )
	{
		$id = $item['id'];
		$site_id =  $item['site']['id'];
		$title = $item['title'];
		$slug = $item['slug'];
		$FM =& get_instance();
		
		$output = '<p class="small helper" id="item_option_'. $id .'">';
		$output .= anchor('view/' . $slug . '/' . $id, 'Comment', 'class="item_comment"');
		$output .= ' &#183; ' . anchor('#item-'. $id, 'Share', 'class="item_share"');
		
		$sql_personal = "SELECT r.rel_id FROM feed_relationship r WHERE r.rel_value=? AND r.rel_attribute=? AND r.rel_type=2 LIMIT 1";
		$query_personal = $FM->db->query( $sql_personal, array( $id, $FM->auth['id'] ) );
		$personal = $query_personal->num_rows();
		
		$sql_hidden = "SELECT r.rel_id FROM feed_relationship r WHERE r.rel_value=? AND r.rel_attribute=? AND r.rel_type=3 LIMIT 1";
		$query_hidden = $FM->db->query( $sql_hidden, array( $site_id, $FM->auth['id'] ) );
		$hidden = $query_hidden->num_rows();
		
		if ( $FM->auth['id'] > 0 ) 
		{
			$output .= ' &#183; ';
			if ( $personal > 0 )
			{
				$output .= anchor('?p=/item/unlike/'. $id, 'Unlike', 'class="item_like"');
			}
			else 
			{
				$output .= anchor('?p=/item/like/'. $id, 'Like', 'class="item_like"');
			}
			
			if ( $hidden < 1 )
			{
				$output .= ' &#183; ' . anchor('?p=/users/hidden/add/'. $site_id, 'Hide', 'class="item_hide"');
			}
			
		}
		
		$output .= '</p>';
		
		$url = site_url( 'view/'. $slug . '/' . $id  );
		$goto = site_url( 'go/to/' . $id );
		
		$output .= '<div id="item_share_' . $id . '" style="display: none;">';
		$output .= '<p>Feel free to share <strong>' . $title . '</strong>.</p>';
		$output .= '<p><input type="text" value="'. $goto .'" class="long" /></p>';

		$output .= '<div class="column">';
		$output .= '<p>' . anchor( 'http://del.icio.us/post?url=' . $goto . '&title=' . htmlentities( $title ), img( base_url() . 'public/images/icons/delicious.png' ) . ' del.icio.us' ) . '</p>';
		$output .= '<p>' . anchor( 'http://www.facebook.com/share.php?u=' . $goto, img( base_url() . 'public/images/icons/facebook.png' ) . ' Facebook' ) . '</p>';
		$output .= '<p>' . anchor( 'http://www.stumbleupon.com/submit?url=' . $url . '&title=' . htmlentities( $title ), img( base_url() . 'public/images/icons/stumbleupon.png' ) . ' Stumbleupon' ) . '</p>';
		$output .= '</div>';
		$output .= '<div class="column">';
		$output .= '<p>' . anchor( 'http://digg.com/submit?phase=2&url=' . $goto . '&title=' . htmlentities( $title ), img( base_url() . 'public/images/icons/digg.png' ) . ' Digg' ) . '</p>';
		$output .= '<p>' . anchor( 'http://www.google.com/bookmarks/mark?op=edit&output=popup&bkmk=' . $goto . '&title=' . htmlentities( $title ), img( base_url() . 'public/images/icons/google.png' ) . ' Google' ) . '</p>';
		$output .= '<p>' . anchor( 'http://twitter.com/home?status=Reading \'' . $title . '\' ' . $goto , img( base_url() . 'public/images/icons/twitter.png' ) . ' Twitter' ) . '</p>';
		$output .= '</div>';
		$output .= '<div style="clear: both"></div>';
		
		$output .= '</div>';
		return $output;
	}
}

?>