<?php

/*******************************************
 * Reports related housekeeping Commands
 * 
 * 1. run manually/automatically executed reports
 * 2. generate report command from report schedule
 * 
 * ***************
 * the commands in this file can be set to run
 * as cron jobs i.e.
 * 
 * php index.php Reports_runner run_reports
 * 
 *******************************************/

class Report_runner extends CI_Controller
{
	
	public function __construct()
	{
		parent::__construct();
		
		# restrict methods here to cli invocation
		if ( ! is_cli())
		{
			echo "These methods Should only be run from the CLI";
			exit();
		}
		
		# prevent script from running more than one instance at a time
		$this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
		if ($this->socket === false) 
		{
			throw new Exception("can't create socket: ".socket_last_error($this->socket));
			exit();
		}
		## set $port to something random like 10012
		$port = 10387;
		
		## hide warning, because error will be checked manually
		if (@socket_bind($this->socket, '127.0.0.1', $port) === false)
		{
			## some instanse of the script is running
			echo "another instance is already running, exiting ...";
			exit();
		}
		
		$this->load->model('reports_model');
	}
		
	
	/************************************************
	 * Flag a Command with an error
	 ************************************************/
	private function flag_command_error($command_id, $comment='')
	{
		echo "ERROR!! Command {$command_id} : {$comment}\n";
		
		$to_save = array
		(
			'updated_at' => date('Y-m-d H:i:s'),
			'status' => 'ERROR',
			'comment'=> $comment,
		);
		$this->core_model->update_object('core_command_queue', $command_id, $this->core_model->null_blank_entries($to_save));
	}

	
	/************************************************
	 * Run Reports Command
	 ************************************************/
	public function run_reports()
	{
		$this->load->helper('file');
		$this->load->helper('path');
		
		echo "-- run_reports starting --\n";
		
		# get pending commands
		$run_report_commands = $this->core_model->get_many_objects('core_command_queue', array('command' => 'RUN_REPORT', 'status' => 'PENDING'));
		
		# create the reports folder if it doesn't exist
		$file_download_base_path = set_realpath('./media/DOWNLOADS/');
		
		$report_save_path = $file_download_base_path.'REPORTS/';
			
		if ( ! is_dir($report_save_path))
		{
			# create the directory
			$this->core_model->create_directory_tree($report_save_path);
		}
		
		foreach ($run_report_commands as &$command)
		{
			echo "Run Reports Command: {$command['id']}\n";
			
			# flag command as running
			$this->core_model->update_object('core_command_queue', $command['id'], array('status' => 'RUNNING', 'updated_at' => date('Y-m-d H:i:s')));
			
			/************************************
			 * Structure of parameters (json) example
			 * {
			 * 		"field": "value",
			 * 		"field": "value",
			 * }
			 * 
			 ************************************/
			$parameters = json_decode($command['parameters_json'], true);
			
			# get the report
			$report = $this->core_model->get_object('report_reports', $command['command_ref']);
			
			$report_query = $report['query'];
			
			foreach ($parameters as $field => &$value)
			{
				$report_query = str_replace('{'.$field.'}', $value, $report_query);
			}
			
			$results = $this->reports_model->run_sql_query($report_query, 'CSV');
			
			# save results to file
			$file_name = $this->core_model->generate_random_string(15);
			$file_name .= '.csv';
			file_put_contents($report_save_path.$file_name, $results);
			
			# update the report output
			$this->core_model->update_many_objects('report_output', array('file_name' => $file_name), array('id_report_reports' => $report['id'], 'ref_command' => $command['id']));
			
			# flag command as complete
			$this->core_model->update_object('core_command_queue', $command['id'], array('status' => 'COMPLETE', 'updated_at' => date('Y-m-d H:i:s')));
		}
		
		echo "\n\n-- run_reports complete --\n";
	}


}

?>