<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Table extends CI_Table
{
	var $no_record 	= 'No record';
	var $summary 	= array();
	
	function clear()
	{
		$this->rows				= array();
		$this->heading			= array();
		$this->auto_heading		= TRUE;	
		$this->summary			= array();
	}
	
	function generate( $table_data = NULL )
	{
		// The table data can optionally be passed to this function
		// either as a database result object or an array
		if ( ! is_null($table_data))
		{
			if (is_object($table_data))
			{
				$this->_set_from_object($table_data);
			}
			elseif (is_array($table_data))
			{
				$set_heading = (count($this->heading) == 0 AND $this->auto_heading == FALSE) ? FALSE : TRUE;
				$this->_set_from_array($table_data, $set_heading);
			}
		}
	
		// Is there anything to display?  No?  Smite them!
		if (count($this->heading) == 0 AND count($this->rows) == 0)
		{
			return 'Undefined table data';
		}
	
		// Compile and validate the template date
		$this->_compile_template();
	
	
		// Build the table!
		
		$out = $this->template['table_open'];
		$out .= $this->newline;		

		// Add any caption here
		if ($this->caption)
		{
			$out .= $this->newline;
			$out .= '<caption>' . $this->caption . '</caption>';
			$out .= $this->newline;
		}

		// Is there a table heading to display?
		if (count($this->heading) > 0)
		{
			$out .= $this->template['heading_row_start'];
			$out .= $this->newline;		

			foreach($this->heading as $heading)
			{
				$out .= $this->template['heading_cell_start'];
				$out .= $heading;
				$out .= $this->template['heading_cell_end'];
			}

			$out .= $this->template['heading_row_end'];
			$out .= $this->newline;				
		}

		// Build the table rows
		if (count($this->rows) > 0)
		{
			$i = 1;
			foreach($this->rows as $row)
			{
				if ( ! is_array($row))
				{
					break;
				}
			
				// We use modulus to alternate the row colors
				$name = (fmod($i++, 2)) ? '' : 'alt_';
			
				$out .= $this->template['row_'.$name.'start'];
				$out .= $this->newline;		
	
				foreach($row as $cell)
				{
					$out .= $this->template['cell_'.$name.'start'];
					
					if ($cell === "")
					{
						$out .= $this->empty_cells;
					}
					else
					{
						$out .= $cell;
					}
					
					$out .= $this->template['cell_'.$name.'end'];
				}
	
				$out .= $this->template['row_'.$name.'end'];
				$out .= $this->newline;	
			}
		} else {
			$out .= $this->template['row_start'];
			$out .= '<td colspan="' . count($this->heading) . '">' . $this->no_record . '</td>';
			$out .= $this->template['row_end'];
		}
		
		// Is there a table summary to display?
		if (count($this->summary) > 0)
		{
			$out .= $this->template['summary_row_start'];
			$out .= $this->newline;		

			foreach($this->summary as $summary)
			{
				$out .= $this->template['summary_cell_start'];
				$out .= $summary;
				$out .= $this->template['summary_cell_end'];
			}

			$out .= $this->template['summary_row_end'];
			$out .= $this->newline;				
		}

		$out .= $this->template['table_close'];
	
		return $out;
	}
	function set_summary()
	{
		$args = func_get_args();
		$this->summary = ( is_array( $args[0] ) ) ? $args[0] : $args;
	}
	
	function _default_template()
	{
		return  array (
			'table_open' 			=> '<table class="datagrid">',

			'heading_row_start' 	=> '<tr>',
			'heading_row_end' 		=> '</tr>',
			'heading_cell_start'	=> '<th>',
			'heading_cell_end'		=> '</th>',

			'row_start' 			=> '<tr>',
			'row_end' 				=> '</tr>',
			'cell_start'			=> '<td>',
			'cell_end'				=> '</td>',

			'row_alt_start' 		=> '<tr class="alt">',
			'row_alt_end' 			=> '</tr>',
			'cell_alt_start'		=> '<td>',
			'cell_alt_end'			=> '</td>',
			
			'summary_row_start' 	=> '<tr class="footer">',
			'summary_row_end' 		=> '</tr>',
			'summary_cell_start'	=> '<td>',
			'summary_cell_end'		=> '</td>',

			'table_close' 			=> '</table>'
		);	
	}
	
}
?>