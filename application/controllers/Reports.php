<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Reports extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->library('custom_auth');

		$this->output->enable_profiler(false);

		# check if the user is logged in
		if ( ! $this->custom_auth->is_authenticated())
		{
			redirect('/login/', 'location');
		}
		
		$this->message_templates = array
		(
			'MISSING_PRIVILEGES' => 'you do not have the necessary privileges to perfom this operation',
			'SAVED_SUCCESSFULLY' => 'the information has been saved successfully',
			'MISSING_ITEM' => 'the item selected could not be found',
			'DELETED_SUCCESSFULLY' => 'the information has been deleted successfully',
			'COPIED_SUCCESSFULLY' => 'the information has been copied over successfully',
		);
		
		#check user has privileges
		if ( ! $this->custom_auth->has_privilege('REPORTS'))
		{
			$this->session->set_flashdata('ERROR', $this->message_templates['MISSING_PRIVILEGES']);
			redirect('/', 'location');
		}
		
		$this->user = $this->custom_auth->get_user();
		$this->breadcrumb = array
		(
			array('label'=>"Reports & Communication", 'url'=>site_url('/reports/'))
		);
		
		# colour code program & session status
		$this->status_colour_mapping = array(
			'ACTIVE' => '<span class="text-success"><b>ACTIVE</b></span>',
			'PENDING' => '<span class="text-muted"><b>PENDING</b></span>',
			'DISABLED' => '<span class="text-danger"><b>DISABLED</b></span>',
			0 => '<span class="text-danger"><b>No</b></span>',
			1 => '<span class="text-success"><b>Yes</b></span>',
		);
		
		$this->load->model('reports_model');
	}

	
	public function index($start_index=0)
	{
		$pagination = $this->config->item('pagination_settings');
		$pagination['per_page'] = 50;
		$pagination['base_url'] = site_url('/reports/index');
		
		if ($this->custom_auth->has_privilege('REPORTS_ADMIN'))
		{
			$pagination['total_rows'] = $this->core_model->count_all_objects('report_reports');
		}
		else
		{
			$pagination['total_rows'] = $this->reports_model->count_reports_for_user($this->user['id']);
		}
		
		$this->pagination->initialize($pagination);
		
		if ( ! (int) $start_index){
			$start_index = 0;
		}
		
		# get reports (with pagination)
		//Introduced an ordering column to order the display of reports within a given group using the SQL:
		# ALTER TABLE `report_reports` ADD `ordering` INT NOT NULL DEFAULT '0' AFTER `grouping`;
		$fd = $this->input->get();
		
		if ($this->custom_auth->has_privilege('REPORTS_ADMIN')) # show all reports
		{
			if (array_key_exists('search', $fd) && strlen(trim($fd['search'])) >= $this->config->item('minimum_search_length')) {
				$data['reports'] = $this->reports_model->search_reports_for_admin(trim($fd['search']));
				$data['SEARCH'] = $fd['search'];
					
			} else {
				$data['reports'] = $this->core_model->get_many_objects('report_reports', null, 'grouping, ordering', $start_index, $records=50);
				$data['pagination'] = $this->pagination->create_links();
			}
		}
		else # show only reports a user has privileges to access & user can run
		{
			if (array_key_exists('search', $fd) && strlen(trim($fd['search'])) >= $this->config->item('minimum_search_length')) {
				$data['reports'] = $this->reports_model->search_reports_for_user($this->user['id'], trim($fd['search']));
				$data['SEARCH'] = $fd['search'];
					
			} else {
				$data['reports'] = $this->reports_model->get_reports_for_user($this->user['id'], $start_index, $records=50);
				$data['pagination'] = $this->pagination->create_links();
			}
		}
		
		$data['user'] = $this->user;
		$data['title'] = "Reports";
		$data['modules'] = $this->custom_auth->get_user_navigation();
		$data['MODULE'] = 'REPORTS';
		$data['SUBMODULE'] = 'REPORTS';
		$data['breadcrumb'] = array_merge($this->breadcrumb, array(array('label'=>$data['title'], 'url'=>'#')));
		
		$this->template->title($data['title']);
		$this->template->set_partial('nav', 'layouts/fixed_navbar-part');
		$this->template->set_partial('messages', 'layouts/messages-part');
		$this->template->set_layout('logged_in_template');
		$this->template->build('reports/reports', $data);
	}

	
	public function report_details($report_id)
	{
		$report = null;
		
		if ($report_id)
		{
			$report = $this->core_model->get_object('report_reports', $report_id);
		}
		
		if ( ! $report)
		{
			$this->session->set_flashdata('ERROR', $this->message_templates['MISSING_ITEM']);
			redirect('/reports/', 'location');
		}
		
		# check is user has report access privileges
		if ( ! $this->reports_model->user_has_report_access($this->user['id'], $report['id']) && ! $this->custom_auth->has_privilege('REPORTS_ADMIN'))
		{
			$this->session->set_flashdata('ERROR', $this->message_templates['MISSING_PRIVILEGES']);
			redirect('/reports/', 'location');
		}
		
		$start_index = 0;
		
		$pagination = $this->config->item('pagination_settings');
		$pagination['per_page'] = 50;
		$pagination['base_url'] = site_url('/reports/report_details');
		$pagination['total_rows'] = $this->core_model->count_objects('report_output', array('id_report_reports'=>$report_id));
		$this->pagination->initialize($pagination);
		
		# get reports (with pagination)
		$fd = $this->input->get();
		
		if (isset($fd['per_page']))
		{
			$start_index = $fd['per_page'];
		}
		
		if ( ! (int)$start_index){
			$start_index = 0;
		}
		
		$data['group_access'] = $this->reports_model->get_report_group_access($report['id']);
		$data['report_output'] = $this->reports_model->get_report_output($report_id, $start_index, $records=50);
		$data['pagination'] = $this->pagination->create_links();
		$data['report'] = $report;
		
		$data['user'] = $this->user;
		$data['title'] = "Report Details";
		$data['modules'] = $this->custom_auth->get_user_navigation();
		$data['MODULE'] = 'REPORTS';
		$data['SUBMODULE'] = 'REPORTS';
		$data['breadcrumb'] =  array_merge($this->breadcrumb, array(array('label'=>'Reports', 'url'=>site_url('/reports/')), array('label'=>'Report Details', 'url'=>'#')));
		
		$this->template->title($data['title']);
		$this->template->set_partial('nav', 'layouts/fixed_navbar-part');
		$this->template->set_partial('messages', 'layouts/messages-part');
		$this->template->set_layout('logged_in_template');
		$this->template->build('reports/report_details', $data);
	}
	
	
	public function run_report($report_id=null, $run_id=null)
	{
		#check user has privileges
		if ( ! $this->custom_auth->has_privilege('RUN_REPORTS'))
		{
			$this->session->set_flashdata('ERROR', $this->message_templates['MISSING_PRIVILEGES']);
			redirect('/reports/report_details/'.$report_id, 'location');
		}
				
		$fd = $this->input->post();
		
		# check if user wants to cancel
		if (isset($fd['cancel']))
		{
			# user cancelled
			redirect('/reports/', 'location');
		}
		
		if (isset($fd['report_id']) && strlen($fd['report_id']) > 0)
		{
			$report_id = $fd['report_id'];
		}
		
		# get the report
		$report = null;
		
		if ($report_id)
		{
			$report = $this->core_model->get_object('report_reports', $report_id);
		}
		
		if ( ! $report)
		{
			$this->session->set_flashdata('ERROR', $this->message_templates['MISSING_ITEM']);
			redirect('/reports/', 'location');
		}
		
		# check is user has report access privileges
		if ( ! $this->reports_model->user_has_report_access($this->user['id'], $report['id']) && ! $this->custom_auth->has_privilege('REPORTS_ADMIN'))
		{
			$this->session->set_flashdata('ERROR', $this->message_templates['MISSING_PRIVILEGES']);
			redirect('/reports/', 'location');
		}
		
		$exit_url = '/reports/report_details/'.$report_id;
		
		if (isset($fd['run_id']) && strlen($fd['run_id']) > 0)
		{
			$run_id = $fd['run_id'];
		}
		
		$object_to_edit = null;
		
		if ($run_id)
		{
			$object_to_edit = $this->core_model->get_object('report_output', $run_id);
		}
		
		if ($object_to_edit)
		{
			$report_command = $this->core_model->get_object('core_command_queue', $object_to_edit['ref_command']);
		}
		
		$report_input_fields = $this->core_model->get_many_objects('report_input_fields', array('id_report_reports' => $report['id']));
		
		if ((bool)$report['enable_run_now'] && (isset($fd['run_now_and_view']) || isset($fd['run_now_and_download'])))
		{
			# get command parameters
			$command_params = array();
		
			foreach ($report_input_fields as &$F)
			{
				$command_params[$F['field']] = $fd[$F['field']];
				
				if (is_array($command_params[$F['field']])) # convert array into its string representation
				{
					$command_params[$F['field']] = "('".implode("','",$command_params[$F['field']])."')";
				}
			}
			
			# run report
			if ((bool)$report['enable_output_grouping'] && ! empty($report['output_grouping_column']))
			{
				$report_data = $this->reports_model->run_report($report['id'], $command_params, true); // group results
			}
			else
			{
				$report_data = $this->reports_model->run_report($report['id'], $command_params);
			}
			
			$output = '';
			
			if ($report_data && count($report_data) > 0)
			{
				if ((bool)$report['enable_output_grouping'] && ! empty($report['output_grouping_column'])) // results grouping enabled
				{
					// get the header row
					$header_row = null;
					foreach ($report_data as $GRP=>&$rows)
					{
						foreach ($rows as &$R)
						{
							$header_row = $R;
							break;
						}
						break;
					}
					
					$header_row_keys = array_keys($header_row);
					$header_format = 'HEADERS_IN_KEYS';
					
					if (is_int($header_row_keys[0]))
					{
						$header_format = 'HEADERS_IN_FIRST_ROW';
					}
					
					// print grouped rows
					if (isset($fd['run_now_and_view'])) // generate HTML
					{
						$output .= '<table class="table table-striped table-condensed">';
						foreach ($report_data as $GRP=>&$rows)
						{
							$output .= '<tr><th colspan="3">&nbsp;</th></tr><tr><th colspan="3">'.$GRP.'</th></tr>';
							if ($header_format == 'HEADERS_IN_KEYS') // print header again for each group
							{
								$output .= '<tr><th>'.implode('</th><th>', $header_row_keys).'</th></tr>';
							}
							else
							{
								$output .= '<tr><th>'.implode('</th><th>', $header_row).'</th></tr>';
							}
							
							$counter = 0;
							foreach ($rows as &$R)
							{
								$counter += 1;
								if ($header_format == 'HEADERS_IN_FIRST_ROW' && $counter == 1)
								{
									continue;
								}
								$output .= '<tr><td>'.implode('</td><td>', $R).'</td></tr>';
							}
						}
						$output .= '</table>';
					}
					else // generate CSV
					{
						foreach ($report_data as $GRP=>&$rows)
						{
							$output .= "\n\n".',"'.$GRP.'"';
							if ($header_format == 'HEADERS_IN_KEYS') // print header again for each group
							{
								$output .= "\n".'"'.implode('","', $header_row_keys).'"'."\n";
							}
							else
							{
								$output .= "\n".'"'.implode('","', $header_row).'"'."\n";
							}
							
							$counter = 0;
							foreach ($rows as &$R)
							{
								$counter += 1;
								if ($header_format == 'HEADERS_IN_FIRST_ROW' && $counter == 1)
								{
									continue;
								}
								$output .= '"'.implode('","', $R).'"'."\n";
							}
						}
					}
				}
				else
				{
					$header_row = $report_data[0];
					$header_row_keys = array_keys($header_row);
					$header_format = 'HEADERS_IN_KEYS';
						
					if (is_int($header_row_keys[0]))
					{
						$header_format = 'HEADERS_IN_FIRST_ROW';
					}
					
					if (isset($fd['run_now_and_view'])) // generate HTML
					{
						$output .= '<table class="table table-striped table-condensed">';
						if ($header_format == 'HEADERS_IN_KEYS') // print header again for each group
						{
							$output .= '<tr><th>sno</th><th>'.implode('</th><th>', $header_row_keys).'</th></tr>';
						}
						else
						{
							$output .= '<tr><th>sno</th><th>'.implode('</th><th>', $header_row).'</th></tr>';
						}
						
						$counter = 0;
						foreach ($report_data as &$R)
						{
							$counter += 1;
							if ($header_format == 'HEADERS_IN_FIRST_ROW' && $counter == 1)
							{
								continue;
							}
							$output .= "<tr><td>$counter</td><td>".implode('</td><td>', $R).'</td></tr>';
						}
						$output .= '</table>';
					}
					else # generate CSV
					{
						if ($header_format == 'HEADERS_IN_KEYS') // print header
						{
							$output .= '"'.implode('","', $header_row_keys).'"'."\n";
						}
						else
						{
							$output = '"'.implode('","', $header_row).'"'."\n";
						}
						
						$counter = 0;
						foreach ($report_data as &$R)
						{
							$counter += 1;
							if ($header_format == 'HEADERS_IN_FIRST_ROW' && $counter == 1)
							{
								continue;
							}
							$output .= '"'.implode('","', $R).'"'."\n";
						}
					}
				}
			}
				
			# check if it is for viewing / download
			if (isset($fd['run_now_and_view']))
			{
				$data['report_results'] = $output;
				
				if (count($report_data) == 0)
				{
					$data['report_results'] = '<div class="text-center" style="color:#ddd;"><h1>No Results Found</h1></div>';
				}
			}
			else
			{
				# download report
				$this->load->helper('download');
				$csv_filename = $this->core_model->generate_random_string(15);
				force_download($csv_filename.'.csv', $output);
			}
			
		}
		elseif (isset($fd['run_in_background']))
		{
			# get command parameters
			$command_params = array();
		
			foreach ($report_input_fields as &$F)
			{
				$command_params[$F['field']] = $fd[$F['field']];
							
				if (is_array($command_params[$F['field']])) # convert array into its string representation
				{
					$command_params[$F['field']] = "('".implode("','",$command_params[$F['field']])."')";
				}
			}
			
			if ($object_to_edit)
			{
				# delete output file first
				$this->load->helper('path');
				
				$file_download_base_path = set_realpath($this->config->item('file_download_base_path'));
				$report_save_path = $file_download_base_path.'REPORTS/';
				
				if ( ! empty($object_to_edit['file_name']))
				{
					unlink($report_save_path.$object_to_edit['file_name']);
				}
				
				if ($report_command)
				{
					# update command data
					$command_data = array
					(
						'parameters_json' => json_encode($command_params),
						'updated_at' => date('Y-m-d H:i:s'),
						'status' => 'PENDING',
						'actor' => $this->user['username'],
					);
					$this->core_model->update_object('core_command_queue', $object_to_edit['ref_command'], $this->core_model->null_blank_entries($command_data));
					$entry_id = $object_to_edit['ref_command'];
				}
				else
				{
					# save command data
					$command_data = array
					(
						'command' => $this->config->item('run_report_command'),
						'command_ref' => $report['id'],
						'parameters_json' => json_encode($command_params),
						'created_at' => date('Y-m-d H:i:s'),
						'updated_at' => date('Y-m-d H:i:s'),
						'status' => 'PENDING',
						'actor' => $this->user['username'],
					);
					$entry_id = $this->core_model->create_object('core_command_queue', $this->core_model->null_blank_entries($command_data));
				}
				
				# update report output
				$report_output = array(
					'label' => $fd['kjFz_EvnQ2y8o0'],
					'ref_command' => $entry_id,
				);
				$this->core_model->update_object('report_output', $object_to_edit['id'], $this->core_model->null_blank_entries($report_output));
				
			}
			else
			{
				# save command data
				$command_data = array
				(
					'command' => $this->config->item('run_report_command'),
					'command_ref' => $report['id'],
					'parameters_json' => json_encode($command_params),
					'created_at' => date('Y-m-d H:i:s'),
					'updated_at' => date('Y-m-d H:i:s'),
					'status' => 'PENDING',
					'actor' => $this->user['username'],
				);
				$entry_id = $this->core_model->create_object('core_command_queue', $this->core_model->null_blank_entries($command_data));
				
				# save report output
				$report_output = array(
					'id_report_reports' => $report_id,
					'label' => $fd['kjFz_EvnQ2y8o0'],
					'ref_command' => $entry_id,
				);
				$this->core_model->create_object('report_output', $this->core_model->null_blank_entries($report_output));
			}
			
			$this->session->set_flashdata('SUCCESS', "The command to generate the report has been sent successfully. The process will run in the background and may take some time to complete.");
			# redirect to details page
			redirect($exit_url, 'location');
		}
		
		# create the form
		$this->autoform->add(array('name'=>'report_id', 'type'=>'hidden', 'value'=>$report_id));
		$this->autoform->add(array('name'=>'kjFz_EvnQ2y8o0', 'type'=>'text', 'placeholder'=>'Report Label', 'label'=>'Report Label'));
		
		if ($object_to_edit)
		{
			# get the parameters used to run the report
			$saved_query_params = array();
			$run_command = $this->core_model->get_object('core_command_queue', $object_to_edit['ref_command']);
			if ($run_command)
			{
				$saved_query_params = json_decode($run_command['parameters_json'], true);
			}
			$this->autoform->add(array('name'=>'run_id', 'type'=>'hidden', 'value'=>$run_id));
			$this->autoform->set('kjFz_EvnQ2y8o0', array('value' => $object_to_edit['label']));
		}
		
		foreach ($report_input_fields as &$F)
		{
			if ($object_to_edit && isset($saved_query_params[$F['field']]))
			{
				$field_value = $saved_query_params[$F['field']];
			}
			else
			{
				$field_value = $F['default_value'];
			}
			
			# handle selection fields
			if (in_array($F['field_type'], array('SELECT','MULTISELECT')))
			{
				# process choices
				$select_field_choices = array();
				
				if ($F['select_field_source_type'] == 'LIST')
				{
					# this must be a comma separated list of values
					foreach (explode(',', $F['select_field_source']) as $O)
					{
						$select_field_choices[$O] = $O;
					}
				}
				elseif ($F['select_field_source_type'] == 'RANGE')
				{
					# we expect a range like: 1990-2016:1 (1990 upto 2016 in increments of 1)
					# or 1-20:2 (1 upto 20 in increments of 2)
					$params = explode(':', $F['select_field_source']);
					$__range = explode('-', $params[0]);
					
					if (count($params) == 2 && (int) $params[1])
					{
						$range_step = (int) $params[1];
					}
					else
					{
						$range_step = 1;
					}
					
					if (count($__range) == 2)
					{
						foreach (range((float) $__range[0], (float) $__range[1], $range_step) as $V)
						{
							$select_field_choices[$V] = $V;
						}
					}
				}
				elseif ($F['select_field_source_type'] == 'SQL')
				{
					# The query must return 2 columns: id & label
					$values = $this->reports_model->run_sql_query($F['select_field_source']);
					if ($values)
					{
						foreach ($values as &$V)
						{
							if (isset($V['id']) && isset($V['label']))
							{
								$select_field_choices[$V['id']] = $V['label'];
							}
						}
					}
				}
				elseif ($F['select_field_source_type'] == 'LOOKUP')
				{
					$select_field_choices = $this->core_model->get_select_box_options_from_lookup($F['select_field_source']);
					unset($select_field_choices['']);
				}
				
				if ($F['field_type'] == 'MULTISELECT')
				{
					$this->autoform->add(array('name'=>$F['field'].'[]', 'type'=>'multiselect', 'options'=>$select_field_choices, 'placeholder'=>$F['field'].': '.$F['placeholder_text'], 'value'=>$field_value));
				}
				else
				{
					$this->autoform->add(array('name'=>$F['field'], 'type'=>'select', 'options'=>$select_field_choices, 'placeholder'=>$F['field'].': '.$F['placeholder_text'], 'value'=>$field_value));
				}
			}
			else
			{
				$this->autoform->add(array('name'=>$F['field'], 'type'=>'text', 'placeholder'=>$F['placeholder_text'], 'value'=>$field_value));
			}
			
			$this->autoform->set_help_text($F['field'], $F['help_text']);
			
			if ((bool) $F['is_mandatory'])
			{
				$this->autoform->set($F['field'], array('required'=>'required'));
			}
		}
		$this->autoform->set_all(array('class'=>'form-control'));
		
		$this->autoform->wrap_each('<div class="form-group form-group-sm" style="padding:10px;border:1px solid #ccc;">','</div>');
		$this->autoform->wrap_each_field_only('<div>','</div>');
		
		# form buttons
		$form_buttons = '<div class="button-row" style="margin-top:20px;"><a class="btn btn-default" href="'.site_url($exit_url).'" role="button">Cancel</a> '
			.form_submit(array('type'=>'submit', 'name'=>'run_in_background', 'value'=>'Run in Background', 'class'=>'btn btn-primary'));
		
		if ((bool)$report['enable_run_now'])
		{
			$form_buttons .= form_submit(array('type'=>'submit', 'name'=>'run_now_and_view', 'value'=>'Run Immediately & View', 'class'=>'btn btn-primary'));
			$form_buttons .= form_submit(array('type'=>'submit', 'name'=>'run_now_and_download', 'value'=>'Run Immediately & Download', 'class'=>'btn btn-primary'));
		}
		$form_buttons .= '</div>';
		
		$this->autoform->buttons($form_buttons);
		
		# prepare template
		$data['report'] = $report;
		$data['form'] = $this->autoform->generate('reports/run_report', array('class'=>'form-inline'));
		
		$data['user'] = $this->user;
		$data['title'] = "Run Report";
		$data['modules'] = $this->custom_auth->get_user_navigation();
		$data['MODULE'] = 'REPORTS';
		$data['SUBMODULE'] = 'REPORTS';
		$data['breadcrumb'] = array_merge($this->breadcrumb, array(array('label'=>'Reports', 'url'=>site_url('/reports/')), array('label'=>'Report Details', 'url'=>site_url($exit_url)), array('label'=>$data['title'], 'url'=>'#')));
		
		$this->template->title($data['title']);
		$this->template->set_partial('nav', 'layouts/fixed_navbar-part');
		$this->template->set_partial('messages', 'layouts/messages-part');
		$this->template->set_layout('logged_in_template');
		$this->template->build('reports/run_report', $data);
	}

	
	public function modify_report($id=null)
	{
		#check user has privileges
		if ( ! $this->custom_auth->has_privilege('REPORTS_ADMIN'))
		{
			$this->session->set_flashdata('ERROR', $this->message_templates['MISSING_PRIVILEGES']);
			redirect('/reports/', 'location');
		}
		
		$fd = $this->input->post();
		
		# check if user wants to cancel
		if (isset($fd['cancel']))
		{
			# user cancelled
			redirect('/reports/', 'location');
		}
	
		if (isset($fd['id']) && strlen($fd['id']) > 0)
		{
			$id = $fd['id'];
		}
		
		$object_to_edit = null;
		if ($id)
		{
			$object_to_edit = $this->core_model->get_object('report_reports', $id);
		}
		
		$report_input_fields = array();
		
		if ($object_to_edit)
		{
			$report_input_fields = $this->core_model->get_many_objects('report_input_fields', array('id_report_reports' => $object_to_edit['id']));
		}
		
		# validate form data
		$form_validation_rules = array(
			array(
				'field'   => 'name',
				'rules'   => 'required'
			),
			array(
				'field'   => 'query',
				'rules'   => 'required'
			),
			array(
				'field'   => 'user_can_run',
				'rules'   => 'required'
			),
			array(
				'field'   => 'enable_run_now',
				'rules'   => 'required'
			),
		);
		
		$this->form_validation->set_rules($form_validation_rules);
		
		if ($this->form_validation->run() == true)
		{
			# form is valid, save data
			$fd = $this->input->post();
		
			# remove unwanted form data and save
			unset($fd['submit']);
		
			$post_custom_fields = array();
			for ($i = 0; $i < count($fd['field']); $i++)
			{
				if (strlen($fd['field'][$i]) == 0)
				{
					continue;
				}
				$post_custom_fields[] = array(
					'field' => $fd['field'][$i], 
					'field_type' => $fd['field_type'][$i],
					'is_mandatory' => $fd['is_mandatory'][$i],
					'default_value' => $fd['default_value'][$i], 
					'placeholder_text' => $fd['placeholder_text'][$i],
					'help_text' => $fd['help_text'][$i],
					'select_field_source_type' => $fd['select_field_source_type'][$i],
					'select_field_source' => $fd['select_field_source'][$i],
				);
			}
			
			# save the data
			if ($object_to_edit)
			{
				$to_save = array(
					'name' => $fd['name'],
					'description' => $fd['description'],
					'user_can_run' => $fd['user_can_run'],
					'enable_run_now' => $fd['enable_run_now'],
					'grouping' => $fd['grouping'],
					'ordering' => $fd['ordering'],
					'query' => $fd['query'],
					'enable_pivoting' => $fd['enable_pivoting'],
					'pivot_on_columns' => $fd['pivot_on_columns'],
					'pivot_label_columns' => $fd['pivot_label_columns'],
					'pivot_value_columns' => $fd['pivot_value_columns'],
					'pivot_column_name_separator' => $fd['pivot_column_name_separator'],
					'pivot_sum_rows' => $fd['pivot_sum_rows'],
					'pivot_sum_columns' => $fd['pivot_sum_columns'],
					'enable_output_grouping' => $fd['enable_output_grouping'],
					'output_grouping_column' => $fd['output_grouping_column'],
				);
				$this->core_model->update_object('report_reports', $id, $this->core_model->null_blank_entries($to_save));
				$entry_id = &$id;
			}
			else
			{
				$to_save = array(
					'name' => $fd['name'],
					'description' => $fd['description'],
					'user_can_run' => $fd['user_can_run'],
					'enable_run_now' => $fd['enable_run_now'],
					'grouping' => $fd['grouping'],
					'ordering' => $fd['ordering'],
					'query' => $fd['query'],
					'enable_pivoting' => $fd['enable_pivoting'],
					'pivot_on_columns' => $fd['pivot_on_columns'],
					'pivot_label_columns' => $fd['pivot_label_columns'],
					'pivot_value_columns' => $fd['pivot_value_columns'],
					'pivot_column_name_separator' => $fd['pivot_column_name_separator'],
					'pivot_sum_rows' => $fd['pivot_sum_rows'],
					'pivot_sum_columns' => $fd['pivot_sum_columns'],
					'enable_output_grouping' => $fd['enable_output_grouping'],
					'output_grouping_column' => $fd['output_grouping_column'],
					'created_by' => $this->user['username'],
					'created_at' => date('Y-m-d H:i:s'),
				);
				$entry_id = $this->core_model->create_object('report_reports', $this->core_model->null_blank_entries($to_save));
			}
			
			# delete and recreate custom report fields
			$this->core_model->delete_many_objects('report_input_fields', array('id_report_reports'=>$entry_id));
			
			foreach ($post_custom_fields as &$F)
			{
				$F['id_report_reports'] = $entry_id;
				$this->core_model->create_object('report_input_fields', $this->core_model->null_blank_entries($F));
			}
			$this->session->set_flashdata('SUCCESS', $this->message_templates['SAVED_SUCCESSFULLY']);
			
			# delete and recreate group access privileges
			if (isset($fd['group_access']) && is_array($fd['group_access']))
			{
				$this->core_model->delete_many_objects('report_group_access', array('id_report_reports'=>$entry_id));
				
				foreach ($fd['group_access'] as &$GA)
				{
					$this->core_model->create_object('report_group_access', array('id_report_reports'=> $entry_id, 'id_core_groups'=>$GA));
				}
			}
			
			# redirect to details page
			if ($object_to_edit)
			{
				redirect('/reports/report_details/'.$object_to_edit['id'], 'location');
			}
			else
			{
				redirect('/reports/', 'location');
			}
		}
		else
		{
			$_POST['field'] = $_POST['field_type'] = $_POST['default_value'] = $_POST['placeholder_text'] = $_POST['help_text'] = $_POST['select_field_source_type'] = $_POST['select_field_source'] = '';
		}
		
		# create the form
		if ($object_to_edit)
		{
			$this->autoform->sql($this->core_model->get_select_sql('report_reports', $object_to_edit['id']));
		}
		else
		{
			$this->autoform->table('report_reports');
			$this->autoform->set('pivot_column_name_separator', array('value'=>' - '));
			$this->autoform->set(array('enable_pivoting','pivot_sum_rows','pivot_sum_columns','enable_output_grouping','ordering'), array('value' => 0));
		}
		$this->autoform->set('name', array('label'=>'Report Name', 'required'=>'required'));
		$this->autoform->set('description', array('type'=>'textarea', 'rows'=>3));
		$this->autoform->set('user_can_run', array('type'=>'select', 'label'=>'Allow Users to Run This Report', 'value'=>1, 'options'=>$this->core_model->get_select_box_options_from_lookup('BOOL_YES_NO')));
		$this->autoform->set('enable_run_now', array('type'=>'select', 'label'=>'Allow Users to Run Immediately', 'value'=>1, 'options'=>$this->core_model->get_select_box_options_from_lookup('BOOL_YES_NO')));
		$this->autoform->set('query', array('type'=>'textarea', 'label'=>'SQL Query', 'required'=>'required'));
		$this->autoform->set('enable_pivoting', array('type'=>'select', 'label'=>'Enable Pivoting', 'options'=>$this->core_model->get_select_box_options_from_lookup('BOOL_YES_NO')));
		$this->autoform->set('pivot_sum_rows', array('type'=>'select', 'label'=>'Sum Pivot Rows?', 'options'=>$this->core_model->get_select_box_options_from_lookup('BOOL_YES_NO')));
		$this->autoform->set('pivot_sum_columns', array('type'=>'select', 'label'=>'Sum Pivot Columns?', 'options'=>$this->core_model->get_select_box_options_from_lookup('BOOL_YES_NO')));
		$this->autoform->set_help_text('pivot_on_columns', '<div class="col-sm-4"></div><div class="col-sm-8 text-muted">Enter values separated by a comma only</div>');
		$this->autoform->set_help_text('pivot_label_columns', '<div class="col-sm-4"></div><div class="col-sm-8 text-muted">Enter values separated by a comma only</div>');
		$this->autoform->set_help_text('pivot_value_columns', '<div class="col-sm-4"></div><div class="col-sm-8 text-muted">Enter values separated by a comma only</div>');
		$this->autoform->set('enable_output_grouping', array('type'=>'select', 'label'=>'Enable Grouping of Output?', 'options'=>$this->core_model->get_select_box_options_from_lookup('BOOL_YES_NO')));
		
		$this->autoform->remove(array('created_by','created_at','remote_id','sync'));
		$this->autoform->set_all(array('class'=>'form-control', 'label'=>array('class'=>'control-label  col-sm-4')));
		$this->autoform->wrap_each_field_only('<div class="col-sm-8">','</div>');
		$this->autoform->wrap_each('<div class="form-group form-group-sm">','</div>');
		
		# prepare template
		$data['partial_form'] = $this->autoform->fields();
		
		// add group access privileges
		$this->autoform->clear();
		$data['partial_form'] .= '<div class="col-sm-12"><h4>Group Access Privileges</h4></div>';
		
		if ($object_to_edit)
		{
			$group_access = $this->reports_model->get_report_group_access($object_to_edit['id']);
		}
		else
		{
			$group_access = $this->reports_model->get_report_group_access();
		}
		
		foreach ($group_access as $row)
		{
			if ($row['id_core_groups'])
			{
				$this->autoform->add(array('name'=>'group_access[]', 'type'=>'checkbox', 'checked'=>true, 'value'=>$row['id'], 'label'=>$row['name'].'<p class="help-block"><small>'.$row['description'].'</small></p>', ));
			}
			else
			{
				$this->autoform->add(array('name'=>'group_access[]', 'type'=>'checkbox', 'value'=>$row['id'], 'label'=>$row['name'].'<p class="help-block"><small>'.$row['description'].'</small></p>', ));
			}
		}
		
		$this->autoform->wrap_each('<div class="col-sm-12 col-md-6 col-lg-4"><div class="checkbox">','</div></div>');
		$data['partial_form'] .= $this->autoform->fields();
		
		$data['report_input_fields'] = $report_input_fields;
		
		$data['user'] = $this->user;
		$data['title'] = "Modify Report";
		$data['modules'] = $this->custom_auth->get_user_navigation();
		$data['MODULE'] = 'REPORTS';
		$data['SUBMODULE'] = 'REPORTS';
		
		if ($object_to_edit)
		{
			$data['report'] = $object_to_edit;
			$data['breadcrumb'] = array_merge($this->breadcrumb, array(array('label'=>'Reports', 'url'=>site_url('/reports/')), array('label'=>'Report Details', 'url'=>site_url('/reports/report_details/'.$object_to_edit['id'])), array('label'=>$data['title'], 'url'=>'#')));
		}
		else
		{
			$data['breadcrumb'] = array_merge($this->breadcrumb, array(array('label'=>'Reports', 'url'=>site_url('/reports/')), array('label'=>'Create New Report', 'url'=>'#')));
		}
		
		# add custom js
		$this->template->inject_partial('custom_js_bottom', '<script src="'.base_url('/media/static/misc_functions.js').'"></script>');
		
		$this->template->title($data['title']);
		$this->template->set_partial('nav', 'layouts/fixed_navbar-part');
		$this->template->set_partial('messages', 'layouts/messages-part');
		$this->template->set_layout('logged_in_template');
		$this->template->build('reports/modify_report', $data);
	}
	
	
	public function delete_report_output($report_id, $id)
	{
		$this->load->helper('path');
		
		# check user has privileges
		if ( ! $this->custom_auth->has_privilege('REPORTS_ADMIN'))
		{
			$this->session->set_flashdata('ERROR', $this->message_templates['MISSING_PRIVILEGES']);
			redirect('/reports/', 'location');
		}
		
		# get the report
		$report = $this->core_model->get_object('report_reports', $report_id);
		
		if ( ! $report)
		{
			$this->session->set_flashdata('ERROR', $this->message_templates['MISSING_ITEM']);
			redirect('/reports/', 'location');
		}
		
		# get the report output
		if ($id)
		{
			$object_to_delete = $this->core_model->get_object('report_output', $id, array('id_report_reports' => $report['id']));
		}
		
		if ( ! $object_to_delete)
		{
			$this->session->set_flashdata('ERROR', $this->message_templates['MISSING_ITEM']);
			redirect('/reports/report_details/'.$report['id'], 'location');
		}
		
		$file_download_base_path = set_realpath($this->config->item('file_download_base_path'));
		$report_save_path = $file_download_base_path.'REPORTS/';
		
		# delete report output
		# delete output file
		if ( ! empty($object_to_delete['file_name']))
		{
			$file_deletion = unlink($report_save_path.$object_to_delete['file_name']);
		}
		
		if ($file_deletion)
		{
			# delete output record
			$this->core_model->delete_object('report_output', $object_to_delete['id']);
			# delete report command
			$this->core_model->delete_object('core_command_queue', $object_to_delete['ref_command']);
		
			$this->session->set_flashdata('WARNING', $this->message_templates['DELETED_SUCCESSFULLY']);
		}
		redirect('/reports/report_details/'.$report['id'], 'location');
	}
	
	
	public function delete_report($id)
	{
		$this->load->helper('path');
		
		# check user has privileges
		if ( ! $this->custom_auth->has_privilege('REPORTS_ADMIN'))
		{
			$this->session->set_flashdata('ERROR', $this->message_templates['MISSING_PRIVILEGES']);
			redirect('/reports/', 'location');
		}
		
		# get the report
		if ($id)
		{
			$object_to_delete = $this->core_model->get_object('report_reports', $id);
		}
		
		if ( ! $object_to_delete)
		{
			$this->session->set_flashdata('ERROR', $this->message_templates['MISSING_ITEM']);
			redirect('/reports/', 'location');
		}
		
		$file_download_base_path = set_realpath($this->config->item('file_download_base_path'));
		$report_save_path = $file_download_base_path.'REPORTS/';
		
		# delete report output
		$report_instances = $this->core_model->get_many_objects('report_output', array('id_report_reports' => $object_to_delete['id']));
		
		foreach ($report_instances as &$R)
		{
			# delete output file
			if ( ! empty($R['file_name']))
			{
				unlink($report_save_path.$R['file_name']);
			}
			# delete output record
			$this->core_model->delete_object('report_output', $R['id']);
			# delete report command
			$this->core_model->delete_object('core_command_queue', $R['ref_command']);
		}
		
		# delete report input fields
		$this->core_model->delete_many_objects('report_input_fields', array('id_report_reports' => $object_to_delete['id']));
		
		# delete report record
		$this->core_model->delete_object('report_reports', $object_to_delete['id']);
		
		$this->session->set_flashdata('WARNING', $this->message_templates['DELETED_SUCCESSFULLY']);
		redirect('/reports/', 'location');
	}
}

?>
