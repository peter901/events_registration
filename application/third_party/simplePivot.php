<?php 

/***************************
 * Create a Pivot table
 * brian.muhumuza@gmail.com
 * 
 ***************************/
function simplePivot($data, $pivot_on, $label_columns, $value_columns, $col_name_separator='_', $sum_rows=false, $sum_columns=false)
{
	if ( ! is_array($data) || ! is_array($pivot_on) || ! is_array($label_columns) || ! is_array($value_columns))
	{
		return ['ERROR: invalid data passed. all parapeters should be passed as arrays'];
	}
	
	if (count($data) == 0 || count($pivot_on) == 0 || count($label_columns) == 0 || count($value_columns) == 0)
	{
		return ['ERROR: empty parameters detected. all parapeters should be specified'];
	}
	
	# check if all pivot columns exist in the data array
	foreach ($pivot_on as &$C)
	{
		if ( ! array_key_exists($C, $data[0]))
		{
			return ['ERROR: pivot column missing in data array.'];
		}
	}
	
	# check if all columns for labels exist in the data array
	foreach ($label_columns as &$C)
	{
		if ( ! array_key_exists($C, $data[0]))
		{
			return ['ERROR: label column missing in data array.'];
		}
	}

	# check if all columns for values exist in the data array
	foreach ($value_columns as &$C)
	{
		if ( ! array_key_exists($C, $data[0]))
		{
			return ['ERROR: label column missing in data array.'];
		}
	}

	# do pivoting
	$pivot_data = array();
	$all_pivot_columns = array();
	$all_row_sum_columns = array();
	$col_sum = array();
	
	foreach ($data as &$DT)
	{
		$pivot_on_code = '';
		$pivot_on_values = array();
		
		foreach ($pivot_on as &$PVT)
		{
			$pivot_on_code .= $DT[$PVT];
			$pivot_on_values[] = $DT[$PVT];
		}
		
		if ( ! isset($pivot_data[$pivot_on_code]))
		{
			$pivot_data[$pivot_on_code] = array(
				'pivot_on_values' => $pivot_on_values,
				'pivot_columns' => array(),
				'row_sums' => array(),
			);
		}
		
		$pvt_col_labels = '';
		
		foreach ($label_columns as &$CL)
		{
			$pvt_col_labels .= $DT[$CL] . $col_name_separator;
		}
		
		foreach ($value_columns as &$V)
		{
			$pvt_col = $pvt_col_labels . $V;
			
			if ( ! in_array($pvt_col, $all_pivot_columns))
			{
				$all_pivot_columns[] = $pvt_col;
			}
			
			$pivot_data[$pivot_on_code]['pivot_columns'][$pvt_col] = $DT[$V];
			
			if ($sum_rows)
			{
				if ( ! in_array($V, $all_row_sum_columns))
				{
					$all_row_sum_columns[] = $V;
				}
				
				if (isset($pivot_data[$pivot_on_code]['row_sums'][$V]))
				{
					$pivot_data[$pivot_on_code]['row_sums'][$V] += $DT[$V];
				}
				else
				{
					$pivot_data[$pivot_on_code]['row_sums'][$V] = $DT[$V];
				}
			}
			
			if ($sum_columns)
			{
				if (isset($col_sum[$pvt_col]))
				{
					$col_sum[$pvt_col] += $DT[$V];
				}
				else
				{
					$col_sum[$pvt_col] = $DT[$V];
				}
			}
		}
	}
	
	$output = array();
	
	$headers = array();
	
	# output column headers
	foreach ($pivot_on as &$PVT)
	{
		$headers[] = $PVT;
	}
	
	foreach ($all_pivot_columns as &$PC)
	{
		$headers[] = $PC;
	}
	
	if ($sum_rows)
	{
		foreach ($all_row_sum_columns as &$C)
		{
			$headers[] = 'SUM' . $col_name_separator . $C;
		}
	}
	
	$output[] = $headers;
	
	# output values
	foreach ($pivot_data as &$D)
	{
		$row = array();
		
		foreach ($D['pivot_on_values'] as &$V)
		{
			$row[] = $V;
		}
		
		foreach ($all_pivot_columns as &$PC)
		{
			if (isset($D['pivot_columns'][$PC]))
			{
				$row[] = $D['pivot_columns'][$PC];
			}
			else
			{
				$row[] = '';
			}
		}
		
		if ($sum_rows)
		{
			foreach ($all_row_sum_columns as &$C)
			{
				$row[] = $D['row_sums'][$C];
			}
		}
		
		$output[] = $row;
	}
	
	# output column totals
	if ($sum_columns)
	{
		$row = array();
		
		foreach ($pivot_on as &$SC)
		{
			$row[] = '';
		}
		
		foreach ($all_pivot_columns as &$PC)
		{
			if (isset($col_sum[$PC]))
			{
				$row[] = $col_sum[$PC];
			}
			else
			{
				$row[] = '';
			}
		}
		
		$output[] = $row;
	}
	
	return $output;
}


?>