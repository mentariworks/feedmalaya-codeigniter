<?php
/**
 * CodeIgniter Facebook Connect Library (http://www.haughin.com/code/facebook/)
 * 
 * Author: Elliot Haughin (http://www.haughin.com), elliot@haughin.com
 * 
 * VERSION: 1.0 (2009-05-18)
 * LICENSE: GNU GENERAL PUBLIC LICENSE - Version 2, June 1991
 * 
 **/


	include(APPPATH.'libraries/facebook-client/facebook.php');

	class Facebook_connect {

		private $ci;
		private $_api_key		= NULL;
		private $_secret_key	= NULL;
		public 	$user 			= NULL;
		public 	$user_id 		= FALSE;
		
		public $fb;
		public $client;
		
		function Facebook_connect()
		{
			$this->ci =& get_instance();
			$this->ci->fb_connect = $this;
		}

		function initiate()
		{
			$this->_api_key		= $this->ci->option->get('facebook_api_key');
			$this->_secret_key	= $this->ci->option->get('facebook_secret_key');

			$this->fb = new Facebook($this->_api_key, $this->_secret_key);
			
			$this->client = $this->fb->api_client;
			
			$this->user_id = $this->fb->get_loggedin_user();
			
			$fb = TRUE;
			
			if ( $this->user_id !== NULL )
			{
				$fb = $this->_manage_session();
			}
			else 
			{
				delete_cookie( 'auth' );
				$fb = FALSE;
			}
			
			$this->ci->fb_user = $this->user;
			
			return $fb;
		}

		private function _manage_session()
		{
			$data = $this->_get_auth_cookie();
			$fb = TRUE;
			$user = FALSE;
			$profile_data = array( 'uid', 'first_name', 'last_name', 'name', 'locale', 'pic_square', 'profile_url' );
			
			if ( $this->user_id !== NULL )
			{
				if ( !!$data AND $data[0] > 0 ) {
					$fb = array('value' => $data[2]);
					$user = $this->_get_user( $data[0] );
					$user['role'] = $data[1];
				}
				else {
					
					try {
						$info = $this->fb->api_client->users_getInfo( $this->user_id, $profile_data );
						
						if ( isset($info[0]) AND count($info[0]) > 0 )
						{
							$user = $info[0];
							$role = $this->_update_user( $user );
							
							if ( $data[0] !== $this->user_id )
							{
								$cookie = array (
							        'name' => 'auth',
							        'value' => $user['uid'] . "|" . md5( $user['name'] . $user['profile_url'] ) . "|" . $role,
							        'expire' => 0
						        );
						
						        set_cookie( $cookie );
								
								$fb = $cookie;
							}
						}
					
						$user['role'] = $role;
					} catch( FacebookRestClientException $e ) {
						delete_cookie( 'auth' );
					}
				}
				
			}
			else 
			{
				// Need to destroy session
				delete_cookie( 'auth' );
				$user = FALSE;
			}
			
			$this->user = $user;
			
			if ( $user === FALSE )
			{
				delete_cookie( 'auth' );
			}
			
			return $fb;
		}
		function _get_auth_cookie()
		{
			if ( $this->ci->input->cookie( 'auth' ) )
			{
				$cookies = html_entity_decode( $this->ci->input->cookie( 'auth', TRUE ) );
            	$cookie = explode( "|", $cookies );
			
				return array( $cookie[0], $cookie[2], $cookies );
			}
			else 
			{
				return FALSE;
			}
		}
		function _update_user( $user )
		{
			$sql = "SELECT u.user_id, u.user_group FROM feed_user u WHERE u.user_id=?";
			$query = $this->ci->db->query( $sql, array( $user['uid'] ) );
			
			if ( $query->num_rows() > 0 ) 
			{
				$row = $query->row();
				
				$sql_update = "UPDATE feed_user 
					SET user_firstname=?, 
					user_lastname=?, 
					user_name=?, 
					user_locale=?, 
					user_profile_url=?, 
					user_image=?
					WHERE user_id=?";
				$this->ci->db->query( $sql_update, array(
					$user['first_name'],
					$user['last_name'],
					$user['name'],
					$user['locale'],
					$user['profile_url'],
					$user['pic_square'],
					$user['uid']
				) );
				
				return $row->user_group;
			}
			else 
			{
				$sql_insert = "INSERT INTO feed_user (user_id, user_firstname, user_lastname, user_name, user_locale, user_profile_url, user_image, user_group) 
				VALUES (?, ?, ?, ?, ?, ?, ?, 2)";
				$this->ci->db->query( $sql_insert, array(
					$user['uid'],
					$user['first_name'],
					$user['last_name'],
					$user['name'],
					$user['locale'],
					$user['profile_url'],
					$user['pic_square']
				) );
				
				$id = $this->ci->db->insert_id();
				
				return 2;
			}
		}
		function _get_user( $id )
		{
			$data = array();
			$sql = "SELECT * FROM feed_user u WHERE u.user_id=?";
			$query = $this->ci->db->query( $sql, array( $id ) );
			
			if ( $query->num_rows() > 0 ) 
			{
				$row = $query->row();
				
				$data['first_name'] = $row->user_firstname;
				$data['last_name'] = $row->user_lastname;
				$data['name'] = $row->user_name;
				$data['locale'] = $row->user_locale;
				$data['profile_url'] = $row->user_profile_url;
				$data['pic_square'] = $row->user_image;
				$data['uid'] = $row->user_id;
				
			}
			
			return $data;
		}
	}