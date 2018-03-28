<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Name:  Custom Auth Lib
*
* Version: 0.1
*
* Author: brian.muhumuza@gmail.com
*
*/

class Custom_auth
{
	/**
	 * messages
	 *
	 * @var array
	 **/
	protected $messages = array();
	
	/**
	 * errors
	 *
	 * @var array
	 **/
	protected $errors = array();
	
	/**
	 * message_start_delimiter
	 *
	 * @var string
	 **/
	public $message_start_delimiter = '';
	
	/**
	 * message_end_delimiter
	 *
	 * @var string
	 **/
	public $message_end_delimiter = '. ';
	
	/**
	 * error_start_delimiter
	 *
	 * @var string
	 **/
	public $error_start_delimiter = '';
	
	/**
	 * error_end_delimiter
	 *
	 * @var string
	 **/
	public $error_end_delimiter = '. ';
		
	/**
	 * __construct
	 *
	 * @return void
	 **/
	public function __construct()
	{
		$this->load->library('session');
		$this->load->model('core_model');
	}
	
	/**
	 * __get
	 *
	 * Enables the use of CI super-global without having to define an extra variable.
	 *
	 * I can't remember where I first saw this, so thank you if you are the original author. -Militis
	 *
	 * @access	public
	 * @param	$var
	 * @return	mixed
	 */
	public function __get($var)
	{
		return get_instance()->$var;
	}
	
	/**
	 * hash password
	 *
	 * @param	$password	string
	 * @return string
	 **/
	public function hash_password($password)
	{
		return $this->core_model->create_joomla_hash($password);
	}
	
	/**
	 * login
	 *
	 * @param	$username	string
	 * @param	$password	string
	 * @return bool
	 **/
	public function login($username, $password)
	{
		if (empty($username) || empty($password))
		{
			$this->set_error('invalid username and/or password');
			return false;
		}
		
		$_user = $this->core_model->get_user_with_username($username, true);
		
		if ( ! $_user)
		{
			$this->set_error('invalid account or account not active');
			return false;
		}
		
		if ($_user['auth_endpoint'] == 'LDAP') # LDAP authentication
		{
			require_once(APPPATH.'third_party/adLDAP/adLDAP.php');
			
			$ldap_auth_settings = $this->config->item('LDAP_auth_settings');
			
	        try
	        {
	        	$adldap = new adLDAP($ldap_auth_settings);
	        }
	        catch (adLDAPException $e)
	        {
	        	$this->set_error('error authenticating to LDAP: '.$e);
	            return false;   
	        }
			
			if ($adldap->authenticate($username, $password))
	        {
	        	$user = $_user;
	        }
	        else
	        {
	        	$this->set_error('invalid username and/or password');
	        	return false;
	        }
		}
		else # Local Authentication
		{
			$user = $this->core_model->check_user_credentials_joomla($username, $password);
		}
		
		if ( ! $user)
		{
			$this->set_error('invalid username and/or password');
			return false;
		}
		
		# store session data
		$new_session_data = array
		(
			'user_id' => $user['id'],
			'username'  => $username,
			'authenticated' => true,
			'privileges' => array('REPORTS','REPORTS_ADMIN','RUN_REPORTS'),
			'navigation' => array(
					array('label'=>'Attendance', 'code'=>'ATTENDANCE', 'url'=>'/administration/attendance/', 'submodules'=>null),
					array('label'=>'Barcode activities', 'code'=>'BARCODE', 'url'=>'/administration/barcode_activities_list/', 'submodules'=>null),
					array('label'=>'Reports', 'code'=>'REPORTS', 'url'=>'/reports/', 'submodules'=>null),
			), # todo
			'profiles' => array(),
			'user' => $user,
		);
		
		$this->session->set_userdata($new_session_data);
		$this->core_model->update_last_login($user['id']);
				
		return true;
	}
	
	
	/**
	 * logout
	 *
	 * @return void
	 **/
	public function logout()
	{
        $this->session->unset_userdata(array('user_id', 'username', 'authenticated', 'privileges', 'navigation', 'profiles', 'user'));

		// Destroy the session
		$this->session->sess_destroy();

		$this->session->sess_regenerate(TRUE);

		$this->set_message('logout_successful');
		return TRUE;
	}

	/**
	 * is_authenticated
	 *
	 * @return bool
	 **/
	public function is_authenticated()
	{
		$auth = $this->session->userdata('authenticated');
		
		if ($auth)
		{
			return true;
		}
		
		return false;
	}

	/**
	 * get_user_id
	 *
	 * @return integer
	 **/
	public function get_user_id()
	{
		$user_id = $this->session->userdata('user_id');
		if ( ! empty($user_id))
		{
			return $user_id;
		}
		return null;
	}

	/**
	 * get user
	 *
	 * @return array
	 **/
	public function get_user()
	{
		$user = $this->session->userdata('user');
		if ( ! empty($user))
		{
			return $user;
		}
		return null;
	}
	
	
	/**
	 * has privilege
	 *
	 * @param	$privilege	string
	 * @return array
	 **/
	public function has_privilege($privilege)
	{
		if (isset($_SESSION['privileges']) && in_array($privilege, $_SESSION['privileges']))
		{
			return true;
		}
		return false;
	}
	
	
	/**
	 * has profile
	 *
	 * @param	$profile	string
	 * @return array
	 **/
	public function has_profile($profile)
	{
		if (isset($_SESSION['profiles']) && in_array($profile, $_SESSION['profiles']))
		{
			return true;
		}
		return false;
	}
	
	
	/**
	 * get_user_navigation
	 *
	 * @return array
	 **/
	public function get_user_navigation()
	{
		$navigation = $this->session->userdata('navigation');
		if (empty($navigation))
		{
			return array();
		}
		return $navigation;
	}
	
	
	/**
	 * get_username
	 *
	 * @return string
	 **/
	public function get_username()
	{
		$user_id = $this->session->userdata('username');
		if ( ! empty($user_id))
		{
			return $user_id;
		}
		return null;
	}
	

	/**
	 * set_message
	 *
	 * Set a message
	 *
	 * @return boolean
	 **/
	public function set_message($message)
	{
		$this->messages[] = $message;

		return true;
	}
	
	
	/**
	 * messages
	 *
	 * Get the messages
	 *
	 * @return string
	 **/
	public function messages()
	{
		$_output = '';
		foreach ($this->messages as $message)
		{
			$_output .= $this->message_start_delimiter . $message . $this->message_end_delimiter;
		}
	
		return $_output;
	}
	
	/**
	 * messages as array
	 *
	 * @return array
	 **/
	public function messages_array()
	{
		return $this->messages;
	}
	
	
	/**
	 * clear_messages
	 *
	 * @return boolean
	 **/
	public function clear_messages()
	{
		$this->messages = array();
	
		return true;
	}
	
	
	/**
	 * set_error
	 *
	 * @return boolean
	 **/
	public function set_error($error)
	{
		$this->errors[] = $error;
	
		return true;
	}
	
	/**
	 * errors
	 *
	 * Get the error message
	 *
	 * @return void
	 **/
	public function errors()
	{
		$_output = '';
		foreach ($this->errors as $error)
		{
			$_output .= $this->error_start_delimiter . $error . $this->error_end_delimiter;
		}
	
		return $_output;
	}
	
	/**
	 * errors as array
	 *
	 * @return array
	 **/
	public function errors_array()
	{
		return $this->errors;
	}
	
	
	/**
	 * clear_errors
	 *
	 * @return boolean
	 **/
	public function clear_errors()
	{
		$this->errors = array();
	
		return true;
	}
	
}
