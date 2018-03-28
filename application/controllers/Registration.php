<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Registration extends CI_Controller {

	public function __construct() {
		parent::__construct();
		
		$this->load->helper('captcha');
		$this->load->helper('text');
		
		/*
		|--------------------------------------------------------------------------
		| generic_form_search
		|--------------------------------------------------------------------------
		|
		| options for the generic form search
		|
		| possibilities:
		|
		|	'SEARCH_OBJECT_BY_SOME_CRITERIA' => array
		|	(
		|		'table' => '<table name>',
		|		'select' => '<(optional) SQL select clause: we should always have id and name>',
		|		'where' => '<(optional) SQL where clause: use {search} where the search string should be placed>',
		|		'order_by' => '<(optional) SQL order by clause>',
		|		'convert_to_drop_down_options' => '<(optional) removes the key:id and value:name labels>',
		|		'ignore_min_search_length' => '<(optional) ignore minimum search length>',
		|	),
		|	'SEARCH_LOOKUPS' => array
		|	(
		|		'lookup' => '<lookup group to filter -- must be present but can be null>',
		|		'lookup_parent_group' => '<(optional) lookup parent group to filter>',
		|		'lookup_parent_value' => '<(optional) lookup parent value to filter>',
		|		'convert_to_search_as_you_type_values' => '<(optional) uses key:id and value:text>',
		|		'ignore_min_search_length' => '<(optional) ignore minimum search length>',
		|	)
		*/
		
		$this->generic_form_search = array
		(
			'country' => array
			(
				'table' => 'core_countries',
				'select' => 'name AS id, name AS text',
				'where' => 'name LIKE "%{search}%"',
			),
			'country_dial_code_from_name' => array
			(
				'table' => 'core_countries',
				'select' => 'name AS id, dial_code AS text',
				'where' => 'name = "{search}"',
				'convert_to_drop_down_options' => true,
				'ignore_min_search_length' => true,
			),
			'org_search' => array
			(
				'table' => '(SELECT DISTINCT emp_organisation FROM core_registrations) A',
				'select' => 'A.emp_organisation AS id, A.emp_organisation AS text',
				'where' => 'A.emp_organisation LIKE "%{search}%"',
			),
			'org_industry_search' => array
			(
				'table' => 'core_lookups',
				'select' => 'value AS id, label AS text',
				'where' => 'grouping="ORG_INDUSTRIES" AND value LIKE "%{search}%" UNION SELECT emp_industry AS id, emp_industry AS text FROM core_registrations WHERE emp_industry LIKE "%{search}%"',
			),
			'job_title_search' => array
			(
				'table' => '(SELECT DISTINCT emp_job_title FROM core_registrations) A',
				'select' => 'A.emp_job_title AS id, A.emp_job_title AS text',
				'where' => 'A.emp_job_title LIKE "%{search}%"',
			),
			'acc_body_search' => array
			(
				'table' => 'core_lookups',
				'select' => 'value AS id, label AS text',
				'where' => 'grouping="ACCOUNTANCY_BODIES" AND value LIKE "%{search}%" UNION SELECT acc_body AS id, acc_body AS text FROM core_registrations WHERE acc_body LIKE "%{search}%"',
			),
			'insurance_body_search' => array
			(
				'table' => '(SELECT DISTINCT insurance_body FROM core_registrations) A',
				'select' => 'A.insurance_body AS id, A.insurance_body AS text',
				'where' => 'A.insurance_body LIKE "%{search}%"',
			),
		);
		
		$this->member_information = null;
	}

	
	private function disable_browser_cache()
	{
		$this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
		$this->output->set_header('Pragma: no-cache');
		$this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
	}
	
	
	private function generate_captcha()
	{
		$captcha_settings = array(
			'pool'          => '23456789abcdefghkmnprstuvwxyzABCDEFGHJKLMNPRSTUVWXYZ',
			'img_path'      => './media/CAPTCHA/',
			'img_url'       => base_url('/media/CAPTCHA/'),
			'img_id'        => 'id_captcha',
			'colors'		=> array(
				'background' => array(255, 255, 255),
				'border'	=> array(1, 57, 113),
				'text'		=> array(1, 57, 113),
				'grid'		=> array(0, 99, 200),
			),
		);
		
		$cap = create_captcha($captcha_settings);
		
		$this->core_model->create_object('core_captcha', $this->core_model->null_blank_entries(
			array(
				'captcha_time' => $cap['time'],
		        'ip_address' => $this->input->ip_address(),
		        'word' => $cap['word']
			)
		));
		return $cap['image'];
	}
	
	
	public function validate_captcha($value)
	{
		if (empty($value))
		{
			return false;
		}
		# First, delete old captchas
		$expiration = time() - 7200; # 2 hours
		$this->core_model->delete_many_objects('core_captcha', array('captcha_time < ' => $expiration));
		
		# get the captcha
		$cap = $this->core_model->get_object('core_captcha', null, array('word' => $value, 'captcha_time > ' => $expiration, 'ip_address' => $this->input->ip_address()));
		
		if ($cap)
		{
			return true;
		}
		return false;
	}
	
	
	private function get_member_information($member_number)
	{
		//  Initiate curl
		$ch = curl_init();
		// Disable SSL verification
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		// Will return the response, if false it print the response
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		// Set the url
		curl_setopt($ch, CURLOPT_URL, 'https://icpauportal.com/index.php/api/people/member/?member_id='.$member_number);
#		curl_setopt($ch, CURLOPT_URL, 'http://localhost/icpau_admin/index.php/api/people/member/?member_id='.$member_number);
		// set headers
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json","X-API-KEY: Gv5jVCL5YhycsNEeJ0eectHXY6tQSnBpHxxtZ7oO"));
		// Execute
		$result = curl_exec($ch);
		// Closing
		curl_close($ch);
		
		$this->member_information = json_decode($result, true);
		
		// obfuscate contact information
		if (isset($this->member_information['email']))
		{
			$this->member_information['obfuscated_email'] = obfuscate_email($this->member_information['email']);
		}
		
		if (isset($this->member_information['telephone']))
		{
			$this->member_information['obfuscated_telephone'] = obfuscate_text($this->member_information['telephone']);
		}
	}
	
	
	public function validate_member_number($value)
	{
		if (empty($value))
		{
			return false;
		}
		
		// check if member has already registered for this event
		if ($this->core_model->count_objects('core_registrations', array('member_number' => $value)) > 0)
		{
			$this->form_validation->set_message('validate_member_number', 'A member with this member number has already registered for this event.');
			return false;
		}

		// check if member number is valid
		$this->get_member_information($value);
		
		if (isset($this->member_information['error']))
		{
			$this->form_validation->set_message('validate_member_number', 'The member number entered is invalid');
			return false;
		}
		
		return true;
	}
	
	
	public function index()
	{
		$this->output->enable_profiler(false);
		
		$fd = $this->input->post();
		
		# create validation rules for form data
		$form_validation_rules = array
		(
			array
			(
				'field'   => 'guidelines_seen',
				'rules'   => 'required',
				'errors' => array(
					'required' => 'please confirm you have read and understood the guidelines',
				),
			),
/*			array
			(
				'field'   => 'captcha',
				'rules'   => 'callback_validate_captcha'
			),
*/		);
		
		// check if member number has been filled & add to validation rules
		if (isset($fd['icpau_member_option']) && $fd['icpau_member_option'] == 'YES')
		{
			if (isset($fd['member_number']) && ! empty($fd['member_number']))
			{
				$form_validation_rules[] = array(
					'field'   => 'member_number',
					'rules'   => 'callback_validate_member_number'
				);
			}
			else
			{
				$form_validation_rules[] = array(
					'field'   => 'member_number',
					'rules'   => 'required',
					'errors' => array(
							'required' => 'please enter your ICPAU Member or Student Number',
					),
				);
			}
		}
		
		$this->form_validation->set_rules($form_validation_rules);
		
		if ($this->form_validation->run() == true)
		{
			$fd = $this->input->post();
			
			$entry_id = $this->core_model->create_object('event_id_series', array('ref' => date('Y-m-d H:i:s')));
			$registration_code = 'ECNFM'.date('dm').str_pad($entry_id, 4, '0', STR_PAD_LEFT);
			
			if ($this->member_information)
			{
				return $this->register($registration_code, $fd['member_number']);
			}
			
			return $this->register($registration_code);
		}
		
		$data['title'] = "ICPAU Event Registration - Registration";
		$data['page'] = "HOME";

#		$data['CAPTCHA'] = $this->generate_captcha();

		$custom_js_bottom = "<script type=\"text/javascript\">
		$(\"input[name='icpau_member_option']\").change(function()
		{
		    if ($(this).val() == 'YES') {
		        // show icpau_member_block div
		    	$('#icpau_member_block').css('display', 'block');
		    } else {
		        // hide insurance div
		    	$('#icpau_member_block').css('display', 'none');
		    }
		
			if ($(this).val() == 'NO') {
				// show the warning message
		    	$('#non_icpau_member_block').css('display', 'block');
			} else {
		        // hide warning message
		    	$('#non_icpau_member_block').css('display', 'none');
		    }
		});
		</script>";
		
		$this->template->inject_partial('custom_js_bottom', $custom_js_bottom);
		
		$this->template->title($data['title']);
		$this->template->build('registration/index', $data);
	}
	
	
	public function terms()
	{
		$data['title'] = "ICPAU Event Registration - Terms and Conditions";
		$data['page'] = "HOME";
					
		$this->template->title($data['title']);
		$this->template->build('registration/terms_and_conditions', $data);
	}
	
	
	public function register($registration_code=null, $member_number=null)
	{
		$this->output->enable_profiler(false);
		
		# disable browser caching
		$this->disable_browser_cache();
		
		$fd = $this->input->post();
		$data['fd'] = $fd;
		
		if (isset($fd['registration_code']) && ! empty($fd['registration_code']))
		{
			$registration_code = $fd['registration_code'];
		}
		
		if ( ! $registration_code)
		{
			redirect('/', 'location');
		}
		
		// check if we are registering a member
		if (isset($fd['member_number']) && ! empty($fd['member_number']))
		{
			$member_number = $fd['member_number'];
		}
		
		if ($member_number)
		{
			// get member information
			if ( ! $this->member_information)
			{
				$this->get_member_information($member_number);
			}
			
			if ( ! isset($this->member_information['error']))
			{
				$data['member_information'] = $this->member_information;
			}
		}
		
		# create validation rules for form data
		$form_validation_rules = array
		(
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
					'field'   => 'dob',
					'rules'   => 'required|callback_valid_date[Y-m-d]'
			),
			array
			(
					'field'   => 'gender',
					'rules'   => 'required'
			),
			array
			(
				'field'   => 'nationality',
				'rules'   => 'required'
			),
			array
			(
				'field'   => 'email',
				'rules'   => 'required|valid_email|is_unique[core_registrations.email]',
				'errors' => array(
					'is_unique' => 'This email address has already been registered.',
				),
			),
			array
			(
				'field'   => 'telephone',
				'rules'   => 'required'
			),
			array
			(
				'field'   => 'emp_organisation',
				'rules'   => 'required'
			),
			array
			(
				'field'   => 'emp_job_title',
				'rules'   => 'required'
			),
		);
		
		if (isset($fd['nok_name']) && ! empty($fd['nok_name']))
		{
			$form_validation_rules[] = array('field' => 'nok_country', 'rules' => 'required');
			$form_validation_rules[] = array('field' => 'nok_telephone', 'rules' => 'required');
		}
		
		if (isset($fd['nok_email']) && ! empty($fd['nok_email']))
		{
			$form_validation_rules[] = array('field' => 'nok_email', 'rules' => 'valid_email');
		}
		
		if (isset($fd['emp_email']) && ! empty($fd['emp_email']))
		{
			$form_validation_rules[] = array('field' => 'emp_email', 'rules' => 'valid_email');
		}
		
		if (isset($fd['acc_body_member']) && $fd['acc_body_member'] == 'YES')
		{
			$form_validation_rules[] = array('field' => 'acc_body[]', 'rules' => 'required');
		}
		
		if (isset($fd['insurance']) && $fd['insurance'] == 'YES')
		{
			$form_validation_rules[] = array('field' => 'insurance_body', 'rules' => 'required');
		}
		
		if (isset($fd['insurance']) && $fd['insurance'] == 'NO')
		{
			$fd['insurance_body'] = '';
		}
		
		if (isset($fd['travel_arrival_date']) && ! empty($fd['travel_arrival_date']))
		{
			$form_validation_rules[] = array('field' => 'travel_arrival_date', 'rules' => 'callback_valid_date[Y-m-d H:i]');
		}
		
		if (isset($fd['travel_departure_date']) && ! empty($fd['travel_departure_date']))
		{
			$form_validation_rules[] = array('field' => 'travel_departure_date', 'rules' => 'callback_valid_date[Y-m-d H:i]');
		}
		
		$ERRORS = 0;
		$accompanying_persons = null;
		
		if (isset($fd['nationality']) && $fd['nationality'] == $this->config->item('local_country'))
		{
			if ( ! isset($data['member_information']))
			{
				$form_validation_rules[] = array('field' => 'national_id', 
					'rules' => 'required|is_unique[core_registrations.national_id]',
					'errors' => array('is_unique' => 'This national id is already registered.')
				);
			}
		}
		else
		{
			$form_validation_rules[] = array(
				'field' => 'passport_no', 
				'rules' => 'required|is_unique[core_registrations.passport_no]',
				'errors' => array('is_unique' => 'This Passport number is already registered.')
			);
			$form_validation_rules[] = array('field' => 'passport_issue_place', 'rules' => 'required');
			$form_validation_rules[] = array('field' => 'passport_issue_date', 'rules' => 'required|callback_valid_date[Y-m-d]');
			$form_validation_rules[] = array('field' => 'passport_expiry_date', 'rules' => 'required|callback_valid_date[Y-m-d]');
		}
		
		# validate accompanying persons
		$accompanying_persons = array();
		
		if (isset($fd['accomp_name']))
		{
			for ($i = 0; $i < count($fd['accomp_name']); $i++)
			{
				if (empty($fd['accomp_name'][$i]))
				{
					continue;
				}
				
				$accomp_person = array
				(
					'name' => (isset($fd['accomp_name'][$i]) ? $fd['accomp_name'][$i] : ''),
					'dob' => (isset($fd['accomp_dob'][$i]) ? $fd['accomp_dob'][$i] : ''),
					'gender' => (isset($fd['accomp_gender'][$i]) ? $fd['accomp_gender'][$i] : ''),
					'national_id' => '',
					'passport_no' => '',
					'passport_issue_place' => '',
					'passport_issue_date' => '',
					'passport_expiry_date' => '',
					'ERRORS' => array('missing' => array(), 'invalid' => array()),
				);
				
				foreach (array('name','dob','gender') as $MF)
				{
					if (empty($accomp_person[$MF]))
					{
						$accomp_person['ERRORS']['missing'][] = $MF;
						$ERRORS += 1;
					}
				}
				
				if ($fd['nationality'] == $this->config->item('local_country'))
				{
					$accomp_person['national_id'] = (isset($fd['accomp_national_id'][$i]) ? $fd['accomp_national_id'][$i] : '');
					
					if (empty($accomp_person['national_id']))
					{
						$accomp_person['ERRORS']['missing'][] = 'national_id';
						$ERRORS += 1;
					}
				}
				else
				{
					$accomp_person['passport_no'] = (isset($fd['accomp_passport_no'][$i]) ? $fd['accomp_passport_no'][$i] : '');
					$accomp_person['passport_issue_place'] = (isset($fd['accomp_passport_issue_place'][$i]) ? $fd['accomp_passport_issue_place'][$i] : '');
					$accomp_person['passport_issue_date'] = (isset($fd['accomp_passport_issue_date'][$i]) ? $fd['accomp_passport_issue_date'][$i] : '');
					$accomp_person['passport_expiry_date'] = (isset($fd['accomp_passport_expiry_date'][$i]) ? $fd['accomp_passport_expiry_date'][$i] : '');
					
					# check for missing information
					foreach (array('passport_no','passport_issue_place','passport_issue_date','passport_expiry_date') as $MF)
					{
						if (empty($accomp_person[$MF]))
						{
							$accomp_person['ERRORS']['missing'][] = $MF;
							$ERRORS += 1;
						}
						elseif ($accomp_person[$MF] == '0000-00-00')
						{
							$accomp_person['ERRORS']['missing'][] = $MF;
							$ERRORS += 1;
						}
					}
					
					# check for invalid pasport related information
					# passport issue date
					if (isset($accomp_person['passport_issue_date']) && ! in_array('passport_issue_date', $accomp_person['ERRORS']['missing']))
					{
						if ( ! $this->valid_date($accomp_person['passport_issue_date']))
						{
							$accomp_person['ERRORS']['invalid'][] = 'passport_issue_date';
						}
					}
					# passport expiry date
					if (isset($accomp_person['passport_expiry_date']) && ! in_array('passport_expiry_date', $accomp_person['ERRORS']['missing']))
					{
						if ( ! $this->valid_date($accomp_person['passport_expiry_date']))
						{
							$accomp_person['ERRORS']['invalid'][] = 'passport_expiry_date';
						}
					}
				}
				
				# check for invalid information
				# DOB
				if (isset($accomp_person['dob']) && ! in_array('dob', $accomp_person['ERRORS']['missing']))
				{
					if ( ! $this->valid_date($accomp_person['dob']))
					{
						$accomp_person['ERRORS']['invalid'][] = 'dob';
					}
				}
				# travel arrival date
				if (isset($accomp_person['travel_arrival_date']) && ! empty($accomp_person['travel_arrival_date']) && ! $this->valid_date($accomp_person['travel_arrival_date'], 'Y-m-d H:i'))
				{
					$accomp_person['ERRORS']['invalid'][] = 'travel_arrival_date';
				}
				# travel departure date
				if (isset($accomp_person['travel_departure_date']) && ! empty($accomp_person['travel_departure_date']) && ! $this->valid_date($accomp_person['travel_departure_date'], 'Y-m-d H:i'))
				{
					$accomp_person['ERRORS']['invalid'][] = 'travel_departure_date';
				}
				
				$accompanying_persons[] = $accomp_person;
			}
		}
		
		$this->form_validation->set_message('required', 'This field is required.');
		$this->form_validation->set_message('valid_email', 'This field should contain a valid email address.');
		
		$this->form_validation->set_rules($form_validation_rules);
		$this->form_validation->set_error_delimiters('','');
		
		if (isset($fd['register']) && $this->form_validation->run() == true && $ERRORS == 0)
		{
			$fd = $this->input->post();
			
			# check if this information has already been saved
			if ($this->core_model->count_objects('core_registrations', array('registration_code'=>$registration_code)) > 0)
			{
				return $this->payment_options($registration_code);
			}
			
			// get un-obfuscated contact info
			if ($member_number && ! isset($this->member_information['error']))
			{
				if (isset($fd['telephone']) && $fd['telephone'] == $this->member_information['obfuscated_telephone'])
				{
					$fd['telephone'] = $this->member_information['telephone'];
				}
		
				if (isset($fd['email']) && $fd['email'] == $this->member_information['obfuscated_email'])
				{
					$fd['email'] = $this->member_information['email'];
				}
			}
			
			# remove unwanted values
			unset($fd['register']);
			unset($fd['accomp_name']);
			unset($fd['accomp_dob']);
			unset($fd['accomp_gender']);
			unset($fd['accomp_national_id']);
			unset($fd['accomp_passport_no']);
			unset($fd['accomp_passport_issue_place']);
			unset($fd['accomp_passport_issue_date']);
			unset($fd['accomp_passport_expiry_date']);
			unset($fd['captcha']);
			
			# determine the payment rate category
			$rate_category = 'INTERNATIONAL';
			
			if (in_array($fd['nationality'], $this->config->item('local_rate_countries')))
			{
				$rate_category = 'LOCAL';
			}
			elseif (isset($fd['acc_body_member']) && $fd['acc_body_member'] == 'YES')
			{
				foreach ($fd['acc_body'] as &$A)
				{
					if (in_array($A, $this->config->item('local_rate_accountancy_bodies')))
					{
						$rate_category = 'LOCAL';
					}
				}
			}
			$fd['rate_category'] = $rate_category;
			
			# set other values
			$fd['update_registration_code'] = $this->core_model->generate_random_string(20, '01233456789');
			$fd['created_at'] = date('Y-m-d H:i');
			$fd['updated_at'] = date('Y-m-d H:i');
			
			# convert accountancy bodies value from array to string
			if (isset($fd['acc_body_member']) && $fd['acc_body_member'] == 'YES')
			{
				$fd['acc_body'] = implode(', ', $fd['acc_body']);
			}
			else
			{
				$fd['acc_body'] = '';
			}
			
			# force names into title case
			$fd['first_name'] = ucwords(strtolower($fd['first_name']));
			$fd['last_name'] = ucwords(strtolower($fd['last_name']));
			$fd['other_names'] = ucwords(strtolower($fd['other_names']));
			
			# save registration
			$entry_id = $this->core_model->create_object('core_registrations', $this->core_model->null_blank_entries($fd));
			
			# save accompanying persons
			if ($entry_id)
			{
				foreach ($accompanying_persons as &$AP)
				{
					$name_split = explode(' ', $AP['name']);
					$AP['first_name'] = $name_split[0];
					if (count($name_split) > 1)
					{
						$AP['last_name'] = $name_split[1];
					}
					if (count($name_split) > 2)
					{
						unset($name_split[0]);
						unset($name_split[1]);
						$AP['other_names'] = implode(' ', $name_split);
					}
					unset($AP['name']);
					unset($AP['ERRORS']);
					$AP['parent_registration_code'] = $registration_code;
					$AP['created_at'] = date('Y-m-d H:i');
					$AP['updated_at'] = date('Y-m-d H:i');
					
					$event_id = $this->core_model->create_object('event_id_series', array('ref' => date('Y-m-d H:i:s')));
					$AP['registration_code'] = 'ECNFM'.date('dm').str_pad($event_id, 4, '0', STR_PAD_LEFT);
					
					$this->core_model->create_object('core_registrations', $this->core_model->null_blank_entries($AP));
				}
			}
			
			return $this->payment_options($registration_code, $fd['update_registration_code']);
		}
		
		if (isset($fd['register']))
		{
			$ERRORS += 1;
		}
		
		$data['title'] = "ICPAU Event Registration";
		$data['registration_code'] = $registration_code;
		$data['accompanying_persons'] = $accompanying_persons;
		$data['ERRORS'] = $ERRORS;
		
		$data['CAPTCHA'] = $this->generate_captcha();
		
		$custom_css_top = '<link rel="stylesheet" href="'.base_url('/media/static/daterangepicker/daterangepicker.css').'">'; # date range picker
		
		$custom_js_bottom = '<script src="'.base_url('/media/static/moment.min.js').'"></script>'; # moment
		$custom_js_bottom .= '<script src="'.base_url('/media/static/daterangepicker/daterangepicker.js').'"></script>'; # date range picker
		$custom_js_bottom .= '<script type="text/javascript">';
		$custom_js_bottom .= "$('.general_datetime_select').daterangepicker({singleDatePicker: true, showDropdowns: true, timePicker: true, timePickerIncrement: 5, timePicker12Hour: false, format: 'YYYY-MM-DD HH:mm'});";
		$custom_js_bottom .= "$('.general_date_select').daterangepicker({singleDatePicker: true, showDropdowns: true, format: 'YYYY-MM-DD'});";

		$custom_js_bottom .= "$('.country_ajax_dropdown').select2({
			allowClear: true,
			ajax: {
				url: '".site_url('registration/generic_form_search_json/?c=country')."',
				dataType: 'json',
				delay: 250,
			},
		});";
		$custom_js_bottom .= "$('.gender-dropdown').select2({
			allowClear: true, data: [{id: '', text: ''}, {id: 'FEMALE', text: 'Female'}, {id: 'MALE', text: 'Male'}],
		});";
		$custom_js_bottom .= "$('.org_ajax_search').select2({
			tags: true,
			allowClear: true,
			ajax: {
				url: '".site_url('registration/generic_form_search_json/?c=org_search')."',
				dataType: 'json',
				delay: 250,
			},
		});";
		$custom_js_bottom .= "$('.industry_ajax_search').select2({
			tags: true,
			allowClear: true,
			ajax: {
				url: '".site_url('registration/generic_form_search_json/?c=org_industry_search')."',
				dataType: 'json',
				delay: 250,
			},
		});";
		$custom_js_bottom .= "$('.job_title_ajax_search').select2({
			tags: true,
			allowClear: true,
			ajax: {
				url: '".site_url('registration/generic_form_search_json/?c=job_title_search')."',
				dataType: 'json',
				delay: 250,
			},
		});";
		$custom_js_bottom .= "$('.acc_body_ajax_search').select2({
			tags: true,
			ajax: {
				url: '".site_url('registration/generic_form_search_json/?c=acc_body_search')."',
				dataType: 'json',
				delay: 250,
			},
		});";
		$custom_js_bottom .= "$(\"input[name='acc_body_member']\").change(function()
		{
		    if ($(this).val() == 'YES') {
		        // show acc_body_block div
		    	$('#acc_body_block').css('display', 'block');
		    } else {
		        // hide acc_body_block div
		    	$('#acc_body_block').css('display', 'none');
		    }
		});";
		
		if ( ! isset($fd['acc_body_member']) || (isset($fd['acc_body_member']) && $fd['acc_body_member'] == 'NO'))
		{
			$custom_js_bottom .= "
			// hide acc_body_block div by default
    		$('#acc_body_block').css('display', 'none');";
		}
		
		$custom_js_bottom .= "$('.insurance_body_ajax_search').select2({
			tags: true,
			allowClear: true,
			ajax: {
				url: '".site_url('registration/generic_form_search_json/?c=insurance_body_search')."',
				dataType: 'json',
				delay: 250,
			},
		});";
		$custom_js_bottom .= "$(\"input[name='insurance']\").change(function()
		{
		    if ($(this).val() == 'YES') {
		        // show insurance_block div
		    	$('#insurance_block').css('display', 'block');
		    } else {
		        // hide insurance div
		    	$('#insurance_block').css('display', 'none');
		    }
				
			if ($(this).val() == 'NO') {
				// show the warning message
		    	$('#insurance_warning_block').css('display', 'block');
			} else {
		        // hide warning message
		    	$('#insurance_warning_block').css('display', 'none');
		    }
		});";
		
		if ( ! isset($fd['insurance']) || (isset($fd['insurance']) && $fd['insurance'] == 'NO'))
		{
			$custom_js_bottom .= "
			// hide insurance_block div by default
    		$('#insurance_block').css('display', 'none');";
		}
		
		$custom_js_bottom .= '</script>';
		
		$this->template->inject_partial('custom_css_top', $custom_css_top);
		$this->template->inject_partial('custom_js_bottom', $custom_js_bottom);
		
		$this->template->title($data['title']);
		$this->template->build('registration/register', $data);
	}
	
	
	public function valid_date($date, $format='Y-m-d')
	{
		$d = DateTime::createFromFormat($format, $date);
		
		//Check for valid date in given format
		if($d && $d->format($format) == $date)
		{
			return true;
		}
		else 
		{
			$this->form_validation->set_message('valid_date', 'This field should contain a valid date.');
			return false;
		}
	}
	
	
	public function valid_dual_date($dual_date, $separator=' > ', $format='Y-m-d H:i', $return_split_dates=false)
	{		
		$dual_date_split = explode($separator, $dual_date);
						
		if (count($dual_date_split) == 2 && $this->valid_date($dual_date_split[0], $format) && $this->valid_date($dual_date_split[1], $format))
		{
			# is valid
			if ($return_split_dates)
			{
				return array($dual_date_split[0], $dual_date_split[1]);
			}
			else
			{
				return true;
			}
		}
		else
		{
			if ($return_split_dates)
			{
				return null;
			}
			else
			{
				$this->form_validation->set_message('valid_dual_date', 'one/all the dates in this field are invalid');
				return false;
			}
		}
	}
	
	
	public function payment_options($registration_code=null, $update_code=null, $ext_req=null)
	{
		$fd = $this->input->post();
		
		if (isset($fd['ext_req']) && ! empty($fd['ext_req']))
		{
			$ext_req = $fd['ext_req'];
		}
		
		# get reg code from post
		if (isset($fd['registration_code']) && ! empty($fd['registration_code']))
		{
			$registration_code = $fd['registration_code'];
		}
		
		if ( ! $registration_code)
		{
			redirect('/', 'location');
		}
		
		$reg = $this->core_model->get_object('core_registrations', null, array('registration_code'=>$registration_code));
		
		if ( ! $reg)
		{
			redirect('/', 'location');
		}
		
		# get update code from post
		if (isset($fd['update_registration_code']) && ! empty($fd['update_registration_code']))
		{
			$update_code = $fd['update_registration_code'];
		}
		
		if ( ! $update_code)
		{
			redirect('/registration/complete/', 'location');
		}
		elseif ($update_code != $reg['update_registration_code'])
		{
			redirect('/registration/complete/', 'location');
		}
		
		if (isset($fd['later']))
		{
			redirect('/registration/complete/2/', 'location');
		}
		
		# get accompanying persons
		$acc_persons = $this->core_model->get_many_objects('core_registrations', array('parent_registration_code' => $reg['registration_code']));
		
		# get accompanying persons not invoiced
		$uninvoiced_acc_persons = array();
		
		foreach ($acc_persons as &$A)
		{
			if ( ! $A['invoice_code'])
			{
				$uninvoiced_acc_persons[] = $A;
			}
		}
		
		# check if we should create an invoice
		if (count($uninvoiced_acc_persons) > 0 || ! $reg['invoice_code'])
		{
			# create invoice
			$event_rates = $this->config->item('event_rates');
			
			$acc_persons_cost = 0;
			$acc_persons_desc = '';
			$acc_persons_count = count($uninvoiced_acc_persons);
			
			// only consider accompanying persons if attendant is resident
			if ($reg['hotel'] != 'MEM_NON_RESIDENT' && $reg['hotel'] != 'NON-MEM_NON_RESIDENT')
			{
				foreach ($uninvoiced_acc_persons as &$A)
				{
					$acc_persons_desc .= "<br>{$A['first_name']} {$A['last_name']} {$A['other_names']}, Reg ID: {$A['registration_code']}";
					
					if ( ! empty($reg['member_number']))
					{
						$acc_persons_cost += $event_rates['MEM_ACCOMPANYING_PERSON'];
					}
					else
					{
						$acc_persons_cost += $event_rates['NON-MEM_ACCOMPANYING_PERSON'];
					}
				}
			}
			
			$invoice_content = array(
				'event_rates' => $event_rates,
				'acc_persons_desc' => $acc_persons_desc,
				'acc_persons_count' => $acc_persons_count,
				'acc_persons_cost' => number_format($acc_persons_cost),
			);
			
			$grand_total = $acc_persons_cost;
			
			if ( ! $reg['invoice_code'])
			{
				$rate = $event_rates[$reg['hotel']];
				$cost = "{$event_rates[$reg['hotel']]}";
				$grand_total += $event_rates[$reg['hotel']];
				
				$invoice_content['reg_rate'] = number_format($rate);
				$invoice_content['reg_cost'] = number_format($cost);
			}
			
			$invoice_content['grand_total'] = number_format($grand_total);
			$invoice_content['grand_total_words'] = ucfirst(convert_number_to_words($grand_total)) . ' only';
			
			$invoice_code = $this->core_model->generate_random_string(5, '1233456789').$reg['id'];
			
			$entry_id = $this->core_model->create_object('core_invoices', array(
				'code' => $invoice_code,
				'amount' => $grand_total,
				'content' => json_encode($invoice_content),
				'created_at' => date('Y-m-d H:i:s'),
				'updated_at' => date('Y-m-d H:i:s'),
			));
			
			if ( ! $reg['invoice_code'])
			{
				# update registration
				$this->core_model->update_object('core_registrations', $reg['id'], array('invoice_code'=>$invoice_code));
			}
			
			foreach ($uninvoiced_acc_persons as &$A)
			{
				$this->core_model->update_object('core_registrations', $A['id'], array('invoice_code'=>$invoice_code));
			}
		}
		
		# create validation rules for form data
		$form_validation_rules = array
		(
			array
			(
				'field'   => 'invoice_to_pay',
				'rules'   => 'required',
				'errors' => array(
					'required' => 'please select the invoice to make payment against',
				),
			),
			array
			(
				'field'   => 'payment_option',
				'rules'   => 'required',
				'errors' => array(
					'required' => 'please select your prefered payment method',
				),
			),
		);
		
		$this->form_validation->set_rules($form_validation_rules);
		
		if ($this->form_validation->run() == true)
		{
			$fd = $this->input->post();
						
			# update the payment method
			$this->core_model->update_object('core_invoices', $fd['invoice_to_pay'], array('payment_type'=>$fd['payment_option']));
			
			# get the invoice
			$invoice_to_pay = $this->core_model->get_object('core_invoices', $fd['invoice_to_pay']);
			
			if ($fd['payment_option'] == 'CARD') # CARD payments
			{
				include_once APPPATH.'/third_party/CSCP.php';
				
				$payment_params = array(
					'profile_id' => $this->config->item('cp_profile_id'),
					'access_key' => $this->config->item('cp_access_key'),
					'transaction_uuid' => $invoice_to_pay['code'].date('YmdHis'),
					'signed_field_names' => "profile_id,access_key,transaction_uuid,signed_field_names,unsigned_field_names,signed_date_time,locale,transaction_type,reference_number,amount,currency,bill_to_email,item_1_name,item_2_name",
					'unsigned_field_names' => '',
					'signed_date_time' => gmdate("Y-m-d\TH:i:s\Z"),
					'locale' => 'en',
					'transaction_type' => 'sale',
					'reference_number' => $reg['registration_code'].'-'.$invoice_to_pay['code'],
					'amount' => $invoice_to_pay['amount'],
					'currency' => 'USD',
					'bill_to_email' => $reg['email'],
					'item_1_name' => 'Africa Congress of Accountants (ACOA) 2017 Conference',
					'item_2_name' => 'Accompanying Persons',
				);
				
				$signature = sign($payment_params);
				
				$payment_params['signature'] = $signature;
				
				$data['reg'] = $reg;
				$data['payment_params'] = $payment_params;
				$data['invoice_to_pay'] = $invoice_to_pay;
				
				$data['title'] = "ICPAU Event Registration - Card Payment";
				$data['page'] = "HOME";
					
				$this->template->title($data['title']);
				$this->template->build('registration/card_payment', $data);
				return;
			}
			elseif ($fd['payment_option'] == 'TT' || $fd['payment_option'] == 'CASH') # TT/CASH payments
			{
				# determine if we should resend the invoice
				if (isset($fd['resend_invoice_email']) && (bool) $invoice_to_pay['resend_invoice_email'] == false)
				{
					$this->core_model->update_object('core_invoices', $invoice_to_pay['id'], array('resend_invoice_email'=>true));
				}
			}
			
			if ($ext_req)
			{
				redirect("/registration/update/?reg={$registration_code}&uc={$update_code}&e=1", 'location');
			}
			else
			{
				redirect('/registration/complete/', 'location');
			}
		}
		
		# get unpaid invoices
		$all_invoice_codes = array();
		
		# get registration again
		$reg = $this->core_model->get_object('core_registrations', null, array('registration_code'=>$registration_code));
		# get accompanying persons again
		$acc_persons = $this->core_model->get_many_objects('core_registrations', array('parent_registration_code' => $reg['registration_code']));
		
		$all_invoice_codes[] = $reg['invoice_code'];
		
		foreach ($acc_persons as &$A)
		{
			if ( ! in_array($A['invoice_code'], $all_invoice_codes))
			{
				$all_invoice_codes[] = $A['invoice_code'];
			}
		}
		
		$unpaid_invoices = $this->core_model->get_many_objects('core_invoices', array('payment_made'=>false), null, null, null, array('code'=>$all_invoice_codes));
		
		# exit if user has no unpaid invoices
		if (count($unpaid_invoices) == 0 || empty($unpaid_invoices))
		{
			if ($ext_req)
			{
				redirect("/registration/update/?reg={$registration_code}&uc={$update_code}&e=1", 'location');
			}
			else
			{
				redirect('/registration/complete/', 'location');
			}
		}
		
		$data['unpaid_invoices'] = $unpaid_invoices;
		
		$data['reg'] = $reg;
		$data['ext_req'] = $ext_req;
		
		$data['title'] = "ICPAU Event Registration - Payment Options";
		$data['page'] = "HOME";
					
		$this->template->title($data['title']);
		$this->template->build('registration/payment_options', $data);
	}
	
	
	public function complete($operation=null)
	{
		$data['title'] = "ICPAU Event Registration - Registration Complete";
		$data['page'] = "HOME";
		$data['operation'] = $operation;
		
		$this->template->title($data['title']);
		$this->template->build('registration/completion', $data);
	}
	
	
	public function update()
	{
		$get_fd = $this->input->get();
		
		$registration_code = $update_code = $reg = null;
		
		# get reg code from get
		if (isset($get_fd['reg']) && ! empty($get_fd['reg']))
		{
			$registration_code = $get_fd['reg'];
		}
		
		# get update code from get
		if (isset($get_fd['uc']) && ! empty($get_fd['uc']))
		{
			$update_code = $get_fd['uc'];
		}
		
		$ext_req = null;
		
		if (isset($get_fd['e']))
		{
			$ext_req = true;
		}
		
		$fd = $this->input->post();
		
		if (isset($fd['later']))
		{
			redirect('/registration/complete/', 'location');
		}
		
		if (isset($fd['ext_req']))
		{
			$ext_req = true;
		}
		
		# get reg code from get
		if (isset($fd['reg']) && ! empty($fd['reg']))
		{
			$registration_code = $fd['reg'];
		}
		
		# get update code from get
		if (isset($fd['uc']) && ! empty($fd['uc']))
		{
			$update_code = $fd['uc'];
		}
		
		$uninvoiced_regs = array();
		$all_invoice_codes = array();
		$reg = null;
		
		if ($registration_code && $update_code)
		{
			$reg = $this->core_model->get_object('core_registrations', null, array('registration_code'=>$registration_code, 'update_registration_code'=>$update_code));

			if ( ! $reg['invoice_code'])
			{
				$uninvoiced_regs[] = $reg['id'];
			}
			elseif ( ! in_array($reg['invoice_code'], $all_invoice_codes))
			{
				$all_invoice_codes[] = $reg['invoice_code'];
			}
			
			if ($reg)
			{
				$reg['accompanying_persons'] = $this->core_model->get_many_objects('core_registrations', array('parent_registration_code'=>$reg['registration_code']));
				
				foreach ($reg['accompanying_persons'] as &$AP)
				{
					$AP['ERRORS'] = array('missing' => array(), 'invalid' => array());
					
					if ( ! $AP['invoice_code'])
					{
						$uninvoiced_regs[] = $AP['id'];
					}
					elseif ( ! in_array($AP['invoice_code'], $all_invoice_codes))
					{
						$all_invoice_codes[] = $AP['invoice_code'];
					}
				}
			}
		}
		
		if ( ! $reg)
		{
			redirect('/', 'location');
		}
		
		if (count($uninvoiced_regs) > 0)
		{
			return $this->payment_options($reg['registration_code'], $reg['update_registration_code'], $ext_req);
		}
		
		# create validation rules for form data
		$form_validation_rules = array(
/*			array
			(
				'field'   => 'captcha',
				'rules'   => 'callback_validate_captcha'
			),
*/		);
		
		if ($reg['nationality'] != $this->config->item('local_country'))
		{
			# create validation rules for form data
			if (isset($fd['travel_arrival_date']) && ! empty($fd['travel_arrival_date']))
			{
				$form_validation_rules[] = array('field' => 'travel_arrival_date', 'rules' => 'callback_valid_date[Y-m-d H:i]');
			}
			
			if (isset($fd['travel_departure_date']) && ! empty($fd['travel_departure_date']))
			{
				$form_validation_rules[] = array('field' => 'travel_departure_date', 'rules' => 'callback_valid_date[Y-m-d H:i]');
			}
		}
		
		if (isset($fd['insurance']) && $fd['insurance'] == 'YES')
		{
			$form_validation_rules[] = array('field' => 'insurance_body', 'rules' => 'required');
		}
		
		# validate accompanying persons
		$ERRORS = 0;
		$accompanying_persons = array();
		
		if (isset($fd['accomp_reg_code']))
		{
			for ($i = 0; $i < count($fd['accomp_reg_code']); $i++)
			{
				$accomp_person = array
				(
					'registration_code' => $fd['accomp_reg_code'][$i],
					'travel_from_country' => (isset($fd['accomp_travel_from_country'][$i]) ? $fd['accomp_travel_from_country'][$i] : ''),
					'travel_arrival_date' => (isset($fd['accomp_travel_arrival_date'][$i]) ? $fd['accomp_travel_arrival_date'][$i] : ''),
					'travel_departure_date' => (isset($fd['accomp_travel_departure_date'][$i]) ? $fd['accomp_travel_departure_date'][$i] : ''),
					'hotel' => (isset($fd['accomp_hotel'][$i]) ? $fd['accomp_hotel'][$i] : ''),
					'hotel_room_type' => (isset($fd['accomp_hotel_room_type'][$i]) ? $fd['accomp_hotel_room_type'][$i] : ''),
					'ERRORS' => array('missing' => array(), 'invalid' => array()),
				);
			
				# travel arrival date
				if (isset($accomp_person['travel_arrival_date']) && ! empty($accomp_person['travel_arrival_date']) && ! $this->valid_date($accomp_person['travel_arrival_date'], 'Y-m-d H:i'))
				{
					$accomp_person['ERRORS']['invalid'][] = 'travel_arrival_date';
				}
				# travel departure date
				if (isset($accomp_person['travel_departure_date']) && ! empty($accomp_person['travel_departure_date']) && ! $this->valid_date($accomp_person['travel_departure_date'], 'Y-m-d H:i'))
				{
					$accomp_person['ERRORS']['invalid'][] = 'travel_departure_date';
				}
				
				$accompanying_persons[] = $accomp_person;
			}
		}
		
		# validate NEW accompanying persons
		$new_accompanying_persons = array();
		
		if (isset($fd['new_accomp_name']))
		{
			for ($i = 0; $i < count($fd['new_accomp_name']); $i++)
			{
				if (empty($fd['new_accomp_name'][$i]))
				{
					continue;
				}
				
				$new_accomp_person = array
				(
					'name' => (isset($fd['new_accomp_name'][$i]) ? $fd['new_accomp_name'][$i] : ''),
					'dob' => (isset($fd['new_accomp_dob'][$i]) ? $fd['new_accomp_dob'][$i] : ''),
					'gender' => (isset($fd['new_accomp_gender'][$i]) ? $fd['new_accomp_gender'][$i] : ''),
					'national_id' => '',
					'passport_no' => '',
					'passport_issue_place' => '',
					'passport_issue_date' => '',
					'passport_expiry_date' => '',
					'ERRORS' => array('missing' => array(), 'invalid' => array()),
				);
				
				foreach (array('name','dob','gender') as $MF)
				{
					if (empty($new_accomp_person[$MF]))
					{
						$new_accomp_person['ERRORS']['missing'][] = $MF;
						$ERRORS += 1;
					}
				}
				
				if ($reg['nationality'] == $this->config->item('local_country'))
				{
					$new_accomp_person['national_id'] = (isset($fd['new_accomp_national_id'][$i]) ? $fd['new_accomp_national_id'][$i] : '');
					
					if (empty($new_accomp_person['national_id']))
					{
						$new_accomp_person['ERRORS']['missing'][] = 'national_id';
						$ERRORS += 1;
					}
				}
				else
				{
					$new_accomp_person['passport_no'] = (isset($fd['new_accomp_passport_no'][$i]) ? $fd['new_accomp_passport_no'][$i] : '');
					$new_accomp_person['passport_issue_place'] = (isset($fd['new_accomp_passport_issue_place'][$i]) ? $fd['new_accomp_passport_issue_place'][$i] : '');
					$new_accomp_person['passport_issue_date'] = (isset($fd['new_accomp_passport_issue_date'][$i]) ? $fd['new_accomp_passport_issue_date'][$i] : '');
					$new_accomp_person['passport_expiry_date'] = (isset($fd['new_accomp_passport_expiry_date'][$i]) ? $fd['new_accomp_passport_expiry_date'][$i] : '');
					
					# check for missing information
					foreach (array('passport_no','passport_issue_place','passport_issue_date','passport_expiry_date') as $MF)
					{
						if (empty($new_accomp_person[$MF]))
						{
							$new_accomp_person['ERRORS']['missing'][] = $MF;
							$ERRORS += 1;
						}
						elseif ($new_accomp_person[$MF] == '0000-00-00')
						{
							$new_accomp_person['ERRORS']['missing'][] = $MF;
							$ERRORS += 1;
						}
					}
					
					# check for invalid pasport related information
					# passport issue date
					if (isset($new_accomp_person['passport_issue_date']) && ! in_array('passport_issue_date', $new_accomp_person['ERRORS']['missing']))
					{
						if ( ! $this->valid_date($new_accomp_person['passport_issue_date']))
						{
							$new_accomp_person['ERRORS']['invalid'][] = 'passport_issue_date';
						}
					}
					# passport expiry date
					if (isset($new_accomp_person['passport_expiry_date']) && ! in_array('passport_expiry_date', $new_accomp_person['ERRORS']['missing']))
					{
						if ( ! $this->valid_date($new_accomp_person['passport_expiry_date']))
						{
							$new_accomp_person['ERRORS']['invalid'][] = 'passport_expiry_date';
						}
					}
				}
				
				# check for invalid information
				# DOB
				if (isset($new_accomp_person['dob']) && ! in_array('dob', $new_accomp_person['ERRORS']['missing']))
				{
					if ( ! $this->valid_date($new_accomp_person['dob']))
					{
						$new_accomp_person['ERRORS']['invalid'][] = 'dob';
					}
				}
				# travel arrival date
				if (isset($new_accomp_person['travel_arrival_date']) && ! empty($new_accomp_person['travel_arrival_date']) && ! $this->valid_date($new_accomp_person['travel_arrival_date'], 'Y-m-d H:i'))
				{
					$new_accomp_person['ERRORS']['invalid'][] = 'travel_arrival_date';
				}
				# travel departure date
				if (isset($new_accomp_person['travel_departure_date']) && ! empty($new_accomp_person['travel_departure_date']) && ! $this->valid_date($new_accomp_person['travel_departure_date'], 'Y-m-d H:i'))
				{
					$new_accomp_person['ERRORS']['invalid'][] = 'travel_departure_date';
				}
				
				$new_accompanying_persons[] = $new_accomp_person;
			}
		}
		
		$this->form_validation->set_rules($form_validation_rules);
		
		if (isset($fd['update']) && ($this->form_validation->run() == true || count($form_validation_rules) == 0) && $ERRORS == 0)
		{
			$fd = $this->input->post();
			
			# remove unwanted values
			unset($fd['update']);
			unset($fd['reg']);
			unset($fd['uc']);
			unset($fd['ext_req']);
			unset($fd['accomp_reg_code']);
			unset($fd['accomp_travel_from_country']);
			unset($fd['accomp_travel_arrival_date']);
			unset($fd['accomp_travel_departure_date']);
			unset($fd['accomp_hotel']);
			unset($fd['accomp_hotel_room_type']);
			unset($fd['captcha']);
			
			unset($fd['new_accomp_name']);
			unset($fd['new_accomp_dob']);
			unset($fd['new_accomp_gender']);
			unset($fd['new_accomp_national_id']);
			unset($fd['new_accomp_passport_no']);
			unset($fd['new_accomp_passport_issue_place']);
			unset($fd['new_accomp_passport_issue_date']);
			unset($fd['new_accomp_passport_expiry_date']);
			
			$fd['updated_at'] = date('Y-m-d H:i');
			
			$this->core_model->update_many_objects('core_registrations', $this->core_model->null_blank_entries($fd), array('registration_code'=>$registration_code, 'update_registration_code'=>$update_code));

			foreach ($accompanying_persons as &$AP)
			{
				$ap_reg_code = $AP['registration_code'];
				unset($AP['ERRORS']);
				unset($AP['registration_code']);
				$this->core_model->update_many_objects('core_registrations', $this->core_model->null_blank_entries($AP), array('registration_code'=>$ap_reg_code, 'parent_registration_code'=>$registration_code));
			}
			
			# save new accompanying persons
			foreach ($new_accompanying_persons as &$AP)
			{
				$name_split = explode(' ', $AP['name']);
				$AP['first_name'] = $name_split[0];
				if (count($name_split) > 1)
				{
					$AP['last_name'] = $name_split[1];
				}
				if (count($name_split) > 2)
				{
					unset($name_split[0]);
					unset($name_split[1]);
					$AP['other_names'] = implode(' ', $name_split);
				}
				unset($AP['name']);
				unset($AP['ERRORS']);
				$AP['parent_registration_code'] = $reg['registration_code'];
				$AP['created_at'] = date('Y-m-d H:i');
				$AP['updated_at'] = date('Y-m-d H:i');
				
				$event_id = $this->core_model->create_object('event_id_series', array('ref' => date('Y-m-d H:i:s')));
				$AP['registration_code'] = 'ECNFM'.date('dm').str_pad($event_id, 4, '0', STR_PAD_LEFT);
				
				$this->core_model->create_object('core_registrations', $this->core_model->null_blank_entries($AP));
			}
			
			if (count($new_accompanying_persons) > 0)
			{
				redirect("/registration/payment_options/{$reg['registration_code']}/{$reg['update_registration_code']}/1/", 'location');
			}
			elseif ($ext_req)
			{
				redirect('/registration/complete/1/', 'location');
			}
			else
			{
				redirect('/registration/complete/', 'location');
			}
		}
		
		$data['reg'] = $reg;
		$data['ext_req'] = $ext_req;
		$data['hotels'] =$this->core_model->get_many_objects('core_hotels'); 
		$data['unpaid_invoices'] = $this->core_model->get_many_objects('core_invoices', array('payment_made'=>false), null, null, null, array('code'=>$all_invoice_codes));
		$data['new_accompanying_persons'] = $new_accompanying_persons;
		
		$data['title'] = "ICPAU Event Registration - Payment Options";
		$data['page'] = "HOME";
		
		$data['CAPTCHA'] = $this->generate_captcha();
					
		$custom_css_top = '<link rel="stylesheet" href="'.base_url('/media/static/daterangepicker/daterangepicker.css').'">'; # date range picker
		
		$custom_js_bottom = '<script src="'.base_url('/media/static/moment.min.js').'"></script>'; # moment
		$custom_js_bottom .= '<script src="'.base_url('/media/static/daterangepicker/daterangepicker.js').'"></script>'; # date range picker
		$custom_js_bottom .= '<script type="text/javascript">';
		$custom_js_bottom .= "$('.general_datetime_select').daterangepicker({singleDatePicker: true, showDropdowns: true, timePicker: true, timePickerIncrement: 5, timePicker12Hour: false, format: 'YYYY-MM-DD HH:mm'});";
		$custom_js_bottom .= "$('.general_date_select').daterangepicker({singleDatePicker: true, showDropdowns: true, format: 'YYYY-MM-DD'});";

		$custom_js_bottom .= "$('.gender-dropdown').select2({
			allowClear: true, data: [{id: '', text: ''}, {id: 'FEMALE', text: 'Female'}, {id: 'MALE', text: 'Male'}],
		});";
		$custom_js_bottom .= "$('.country_ajax_dropdown').select2({
			allowClear: true,
			ajax: {
				url: '".site_url('registration/generic_form_search_json/?c=country')."',
				dataType: 'json',
				delay: 250,
			},
		});";
		$custom_js_bottom .= "$('.insurance_body_ajax_search').select2({
			tags: true,
			allowClear: true,
			ajax: {
				url: '".site_url('registration/generic_form_search_json/?c=insurance_body_search')."',
				dataType: 'json',
				delay: 250,
			},
		});";
		$custom_js_bottom .= "$(\"input[name='insurance']\").change(function()
		{
		    if ($(this).val() == 'YES') {
		        // show insurance_block div
		    	$('#insurance_block').css('display', 'block');
		    } else {
		        // hide insurance div
		    	$('#insurance_block').css('display', 'none');
		    }
				
			if ($(this).val() == 'NO') {
				// show the warning message
		    	$('#insurance_warning_block').css('display', 'block');
			} else {
		        // hide warning message
		    	$('#insurance_warning_block').css('display', 'none');
		    }
		});
		// hide insurance_block div by default
    	$('#insurance_block').css('display', 'none');";
		
		$custom_js_bottom .= '</script>';
		
		$this->template->inject_partial('custom_css_top', $custom_css_top);
		$this->template->inject_partial('custom_js_bottom', $custom_js_bottom);
		
		$this->template->title($data['title']);
		$this->template->build('registration/update_registration', $data);
	}
	
	
	# Generic Form Search Method
	public function generic_form_search_json()
	{
		$this->output->set_content_type('application/json', 'utf-8');
		
		$results = array();
		
		$fd = $this->input->get();
		
		if (isset($fd['c']) && strlen($fd['c']) > 0)
		{
			$config = $fd['c'];
		}
		else
		{
			print json_encode($results);
			return;
		}
		
		if ( ! isset($this->generic_form_search[$config]))
		{
			print json_encode($results);
			return;
		}
		
		if (isset($fd['q']) && strlen(trim($fd['q'])) > 0 && isset($this->generic_form_search[$config]['ignore_min_search_length']))
		{
			$search = trim($fd['q']);
		}
		elseif (isset($fd['q']) && strlen(trim($fd['q'])) >= 2)
		{
			$search = trim($fd['q']);
		}
		else
		{
			print json_encode($results);
			return;
		}
		
		if (isset($this->generic_form_search[$config]['table'])) # this is a table search
		{
			if (isset($this->generic_form_search[$config]['select']))
			{
				$select = $this->generic_form_search[$config]['select'];
			}
			else
			{
				$select = "*";
			}
			
			if (isset($this->generic_form_search[$config]['where']))
			{
				$where = $this->generic_form_search[$config]['where'];
				$where = str_replace('{search}', $search, $where);
			}
			else
			{
				$where = '';
			}
			
			if (isset($this->generic_form_search[$config]['order_by']))
			{
				$order_by = $this->generic_form_search[$config]['order_by'];
			}
			else
			{
				$order_by = '';
			}
			
			$values = $this->core_model->generic_form_search($select, $this->generic_form_search[$config]['table'], $where, $order_by);
			
			if (isset($this->generic_form_search[$config]['convert_to_drop_down_options']))
			{
				foreach ($values as &$V)
				{
					$results[$V['id']] = $V['text'];
				}
				print json_encode($results);
				return;
			}
			
			print json_encode(array('results'=>$values));
			return;
		}
		elseif (isset($this->generic_form_search[$config]['lookup'])) # this is a lookup search
		{
			if (isset($this->generic_form_search[$config]['lookup_parent_group']))
			{
				$parent_grouping = $this->generic_form_search[$config]['lookup_parent_group'];
			}
			else
			{
				$parent_grouping = null;
			}
			
			if (isset($this->generic_form_search[$config]['lookup_parent_value']))
			{
				$parent_value = $this->generic_form_search[$config]['lookup_parent_value'];
			}
			else
			{
				$parent_value = null;
			}
			
			$lookups = $this->core_model->get_select_box_options_from_lookup($this->generic_form_search[$config]['lookup'], $parent_grouping, $parent_value);
			
			# check if we should return labelled values
			if (isset($this->generic_form_search[$config]['convert_to_search_as_you_type_values']))
			{
				foreach ($lookups as $K => &$V)
				{
					$results[] = array('id' => $K, 'name' => $V);
				}
				print json_encode(array('results'=>$results));
				return;
			}
				
			print json_encode($lookups);
			return;
		}
		
		print json_encode($results);
	}


}

?>
