<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Administration extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		
		$this->load->library('custom_auth');
		
		# check if the user is logged in
		if ($this->custom_auth->is_authenticated() == false)
		{
			#skip if commands are run from cli
			if(!is_cli())
			{
				redirect('/core/login/', 'location');
			}
		}
		
		$this->user = $this->custom_auth->get_user();
		$this->breadcrumb = array
		(
			array('label'=>"Event Registrations", 'url'=>site_url("/administration/"))
		);
		
		$this->message_templates = array
		(
				'MISSING_PRIVILEGES' => 'you do not have the necessary privileges to perfom this operation',
				'SAVED_SUCCESSFULLY' => 'the information has been saved successfully',
				'MISSING_ITEM' => 'the item selected could not be found',
				'DELETED_SUCCESSFULLY' => 'the information has been deleted successfully',
		);
		
		$this->load->model('reports_model');
	}

	/*
	 * index method
	 * 
	 * index.php administration or administration/index
	 *  
	 * */
	 
	public function index($start_index=0)
	{
				
		$fd = $this->input->get();
		
		$pagination = array
		(
			'base_url' => null,
			'total_rows' => null,
			'per_page' => 50,
			'num_links' => 15,
			'full_tag_open' => '<ul class="pagination pagination-sm">',
			'full_tag_close' => '</ul>',
			'first_tag_open' => '<li>',
			'first_link' => '&laquo; First',
			'first_tag_close' => '</li>',
			'prev_tag_open' => '<li>',
			'prev_link' => '&laquo;',
			'prev_tag_close' => '</li>',
			'next_tag_open' => '<li>',
			'next_link' => '&raquo;',
			'next_tag_close' => '</li>',
			'cur_tag_open' => '<li class="active"><a href="#">',
			'cur_tag_close' => '</a></li>',
			'num_tag_open' => '<li>',
			'num_tag_close' => '</li>',
			'last_tag_open' => '<li>',
			'last_link' => 'Last &raquo;',
			'last_tag_close' => '</li>',
		);
		$pagination['base_url'] = site_url('/administration/index');
		$pagination['total_rows'] = $this->core_model->count_all_objects('core_registrations');
		$this->pagination->initialize($pagination);
		
		if ( ! (int)$start_index)
		{
			$start_index = 0;
		}
		
		$filter_values = array('search' => '', 'date_from' => '', 'date_to' => '');
		
		if (isset($fd['filter']))
		{
			if (isset($fd['search']) && strlen(trim($fd['search'])) >= $this->config->item('minimum_search_length'))
			{
				$filter_values['search'] = $fd['search'];
			}
			
			if (isset($fd['date_from']) && strlen(trim($fd['date_from'])) > 0)
			{
				$filter_values['date_from'] = $fd['date_from'];
			}
			
			if (isset($fd['date_to']) && strlen(trim($fd['date_to'])) > 0)
			{
				$filter_values['date_to'] = $fd['date_to'];
			}
			
			$data['registrations'] = $this->reports_model->filter_main_registrations($filter_values);
			$data['FILTER'] = true;
		}
		else
		{
			$data['registrations'] = $this->reports_model->get_main_registrations($start_index, $records=50);
			$data['pagination'] = $this->pagination->create_links();
		}
		
		$data['filter_values'] = $filter_values;
		
		$data['user'] = $this->user;
		$data['title'] = "Event Registrations";
		$data['modules'] = $this->custom_auth->get_user_navigation();
		$data['MODULE'] = 'HOME';
		$data['SUBMODULE'] = 'REGS';
		$data['breadcrumb'] = $this->breadcrumb;
		
		# add css & js for date picker
		$this->template->inject_partial('custom_css_top', '<link href="'.base_url('/media/static/bootstrap/css/bootstrap-datetimepicker.min.css').'" rel="stylesheet">');
		$this->template->inject_partial('custom_js_bottom', '<script src="'.base_url('/media/static/moment.min.js').'"></script>'.'<script src="'.base_url('/media/static/bootstrap/js/bootstrap-datetimepicker.min.js').'"></script>'.
			"<script type=\"text/javascript\">$(function () { $('#date_from').datetimepicker({format:'YYYY-MM-DD'}); $('#date_to').datetimepicker({format:'YYYY-MM-DD'}); });".
			'function getRegDetails(reg_id)
			{
				$.ajax({
					url: "'.site_url('administration/registration_details').'" + "/" + reg_id,
					cache: false,
					success: function(output){
						$("#id_reg_details").html(output);
					}
				});
			}</script>');
		
		$this->template->title($data['title']);
		$this->template->set_partial('nav', 'layouts/fixed_navbar-part');
		$this->template->set_partial('messages', 'layouts/messages-part');
		$this->template->set_layout('logged_in_template');
		$this->template->build('administration/registrations', $data);
	}
	
	/*
	 * Barcode activities -
	 *  
	 * 	->Method developed to enable delegates at a conference book for sessions that occur at the same time.
	 *	->Barcode activity is created and sessions under the activity created.
	 * 	->Session barcodes are generated and printed.
	 * 	->Scanning delegate barcode code and session barcode allows on to book for an activity. 
	 * 
	 * @author peter ahumuza hmz2peter@gmail.com
	 * 
	 * methods
	 * 1.barcode_activities_list - lists barcode activities
	 * 2.barcode_activities - Add and Edit barcode activities
	 * 3.barcode_activities_details - barcode activity details
	 * 4.barcode_activities_delete - delete barcode_activity
	 * 
	 * */
	 
	public function barcode_activities_list()
	{

		$data['user'] = $this->user;
		$data['data']=$this->core_model->get_many_objects('barcode_activities');
		$data['title'] = "Barcode activities list";
		$data['modules'] = $this->custom_auth->get_user_navigation();
		$data['MODULE'] = 'BARCODE';
		$data['breadcrumb'] = array(
				array('label'=>"Barcode activities", 'url'=>"#")
				);
		
		
		$this->template->title($data['title']);
		$this->template->set_partial('nav', 'layouts/fixed_navbar-part');
		$this->template->set_partial('messages', 'layouts/messages-part');
		$this->template->set_layout('logged_in_template');
		$this->template->build('administration/barcode_list', $data);
		
	}
	 
	public function barcode_activities($id=null)
	{
		$fd = $this->input->post();
		
		if($fd)
		{
			unset($fd['submit']);
			
			$config =array(
						array(
							'field'=>'activity',
							'label'=>'Activity',
							'rules'=>'required'
							)
						);
						
			$this->form_validation->set_rules($config);
			
			if($this->form_validation->run() === true)
			{
				//editing
				if($id)
				{
					$fd['updated_at']=date('Y-m-d h:i:s');
					
					$this->core_model->update_object('barcode_activities',$id,$fd);
				}
				//adding
				else
				{
					$this->core_model->create_object('barcode_activities',$fd);
				}
				
				$this->session->set_flashdata('SUCCESS',$this->message_templates['SAVED_SUCCESSFULLY']);
				
				redirect('/administration/barcode_activities_list','location');
			}
		}
		
		//editing
		if($id)
		{
			$this->autoform->sql('select * from barcode_activities where id ='.$id);
		}
		//adding
		else
		{
			$this->autoform->table('barcode_activities');
		}
		
		$this->autoform->remove(array('created_at','updated_at','sync','remote_id'));
		$this->autoform->set(array('activity'),array('required'=>'required'));
		$this->autoform->set(array('description'),array('type'=>'textarea'));
		
		$this->autoform->set_all(array('class'=>'form-control', 'label'=>array('class'=>'control-label  col-sm-4')));
		$this->autoform->wrap_each_field_only('<div class="col-sm-8">','</div>');
		$this->autoform->wrap_each('<div class="form-group form-group-sm">','</div>');
		$this->autoform->buttons('<div class="button-row"> <a href="'.site_url('administration/barcode_activities_list').'" class="btn btn-default"> Cancel</a> '.form_submit(array('type'=>'submit', 'name'=>'submit', 'value'=>'Submit', 'class'=>'btn btn-primary')).'</div>');
		
		
		$data['user'] = $this->user;
		$data['form'] = $this->autoform->generate('/administration/barcode_activities/'.$id,array('class'=>'form-horizontal'), true);
		$data['title'] = "Barcode activities";
		$data['modules'] = $this->custom_auth->get_user_navigation();
		$data['MODULE'] = 'BARCODE';
		$data['breadcrumb'] = array(
				array('label'=>"Barcode activities", 'url'=>site_url('administration/barcode_activities_list')),
				array('label'=>"Add/Edit Activity", 'url'=>"#")
				);
		
		
		$this->template->title($data['title']);
		$this->template->set_partial('nav', 'layouts/fixed_navbar-part');
		$this->template->set_partial('messages', 'layouts/messages-part');
		$this->template->set_layout('logged_in_template');
		$this->template->build('layouts/form_body', $data);

	}
	
	public function barcode_activities_details($id)
	{
		$barcode_activities = $this->core_model->get_object('barcode_activities',$id);
		
		$barcode_sessions = $this->core_model->get_many_objects('barcode_sessions',array('id_barcode_activities'=>$id));
		
		$data['user'] = $this->user;
		$data['barcode_activities'] = $barcode_activities;
		$data['barcode_sessions'] = $barcode_sessions;
		$data['title'] = "Barcode activities";
		$data['modules'] = $this->custom_auth->get_user_navigation();
		$data['MODULE'] = 'BARCODE';
		$data['breadcrumb'] = array(
				array('label'=>"Barcode activities", 'url'=>site_url('administration/barcode_activities_list')),
				array('label'=>"Activity details", 'url'=>"#")
				);
		
		$this->template->title($data['title']);
		$this->template->set_partial('nav', 'layouts/fixed_navbar-part');
		$this->template->set_partial('messages', 'layouts/messages-part');
		$this->template->set_layout('logged_in_template');
		$this->template->build('administration/barcode_details', $data);
	}
	
	public function barcode_activities_delete($id)
	{
		$this->core_model->delete_object('barcode_activities',$id);
		
		$this->session->set_flashdata('WARNING',$this->message_templates['DELETED_SUCCESSFULLY']);
		
		redirect('administration/barcode_activities_list','location');
	}
	
	/*
	 * Barcode activity sessions
	 * 
	 * 
	 * 
	 * */
	 
	public function barcode_activity_session($activity_id,$session_id = null)
	{
		$fd = $this->input->post();
		
		if($fd)
		{
			unset($fd['submit']);
			
			$config=array(
						array(
							'field'=>'session',
							'label'=>'Session',
							'rules'=>'required'
						),
						array(
							'field'=>'maximum_number',
							'label'=>'Maximum number',
							'rules'=>'required|is_numeric'
						),
					);
					
			$this->form_validation->set_rules($config);
			
			if($this->form_validation->run() === true)
			{
				$fd['id_barcode_activities'] = $activity_id;
				
				//editing
				if($session_id)
				{
					$this->core_model->update_object('barcode_sessions',$session_id,$fd,array('id_barcode_activities'=>$activity_id));
				}
				//adding
				else
				{
					$this->core_model->create_object('barcode_sessions',$fd);
				}
				
				$this->session->set_flashdata('SUCCESS',$this->message_templates['SAVED_SUCCESSFULLY']);
				
				redirect('administration/barcode_activities_details/'.$activity_id,'location');
			}
		}
		
		$barcode_activity = $this->core_model->get_object('barcode_activities',$activity_id);
		$title='';
		
		
		//editing
		if($session_id)
		{
			$barcode_sessions = $this->core_model->get_object('barcode_sessions',$activity_id);
			$title ="Edit ".$barcode_activity['activity']." - ".$barcode_sessions['session']." session";
			
			$this->autoform->sql('select * from barcode_sessions where id ='.$session_id);
		}
		//adding
		else
		{
			$title ="Add Session to ".$barcode_activity['activity'];
			$this->autoform->table('barcode_sessions');
		}
		
		
		$this->autoform->remove(array('id_barcode_activities','created_at','updated_at','sync','remote_id'));
		
		$this->autoform->set('description', array('type'=>'textarea'));
		$this->autoform->set_all(array('class'=>'form-control', 'label'=>array('class'=>'control-label  col-sm-4')));
		$this->autoform->wrap_each_field_only('<div class="col-sm-8">','</div>');
		$this->autoform->wrap_each('<div class="form-group form-group-sm">','</div>');
		$this->autoform->buttons('<div class="button-row"> <a href="'.site_url('administration/barcode_activities_details/'.$activity_id).'" class="btn btn-default"> Cancel</a> '.form_submit(array('type'=>'submit', 'name'=>'submit', 'value'=>'Submit', 'class'=>'btn btn-primary')).'</div>');

		$data['user'] = $this->user;
		$data['form'] = $this->autoform->generate('/administration/barcode_activity_session/'.$activity_id.'/'.$session_id,array('class'=>'form-horizontal'), true);
		$data['title'] = $title;
		$data['modules'] = $this->custom_auth->get_user_navigation();
		$data['MODULE'] = 'BARCODE';
		$data['breadcrumb'] = array(
				array('label'=>"Barcode activities", 'url'=>site_url('administration/barcode_activities_list')),
				array('label'=>"Barcode activity", 'url'=>site_url('administration/barcode_activities_details/'.$activity_id)),
				array('label'=>$title, 'url'=>"#")
				);
		
		
		$this->template->title($data['title']);
		$this->template->set_partial('nav', 'layouts/fixed_navbar-part');
		$this->template->set_partial('messages', 'layouts/messages-part');
		$this->template->set_layout('logged_in_template');
		$this->template->build('layouts/form_body', $data);
	
	}
	
	public function barcode_activity_session_delete($activity_id,$session_id)
	{
		$this->core_model->delete_object('barcode_sessions',$session_id);
		
		$this->session->set_flashdata('WARNING',$this->message_templates['DELETED_SUCCESSFULLY']);
		
		redirect('administration/barcode_activities_details/'.$activity_id,'location');

	} 
	
	public function barcode_activities_book($id)
	{
		$fd = $this->input->post();
		
		$barcode_activity = $this->core_model->get_object('barcode_activities',$id);
		
		if($fd)
		{
			$config= array(
						array(
							'field' => 'registration_code',
							'label' => 'Barcode',
							'rules' => 'required|callback_reg_check|callback_attendance_check'
						)
					);
			
			$this->form_validation->set_rules($config);
			
			
			if($this->form_validation->run() == true)
			{
				//get delegate 
				$delegate = $this->core_model->get_object('core_registrations',null,array('registration_code'=>$fd['registration_code']));
				
				//check if delegate already booked for this activity
				$SQL ="select * from barcode_session_bookings where id_core_registrations = {$delegate['id']} and id_barcode_activities ={$id}";
				
				$booking = $this->reports_model->run_sql_query($SQL);
				
				
				if($booking)
				{
					//get delegate booking
					$SQL ="select * from barcode_sessions where id_barcode_activities ={$id} and id ={$booking[0]['id_barcode_sessions']}";
					
					$barcode_sessions = $this->reports_model->run_sql_query($SQL);
					
					$this->session->set_flashdata('WARNING',"{$delegate['first_name']} {$delegate['last_name']} {$delegate['other_names']} is already booked for {$barcode_activity['activity']} in the {$barcode_sessions[0]['session']} session. Continuation will modify the booking.");
				}
				else
				{
					$this->session->set_flashdata('SUCCESS',"Scan session barcode to book {$delegate['first_name']} {$delegate['last_name']} {$delegate['other_names']} a  session.");
				}
				
				redirect('administration/barcode_session_book/'.$barcode_activity['id'].'/'.$delegate['id'],'location');
			}
			else
			{ 
				$this->session->set_flashdata('ERROR',validation_errors());
			}

		}
		
		$form  = '<div class="form-horizontal">
			<table class="table table-condensed table-striped table-bordered">
				<tr>
					<td>
						'.form_open("/administration/barcode_activities_book/{$id}").' 
						<div class="input-group input-group-sm"><span class="input-group-addon">&nbsp;<span class="glyphicon glyphicon-barcode" aria-hidden="true"></span>Scan delegate barcode</span>
						<input type="text" maxlength="12" autofocus="autofocus" name="registration_code" class="form-control" aria-label="search" placeholder="Scan delegate barcode" value="" />
						<span class="input-group-btn"><input type="submit" value="Go" class="btn btn-default"/></span></div>
						'.form_close().'
					</td>
				</tr>
			</table>
		</div>';
		
		$data['user'] = $this->user;
		$data['form']=$form;
		$data['title'] = "Scan delegate barcode";
		$data['modules'] = $this->custom_auth->get_user_navigation();
		$data['MODULE'] = 'BARCODE';
		$data['breadcrumb'] = array(
				array('label'=>"Barcode activities", 'url'=>site_url("administration/barcode_activities_list")),
				array('label'=>"Book delegate for ".$barcode_activity['activity'], 'url'=>"#")
				);
		
		
		$this->template->title($data['title']);
		$this->template->set_partial('nav', 'layouts/fixed_navbar-part');
		$this->template->set_partial('messages', 'layouts/messages-part');
		$this->template->set_layout('logged_in_template');
		$this->template->build('layouts/form_body', $data);
	}
	
	
	public function attendance_check()
	{
		$fd = $this->input->post();
		
		//get delegate
		$SQL ="select id,first_name,last_name,other_names from core_registrations where registration_code='{$fd['registration_code']}'";
		
		$C = $this->reports_model->run_sql_query($SQL)[0];
		
		//check if delegate booked for 
		$SQL ="select count(1) num from core_session_attendees where id_core_registrations={$C['id']}";
		$COUNT  = (int) $this->reports_model->run_sql_query($SQL)[0]['num'];
		
		if($COUNT < 1)
		{
			//delegate has not registered 
			$this->form_validation->set_message('attendance_check',"{$C['first_name']} {$C['last_name']} {$C['other_names']} is NOT CLOCKED IN");
			return false;
		}
		
		return true;
		
	}
	
	public function session_check()
	{
		$fd = $this->input->post();

		#get delegate
		$delegate = $this->core_model->get_object('core_registrations',$fd['delegate_id']);
		
		#get activity
		$barcode_activity = $this->core_model->get_object('barcode_activities',$fd['activity_id']);
		
		#get session
		$barcode_session = $this->core_model->get_object('barcode_sessions',null,array('code'=>$fd['session_code']));

		#check if session exits
		$barcode_session_num = $this->core_model->count_objects('barcode_sessions',array('code'=>$fd['session_code'],'id_barcode_activities'=>$fd['activity_id']));
		if($barcode_session_num < 1)
		{
			#error
			$this->form_validation->set_message('session_check',"<b>Session</b> does not exist or does not belong to this activity");
			return false;
		}
		
		#check if slots are still available
		$booked_slots = $this->core_model->count_objects('barcode_session_bookings',array('id_barcode_sessions'=>$barcode_session['id']));
		$remaining_slots = $barcode_session['maximum_number'] - $booked_slots;
		
		if($remaining_slots < 1)
		{
			#error
			$this->form_validation->set_message('session_check',"<b>{$barcode_session['session']} session</b> is fully booked");
			return false;
		}
		
		#check if delegate is from uganda
		if($delegate['nationality']=='UGANDA')
		{
			#get uganda slots
			$uganda_slots =(int) $barcode_session['uganda_slots']; 
			
			#Count ugandans booked for this activity
			$SQL = "select count(*) num 
					from core_registrations c JOIN barcode_session_bookings bsb ON c.id = bsb.id_core_registrations
					where c.nationality = 'UGANDA' and bsb.id_barcode_sessions={$barcode_session['id']}";
			$ug_count  = $this->reports_model->run_sql_query($SQL)[0]['num'];
			$ug_remaining_slots= $uganda_slots - $ug_count; 
			
			#check if slots are available for ugandans
			if($ug_remaining_slots < 1)
			{
				$this->form_validation->set_message('session_check',"<b>{$barcode_session['session']} session</b> is fully booked for UGANDA");
				return false;
			}
		}
		
		
		return true;
	}
	
	public function barcode_session_book($activity_id,$delegate_id)
	{
		$fd = $this->input->post();
		
		#get activity
		$barcode_activity = $this->core_model->get_object('barcode_activities',$activity_id);
		
		//get delegate 
		$delegate = $this->core_model->get_object('core_registrations',$delegate_id);
		
		
		if($fd)
		{
			$config=array(
					array(
						'field'=>'session_code',
						'label'=>'Session',
						'rules'=>'required|callback_session_check'
					)
				);
				
			$this->form_validation->set_rules($config);
			
			if($this->form_validation->run() === true)
			{
				//check if delegate already booked for this activity
				$SQL ="select * from barcode_session_bookings where id_core_registrations = {$delegate_id} and id_barcode_activities ={$activity_id}";
				
				$booking = $this->reports_model->run_sql_query($SQL);
				

				if($booking)
				{
					
					#delete previous booking
					$Q = "delete from barcode_session_bookings where id_core_registrations = {$delegate_id} and id_barcode_activities ={$activity_id}";
					$this->reports_model->run_sql_query($Q,'SQL');
					#$this->core_model->delete_object('barcode_session_bookings',$booking[0]['id']);
				}
				
				#get session
				$barcode_session = $this->core_model->get_object('barcode_sessions',null,array('code'=>$fd['session_code']));
				
				$this->session->set_flashdata('SUCCESS',$this->message_templates['SAVED_SUCCESSFULLY'].' '.$delegate['first_name'].' '.$delegate['last_name'].' for '.$barcode_session['session']);
				
				$input_array = array(
								'id_core_registrations'=>$delegate_id,
								'id_barcode_activities'=>$activity_id,
								'id_barcode_sessions'=>$barcode_session['id']
					);
				#create object
				$this->core_model->create_object('barcode_session_bookings',$input_array);
				
				redirect('administration/barcode_activities_book/'.$activity_id, 'location');
			}
			else
			{
				$this->session->set_flashdata('ERROR',validation_errors());
			}
		}
		
		#get_sessions
		$barcode_sessions = $this->core_model->get_many_objects('barcode_sessions',array('id_barcode_activities'=>$activity_id));
		
		$sessions='';
		foreach ($barcode_sessions as $R)
		{
			#booked slota
			$booked_slots = $this->core_model->count_objects('barcode_session_bookings',array('id_barcode_sessions'=>$R['id']));
			
			$remaining_slots = $R['maximum_number'] - $booked_slots;
			
			$class_color ='default';
			
			#ug slots
			#Count ugandans booked for this activity
			$SQL = "select count(*) num 
					from core_registrations c JOIN barcode_session_bookings bsb ON c.id = bsb.id_core_registrations
					where c.nationality = 'UGANDA' and bsb.id_barcode_sessions={$R['id']}";
			$ug_count  = $this->reports_model->run_sql_query($SQL)[0]['num'];
			$ug_remaining =$R['uganda_slots']-$ug_count;
			
			if($remaining_slots < 10)
			{
				$class_color ='danger';
			}
			
			$sessions .="<tr class='{$class_color}'>
							<td>{$R['session']}</td>
							<td>{$R['description']}</td>
							<td>{$booked_slots}</td>
							<td>{$R['maximum_number']}</td>
							<td>{$remaining_slots}</td>
							<td>&nbsp;</td>
							<td>{$R['uganda_slots']}</td>
							<td>{$ug_count}</td>
							<td>{$ug_remaining}</td>
						</tr>";
		}

		
		$form  = '<div class="form-horizontal">
			<table class="table table-condensed table-striped table-bordered">
				<tr>
					<td>
						'.form_open("/administration/barcode_session_book/{$activity_id}/{$delegate_id}").'
						'.form_input(array('type'=>'hidden','name'=>'delegate_id','value'=>$delegate_id)).' 
						'.form_input(array('type'=>'hidden','name'=>'activity_id','value'=>$activity_id)).' 
						<div class="input-group input-group-sm"><span class="input-group-addon">&nbsp;<span class="glyphicon glyphicon-barcode" aria-hidden="true"></span>  Scan session barcode</span>
						<input type="text" maxlength="12" autofocus="autofocus" name="session_code" class="form-control" aria-label="search" placeholder=" Scan session barcode" value="" />
						<span class="input-group-btn"><a class="btn btn-danger" href="'.site_url("administration/barcode_activities_book/{$activity_id}/{$delegate_id}").'">Cancel</a> <input type="submit" value="Go" class="btn btn-primary"/></span></div>
						'.form_close().'
					</td>
				</tr>
			</table>
		</div>
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-condensed">
			<tr style="white-space: nowrap;">
				<th>Session</th>
				<th>description</th>
				<th>Booked</th>
				<th>Maximum number</th>
				<th>Remaining</th>
				<td>&nbsp;</td>
				<th>Ug slots</th>
				<th>Ug booked</th>
				<th>Ug remaining</th>
			</tr>
			'.$sessions.'
			</table>
		</div>';
		
		$data['user'] = $this->user;
		$data['form']=$form;
		$data['title'] = "Scan session barcode to book <span class='text-primary'>{$delegate['first_name']} {$delegate['last_name']} {$delegate['other_names']}</span> for a session";
		$data['modules'] = $this->custom_auth->get_user_navigation();
		$data['MODULE'] = 'BARCODE';
		$data['breadcrumb'] = array(
				array('label'=>"Barcode activities", 'url'=>site_url("administration/barcode_activities_list")),
				array('label'=>"Book delegate for ".$barcode_activity['activity'], 'url'=>site_url("administration/barcode_activities_book/{$activity_id}")),
				array('label'=>"Barcode activity session", 'url'=>"#")
				);
		
		
		$this->template->title($data['title']);
		$this->template->set_partial('nav', 'layouts/fixed_navbar-part');
		$this->template->set_partial('messages', 'layouts/messages-part');
		$this->template->set_layout('logged_in_template');
		$this->template->build('layouts/wide_form_body', $data);
	}
	
	/*
	 * Generate session barcode
	 * 
	 * index.php administration/generate_session_barcodes
	 * 
	 * 1. select sessions
	 * 2. generate barcodes
	 * 3. update barcode_sessions
	 * 4. generate pdf 
	 * 
	 * */
	 
	public function generate_session_barcodes($id)
	{
		include_once APPPATH.'third_party/mpdf60/mpdf.php';
		$pdf_session_barcodes_path = './media/PDF/session_barcodes/';
		$pdf_folder_path = './media/PDF/';
		$png_save_barcode_path ='./media/PDF/barcodes/';
		
		
		$SQL = "SELECT * FROM barcode_sessions WHERE id_barcode_activities={$id}";
		
		$barcode_sessions = $this->reports_model->run_sql_query($SQL);
		
		//barcode parameters
		$size=140;
		$orientation="horizontal";
		$code_type="code128";
		$print=true;
		$sizefactor = "1";
		
		
		$pdf  = new mPDF();
		$pdf->AddPage();
		
		foreach($barcode_sessions as &$B)
		{
			 
			$random_string = $this->core_model->generate_random_string(7-strlen($B['id']),'ABCDEFGHJKLMNOPQRSTUVWXY');
			
			#$text = substr($B['session'], 0,5).str_pad($B['id'],7,'0',STR_PAD_LEFT);
			$text = substr(strtoupper($B['session']), 0,5).$random_string.$B['id'];
			$barcode_png_filepath = $pdf_session_barcodes_path.$text.".png";
			
			#create barcode
			$this->barcode( $barcode_png_filepath, $text, $size, $orientation, $code_type, $print, $sizefactor );
			
			#update barcode_sessions
			$this->core_model->update_object('barcode_sessions',$B['id'],array('code'=>$text));
			
			$html ="<div class='panel panel-default'>
						<div class='panel-heading'><h4>{$B['session']}</h4></div>
						<div class='panel-body'>
							<img src='{$barcode_png_filepath}' />
						</div>
					</div>
					<div style='padding-top:20px'>&nbsp;</div>
					";
			$pdf->writeHTML($html);
			 
		}
		
		$pdf->SetCompression(true);
		
		$pdf->Output();
	}
	
	public function make_receipt($registration_code= 'ESAAG18R1136')
	{
		include_once APPPATH.'/third_party/mpdf60/mpdf.php';
		$logo_path = './media/static/ESAAG_logo.png';
		#$pdf_folder_path = './media/PDF/';
		#$png_save_barcode_path ='./media/PDF/barcodes/';
		$pdf = new mPDF();
		
		$SQL = "select * from core_registrations where registration_code='{$registration_code}'";
		$C = $this->reports_model->run_sql_query($SQL)[0];
		
		$date =date('Y-m-d');
		$html = "
			<div style=\"font-family:Calibri,Candara,Segoe,Segoe UI,Optima,Arial,sans-serif;\">
				
				<div id=\"container\" style=\"padding-top:2mm;\">
				
					<div style=\"text-align:center;width:200px;float:left;\">
						<div><img src=\"{$logo_path}\" height='200' width='200'/></div>
					</div>

					<div style=\"text-align:center;border:1px solid;width:200px;float:right;\">
						<div>RECEIPT</div>
					</div>	
				</div>
				
				<div style=\"padding-top:30px;\"></div>
				
				<table width='100%'  border=\"1\" cellpadding=\"10\" cellspacing=\"0\">
					<tr><th colspan='3' >Attention</th></tr>
					<tr>
						<td rowspan='3'>{$C["first_name"]} {$C["other_names"]} {$C["last_name"]} - {$C["nationality"]}</td>
						<td>Receipt No.</td>
						<td>{$C["registration_code"]}</td>
					</tr>
					<tr><td>Date</td><td>{$date}</td></tr>
					<tr><td>Email</td><td>{$C["email"]}</td></tr>
				</table>
				
				<div style=\"padding-top:30px;\"></div>
				
				<table width='100%'  border=\"1\" cellpadding=\"10\" cellspacing=\"0\">
					<tr><td>Description</td><td>Unit Price</td><td>Total</td></tr>
					<tr>
						<td>
								ESAAG Annual Conference reciept <br><br><br>
								{$C["first_name"]} {$C["other_names"]} {$C["last_name"]} <br><br><br>
								
								<b>Venue:</b> Imperial Resort Beach Hotel<br>
								Conference centre, Entebbe, Uganda <br>
								<b>Date</b> 26 February - 02 March 2018
						</td>
						<td>R{$C["amount"]}</td>
						<td>R{$C["amount"]}</td>
					</tr>
					<tr>
						<td colspan='2'><b>Total</b></td>
						<td><b>R{$C["amount"]}</b></td>
					</tr>
				</table>
				
				<div style=\"text-align:center;padding-top:30px;\">
					******************** Thanks ********************
				</div>
			</div>";
		
		// set PDF Template
		#$pdf->SetImportUse();
		#$pdf->SetDocTemplate($pdf_folder_path.'esaag_name_tag.pdf', true);
		// add a page to PDF
		$pdf->AddPage();
		// write html to the PDF
		$pdf->writeHTML($html);
		
		$pdf->SetCompression(true);
		
		$pdf->Output();
	}
	
	
	
	public function make_name_tags($registration_code=NULL)
	{
		include_once APPPATH.'/third_party/mpdf60/mpdf.php';
		$pdf_nametags_path = './media/PDF/nametags/';
		$pdf_folder_path = './media/PDF/';
		$png_save_barcode_path ='./media/PDF/barcodes/';
		
		
		if($registration_code)
		{
			$SQL = "select upper(first_name) first_name,upper(other_names) other_names ,upper (last_name) last_name,registration_code,upper(nationality) nationality,
				delegate_type from core_registrations where registration_code='{$registration_code}'";
		}
		else
		{
			$SQL = "select upper(first_name) first_name,upper(other_names) other_names ,upper (last_name) last_name,registration_code,upper(nationality) nationality, 
					delegate_type from core_registrations
					order by nationality,first_name,other_names,last_name";
		}
		
		$core_registrations = $this->reports_model->run_sql_query($SQL);
		
		
		$pdf  = new mPDF('c','A4-L');
		
		for($i=0; $i<count($core_registrations); $i+=4)
		{
			/***********************************************************
			*					Generate Barcodes
			************************************************************
			*/
			
			#barcode parameters
			$barcode_png_filepath=$png_save_barcode_path.$core_registrations[$i]["registration_code"].'.png';
			$text=$core_registrations[$i]["registration_code"];
			
			$barcode_png_filepath1=$png_save_barcode_path.$core_registrations[$i+1]["registration_code"].'.png';
			$text1=$core_registrations[$i+1]["registration_code"];
			
			$barcode_png_filepath2=$png_save_barcode_path.$core_registrations[$i+2]["registration_code"].'.png';
			$text2=$core_registrations[$i+2]["registration_code"];
			
			$barcode_png_filepath3=$png_save_barcode_path.$core_registrations[$i+3]["registration_code"].'.png';
			$text3=$core_registrations[$i+3]["registration_code"];
			
			
			
			$size=40;
			$orientation="Horizontal";
			$code_type="code128";
			$print=true;
			$sizefactor = "1";
			
			#create barcode
			$this->barcode( $barcode_png_filepath, $text, $size, $orientation, $code_type, $print, $sizefactor );
			$this->barcode( $barcode_png_filepath1, $text1, $size, $orientation, $code_type, $print, $sizefactor );
			$this->barcode( $barcode_png_filepath2, $text2, $size, $orientation, $code_type, $print, $sizefactor );
			$this->barcode( $barcode_png_filepath3, $text3, $size, $orientation, $code_type, $print, $sizefactor );
			
			#margin to for bottom top
			$margintop = 57;
			$fontsize = 25;
			$fontsize1 = 25;
			$fontsize2 = 25;
			$fontsize3 = 25;
			
			
			#string lenght 1 and 2
			
			$namelen =strlen("{$core_registrations[$i]["first_name"]} {$core_registrations[$i]["other_names"]} {$core_registrations[$i]["last_name"]}");
			$name1len =strlen("{$core_registrations[$i+1]["first_name"]} {$core_registrations[$i+1]["other_names"]} {$core_registrations[$i+1]["last_name"]}");
			$name2len =strlen("{$core_registrations[$i+2]["first_name"]} {$core_registrations[$i+2]["other_names"]} {$core_registrations[$i+2]["last_name"]}");
			$name3len =strlen("{$core_registrations[$i+3]["first_name"]} {$core_registrations[$i+3]["other_names"]} {$core_registrations[$i+3]["last_name"]}");
			
			if($namelen > 25)
			{
				$fontsize = 18;
			}

			if($name1len > 25)
			{
				$fontsize1 = 18;
			}

			if($name2len > 25)
			{
				$fontsize2 = 18;
			}

			if($name3len > 25)
			{
				$fontsize3 = 18;
			}
			
			
			
			$category =array(
							'ACCOUNTANT GENERAL'=>array('text-color'=>'WHITE','background'=>'BLACK','border'=>NULL/*'border:1px solid;'*/),
							'GUEST'=>array('text-color'=>'WHITE','background'=>'BLACK','border'=>NULL/*'border:1px solid;'*/),
							'DELEGATE'=>array('text-color'=>'WHITE','background'=>'BLUE','border'=>NULL),
							'SECRETARIAT'=>array('text-color'=>'WHITE','background'=>'BLUE','border'=>NULL),
							'OFFICIAL'=>array('text-color'=>'WHITE','background'=>'BLACK','border'=>NULL),
							'SPEAKER'=>array('text-color'=>'WHITE','background'=>'GREEN','border'=>NULL),
							'SERVICE PROVIDER'=>array('text-color'=>'WHITE','background'=>'RED','border'=>NULL)
						);
			
			
			$html = "
			<div style=\"font-size:20px;font-weight:bold;font-family:Calibri,Candara,Segoe,Segoe UI,Optima,Arial,sans-serif;\">
				
				<div id=\"container\" style=\"padding-top:32mm;\">
					<div style=\"text-align:center;width:485px;float:left;\">
						<div><img src=\"{$barcode_png_filepath}\"/></div>
						<div style=\"margin-top:-13px;color:RED;font-size:{$fontsize}px;\">
							{$core_registrations[$i]["first_name"]}
							{$core_registrations[$i]["other_names"]}
							{$core_registrations[$i]["last_name"]}
						</div>
						<div style=\"padding-bottom:8px;font-style:italic;\">{$core_registrations[$i]["nationality"]}&nbsp;</div>
						<div style=\"width:70%;margin:auto;
							{$category[$core_registrations[$i]['delegate_type']]['border']}
							background-color:{$category[$core_registrations[$i]['delegate_type']]['background']};
							color:{$category[$core_registrations[$i]['delegate_type']]['text-color']};\">
							{$core_registrations[$i]['delegate_type']}
						</div>
					</div>

					<div style=\"text-align:center;width:485px;float:right;\">
						<div><img src=\"{$barcode_png_filepath1}\"/></div>
						<div style=\"margin-top:-13px;color:RED;font-size:{$fontsize1}px;\">
							{$core_registrations[$i+1]["first_name"]}
							{$core_registrations[$i+1]["other_names"]}
							{$core_registrations[$i+1]["last_name"]}
						</div>
						<div style=\"padding-bottom:8px;font-style:italic;\">{$core_registrations[$i+1]["nationality"]}&nbsp;</div>
						<div style=\"width:70%;margin:auto;
							{$category[$core_registrations[$i+1]['delegate_type']]['border']}
							background-color:{$category[$core_registrations[$i+1]['delegate_type']]['background']};
							color:{$category[$core_registrations[$i+1]['delegate_type']]['text-color']};\">
							{$core_registrations[$i+1]['delegate_type']}
						</div>
					</div>	
				</div>
				
				<div id=\"container1\" style=\"margin-top:{$margintop}mm;\">
					<div style=\"text-align:center;width:485px;float:left;\">
						<div><img src=\"{$barcode_png_filepath2}\"/></div>
						<div style=\"margin-top:-13px;color:RED;font-size:{$fontsize2}px;\">
							{$core_registrations[$i+2]["first_name"]}
							{$core_registrations[$i+2]["other_names"]}
							{$core_registrations[$i+2]["last_name"]}
						</div>
						<div style=\"padding-bottom:8px;font-style:italic;\">{$core_registrations[$i+2]["nationality"]}&nbsp;</div>
						<div style=\"width:70%;margin:auto;
							{$category[$core_registrations[$i+2]['delegate_type']]['border']}
							background-color:{$category[$core_registrations[$i+2]['delegate_type']]['background']};
							color:{$category[$core_registrations[$i+2]['delegate_type']]['text-color']};\">
							{$core_registrations[$i+2]['delegate_type']}
						</div>
					</div>
		
					<div style=\"text-align:center;width:485px;float:right;\">
						<div><img src=\"{$barcode_png_filepath3}\"/></div>
						<div style=\"margin-top:-13px;color:RED;font-size:{$fontsize3}px;\">
							{$core_registrations[$i+3]["first_name"]}
							{$core_registrations[$i+3]["other_names"]}
							{$core_registrations[$i+3]["last_name"]}
						</div>
						<div style=\"padding-bottom:8px;font-style:italic;\">{$core_registrations[$i+3]["nationality"]}&nbsp;</div>
						<div style=\"width:70%;margin:auto;
							{$category[$core_registrations[$i+3]['delegate_type']]['border']}
							background-color:{$category[$core_registrations[$i+3]['delegate_type']]['background']};
							color:{$category[$core_registrations[$i+3]['delegate_type']]['text-color']};\">
							{$core_registrations[$i+3]['delegate_type']}
						</div>
					</div>
				</div>
			</div>
			";
			// set PDF Template
			$pdf->SetImportUse();
			$pdf->SetDocTemplate($pdf_folder_path.'esaag_name_tag.pdf', true);
			// add a page to PDF
			$pdf->AddPage();
			// write html to the PDF
			$pdf->writeHTML($html);
		}
		
		
		$pdf->SetCompression(true);
		
		$pdf->Output();
	}
	
	public function duplicates()
	{
		$core_registrations  = $this->core_model->get_many_objects('core_registrations');
		$duplicates = array();
		$td = '';
		$skip_ids ='';
		
		foreach($core_registrations as $C)
		{
			$compare_fields = array(
					'first_name' =>$C['first_name'],
					'last_name' =>$C['last_name'],
					'other_names' =>$C['other_names'],
			);

			$duplicates = $this->core_model->find_duplicates_in_table('core_registrations', $compare_fields, 100,$C['id'].$skip_ids);
			
			if(!empty($duplicates))
			{
				$td .= "<tr><td>{$C['registration_code']}</td><td>{$C['first_name']}</td><td>{$C['last_name']}</td><td>{$C['other_names']}</td><td>&nbsp;</td></tr>"; 
				foreach($duplicates as $D)
				{
					$td .="<tr><td>{$D['registration_code']}</td><td>{$D['first_name']}</td><td>{$D['last_name']}</td><td>{$D['other_names']}</td><td>{$D['similarity']}</td></tr>";
				}
				
				$td .="<tr><td colspan=5>&nbsp;</td></tr>";
				
				$skip_ids .=",".$D['id'].",".$C['id'];
			}
			unset($duplicates);
		}
		
		$data['user'] = $this->user;
		$data['form'] = "<table class='table table-bordered table-striped table-condensed'>
						<tr><th>Reg ID</th><th>Name</th><th>Surname</th><th></th><th>Percentage</th></tr>
						{$td}</table>";
		$data['title'] = "Event";
		$data['modules'] = $this->custom_auth->get_user_navigation();
		$data['MODULE'] = 'HOME';
		$data['breadcrumb'] = array(
				array('label'=>"Registrations", 'url'=>site_url("/administration")),
				array('label'=>"Possible Duplicates", 'url'=>"#")
				);
		
		
		$this->template->title('Possible Duplicates');
		$this->template->set_partial('nav', 'layouts/fixed_navbar-part');
		$this->template->set_partial('messages', 'layouts/messages-part');
		$this->template->set_layout('logged_in_template');
		$this->template->build('layouts/form_body', $data);
	}
	
	/*
	 * migrate_essag_registrations
	 * 
	 * Migrate essag registration data from essag_reg to core_registrations
	 * 
	 * */
	public function migrate_essag_registrations()
	{
		$essag_reg = $this->core_model->get_many_objects('essag_reg');
		
		foreach($essag_reg as &$E)
		{
			/*
			 * `id` int(10) UNSIGNED NOT NULL,
			 * `payment_status` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
			 * `shirt_size` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
			 * `hotel` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
			 * `leisure_activity` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
			 * `registration_code` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
			 * `first_name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
			 * `last_name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
			 * `other_names` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
			 * `nationality` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
			 * `dob` date DEFAULT NULL,
			 * `gender` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
			 * `national_id` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
			 * `delegate_type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
			 * `email` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
			 * `telephone` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
			 * `emp_organisation` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
			 * */
			 $data = $this->core_model->null_blank_entries($E);
			 
			 $random_string = $this->core_model->generate_random_string(5-strlen($E['id']),'ABCDEFGHJKLMNOPQRSTUVWXY');
			 
			 $data['registration_code'] = 'ESAAG18'.$random_string.$E['id'];
			 
			 if($this->core_model->create_object('core_registrations',$data))
			 {
				$this->core_model->create_object('event_id_series', array('ref' => date('Y-m-d H:i:s')));
				echo "{$E['id']} . Success \n";
			 }
			 else
			 {
				echo "Error \n";
			 }
		}
	}
	
	public function reg_check()
	{
		$fd = $this->input->post();
		
		#get delegate details
		$core_registrations = $this->core_model->get_many_objects("core_registrations",array("registration_code"=>$fd['registration_code']));
		
		
		if(empty($core_registrations))
		{
			$this->form_validation->set_message('reg_check',"<b>{$fd['registration_code']}</b> does not exist");
			return false;
			
		}
		
		#/*
		#check if member has paid
		
		$payment_made = $this->reports_model->run_sql_query("SELECT payment_status from core_registrations r WHERE r.id = {$core_registrations[0]['id']}");

		if(empty($payment_made[0]["payment_status"]) or $payment_made[0]["payment_status"] =='')
		{
			#$this->form_validation->set_message('reg_check',"<b>{$fd['registration_code']} : {$core_registrations[0]['first_name']} {$core_registrations[0]['last_name']} {$core_registrations[0]['other_names']}</b> has NOT PAID for this seminar");
			#return false;
		}
		
		
		#check if delegate is already registered for this session
		if($this->core_model->count_objects("core_session_attendees",array("id_core_sessions" => $fd['session_id'] ,"id_core_registrations" => $core_registrations[0]['id'])) > 0)
		{
			$this->form_validation->set_message('reg_check',"<b>{$fd['registration_code']} : {$core_registrations[0]['first_name']} {$core_registrations[0]['last_name']} {$core_registrations[0]['other_names']}</b> is already registered for this session");
			return false;
		}
	
		
		return true;
	}
	
	
	public function session_attendance($id)
	{
		$fd= $this->input->post();
		
		
		if(isset($fd['registration_code']))
		{
			$config= array(
						array(
								'field' => 'registration_code',
								'label' => 'Barcode',
								'rules' => 'required|callback_reg_check'
						)
					);
			
			$this->form_validation->set_rules($config);
			
			
			if($this->form_validation->run() == true)
			{
				$id_core_registrations = $this->core_model->get_many_objects("core_registrations",array("registration_code"=>$fd['registration_code']));
				$id_core_registrations = $id_core_registrations[0];
				
				$insert_id = $this->core_model->create_object("core_session_attendees",array("id_core_registrations"=>$id_core_registrations['id'],"id_core_sessions"=>$id));
				
				$this->session->set_flashdata('SUCCESS', "<b>{$fd['registration_code']} : {$id_core_registrations['first_name']} {$id_core_registrations['last_name']} {$id_core_registrations['other_names']} </b> 
				sucessfully registered. <b> SHIRT SIZE :{$id_core_registrations['shirt_size']}</b>");
				
				redirect("/administration/session_attendance/{$id}","location");
				
			}
			
		}
		
		$core_sessions  = $this->core_model->get_object('core_sessions',$id);
		
		$data['data']=$this->reports_model->run_sql_query("SELECT cr.*,csa.date 
				FROM core_registrations cr JOIN core_session_attendees csa ON cr.id = csa.id_core_registrations JOIN core_sessions cs ON cs.id=csa.id_core_sessions 
				WHERE cs.id={$id} 
				ORDER BY csa.id DESC");
		$data['id']=$id;
		$data['user'] = $this->user;
		$data['title'] = "Event";
		$data['modules'] = $this->custom_auth->get_user_navigation();
		$data['MODULE'] = 'ATTENDANCE';
		$data['breadcrumb'] = array(
				array('label'=>"Attendance", 'url'=>site_url("/administration/attendance/")),
				array('label'=>"{$core_sessions['session']} registrations", 'url'=>"#")
				);
		
		# add css & js for date picker
		$this->template->inject_partial('custom_css_top', '<link href="'.base_url('/media/static/bootstrap/css/bootstrap-datetimepicker.min.css').'" rel="stylesheet">');
		$this->template->inject_partial('custom_js_bottom', '<script src="'.base_url('/media/static/moment.min.js').'"></script>'.'<script src="'.base_url('/media/static/bootstrap/js/bootstrap-datetimepicker.min.js').'"></script>'.
				"<script type=\"text/javascript\">$(function () { $('#date_from').datetimepicker({format:'YYYY-MM-DD'}); $('#date_to').datetimepicker({format:'YYYY-MM-DD'}); });".
				'function getRegDetails(reg_id)
			{
				$.ajax({
					url: "'.site_url('administration/registration_details').'" + "/" + reg_id,
					cache: false,
					success: function(output){
						$("#id_reg_details").html(output);
					}
				});
			}</script>');
		
		$this->template->title('Attendance');
		$this->template->set_partial('nav', 'layouts/fixed_navbar-part');
		$this->template->set_partial('messages', 'layouts/messages-part');
		$this->template->set_layout('logged_in_template');
		$this->template->build('administration/session_attendance', $data);
	}
	
	public function attendance()
	{
		
		$SQL ="select * from core_sessions";

		$data['data']=$this->reports_model->run_sql_query($SQL);
		$data['user'] = $this->user;
		$data['title'] = "Events";
		$data['modules'] = $this->custom_auth->get_user_navigation();
		$data['MODULE'] = 'ATTENDANCE';
		$data['breadcrumb'] = array(array('label'=>'Attendance','url'=>'#'));
		
		# add css & js for date picker
		$this->template->inject_partial('custom_css_top', '<link href="'.base_url('/media/static/bootstrap/css/bootstrap-datetimepicker.min.css').'" rel="stylesheet">');
		$this->template->inject_partial('custom_js_bottom', '<script src="'.base_url('/media/static/moment.min.js').'"></script>'.'<script src="'.base_url('/media/static/bootstrap/js/bootstrap-datetimepicker.min.js').'"></script>'.
				"<script type=\"text/javascript\">$(function () { $('#date_from').datetimepicker({format:'YYYY-MM-DD'}); $('#date_to').datetimepicker({format:'YYYY-MM-DD'}); });".
				'function getRegDetails(reg_id)
			{
				$.ajax({
					url: "'.site_url('administration/registration_details').'" + "/" + reg_id,
					cache: false,
					success: function(output){
						$("#id_reg_details").html(output);
					}
				});
			}</script>');
		
		$this->template->title('Attendance');
		$this->template->set_partial('nav', 'layouts/fixed_navbar-part');
		$this->template->set_partial('messages', 'layouts/messages-part');
		$this->template->set_layout('logged_in_template');
		$this->template->build('administration/attendance', $data);
	}
	
	
	public function modify_session($session_id=null)
	{
		$fd = $this->input->post();
		
		if($fd)
		{
			$config = array(
					array(
						'field'=>'session',
						'label'=>'Session name',
						'rules'=>'required'
						),
					array(
						'field'=>'date',
						'label'=>'Date',
						'rules'=>'required'
						)
					);
					
			$this->form_validation->set_rules($config);
			
			if($this->form_validation->run()=== true)
			{
				unset($fd['submit']);
				
				//editing
				if($session_id)
				{
					$this->core_model->update_object('core_sessions',$session_id,$fd);
				}
				//adding
				else
				{
					$this->core_model->create_object('core_sessions',$fd);
				}
				
				$this->session->set_flashdata('SUCCESS',$this->message_templates['SAVED_SUCCESSFULLY']);
				redirect('administration/attendance','location');
			}
			else
			{
				$this->session->set_flashdata('ERROR',validation_errors());
			}
			
		}
		
		//editing
		if($session_id)
		{
			$this->autoform->sql('SELECT session, date FROM core_sessions WHERE id ='.$session_id);
			$data['title'] = "Edit session";
		}
		//adding
		else
		{
			$this->autoform->add(array('name'=>'session','type'=>'text'));
			$this->autoform->add(array('name'=>'date','placeholder'=>'YYYY-MM-DD'));
			$data['title'] = "Create new session";
		}
		
		$this->autoform->set(array('session','date'),array('required'=>'required'));
		$this->autoform->wrap_each_field_only('<div class="input-group date" id="session_date">','<div class="input-group-addon"><span class="glyphicon glyphicon-calendar" aria-hidden="true"></span></div></div>', array('date'));
		$this->autoform->set_all(array('class'=>'form-control', 'label'=>array('class'=>'control-label  col-sm-4')));
		$this->autoform->wrap_each_field_only('<div class="col-sm-8">','</div>');
		$this->autoform->wrap_each('<div class="form-group form-group-sm">','</div>');
		$this->autoform->buttons('<div class="button-row"> <a href="'.site_url('administration/attendance').'" class="btn btn-default"> Cancel</a> '.form_submit(array('type'=>'submit', 'name'=>'submit', 'value'=>'Submit', 'class'=>'btn btn-primary')).'</div>');

		# add css & js for date picker
		$this->template->inject_partial('custom_js_bottom', '<script src="'.base_url('/media/static/moment.min.js').'"></script>'.'<script src="'.base_url('/media/static/bootstrap/js/bootstrap-datetimepicker.min.js').'"></script>'.
				"<script type=\"text/javascript\">$(function () { $('#session_date').datetimepicker({format:'YYYY-MM-DD'});});</script>");

		$this->template->inject_partial('custom_css_top', '<link href="'.base_url('/media/static/bootstrap/css/bootstrap-datetimepicker.min.css').'" rel="stylesheet">');

		$data['form']=$this->autoform->generate('administration/modify_session/'.$session_id, array('class'=>'form-horizontal'), true);
		$data['user'] = $this->user;
		$data['modules'] = $this->custom_auth->get_user_navigation();
		$data['MODULE'] = 'ATTENDANCE';
		$data['breadcrumb'] = array(array('label'=>'Attendance','url'=>site_url('administration/attendance')),array('label'=>'Create new Session','url'=>'#'));
		
		$this->template->title($data['title']);
		$this->template->set_partial('nav', 'layouts/fixed_navbar-part');
		$this->template->set_partial('messages', 'layouts/messages-part');
		$this->template->set_layout('logged_in_template');
		$this->template->build('layouts/form_body', $data);
	}
	
	public function delete_session($session_id)
	{
		$this->core_model->delete_object('core_sessions',$session_id);
		
		$this->session->set_flashdata('WARNING',$this->message_templates['DELETED_SUCCESSFULLY']);
		
		redirect('administration/attendance','location');
	}
	
	public function register_special($id = null)
	{
		$fd = $this->input->post();
		$hotel_options=$this->config->item('event_item_labels');
		
		if($fd)
		{
			unset($fd['check_new_user']);
				
			#create validation rules for form data
			$form_validation_rules = array
			(
					array
					(
							'field'   => 'delegate_type',
							'rules'   => 'required'
					),
					array
					(
							'field'   => 'first_name',
							'rules'   => 'required'
					),
					array
					(
							'field'   => 'last_name',
							'rules'   => 'required'
					),
					array
					(
							'field'   => 'nationality',
							'rules'   => 'required'
					),
					array
					(
							'field'   => 'gender',
							'rules'   => 'required'
					),
					/*
					array
					(
							'field'   => 'email',
							'rules'   => 'required|valid_email|is_unique[core_registrations.email]',
							'errors' => array(
									'is_unique' => 'This email address has already been registered.',
							),
					),*/

			);
				
			$this->form_validation->set_rules($form_validation_rules);
				
				
			if($this->form_validation->run() == true)
			{
				//editing
				if($id)
				{
					$this->core_model->update_object('core_registrations',$id,$this->core_model->null_blank_entries($fd));
				}
				//adding
				else
				{
					$entry_id = $this->core_model->create_object('event_id_series', array('ref' => date('Y-m-d H:i:s')));
					
					$random_string = $this->core_model->generate_random_string(5-strlen($entry_id),'ABCDEFGHJKLMNOPQRSTUVWXY');
				
					$fd['registration_code'] = 'ESAAG18'.$random_string.$entry_id;;
				
					#insert into core_registrations
					$insert_id = $this->core_model->create_object("core_registrations",$this->core_model->null_blank_entries($fd));
				}
				$this->session->set_flashdata('SUCCESS', $this->message_templates['SAVED_SUCCESSFULLY']);
				
				# redirect to details page
				redirect('/administration/', 'location');
	
			}
			else
			{
				$this->session->set_flashdata('ERROR','Some form errors exist');
			}
		}
	
			//editing
			if($id)
			{
				$this->autoform->sql('select * from core_registrations where id='.$id);
			}
			//adding
			else
			{
				$this->autoform->table('core_registrations');
			}
			
			
			#remove unwanted fields
			$this->autoform->remove(array('sync','remote_id','city','box','plot_no','street','title','invitation_email_sent',
					'reg_email_sent','invoice_code','updated_at','created_at','update_registration_code','parent_registration_code','registration_code',
					'nok_telephone','nok_email','nok_country','nok_name','hotel_room_type','tour_route',
					'travel_departure_date','travel_arrival_date','travel_from_country','flight_no','passport_issue_place',
					'passport_expiry_date','passport_issue_date','passport_no','insurance_body','insurance','acc_body',
					'acc_body_member','emp_industry','emp_country','emp_job_title','emp_email','emp_telephone',
					'rate_category','hotel','national_id','leisure_activity'));
			
			$this->autoform->buttons('<div class="button-row"><a href="'.site_url('administration').'" class="btn btn-default">Cancel</a> '.form_submit(array('type'=>'submit', 'name'=>'check_new_user', 'value'=>'Submit', 'class'=>'btn btn-primary')).'</div>');
	
			$this->autoform->set(array('first_name','last_name','gender','delegate_type'), array('required'=>'required'));
			$this->autoform->set('gender', array('type'=>'select', 'options'=>array(''=>'Select', 'MALE'=>'MALE','FEMALE'=>'FEMALE')));
			
			$shirt_size = $this->reports_model->run_sql_query("SELECT DISTINCT shirt_size  s FROM core_registrations where shirt_size IS NOT NULL ORDER BY s");
			
			$shirt_size_options = array(''=>'Select');
			foreach($shirt_size as &$S)
			{
				$shirt_size_options[$S['s']]=$S['s'] ;
			}
			
			$countries = $this->core_model->get_many_objects('core_countries');
			
			
			$country_options=array(''=>'Select','UGANDA'=>'UGANDA');
			foreach($countries as &$C)
			{
				$country_name = strtoupper($C['name']);
				$country_options[$country_name]=$country_name; 
			}
			
			$this->autoform->set('nationality',array('type'=>'select','options'=>$country_options));
			$this->autoform->set('shirt_size',array('type'=>'select','options'=>$shirt_size_options));
			$this->autoform->set('dob',array('type'=>'text'));
			$this->autoform->set('last_name',array('label'=>'Last name'));
			$this->autoform->set('first_name',array('label'=>'First name'));
			$this->autoform->set('delegate_type',array('label'=>'Delegate type'));
			$this->autoform->set('other_names',array('label'=>'Other names'));
			$this->autoform->set('nationality',array('label'=>'Country'));
			#$this->autoform->set('leisure_activity',array('label'=>'Excursion activity','type'=>'select',
			#'options'=>$this->get_available_excursions()));
			
			$this->autoform->set('payment_status',array('type'=>'select','options'=>array(''=>'Select...',''=>'NOT PAID','PAID'=>'PAID','COM'=>'COM')));
			$this->autoform->set('delegate_type', array('type'=>'select', 'options'=>array(""=>"Select...",
					'DELEGATE'=>'DELEGATE',
					'ACCOUNTANT GENERAL'=>'ACCOUNTANT GENERAL',
					'SECRETARIAT'=>'SECRETARIAT',
					'GUEST'=>'GUEST',
					'OFFICIAL'=>'OFFICIAL',
					'SPEAKER'=>'SPEAKER',
					'SERVICE PROVIDER'=>'SERVICE PROVIDER'
					)
				)
			);
			
			$this->autoform->set('emp_organisation', array('label'=>'Organisation'));
			$this->autoform->set('dob', array('placeholder'=>'YYYY-MM-DD','label'=>'Date of birth'));
			
			$this->autoform->wrap_each_field_only('<div class="input-group date" id="dob">','<div class="input-group-addon"><span class="glyphicon glyphicon-calendar" aria-hidden="true"></span></div></div>', array('dob'));
	
			$this->autoform->set_all(array('class'=>'form-control', 'label'=>array('class'=>'control-label  col-sm-4')));
			$this->autoform->wrap_each_field_only('<div class="col-sm-8">','</div>');
			$this->autoform->wrap_each('<div class="form-group form-group-sm">','</div>');
	
	
	
			$data['form']=$this->autoform->generate('administration/register_special/'.$id, array('class'=>'form-horizontal'), true);
			$data['user'] = $this->user;
			$data['title'] = "Event registration";
			$data['modules'] = $this->custom_auth->get_user_navigation();
			$data['MODULE'] = 'HOME';
			$data['breadcrumb'] = array(array('label'=>"Register", 'url'=>"#"));
	
			# add css & js for date picker
			$this->template->inject_partial('custom_css_top', '<link href="'.base_url('/media/static/bootstrap/css/bootstrap-datetimepicker.min.css').'" rel="stylesheet">');
			$this->template->inject_partial('custom_js_bottom', '<script src="'.base_url('/media/static/moment.min.js').'"></script>'.'<script src="'.base_url('/media/static/bootstrap/js/bootstrap-datetimepicker.min.js').'"></script>'.
					"<script type=\"text/javascript\">
						$(function () { $('#dob').datetimepicker({format:'YYYY-MM-DD'});});".
					'</script>');
	
	
			$this->template->title($data['title']);
			$this->template->set_partial('nav', 'layouts/fixed_navbar-part');
			$this->template->set_partial('messages', 'layouts/messages-part');
			$this->template->set_layout('logged_in_template');
			$this->template->build('layouts/form_body', $data);
	}
	
	public function get_available_excursions()
	{
		//excursions
		$excursion_activity = $this->config->item('excursion_activity');
		
		foreach($excursion_activity as &$E)
		{
			//check availability
			$SQL ="SELECT count(1) num from core_registrations where leisure_activity like '{$E['label']}'";
			
			$count = array_column($this->reports_model->run_sql_query($SQL),'num');
			
			$options['']='Select...';
			
			if($E['limit'] > (int)$count[0])
			{
				$options[$E['label']] =$E['label'].' '.((int)$count[0] - $E['limit']).' slots'; 
			}
			else
			{
			}
			
		}
		
		return $options;
	}
	
	public function modify_participant($id=null)
	{
		$fd = $this->input->post();
		
		if($id==null)
		{
			$id = $fd['id'];
		}
		
		if(isset($fd['submit']))
		{
			unset($fd['submit']);
			
			#update core registrations
			$this->core_model->update_object('core_registrations',$fd['id'],$fd);
			
			#update core invoices						
			$event_rates = $this->config->item('event_rates');
			$rate = $event_rates[$fd['hotel']];
			
			$invoice_content = array(
				'event_rates'=>$event_rates,
				'acc_persons_desc'=>'',
				'acc_persons_count'=>0,
				'acc_persons_cost'=>'0',
				'reg_rate'=>$rate,
				'reg_cost'=>$rate,
				'grand_total'=>$rate,
				'grand_total_words'=>ucfirst($this->convert_number_to_words($rate)).' only'
			);
			
			$invoice['content']=json_encode($invoice_content);
			$invoice['amount'] =$rate;
			$invoice['resend_invoice_email'] =1;  
			$invoice['sync'] =-1;  
			
			$this->core_model->update_object('core_invoices',null,$invoice,array('code'=>$fd['invoice_code']));

			$this->session->set_flashdata('SUCCESS', $this->message_templates['SAVED_SUCCESSFULLY']);
			redirect('administration', 'location');
			
		}
		
		$this->autoform->sql("select * from core_registrations where id={$id}");
		
		#add styling 
		$this->autoform->set_all(array('class'=>'form-control', 'label'=>array('class'=>'control-label  col-sm-4')));
		$this->autoform->wrap_each_field_only('<div class="col-sm-8">','</div>');
		$this->autoform->wrap_each('<div class="form-group form-group-sm">','</div>');
		
		#remove unwanted fields
		$this->autoform->remove(array('sync','remote_id','city','box','plot_no','street','title','invitation_email_sent',
				'reg_email_sent','updated_at','created_at','update_registration_code','parent_registration_code',
				'nok_telephone','nok_email','nok_country','nok_name','hotel_room_type','tour_route',
				'travel_departure_date','travel_arrival_date','travel_from_country','flight_no','passport_issue_place',
				'passport_expiry_date','passport_issue_date','passport_no','insurance_body','insurance','acc_body',
				'acc_body_member','emp_industry','emp_country','emp_job_title','emp_email','emp_telephone',
		'rate_category'));
		
		#modify fields
		#arrange fields
		$this->autoform->set('delegate_type', array('type'=>'select', 'options'=>array(""=>"Select...",'PARTICIPANT'=>'PARTICIPANT','SPEAKER'=>'SPEAKER','SPONSOR'=>'SPONSOR','ICPAU STAFF'=>'ICPAU STAFF')));
		
		$hotel_options = $this->config->item('event_item_labels');

		$this->autoform->set('hotel',array('type'=>'dropdown','options'=>$hotel_options));
		$this->autoform->set('registration_code',array('disabled'=>'disabled'));
		$this->autoform->set('invoice_code',array('type'=>'hidden'));
		$this->autoform->field('id');
		
		$this->autoform->buttons('<div class="button-row"><a class="btn btn-default" href="'.site_url('administration').'" role="button">Cancel</a>&nbsp; '.form_submit(array('type'=>'submit', 'name'=>'submit', 'value'=>'Submit', 'class'=>'btn btn-primary')).'</div>');
		
		$data['form'] = $this->autoform->generate("administration/modify_participant/{$id}",array('class'=>'form-horizontal'), true);
		$data['user'] = $this->user;
		$data['title'] = "Modify participant";
		$data['modules'] = $this->custom_auth->get_user_navigation();
		$data['MODULE'] = 'REGISTER';
		$data['breadcrumb'] = $this->breadcrumb;
		
		$this->template->title('Attendance');
		$this->template->set_partial('nav', 'layouts/fixed_navbar-part');
		$this->template->set_partial('messages', 'layouts/messages-part');
		$this->template->set_layout('logged_in_template');
		$this->template->build('layouts/form_body', $data);
	}

	
	public function confirm_payment($invoice_id=null)
	{
		# check if the user is logged in
		if ( ! $this->custom_auth->is_authenticated())
		{
			redirect('/administration/login/', 'location');
		}
		
		$fd = $this->input->post();
		
		if (isset($fd['invoice_id']) && strlen($fd['invoice_id']) > 0)
		{
			$invoice_id = $fd['invoice_id'];
		}
		
		$invoice = $this->core_model->get_object('core_invoices', $invoice_id);
		
		if ( ! $invoice)
		{
			$this->session->set_flashdata('ERROR', $this->message_templates['MISSING_ITEM']);
			redirect('/', 'location');
		}
		
		# validate form data
		$form_validation_rules = array
		(
			array
			(
				'field' => 'amount',
				'rules' => 'required',
			),
			array
			(
				'field' => 'payment_proof',
				'rules' => 'required'
			),
			array
			(
				'field' => 'payment_date',
				'rules' => 'required',
			),
		);
		
		$this->form_validation->set_rules($form_validation_rules);
		
		if ($this->form_validation->run() == true)
		{
			unset($fd['invoice_id']);
			
			if (empty($invoice["payment_type"]))
			{
				$fd["payment_type"] = 'TT';
			}
			
			$fd["payment_made"] = true;
			$fd["updated_at"] = date('Y-m-d H:i:s');
			
			$this->core_model->update_object("core_invoices", $invoice['id'], $fd);
			
			$this->session->set_flashdata('SUCCESS', $this->message_templates['SAVED_SUCCESSFULLY']);
			
			# redirect to details page
			redirect('/administration/', 'location');
		}
		
		$invoice_content = json_decode($invoice['content'], true);
		$invoice_html = '<h3>Invoice</h3><table class="table table-bordered table-striped">
			<tr><th>item</th><th>qtty</th><th>amount</th></tr>
			';
		if (isset($invoice_content['reg_rate']))
		{
			$invoice_html .= '<tr><td>Payment for Event Participation'
				.$invoice_content['reg_rate'].'</td><td class="text-center">1</td><td class="text-right">'.number_format($invoice_content['reg_cost']).'</td></tr>
				';
		}
		$invoice_html .= '<h4>Invoice #: '.$invoice['code'].'</h4><tr><td>Accompanying Persons:'.$invoice_content['acc_persons_desc'].'</td><td class="text-center">'.$invoice_content['acc_persons_count'].'</td><td class="text-right">'.$invoice_content['acc_persons_cost'].'</td></tr>
			<tr><th colspan="2">TOTAL</th><th class="text-right">'.number_format($invoice['amount']).'</th></tr>
			<tr><td colspan="3" class="text-right">Amount in Words: <b>'.$invoice_content['grand_total_words'].'</b></td></tr>
			</table><br><div class="col-sm-4 text-right"><b>Payment Type:</b> </div><div class="col-sm-8">'.$invoice['payment_type'].'</div>';
		
		$this->autoform->add(array('name'=>'invoice_id', 'type'=>'hidden', 'value'=>$invoice['id']));
		$this->autoform->set_help_text('invoice_id', $invoice_html);

		$this->autoform->sql($this->core_model->get_select_sql('core_invoices', $invoice['id']));
		$this->autoform->set('amount', array('type'=>'number', 'min'=>0, 'step'=>1));
		$this->autoform->set('payment_date', array('type'=>'text', 'placeholder'=>'YYYY-MM-DD'));
		$this->autoform->wrap_each_field_only('<div class="input-group date" id="payment_date">','<div class="input-group-addon"><span class="glyphicon glyphicon-calendar" aria-hidden="true"></span></div></div>', array('payment_date'));
		$this->autoform->set('payment_proof', array('type'=>'textarea', 'rows'=>4));
		$this->autoform->set(array('amount','payment_date','payment_proof'), array('required'=>'required'));
		$this->autoform->remove(array('code','content','payment_type','payment_made','created_at','updated_at','invoice_email_sent','payment_email_sent','resend_invoice_email','remote_id','sync'));
		
		$this->autoform->buttons('<div class="button-row"><a class="btn btn-default" href="'.site_url('/administration/').'" role="button">Cancel</a>&nbsp; '.form_submit(array('type'=>'submit', 'value'=>'Save', 'class'=>'btn btn-primary')).'</div>');
				
		$this->autoform->set_all(array('class'=>'form-control', 'label'=>array('class'=>'control-label  col-sm-4')));
		$this->autoform->wrap_each_field_only('<div class="col-sm-8">','</div>');
		$this->autoform->wrap_each('<div class="form-group form-group-sm">','</div>');
		
		$data["form"] = $this->autoform->generate("administration/confirm_payment", array('class'=>'form-horizontal'));
		
		$data['user'] = $this->user;
		$data['title'] = "Confirm TT/CASH Payment";
		$data['modules'] = $this->custom_auth->get_user_navigation();
		$data['MODULE'] = 'HOME';
		$data['INFO'] = "Confirm payment for Conference";
		$data['SUBMODULE'] = 'REGS';
		$data['breadcrumb'] = $this->breadcrumb;
		
		# add css & js for date picker
		$this->template->inject_partial('custom_css_top', '<link href="'.base_url('/media/static/bootstrap/css/bootstrap-datetimepicker.min.css').'" rel="stylesheet">');
		$this->template->inject_partial('custom_js_bottom', '<script src="'.base_url('/media/static/moment.min.js').'"></script>'.'<script src="'.base_url('/media/static/bootstrap/js/bootstrap-datetimepicker.min.js').'"></script>'.
				"<script type=\"text/javascript\">$(function () { $('#payment_date').datetimepicker({format:'YYYY-MM-DD'});});".
				'</script>');
		
		$this->template->title($data['title']);
		$this->template->set_partial('nav', 'layouts/fixed_navbar-part');
		$this->template->set_partial('messages', 'layouts/messages-part');
		$this->template->set_layout('logged_in_template');
		$this->template->build('layouts/form_body', $data);
	}
	
	
	public function registration_details($reg_id)
	{
		# check if the user is logged in
		if ( ! $this->custom_auth->is_authenticated())
		{
			echo 'not authenticated';
			return;
		}
		
		$reg = $this->core_model->get_object('core_registrations', $reg_id);
		
		if ( ! $reg)
		{
			echo 'registration not found';
			return;
		}
		
		$invoice_codes = array();
		
		if ( ! empty($reg['invoice_code']))
		{
			$invoice_codes[] = $reg['invoice_code'];
		}
		
		$reg['reg_email_sent'] = $this->core_model->get_themed_boolean($reg['reg_email_sent']);
		$reg['invitation_email_sent'] = $this->core_model->get_themed_boolean($reg['invitation_email_sent']);
		
		$columns = 2;
		$values = &$reg;
		$skip_fields = array('id','first_name','last_name','other_names','remote_id','sync');
		
		// -- end options --
		
		$bstrp_col_width = (int)(12/$columns);
		
		$output_html = '<h3>'.$values['first_name'].' '.$values['last_name'].' '.$values['other_names'].'</h3><div class="row">';
		$output_html .= '<div class="col-sm-'.$bstrp_col_width.'"><table class="table table-condensed">';

		$values_per_column = (int)((count($values) - count($skip_fields))/$columns);
		$counter = 0;
		$column_count = 1;
		foreach ($values as $key=>$value)
		{
			if (in_array($key, $skip_fields))
			{
				continue;
			}
			$counter += 1;
			if ($counter > $values_per_column && $column_count < $columns)
			{
				$output_html .= '</table></div><div class="col-sm-'.$bstrp_col_width.'"><table class="table table-condensed">';
				$column_count += 1;
				$counter = 1;
			}
			$output_html .= '<tr><th>'.ucwords(preg_replace("/[_-]/",' ', $key)).': </th><td>'.$value.'</td></tr>';
		}
		$output_html .= '</table></div></div>';
		
		$accomp_persons = $this->core_model->get_many_objects('core_registrations', array('parent_registration_code'=>$reg['registration_code']), 'id');
		
		if (count($accomp_persons) > 0)
		{
			$output_html .= "<h3>Accompanying Persons</h3>";
			
			foreach ($accomp_persons as &$AP)
			{
				if ( ! empty($AP['invoice_code']) && ! in_array($AP['invoice_code'], $invoice_codes))
				{
					$invoice_codes[] = $AP['invoice_code'];
				}
				
				$columns = 2;
				$values = $AP;
				$skip_fields = array('id','first_name','last_name','other_names','remote_id','sync',
					'nok_name','nok_country','nok_email','nok_telephone','emp_organisation','emp_industry','emp_country','emp_job_title',
					'emp_email','emp_telephone','acc_body_member','acc_body','created_at','updated_at','reg_email_sent',
					'invitation_email_sent','parent_registration_code','nationality','email','telephone','update_registration_code'
				);
				
				// -- end options --
				
				$bstrp_col_width = (int)(12/$columns);
				
				$output_html .= '<h4>'.$values['first_name'].' '.$values['last_name'].' '.$values['other_names'].'</h4><div class="row">';
				$output_html .= '<div class="col-sm-'.$bstrp_col_width.'"><table class="table table-condensed">';
		
				$values_per_column = (int)((count($values) - count($skip_fields))/$columns);
				$counter = 0;
				$column_count = 1;
				foreach ($values as $key=>$value)
				{
					if (in_array($key, $skip_fields))
					{
						continue;
					}
					$counter += 1;
					if ($counter > $values_per_column && $column_count < $columns)
					{
						$output_html .= '</table></div><div class="col-sm-'.$bstrp_col_width.'"><table class="table table-condensed">';
						$column_count += 1;
						$counter = 1;
					}
					$output_html .= '<tr><th>'.ucwords(preg_replace("/[_-]/",' ', $key)).': </th><td>'.$value.'</td></tr>';
				}
				$output_html .= '</table></div></div>';
			}
		}
		
		$output_html .= '<h3>Invoices</h3>';
		# show invoices
		if (count($invoice_codes) > 0)
		{
			$invoices = $this->core_model->get_many_objects('core_invoices', null, 'id', null, null, array('code'=>$invoice_codes));
			
			foreach ($invoices as &$I)
			{
				$invoice_content = json_decode($I['content'], true);
				$output_html .= '<br><h4>Invoice #: '.$I['code'].'</h4><table class="table table-bordered table-striped">
					<tr><th>item</th><th>qtty</th><th>amount</th></tr>
				';
				if (isset($invoice_content['reg_rate']))
				{
					$output_html .= '<tr><td>Registration:<br>'.$reg['first_name'].' '.$reg['last_name'].' '.$reg['other_names'].' , Reg ID:  '.$reg['registration_code'].'<br>'
					.$invoice_content['reg_rate'].'</td><td class="text-center">1</td><td class="text-right">'.$invoice_content['reg_cost'].'</td></tr>
					';
				}
				$output_html .= '<tr><td>Accompanying Persons:'.$invoice_content['acc_persons_desc'].'</td><td class="text-center">'.$invoice_content['acc_persons_count'].'</td><td class="text-right">'.$invoice_content['acc_persons_cost'].'</td></tr>
					<tr><th colspan="2">TOTAL</th><th class="text-right">'.number_format($I['amount']).'</th></tr>
					<tr><td colspan="3" class="text-right">Amount in Words: <b>'.$invoice_content['grand_total_words'].'</b></td></tr>
					';
				
				$output_html .= '<tr><td><b>Payment Method:</b> '.$I['payment_type'].' &nbsp;&nbsp; <b>Invoice Sent:</b> '.$this->core_model->get_themed_boolean($I['invoice_email_sent']).' <br><b>Receipt Sent:</b> '.$this->core_model->get_themed_boolean($I['payment_email_sent']).'</td>';
				
				if ((bool) $I['payment_made'])
				{
					$output_html .= '<td colspan="2" class="text-success text-right"><b>Payment Has Been Made</b><br></td>';
				}
				elseif ( ! (bool) $I['payment_made'] && ($I['payment_type'] == 'TT' || $I['payment_type'] == 'CASH' || empty($I['payment_type'])))
				{
					$output_html .= '<td colspan="2" class="text-danger text-right"><b>Payment Has Not Yet Been Made</b> <a class="btn btn-info btn-xs" href="'.site_url("/administration/confirm_payment/{$I['id']}").'" role="button" ><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Register TT/CASH Payment</a></td>';
				}
				else
				{
					$output_html .= '<td colspan="2" class="text-danger text-right"><b>Payment Has Not Yet Been Made</b></td>';
				}
				
				$output_html .= '</tr>';
				
				$output_html .= '<tr><td>';
				$output_html .= '<a href="'.site_url('administration/download_document/'.$reg['registration_code'].'/PROFORMA/'.$I['code']).'">Download (Proforma) Invoice</a><br>';
				$output_html .= '</td><td colspan="2">';
				$output_html .= '<a href="'.site_url('administration/resend_document/'.$reg['registration_code'].'/PROFORMA/'.$I['code']).'">Resend (Proforma) Invoice</a><br>';
				$output_html .= '</td></tr>';
				
				$output_html .= '</table>';
			}
		}
		
		echo $output_html;
		return;
	}
	
	/***************************
	 * Document types:
	 *   PROFORMA
	 *   RECEIPT
	 *   INVITATION
	 * 
	 ***************************/
	public function download_document($reg_number, $doc_type, $invoice_code=null)
	{
		$this->load->helper('download');
		$file_path = './media/PDF/';
		
		switch ($doc_type)
		{
			case 'PROFORMA':
				if ($invoice_code)
				{
					$file_path .= 'proformas/'.$reg_number.'_'.$invoice_code.'_proforma_invoice.pdf';
				}
				else
				{
					$file_path .= 'proformas/'.$reg_number.'_proforma_invoice.pdf';
				}
				break;
			case 'RECEIPT':
				if ($invoice_code)
				{
					$file_path .= 'receipts/'.$reg_number.'_'.$invoice_code.'_receipt.pdf';
				}
				else
				{
					$file_path .= 'receipts/'.$reg_number.'_receipt.pdf';
				}
				break;
			case 'INVITATION': // TODO ??????????????????????????
				return;
				break;
			default:
				return download_document($reg_number, 'PROFORMA', $invoice_code);
		}
		
		force_download($file_path, null, true);
	}

	
	/***************************
	 * Document types:
	 *   PROFORMA
	 *   RECEIPT
	 *   INVITATION
	 * 
	 ***************************/
	public function resend_document($reg_number, $doc_type, $invoice_code=null, $email=null)
	{
		$this->load->helper('path');
		$attachment_path = './media/PDF/';
		$title = 'Your ';
		$message = 'Hello
			Please find attached your ';
		
		switch ($doc_type)
		{
			case 'PROFORMA':
				if ($invoice_code)
				{
					$attachment_path .= 'proformas/'.$reg_number.'_'.$invoice_code.'_proforma_invoice.pdf';
				}
				else
				{
					$attachment_path .= 'proformas/'.$reg_number.'_proforma_invoice.pdf';
				}
				$title .= 'proforma invoice / invoice';
				$message .= 'proforma invoice / invoice';
				break;
			case 'RECEIPT':
				if ($invoice_code)
				{
					$attachment_path .= 'receipts/'.$reg_number.'_'.$invoice_code.'_receipt.pdf';
				}
				else
				{
					$attachment_path .= 'receipts/'.$reg_number.'_receipt.pdf';
				}
				$title .= 'receipt';
				$message .= 'receipt';
				break;
			case 'INVITATION': // TODO ??????????????????????????
				return;
				break;
			default:
				return resend_document($reg_number, 'PROFORMA', $email, $invoice_code);
		}
		
		$attachment_full_file_path = set_realpath($attachment_path);
		
		if ( ! $email)
		{
			$reg = $this->core_model->get_object('core_registrations', null, array('registration_code' => $reg_number));
				
			if ( ! $reg)
			{
				return;
			}
			
			$email = $reg['email'];
		}
		
		# send email
		$email_db = mysqli_connect("localhost", "icpau", "icpau123", "ICPAU");
		mysqli_query($email_db, "INSERT INTO customized_messages (from_email, to_email, subject, salute_text, body, abs_attachment_path) VALUES (
		'noreply@icpau.co.ug', '{$email}', {$title}, '', '{$message}', '{$attachment_full_file_path}')");
	}
	
	public function close_popup()
	{
		$this->load->library('parser');
		$data['title'] = "close popup";
		$this->parser->parse('core/popup_close', $data);
	}
	
	/*
	*Students engagment seminar
	*/
	public function students_engagment($consolidated = 0)
	{
		
		if($consolidated == 0)
		{	
			$subjects =array(
					'CPA 13-Advanced Financial Reporting',
					'CPA 8-Financial Reporting',
					'CPA 14-Public Sector Accounting & Reporting',
					'CPA 1-Financial Accounting',
					'CPA 17-Auditing &Other Assurance Services',
					'CPA 9-Advanced Taxation',
					'CPA 15-Business Policy &Strategy',
					'CPA 12-Auditing &Professional Ethics &Values',
					'CPA 16-Advanced Financial Management',
					'CPA 11-Management Decision&Control',
					'CPA 18-Integration of Knowledge'
					);
		
			foreach($subjects as $s)
			{
				$SQL ="SELECT ucase(icpau_id) icpau_id,ucase(full_name) full_name FROM `students_engagement` where 						track like '%{$s}%' order by full_name ASC";
			
				$track[$s] = $this->reports_model->run_sql_query($SQL);
			}
		
		
			$data['registrations'] = $track;
			
			$view ='administration/students_engagment'; 
		}
		else
		{
			$SQL ="SELECT ucase(icpau_id) icpau_id,ucase(full_name) full_name,track   FROM students_engagement where 					track NOT LIKE '[]' ORDER BY full_name ASC";
			
			$data['registrations'] = $this->reports_model->run_sql_query($SQL);
			
			$view ='administration/students_engagment_consolidated'; 
			
		}
		
		
				
		$data['user'] = $this->user;
		$data['title'] = "Event Registrations";
		$data['modules'] = $this->custom_auth->get_user_navigation();
		$data['MODULE'] = 'HOME';
		$data['SUBMODULE'] = 'REGS';
		$data['breadcrumb'] = $this->breadcrumb;
		
				
		$this->template->title($data['title']);
		$this->template->set_partial('nav', 'layouts/fixed_navbar-part');
		$this->template->set_partial('messages', 'layouts/messages-part');
		$this->template->set_layout('logged_in_template');
		$this->template->build($view, $data);

	}

	// Author: Karl Rixon
	// URL: http://www.karlrixon.co.uk/writing/convert-numbers-to-words-with-php/
	public function convert_number_to_words($number) 
	{
		$hyphen      = '-';
		$conjunction = ' and ';
		$separator   = ', ';
		$negative    = 'negative ';
		$decimal     = ' point ';
		$dictionary  = array(
			0                   => 'zero',
			1                   => 'one',
			2                   => 'two',
			3                   => 'three',
			4                   => 'four',
			5                   => 'five',
			6                   => 'six',
			7                   => 'seven',
			8                   => 'eight',
			9                   => 'nine',
			10                  => 'ten',
			11                  => 'eleven',
			12                  => 'twelve',
			13                  => 'thirteen',
			14                  => 'fourteen',
			15                  => 'fifteen',
			16                  => 'sixteen',
			17                  => 'seventeen',
			18                  => 'eighteen',
			19                  => 'nineteen',
			20                  => 'twenty',
			30                  => 'thirty',
			40                  => 'fourty',
			50                  => 'fifty',
			60                  => 'sixty',
			70                  => 'seventy',
			80                  => 'eighty',
			90                  => 'ninety',
			100                 => 'hundred',
			1000                => 'thousand',
			1000000             => 'million',
			1000000000          => 'billion',
			1000000000000       => 'trillion',
			1000000000000000    => 'quadrillion',
			1000000000000000000 => 'quintillion'
		);
		if (!is_numeric($number)) {
			return false;
		}
		if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
			// overflow
			trigger_error(
				'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
				E_USER_WARNING
			);
			return false;
		}
		if ($number < 0) {
			return $negative . $this->convert_number_to_words(abs($number));
		}
		$string = $fraction = null;
		if (strpos($number, '.') !== false) {
			list($number, $fraction) = explode('.', $number);
		}
		switch (true) {
			case $number < 21:
				$string = $dictionary[$number];
				break;
			case $number < 100:
				$tens   = ((int) ($number / 10)) * 10;
				$units  = $number % 10;
				$string = $dictionary[$tens];
				if ($units) {
					$string .= $hyphen . $dictionary[$units];
				}
				break;
			case $number < 1000:
				$hundreds  = $number / 100;
				$remainder = $number % 100;
				$string = $dictionary[$hundreds] . ' ' . $dictionary[100];
				if ($remainder) {
					$string .= $conjunction . $this->convert_number_to_words($remainder);
				}
				break;
			default:
				$baseUnit = pow(1000, floor(log($number, 1000)));
				$numBaseUnits = (int) ($number / $baseUnit);
				$remainder = $number % $baseUnit;
				$string = $this->convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
				if ($remainder) {
					$string .= $remainder < 100 ? $conjunction : $separator;
					$string .= $this->convert_number_to_words($remainder);
				}
				break;
		}
		if (null !== $fraction && is_numeric($fraction)) {
			$string .= $decimal;
			$words = array();
			foreach (str_split((string) $fraction) as $number) {
				$words[] = $dictionary[$number];
			}
			$string .= implode(' ', $words);
		}
		return $string;
	}
	
		/*********************************************************
	 * Barcode generation... returns path to the barcode image
	 * source: http://davidscotttufts.com/2009/03/31/how-to-create-barcodes-in-php/
	 *********************************************************/
	private function barcode( $filepath="", $text="0", $size="20", $orientation="horizontal", $code_type="code128", $print=false, $SizeFactor="1" )
	{
		$code_string = "";
		// Translate the $text into barcode the correct $code_type
		if ( in_array(strtolower($code_type), array("code128", "code128b")) ) {
			$chksum = 104;
			// Must not change order of array elements as the checksum depends on the array's key to validate final code
			$code_array = array(" "=>"212222","!"=>"222122","\""=>"222221","#"=>"121223","$"=>"121322","%"=>"131222","&"=>"122213","'"=>"122312","("=>"132212",")"=>"221213","*"=>"221312","+"=>"231212",","=>"112232","-"=>"122132","."=>"122231","/"=>"113222","0"=>"123122","1"=>"123221","2"=>"223211","3"=>"221132","4"=>"221231","5"=>"213212","6"=>"223112","7"=>"312131","8"=>"311222","9"=>"321122",":"=>"321221",";"=>"312212","<"=>"322112","="=>"322211",">"=>"212123","?"=>"212321","@"=>"232121","A"=>"111323","B"=>"131123","C"=>"131321","D"=>"112313","E"=>"132113","F"=>"132311","G"=>"211313","H"=>"231113","I"=>"231311","J"=>"112133","K"=>"112331","L"=>"132131","M"=>"113123","N"=>"113321","O"=>"133121","P"=>"313121","Q"=>"211331","R"=>"231131","S"=>"213113","T"=>"213311","U"=>"213131","V"=>"311123","W"=>"311321","X"=>"331121","Y"=>"312113","Z"=>"312311","["=>"332111","\\"=>"314111","]"=>"221411","^"=>"431111","_"=>"111224","\`"=>"111422","a"=>"121124","b"=>"121421","c"=>"141122","d"=>"141221","e"=>"112214","f"=>"112412","g"=>"122114","h"=>"122411","i"=>"142112","j"=>"142211","k"=>"241211","l"=>"221114","m"=>"413111","n"=>"241112","o"=>"134111","p"=>"111242","q"=>"121142","r"=>"121241","s"=>"114212","t"=>"124112","u"=>"124211","v"=>"411212","w"=>"421112","x"=>"421211","y"=>"212141","z"=>"214121","{"=>"412121","|"=>"111143","}"=>"111341","~"=>"131141","DEL"=>"114113","FNC 3"=>"114311","FNC 2"=>"411113","SHIFT"=>"411311","CODE C"=>"113141","FNC 4"=>"114131","CODE A"=>"311141","FNC 1"=>"411131","Start A"=>"211412","Start B"=>"211214","Start C"=>"211232","Stop"=>"2331112");
			$code_keys = array_keys($code_array);
			$code_values = array_flip($code_keys);
			for ( $X = 1; $X <= strlen($text); $X++ ) {
				$activeKey = substr( $text, ($X-1), 1);
				$code_string .= $code_array[$activeKey];
				$chksum=($chksum + ($code_values[$activeKey] * $X));
			}
			$code_string .= $code_array[$code_keys[($chksum - (intval($chksum / 103) * 103))]];
			$code_string = "211214" . $code_string . "2331112";
		} elseif ( strtolower($code_type) == "code128a" ) {
			$chksum = 103;
			$text = strtoupper($text); // Code 128A doesn't support lower case
			// Must not change order of array elements as the checksum depends on the array's key to validate final code
			$code_array = array(" "=>"212222","!"=>"222122","\""=>"222221","#"=>"121223","$"=>"121322","%"=>"131222","&"=>"122213","'"=>"122312","("=>"132212",")"=>"221213","*"=>"221312","+"=>"231212",","=>"112232","-"=>"122132","."=>"122231","/"=>"113222","0"=>"123122","1"=>"123221","2"=>"223211","3"=>"221132","4"=>"221231","5"=>"213212","6"=>"223112","7"=>"312131","8"=>"311222","9"=>"321122",":"=>"321221",";"=>"312212","<"=>"322112","="=>"322211",">"=>"212123","?"=>"212321","@"=>"232121","A"=>"111323","B"=>"131123","C"=>"131321","D"=>"112313","E"=>"132113","F"=>"132311","G"=>"211313","H"=>"231113","I"=>"231311","J"=>"112133","K"=>"112331","L"=>"132131","M"=>"113123","N"=>"113321","O"=>"133121","P"=>"313121","Q"=>"211331","R"=>"231131","S"=>"213113","T"=>"213311","U"=>"213131","V"=>"311123","W"=>"311321","X"=>"331121","Y"=>"312113","Z"=>"312311","["=>"332111","\\"=>"314111","]"=>"221411","^"=>"431111","_"=>"111224","NUL"=>"111422","SOH"=>"121124","STX"=>"121421","ETX"=>"141122","EOT"=>"141221","ENQ"=>"112214","ACK"=>"112412","BEL"=>"122114","BS"=>"122411","HT"=>"142112","LF"=>"142211","VT"=>"241211","FF"=>"221114","CR"=>"413111","SO"=>"241112","SI"=>"134111","DLE"=>"111242","DC1"=>"121142","DC2"=>"121241","DC3"=>"114212","DC4"=>"124112","NAK"=>"124211","SYN"=>"411212","ETB"=>"421112","CAN"=>"421211","EM"=>"212141","SUB"=>"214121","ESC"=>"412121","FS"=>"111143","GS"=>"111341","RS"=>"131141","US"=>"114113","FNC 3"=>"114311","FNC 2"=>"411113","SHIFT"=>"411311","CODE C"=>"113141","CODE B"=>"114131","FNC 4"=>"311141","FNC 1"=>"411131","Start A"=>"211412","Start B"=>"211214","Start C"=>"211232","Stop"=>"2331112");
			$code_keys = array_keys($code_array);
			$code_values = array_flip($code_keys);
			for ( $X = 1; $X <= strlen($text); $X++ ) {
				$activeKey = substr( $text, ($X-1), 1);
				$code_string .= $code_array[$activeKey];
				$chksum=($chksum + ($code_values[$activeKey] * $X));
			}
			$code_string .= $code_array[$code_keys[($chksum - (intval($chksum / 103) * 103))]];
			$code_string = "211412" . $code_string . "2331112";
		} elseif ( strtolower($code_type) == "code39" ) {
			$code_array = array("0"=>"111221211","1"=>"211211112","2"=>"112211112","3"=>"212211111","4"=>"111221112","5"=>"211221111","6"=>"112221111","7"=>"111211212","8"=>"211211211","9"=>"112211211","A"=>"211112112","B"=>"112112112","C"=>"212112111","D"=>"111122112","E"=>"211122111","F"=>"112122111","G"=>"111112212","H"=>"211112211","I"=>"112112211","J"=>"111122211","K"=>"211111122","L"=>"112111122","M"=>"212111121","N"=>"111121122","O"=>"211121121","P"=>"112121121","Q"=>"111111222","R"=>"211111221","S"=>"112111221","T"=>"111121221","U"=>"221111112","V"=>"122111112","W"=>"222111111","X"=>"121121112","Y"=>"221121111","Z"=>"122121111","-"=>"121111212","."=>"221111211"," "=>"122111211","$"=>"121212111","/"=>"121211121","+"=>"121112121","%"=>"111212121","*"=>"121121211");
			// Convert to uppercase
			$upper_text = strtoupper($text);
			for ( $X = 1; $X<=strlen($upper_text); $X++ ) {
				$code_string .= $code_array[substr( $upper_text, ($X-1), 1)] . "1";
			}
			$code_string = "1211212111" . $code_string . "121121211";
		} elseif ( strtolower($code_type) == "code25" ) {
			$code_array1 = array("1","2","3","4","5","6","7","8","9","0");
			$code_array2 = array("3-1-1-1-3","1-3-1-1-3","3-3-1-1-1","1-1-3-1-3","3-1-3-1-1","1-3-3-1-1","1-1-1-3-3","3-1-1-3-1","1-3-1-3-1","1-1-3-3-1");
			for ( $X = 1; $X <= strlen($text); $X++ ) {
				for ( $Y = 0; $Y < count($code_array1); $Y++ ) {
					if ( substr($text, ($X-1), 1) == $code_array1[$Y] )
						$temp[$X] = $code_array2[$Y];
				}
			}
			for ( $X=1; $X<=strlen($text); $X+=2 ) {
				if ( isset($temp[$X]) && isset($temp[($X + 1)]) ) {
					$temp1 = explode( "-", $temp[$X] );
					$temp2 = explode( "-", $temp[($X + 1)] );
					for ( $Y = 0; $Y < count($temp1); $Y++ )
						$code_string .= $temp1[$Y] . $temp2[$Y];
				}
			}
			$code_string = "1111" . $code_string . "311";
		} elseif ( strtolower($code_type) == "codabar" ) {
			$code_array1 = array("1","2","3","4","5","6","7","8","9","0","-","$",":","/",".","+","A","B","C","D");
			$code_array2 = array("1111221","1112112","2211111","1121121","2111121","1211112","1211211","1221111","2112111","1111122","1112211","1122111","2111212","2121112","2121211","1121212","1122121","1212112","1112122","1112221");
			// Convert to uppercase
			$upper_text = strtoupper($text);
			for ( $X = 1; $X<=strlen($upper_text); $X++ ) {
				for ( $Y = 0; $Y<count($code_array1); $Y++ ) {
					if ( substr($upper_text, ($X-1), 1) == $code_array1[$Y] )
						$code_string .= $code_array2[$Y] . "1";
				}
			}
			$code_string = "11221211" . $code_string . "1122121";
		}
		// Pad the edges of the barcode
		$code_length = 20;
		if ($print) {
			$text_height = 30;
		} else {
			$text_height = 0;
		}
	
		for ( $i=1; $i <= strlen($code_string); $i++ ){
			$code_length = $code_length + (integer)(substr($code_string,($i-1),1));
		}
		if ( strtolower($orientation) == "horizontal" ) {
			$img_width = $code_length*$SizeFactor;
			$img_height = $size;
		} else {
			$img_width = $size;
			$img_height = $code_length*$SizeFactor;
		}
		$image = imagecreate($img_width, $img_height + $text_height);
		$black = imagecolorallocate ($image, 0, 0, 0);
		$white = imagecolorallocate ($image, 255, 255, 255);
		imagefill( $image, 0, 0, $white );
		if ( $print ) {
			imagestring($image, 5, 31, $img_height, $text, $black );
		}
		$location = 10;
		for ( $position = 1 ; $position <= strlen($code_string); $position++ ) {
			$cur_size = $location + ( substr($code_string, ($position-1), 1) );
			if ( strtolower($orientation) == "horizontal" )
				imagefilledrectangle( $image, $location*$SizeFactor, 0, $cur_size*$SizeFactor, $img_height, ($position % 2 == 0 ? $white : $black) );
				else
					imagefilledrectangle( $image, 0, $location*$SizeFactor, $img_width, $cur_size*$SizeFactor, ($position % 2 == 0 ? $white : $black) );
					$location = $cur_size;
		}
	
		// Draw barcode to the screen or save in a file
		if ( $filepath=="" ) {
			header ('Content-type: image/png');
			imagepng($image);
			imagedestroy($image);
		} else {
			imagepng($image,$filepath);
			imagedestroy($image);
		}
	
	}

}

?>
