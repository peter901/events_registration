<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Core extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		
		$this->load->library('custom_auth');
		$this->load->model('reports_model');
	}

	
	public function index()
	{
		# check if the user is logged in
		if ( ! $this->custom_auth->is_authenticated())
		{
			redirect('/login/', 'location');
		}
		
		#$this->load->model('PET_model');
		
		$user = $this->custom_auth->get_user();
		
		$data['user'] = $user;


		//Formulation of Home page notices depending on a user can be generated from here
		if (date("Ymd") > 20170415 and date("Ymd") < 20170516)
		{
			//A better message to be got based on institution_session
			$this->load->model('online_model');
			$exams_confirmation_exists = $this->online_model->attempted_exams_registration($user['identity_id']);
			if (!empty($exams_confirmation_exists))
			{
				$data['exams_confirmation_exists'] = 1;
			}
		}
		
		// get PET supervised students
        $data['PET_mentor_students'] = $this->PET_model->get_mentor_students($user['id']);
        $data['PET_overseer_students'] = $this->PET_model->get_overseer_students($user['id']);
        $data['PET_eligibility'] = $this->PET_model->get_eligibility($user['id']);

		$data['title'] = "Home";
		$data['modules'] = $this->custom_auth->get_user_navigation();
		$data['MODULE'] = 'HOME';
		$data['SUBMODULE'] = null;
		$data['breadcrumb'] = array(array('label'=>"Home", 'url'=>site_url("/")));
		
		$this->template->title($data['title']);
		$this->template->set_partial('nav', 'core/fixed_navbar-part');
		$this->template->set_partial('messages', 'core/messages-part');
		$this->template->set_layout('logged_in_template');
		$this->template->build('core/home', $data);
	}
	
	
	public function login()
	{
		# check if the user is logged in
		if ($this->custom_auth->is_authenticated() == true)
		{
			# user is already logged in, redirect to home
			redirect('/administration/', 'location');
		}

		# validate the form
		$validation = array
		(
			array
			(
				'field'   => 'username', 
				'rules'   => 'required|max_length[30]'
			),
			array
			(
				'field'   => 'password', 
				'rules'   => 'required|max_length[30]'
			),
		);

		$this->form_validation->set_rules($validation);

		if ($this->form_validation->run() == true)
		{
			
			$fd = $this->input->post();
			
			$auth = $this->custom_auth->login( $fd['username'],  $fd['password']);
				
			if ($auth)
			{
				# redirect to home
				redirect('/administration/', 'location');
			}
		}
		
		$this->autoform->add(array('name'=>'username', 'required'=>'required', 'placeholder'=>'Username', 'label'=>'', 'autofocus'=>'autofocus'));
		$this->autoform->add(array('name'=>'password', 'required'=>'required', 'type'=>'password', 'placeholder'=>'Password', 'label'=>''));
		$this->autoform->set_all(array('class'=>'form-control'));
		$this->autoform->wrap_each_field_only('<div class="col-sm-12">','</div>');
		$this->autoform->wrap_each('<div class="form-group form-group">','</div>');
		$this->autoform->buttons(form_submit(array('type'=>'submit', 'value'=>'Login', 'class'=>'btn btn-lg btn-primary btn-block')));
		
		$data['form'] = $this->autoform->generate('core/login', array('class'=>'form-horizontal'));
		$data['title'] = "Login";
		$data['errors'] = $this->custom_auth->errors();
		$data['messages'] = $this->custom_auth->messages();
				
		$this->template->title($data['title']);
		$this->template->set_layout('default_template');
		$this->template->build('administration/login', $data);
	}


	public function logout()
	{
		if ( ! $this->custom_auth->is_authenticated())
		{
			# user is not logged in, redirect to login
			redirect('/core/login/', 'location');
		}

		$this->custom_auth->logout();
		
		# redirect to login
		redirect('/core/login/', 'location');
	}
	
	
	public function change_password()
	{
		# check if the user is logged in
		if ( ! $this->custom_auth->is_authenticated())
		{
			redirect('/login/', 'location');
		}
		
		$user = $this->custom_auth->get_user();
		
		# validate the form
		$validation = array
		(
			array
			(
				'field'   => 'old_password', 
				'rules'   => 'required|max_length[30]|callback_check_old_password'
			),
			array
			(
				'field'   => 'password', 
				'rules'   => 'required|max_length[30]'
			),
			array
			(
				'field'   => 'retype_password', 
				'rules'   => 'required|max_length[30]|matches[password]'
			),
		);

		$this->form_validation->set_rules($validation);

		if ($this->form_validation->run() == true)
		{
			$fd = $this->input->post();
			
			$to_update = array
			(
				'password' => $this->custom_auth->hash_password($fd['password']),
			);
			
			$this->core_model->update_object('core_users', $user['id'], $this->core_model->null_blank_entries($to_update));
			$this->session->set_flashdata('SUCCESS', "Your password has been changed successfully");
			# redirect to home
			redirect('/home/', 'location');
		}
		
		$this->autoform->add(array('name'=>'old_password', 'required'=>'required', 'type'=>'password', 'label'=>'Old Password', 'autofocus'=>'autofocus'));
		$this->autoform->add(array('name'=>'password', 'required'=>'required', 'type'=>'password', 'label'=>'New Password'));
		$this->autoform->add(array('name'=>'retype_password', 'required'=>'required', 'type'=>'password', 'label'=>'Retype New Password'));
		$this->autoform->set_all(array('class'=>'form-control'));
		$this->autoform->set_all(array('class'=>'form-control', 'label'=>array('class'=>'control-label  col-sm-4')));
		$this->autoform->wrap_each_field_only('<div class="col-sm-8">','</div>');
		$this->autoform->wrap_each('<div class="form-group form-group-sm">','</div>');
		$this->autoform->buttons('<div class="button-row"><a class="btn btn-default" href="'.site_url("/").'" role="button">Cancel</a>&nbsp; '.form_submit(array('type'=>'submit', 'value'=>'Save', 'class'=>'btn btn-primary')).'</div>');
		
		$data['form'] = $this->autoform->generate($this->config->item('change_password_base_path'), array('class'=>'form-horizontal'));
		
		if ($user['auth_endpoint'] != 'LOCAL')
		{
			# authentication is from some place else, disable the form
			$this->session->set_flashdata('ERROR', "Changing your password is disabled because your authentication endpoint is not LOCAL. Please contact your Administrator.");
			$this->autoform->set_all(array('disabled'=>'disabled'));
			$data['form'] = '<div class="text-center" style="color:#ddd;"><h1>Authentication Endpoint: '.$user['auth_endpoint'].'</h1></div>';
		}
		
		$data['user'] = $user;
		$data['title'] = "Change Password";
		$data['modules'] = $this->custom_auth->get_user_navigation();
		$data['MODULE'] = 'HOME';
		$data['SUBMODULE'] = '';
		$data['breadcrumb'] = array(array('label'=>"Home", 'url'=>site_url()), array('label'=>$data['title'], 'url'=>'#'));
		
		$this->template->title($data['title']);
		$this->template->set_partial('nav', 'core/fixed_navbar-part');
		$this->template->set_partial('messages', 'core/messages-part');
		$this->template->set_layout('logged_in_template');
		$this->template->build('core/form_body', $data);
	}
	
	
	public function check_old_password($old_password){
		$user = $this->custom_auth->get_user();
		$fd = $this->input->post();
		
		# check credentials
		$_user = $this->core_model->check_user_credentials_joomla($user['username'], $fd['old_password']);
		
		if ( ! $_user) {
			$this->form_validation->set_message('check_old_password', 'The Old Password is invalid');
			return false;
		}
		return true;
	}
	
	
	public function close_popup()
	{
		$this->load->library('parser');
		$data['title'] = "close popup";
		$this->parser->parse('core/popup_close', $data);
	}
	
	
	# Generic Form Search Method
	public function generic_form_search_json()
	{
		$results = array();
		
		# check if the user is logged in
		if ( ! $this->custom_auth->is_authenticated())
		{
			print json_encode($results);
			return;
		}
		
		$fd = $this->input->get();
		
		if (isset($fd['config']) && strlen($fd['config']) > 0)
		{
			$config = $fd['config'];
		}
		else
		{
			print json_encode($results);
			return;
		}
		
		$search_config = $this->config->item('generic_form_search');
		
		if ( ! isset($search_config[$config]))
		{
			print json_encode($results);
			return;
		}
		
		if (isset($fd['query']) && strlen(trim($fd['query'])) > 0 && isset($search_config[$config]['ignore_min_search_length']))
		{
			$search = trim($fd['query']);
		}
		elseif (isset($fd['query']) && strlen(trim($fd['query'])) >= $this->config->item('minimum_search_length'))
		{
			$search = trim($fd['query']);
		}
		else
		{
			print json_encode($results);
			return;
		}
		
		if (isset($search_config[$config]['table'])) # this is a table search
		{
			if (isset($search_config[$config]['select']))
			{
				$select = $search_config[$config]['select'];
			}
			else
			{
				$select = "*";
			}
			
			if (isset($search_config[$config]['where']))
			{
				$where = $search_config[$config]['where'];
				$where = str_replace('{search}', $search, $where);
			}
			else
			{
				$where = '';
			}
			
			if (isset($search_config[$config]['order_by']))
			{
				$order_by = $search_config[$config]['order_by'];
			}
			else
			{
				$order_by = '';
			}
			
			$values = $this->core_model->generic_form_search($select, $search_config[$config]['table'], $where, $order_by);
			
			if (isset($search_config[$config]['convert_to_drop_down_options']))
			{
				foreach ($values as &$V)
				{
					$results[$V['id']] = $V['name'];
				}
				print json_encode($results);
				return;
			}
			
			print json_encode($values);
			return;
		}
		elseif (isset($search_config[$config]['lookup'])) # this is a lookup search
		{
			if (isset($search_config[$config]['lookup_parent_group']))
			{
				$parent_grouping = $search_config[$config]['lookup_parent_group'];
				$parent_grouping = str_replace('{search}', $search, $parent_grouping);
			}
			else
			{
				$parent_grouping = null;
			}
			
			if (isset($search_config[$config]['lookup_parent_value']))
			{
				$parent_value = $search_config[$config]['lookup_parent_value'];
				$parent_value = str_replace('{search}', $search, $parent_value);
			}
			else
			{
				$parent_value = null;
			}
			
			$lookups = $this->core_model->get_select_box_options_from_lookup($search_config[$config]['lookup'], $parent_grouping, $parent_value);
			
			# check if we should return labelled values
			if (isset($search_config[$config]['convert_to_search_as_you_type_values']))
			{
				foreach ($lookups as $K => &$V)
				{
					$results[] = array('id' => $K, 'name' => $V);
				}
				print json_encode($results);
				return;
			}
				
			print json_encode($lookups);
			return;
		}
		
		print json_encode($results);
	}
	
	/*
	 * Password reset password methods
	 * 
	 * */
	
	public function forgot_password($icpau_id = null)
	{
		#$this->output->enable_profiler(TRUE);

		$fd= $this->input->post();
		
		if(isset($fd['identity_id']) and $fd['identity_id'] != '' )
		{
			
			$SQL ="select u.id from core_users u JOIN prof_user_profiles p ON u.id = p.id_core_users where u.identity_id = '{$fd['identity_id']}' or p.profile_id like '{$fd['identity_id']}'";
			
			$user_id = $this->reports_model->run_sql_query($SQL)[0]['id'];
			
			if(!empty($user_id))
			{
				redirect('core/reset_password/'.$user_id , 'location');
			}
			else
			{
				$errors = 'Invalid ICPAU ID or Reg no or Member no';
			}
		}
		
		$this->autoform->add(array('type'=>'identity_id','required'=>'required','name'=>'identity_id','label'=>'','placeholder'=>'ICPAU ID or Reg no or Member no'));
		$this->autoform->set_all(array('class'=>'form-control'));
		$this->autoform->wrap_each_field_only('<div class="col-sm-12">','</div>');
		$this->autoform->wrap_each('<div class="form-group form-group">','</div>');
		$this->autoform->buttons(form_submit(array('type'=>'submit', 'value'=>'Submit', 'class'=>'btn btn-lg btn-primary btn-block')));

		
		# prepare template
		$data['form'] = $this->autoform->generate('core/forgot_password', array('class'=>'form-horizontal'));
		$data['title'] = 'Enter your ICPAU ID or Regno or Member no';
		$data['errors'] = $errors;
		$data['messages'] = $this->custom_auth->messages();

		
		
		$this->template->title($data['title']);
		$this->template->set_layout('default_template');
		$this->template->build('core/forgot_password', $data);

	}
	
	
	public function reset_password($user_id = null)
	{
		#$this->output->enable_profiler(TRUE);
		
		$fd = $this->input->post();
		
		if(empty($user_id))
		{
			$user_id = $fd['user_id'];
		}

		if(isset($fd['id']))
		{
			#generate random 20 character string
			$password_reset_code = $this->core_model->generate_random_string(20);
		
			#update core_users sync=-1,password_reset=0,password_reset_code=random_string
			$this->core_model->update_object('core_users',$user_id,array('sync'=>-1,'password_reset'=>0,'password_reset_code'=>$password_reset_code));

			#update prof_user_contacts sync=-1,password_reset_code_sent =0
			$this->core_model->update_object('prof_user_contacts',$fd['id'],array('sync'=>-1,'password_reset_code_sent'=>0,'password_reset_code_sent'=>0));

			redirect('core/reset_link_sent/'.$fd['email'],'location');
		}
		
		#get user emails
		$SQL ="select id,value from prof_user_contacts where id_core_users ={$user_id} and contact_type like 'EMAIL' and grouping not like 'NEXT_OF_KIN%'";
		$emails= $this->reports_model->run_sql_query($SQL);
		

		foreach($emails as $k => $e)
		{
			if($e['value']=='' || is_null($e['value']))
			{
				continue;
			}
			
			$e = substr($e['value'], 0, 4).'****'.substr($e['value'], strpos($e['value'], "@"));
			
			if($k ==0)
			{
				$this->autoform->add(array('checked'=>'checked','type'=>'radio','value'=>$e['id'],'name'=>'id','label'=>$e));
			}
			else
			{
				$this->autoform->add(array('type'=>'radio','value'=>$e['id'],'name'=>'id','label'=>$e));
			}
		}
		
		#add hidden field
		$this->autoform->add(array('type'=>'hidden','name'=>'user_id','value'=>$user_id));

		#add styling to the form
		$this->autoform->set_all(array('class'=>'form-control'));
		$this->autoform->wrap_each_field_only('<div class="col-sm-2">','</div>');
		$this->autoform->wrap_each('<div class="form-group form-group-sm">','</div>');
		$this->autoform->buttons(form_submit(array('type'=>'submit', 'value'=>'Submit', 'class'=>'btn btn-lg btn-primary btn-block')));

		
		# prepare template
		$data['form'] = $this->autoform->generate('core/reset_password/', array('class'=>'form-horizontal'));
		$data['title'] = 'Select email to send password reset link';
		$data['errors'] = $errors;
		$data['messages'] = $this->custom_auth->messages();	

		$this->template->title($data['title']);
		$this->template->set_layout('default_template');
		$this->template->build('core/reset_password', $data);
	}

	public function reset_link_sent($email)
	{
		#$this->output->enable_profiler(TRUE);
		
		$data['email'] =$email;
		$data['title'] ='Reset link sent';

		$this->template->title($data['title']);
		$this->template->set_layout('default_template');
		$this->template->build('core/reset_link_sent', $data);		
	}

	public function password_reset_form($password_reset_code=null,$user_id=null)
	{
		$fd = $this->input->post();

		if(is_null($password_reset_code) && is_null($user_id))
		{
			$password_reset_code = $fd['password_reset_code'];
			$user_id = $fd['user_id'];
		}

		if(isset($fd['password']) && ! is_null($fd['password']))
		{
			#check if retype password is the same as password
			if($fd['password'] === $fd['retype_password'])
			{
				#create password hash
				$password_hash = $this->core_model->create_joomla_hash($fd['password']);
				
				#update core_users sync = -1, password = password_hash,password_reset =1
				$this->core_model->update_object('core_users',$user_id,array('sync'=>-1,'password'=>$password_hash,'password_reset'=>1));

				#update prof_user_contacts password_reset_code_sent = 1
				$this->core_model->update_object('prof_user_contacts',null,array('password_reset_code_sent'=> 1),array('id_core_users'=>$user_id));

				$this->session->set_flashdata('SUCCESS','Password was successfully reset. Login');
				redirect('core/login','location');
			}
			else
			{
				$data['errors'] ='Passwords do not match';
			}
		}

		$data['title'] = 'Enter new password';

		$user = $this->core_model->get_object('core_users',$user_id,array('password_reset_code'=>$password_reset_code,'password_reset'=>0));

		if(empty($user))
		{
			$data['errors'] ='Password reset link expired';
		}

		$this->autoform->add(array('type'=>'text','name'=>'password','label'=>'','placeholder'=>'New Password'));
		$this->autoform->add(array('type'=>'text','name'=>'retype_password','label'=>'','placeholder'=>'Retype New Password'));
		
		#add hidden fields
		$this->autoform->add(array('type'=>'hidden','name'=>'user_id','value'=>$user_id));
		$this->autoform->add(array('type'=>'hidden','name'=>'password_reset_code','value'=>$password_reset_code));

		#add styling to the form
		$this->autoform->set_all(array('class'=>'form-control'));
		$this->autoform->wrap_each_field_only('<div class="col-sm-12">','</div>');
		$this->autoform->wrap_each('<div class="form-group form-group-sm">','</div>');
		$this->autoform->buttons(form_submit(array('type'=>'submit', 'value'=>'Submit', 'class'=>'btn btn-lg btn-primary btn-block')));

		$data['user'] = $user;
		$data['form'] = $this->autoform->generate('core/password_reset_form/', array('class'=>'form-horizontal'));
		
		$this->template->title($data['title']);
		$this->template->set_layout('default_template');
		$this->template->build('core/password_reset_form', $data);
	}
	
	public function send_password_reset_email()
	{
		$this->output->enable_profiler(false);
		/*
		 * Password reset code documentation
		 * 
		 * 1. user enters icpau id/regno/member number
		 * 2. user selects email contact to send password reset code
		 * 
		 * backend processes
		 * i. core_users === update sync=-1,password_reset=0,password_reset_code =isset
		 * ii. prof_user_contacts === update sync=-1,password_reset_code_sent =0
		 * iii. Send email to user, set prof_user_contacts.password_reset_code_sent=1,sync=-1
		 * 
		 * 3. user resets password
		 * 
		 * backend processes
		 * i. core_users === update sync=-1,password_reset=1,password=new_password
		 * ii.
		 * 
		 * */
		
		
		/*
		 * 1. Get all records with password_reset=0 in core_users plus email contact in prof_user_contacts where password_reset_code_sent=0 
		 * 2. Update password_reset_code_sent=1  in prof_user_contacts for this user
		 */
		 
		 $SQL = "select u.id,first_name,other_names,surname,password_reset_code,value
				from core_users u JOIN prof_user_contacts p on u.id = p.id_core_users
				where u.password_reset = 0 and p.password_reset_code_sent = 0
		 ";
		 $user = $this->reports_model->run_sql_query($SQL);
		 
		 
		 foreach($user as $u)
		 {
			$E['id_core_users'] =1;
			$E['workflow_run_code'] = 'PASSWORD_RESET';
			$E['sender'] ='icpau@icpau.co.ug';
			$E['recipients']=$u['value'];
			$E['subject']='ICPAU Password Reset Request';
			$E['body']="Dear {$u['first_name']} {$u['other_names']} {$u['surname']}
					<br><br>
					<a href=\'icpauportal.com/index.php/core/password_reset_form/{$u['password_reset_code']}/{$u['id']}\' target=\'_blank\'>Click here to reset your password</a><br>
					<br>
					OR
					<br><br>
					Copy and paste the link below into your browser<br><br>
					icpauportal.com/index.php/core/password_reset_form/{$u['password_reset_code']}/{$u['id']}<br><br>
					".$this->config->item('html_email_sign_off');
			$E['content_type'] ="HTML";
			$E['status'] ="PENDING";
			$E['created_at'] =date('Y-m-d H:i:s');
			$E['updated_at'] =date('Y-m-d H:i:s');
			$E['sync'] =0;

			
			
			if($this->core_model->create_object('communication_email_queue',$E))
			{
				$this->core_model->update_object('prof_user_contacts',null,array('password_reset_code_sent'=>1,'sync'=>-1),array('id_core_users'=>$u['id']));
				echo "sent";
			}
			else
			{
				echo "ERROR email not sent";
			}
		 }
		
	}
}

?>
