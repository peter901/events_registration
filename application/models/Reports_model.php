<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reports_model extends CI_Model
{

    public function __construct()
    {
        $this->load->database();
    }
    
    public function get_report_output($report_id, $start_index=0, $records=50)
    {
    	$SQL = "SELECT CQ.*, RO.id AS output_id, RO.label, RO.file_name 
    	FROM report_output RO LEFT JOIN core_command_queue CQ ON RO.ref_command=CQ.id WHERE RO.id_report_reports=? ORDER BY RO.id DESC LIMIT ?,?";
    	$query = $this->db->query($SQL, array($report_id, $start_index, $records));
    	return $query->result_array();
    }
    
    
    public function get_report_group_access($report_id=0)
    {
    	$SQL = "SELECT G.*, A.id_core_groups FROM core_groups G LEFT JOIN (SELECT * FROM report_group_access WHERE id_report_reports=?) A ON G.id=A.id_core_groups ORDER BY G.name";
    	$query = $this->db->query($SQL, array($report_id));
    	return $query->result_array();
    }

    
    public function user_has_report_access($user_id, $report_id)
    {
    	$SQL = "SELECT * FROM core_user_groups UG, report_group_access GA WHERE UG.id_core_groups=GA.id_core_groups AND UG.id_core_users=? AND 
    		GA.id_report_reports=?";
    	$query = $this->db->query($SQL, array($user_id, $report_id));
    	
    	if ($query->num_rows() > 0)
    	{
    		return true;
    	}
    	
    	return false;
    }
    
    
    public function search_reports_for_admin($search)
    {
    	$SQL = "SELECT * FROM report_reports WHERE name LIKE '%".$this->db->escape_like_str($search)."%' ORDER BY id DESC";
    	$query = $this->db->query($SQL);
    	
    	return $query->result_array();
    }
    
    
    public function get_reports_for_user($user_id, $start_index=0, $records=50)
    {
    	$SQL = "SELECT * FROM report_reports WHERE user_can_run=1 AND id IN 
    		(SELECT DISTINCT GA.id_report_reports FROM core_user_groups UG, report_group_access GA WHERE UG.id_core_groups=GA.id_core_groups AND UG.id_core_users=?) 
    		ORDER BY grouping, ordering LIMIT ?,?";
    	$query = $this->db->query($SQL, array($user_id, $start_index, $records));
    	
    	return $query->result_array();
    }
    
    
    public function count_reports_for_user($user_id)
    {
    	$SQL = "SELECT * FROM report_reports WHERE user_can_run=1 AND id IN
    		(SELECT DISTINCT GA.id_report_reports FROM core_user_groups UG, report_group_access GA WHERE UG.id_core_groups=GA.id_core_groups AND UG.id_core_users=?)";
    	$query = $this->db->query($SQL, array($user_id));
    	 
    	return $query->num_rows();
    }

    
    public function search_reports_for_user($user_id, $search)
    {
    	$SQL = "SELECT * FROM report_reports WHERE user_can_run=1 AND name LIKE '%".$this->db->escape_like_str($search)."%' AND id IN 
    		(SELECT DISTINCT GA.id_report_reports FROM core_user_groups UG, report_group_access GA WHERE UG.id_core_groups=GA.id_core_groups AND UG.id_core_users=?) 
    		ORDER BY grouping, ordering";
    	$query = $this->db->query($SQL, array($user_id));
    	
    	return $query->result_array();
    }
    
    
    public function run_sql_query($SQL, $output='ARRAY')
    {
    	if ( ! in_array($output, ['CSV', 'ARRAY','SQL']))
    	{
    		$output = 'ARRAY';
    	}
    	
    	$query = $this->db->query($SQL);
    	
    	if ($output == 'CSV')
    	{
    		$this->load->dbutil();
    		return $this->dbutil->csv_from_result($query);
    	}
    	
    	if ($output == 'SQL')
    	{
    		return true;
    	}
    	return $query->result_array();
    }
    
    
	/************************************
	 * Expected Structure of parameters
	 * {
	 * 		"field": "value",
	 * 		"field": "value",
	 * }
	 * 
	 ************************************/
    public function run_report($report_id, $parameters, $group_results=false)
    {
    	# get the report
    	$query = $this->db->get_where('report_reports', array('id' => $report_id));
    	
    	if ($query->num_rows() == 0)
    	{
    		return null;
    	}
    	
    	$report = $query->row_array();
    	
    	$report_query = $report['query'];
    		
    	foreach ($parameters as $field => &$value)
    	{
    		$report_query = str_replace('{'.$field.'}', $value, $report_query);
    	}

    	$results = $this->run_sql_query($report_query);
    	
    	# check if we have pivoting enabled
    	if ((bool)$report['enable_pivoting'])
    	{
    		include_once APPPATH.'/third_party/simplePivot.php';
    	
    		$pivot_on = explode(',', $report['pivot_on_columns']);
    		$label_columns = explode(',', $report['pivot_label_columns']);
    		$value_columns = explode(',', $report['pivot_value_columns']);
    		$col_name_separator = $report['pivot_column_name_separator'];
    		$sum_rows = (bool)$report['pivot_sum_rows'];
	   		$sum_columns = (bool)$report['pivot_sum_columns'];
	
	   		if ($group_results && ! empty($report['output_grouping_column']) && count($results) > 0)
	   		{
    			return simplePivot($results, $pivot_on, $label_columns, $value_columns, $col_name_separator, $sum_rows, $sum_columns, $report['output_grouping_column']);
	   		}
	   		
	   		return simplePivot($results, $pivot_on, $label_columns, $value_columns, $col_name_separator, $sum_rows, $sum_columns);
    	}
    	else
    	{
    		if ($group_results && ! empty($report['output_grouping_column']) && count($results) > 0)
    		{
    			$header_row_keys = array_keys($results[0]);
    			$row_grouping = array();
    			
    			// group results
    			if (in_array($report['output_grouping_column'], $header_row_keys)) // check if group by column exists in results
				{
					foreach ($results as &$R)
					{
						if ( ! isset($row_grouping[$R[$report['output_grouping_column']]]))
						{
							$row_grouping[$R[$report['output_grouping_column']]] = array();
						}
							
						$row_grouping[$R[$report['output_grouping_column']]][] = $R;
					}
				}
				else // group by column does not exist in results
				{
					$row_grouping[' '] = $results;
				}
				
    			return $row_grouping;
    		}
    		
    		return $results;
    	}
    }


    public function get_main_registrations($start_index=0, $records=50)
    {
    	$SQL = "SELECT R.*, I.payment_proof, I.payment_type, I.payment_made FROM core_registrations R LEFT JOIN core_invoices I 
    			ON R.invoice_code=I.code WHERE R.parent_registration_code IS NULL ORDER BY I.payment_made, R.id DESC LIMIT ?,?";
    	$query = $this->db->query($SQL, array((int)$start_index, $records));
    	return $query->result_array();
    }
    
    public function filter_main_registrations($filter_values)
    {
    	$SQL = "SELECT  R.*, I.payment_type, I.payment_made FROM core_registrations R LEFT JOIN core_invoices I 
    			ON R.invoice_code=I.code WHERE 1 ";
    	$FILTER_SQL = '';
    	$FILTER = false;
    	$query_params = array();
    	
    	if ( ! empty($filter_values['search']))
    	{
    		$FILTER = true;
    		$FILTER_SQL .= " AND (R.first_name LIKE '%".$this->db->escape_like_str($filter_values['search']).
    		"%' OR R.nationality LIKE '%".$this->db->escape_like_str($filter_values['search']).
    		"%' OR R.last_name LIKE '%".$this->db->escape_like_str($filter_values['search']).
    		"%' OR R.emp_organisation LIKE '%".$this->db->escape_like_str($filter_values['search']).
    		"%' OR R.acc_body LIKE '%".$this->db->escape_like_str($filter_values['search']).
    		"%' OR R.registration_code LIKE '%".$this->db->escape_like_str($filter_values['search']).
    		"%' OR R.registration_code=? OR R.other_names LIKE '%".$this->db->escape_like_str($filter_values['search'])."%')";
    		$query_params[] = $filter_values['search'];
    	}
    	
    	if ( ! empty($filter_values['date_from']))
    	{
    		$FILTER = true;
    		$FILTER_SQL .= " AND R.created_at >= ?";
    		$query_params[] = $filter_values['date_from'].' 00:00:00';
    	}
    	
    	if ( ! empty($filter_values['date_to']))
    	{
    		$FILTER = true;
    		$FILTER_SQL .= " AND R.created_at <= ?";
    		$query_params[] = $filter_values['date_to'].' 23:59:59';
    	}
    	
    	if ( ! $FILTER)
    	{
    		return array();
    	}
    	
    	$SQL .= $FILTER_SQL . " AND R.parent_registration_code IS NULL ORDER BY I.payment_made, R.id DESC";
    	
    	$query = $this->db->query($SQL, $query_params);
    	return $query->result_array();
    }

    // EMAIL LOGS
    public function filter_email_logs($filter, $ordering='id DESC', $start_index=0, $records=100)
    {
    	if ( ! is_array($filter) || empty($filter))
    	{
    		$filter = array();
    	}
    	$query_params = array();
    	$FILTER_SQL = "";
		    	
    	if (isset($filter['status']))
    	{
    		$FILTER_SQL .= " AND status=? ";
    		$query_params[] = $filter['status'];
    	}
    	
    	if (isset($filter['search']))
    	{
    		$FILTER_SQL .= " AND (workflow_run_code=? OR sender=? OR recipients LIKE '%".$this->db->escape_like_str($filter['search'])."%' 
    		OR recipients_cc LIKE '%".$this->db->escape_like_str($filter['search'])."%' 
    		OR recipients_bcc LIKE '%".$this->db->escape_like_str($filter['search'])."%' OR subject LIKE '%".$this->db->escape_like_str($filter['search'])."%') ";
    		$query_params[] = $filter['search'];
    		$query_params[] = $filter['search'];
    	}
    	
    	$SQL = "SELECT * FROM communication_email_queue WHERE 1 {$FILTER_SQL} ORDER BY {$ordering} LIMIT {$start_index},{$records}";
    	$query = $this->db->query($SQL, $query_params);
    	return $query->result_array();
    }

}

?>
