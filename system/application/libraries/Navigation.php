<?php
if ( ! defined('BASEPATH')) :
	exit('No direct script access allowed');
endif;

class Navigation
{
	public $ci = NULL;
	public function __construct()
	{
		$this->ci =& get_instance();
		$list = array();
		
		if ( $this->ci->auth['id'] == 0 ) :
			array_push( $list, anchor( 'page/login', 'How To Register?') );
		endif;
		
		array_push( $list, anchor( 'page/faq', dom( 'FAQ', 'acronym', 'title="Frequently Asked Questions"' ) ) );
		array_push( $list, anchor( current_url() . '#suggest', 'Suggest', 'class="nav-suggest"' ) );
		array_push( $list, anchor( 'page/our-mission', 'Our Mission') );
		
		if ( $this->ci->auth['role'] == 1 ) : 
			$clist = array();
			array_push( $clist, anchor( '?p=/setting/site', 'Site') );
			array_push( $clist, anchor( '?p=/setting/suggestion', 'Suggestion') );
			array_push( $clist, anchor( '?p=/setting/author', 'Author') );
			array_push( $clist, anchor( '?p=/setting/category', 'Category') );
			array_push( $clist, anchor( '?p=/setting/users', 'User') );
			
			array_push( $list, anchor( '#', 'Setting' ) . ul( $clist ) );
		endif;
		
		if ( $this->ci->auth['id'] > 0 ) :
			$clist = array();
			array_push( $clist, anchor( '?p=/users/hidden', 'Hidden Site') );
			array_push( $list, anchor( 'users/profile/' . $this->ci->auth['id'], 'Profile') . ul( $clist ) );	
		endif;
		
		array_push( $list, anchor( '', 'Home') );
		$content = ul( $list, array( 'id' => 'nav-ul' ) );
		
		$this->ci->ui->append($content, 'navigation'); 
	}	
}

?>