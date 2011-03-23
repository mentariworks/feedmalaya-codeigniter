<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Option
{
	var $ci 		= NULL;
	var $enabled	= FALSE;
	var $data		= array();
	var $config 	= array();
	
	function Option()
	{
		$this->ci =& get_instance();
		$this->ci->option = $this;
		
		$this->ci->config->load( 'application', TRUE );
		$this->config = $this->ci->config->item( 'option', 'application' );
		
		$this->_is_enable();
		$this->_cache_all();
	}
	
	function _is_enable() 
	{
		$test = array ( 'table', 'attribute', 'value' );
		$invalid = FALSE;
		
		if ( $this->config['enable'] === TRUE )
		{
			foreach ( $test as $value )
			{
				if ( trim( $this->config[ $value ] ) === '' ) 
				{
					$invalid = TRUE;	
				}
			}
			
			$this->enabled = ( $invalid === FALSE ? TRUE : FALSE );
		}
		else {
			$this->enabled = FALSE;
		}
	}
	
	function _cache_all()
	{
		if ( $this->enabled === TRUE ) 
		{
			$query = "SELECT " . $this->config['attribute'] . ", " . $this->config['value'] . " FROM " . $this->config['table'];
			$result = $this->ci->db->query( $query );
			
			foreach ( $result->result_array() as $row ) 
			{
				$this->data[ $row[ $this->config[ 'attribute' ] ] ] = $row[ $this->config[ 'value' ] ];	
			}
		}
		
	}
	function get( $name = '' )
	{
		if ( !isset( $this->data[ $name ] ) ) 
		{
			return FALSE;
		}
		else 
		{
			return $this->data[ $name ];
		}
	}
	function update( $name = '', $value = '' )
	{
		if ( $this->enabled === TRUE AND trim( $name ) !== '' ) 
		{
			if ( !isset( $this->data[ $name ] ) ) 
			{
				$query = "INSERT INTO " . $this->config['table'] . " (" . $this->config['value'] . ", " . $this->config['attribute'] . ") VALUES (?, ?)";
			}
			else 
			{
				$query = "UPDATE " . $this->config['table'] . " SET " . $this->config['value'] ."=? WHERE " . $this->config['attribute'] . "=?";
			}
			
			$result = $this->ci->db->query( $query, array( $value, $name ) );
			
			$this->data[ $name ] = $value;
		}
	}
	function delete( $name = '' )
	{
		if ( $this->enabled === TRUE AND trim( $name ) !== '' ) {
			
			$query = "DELETE FROM " . $this->config['table'] . " WHERE " . $this->config['attribute'] . "=?";
			
			$result = $this->ci->db->query( $query, array( $name ) );
			
			$this->data[ $name ] = FALSE;
		}
	}
}

?>