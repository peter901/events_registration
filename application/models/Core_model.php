<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Core_model extends CI_Model
{
	
    public function __construct()
    {
        $this->load->database();
    }
    
    /***************************************
     * Helper Methods
     ***************************************/
	public function generate_random_string($length=7, $alphabet=null)
	{
		if ( ! $alphabet)
		{
			$alphabet = 'abcdefghjklmnopqrstuvwxyzABCDEFGHJKLMNOPQRSTUVWXYZ0123456789';
		}

		$alphabet_length = strlen($alphabet);
		$random_str = '';

		for ($i=0; $i < $length; $i++) { 
			$random_str .= $alphabet[rand(0, $alphabet_length - 1)];
		}
		return $random_str;
	}

	
	public function create_directory_tree($dir_path)
	{
		if (is_dir($dir_path))
		{
			return; # dir already exists
		}
		
		mkdir($dir_path, 0755, true);
	}

	
	public function get_time_left($datetime, $units=2)
	{
		if (empty($datetime))
		{
			return '';
		}
		
		$this->load->helper('date');
		
		$time_left = timespan(time(), strtotime($datetime), $units);
		if ($time_left == '1 Second')
		{
			$time_left = '<span class="text-danger">Expired</span>';
		}
		else
		{
			$time_left = '<span class="text-success">'.$time_left.'</span>';
		}
		return $time_left;
	}
	
	
	public function shuffle_assoc_array($a)
	{
		$keys = array_keys($a);
		shuffle($keys);
		$b = array();
		foreach ($keys as $k)
		{
			$b[$k] = $a[$k];
		}
		return $b;
	}

	
	public function null_blank_entries($a)
	{
		foreach (array_keys($a) as $k)
		{
			if ($a[$k] === '')
			{
				$a[$k] = null;
			}
		}
		return $a;
	}
	
	
	public function clean_profile_ids($profile_ids, $delimiter=';')
	{
		$profile_ids = str_replace($delimiter.$delimiter, " ", $profile_ids);
		return str_replace($delimiter, "", $profile_ids);
	}
	
	
	public function get_select_sql($table, $id)
	{
		return 'SELECT * FROM '.$table.' WHERE id='.$id;
	}
	

	public function get_ordering_url($order_by_field)
	{
		$request_uri = urldecode($_SERVER['REQUEST_URI']);
		
		$url_prefix = '&';
		
		if (strpos($request_uri, '?') === false)
		{
			$url_prefix = '?';
		}
		
		$order_by_url = 'ordering=';
		
		if (strpos($request_uri, $order_by_field.' ASC') !== false)
		{
			$order_by_url .= "{$order_by_field} DESC";
		}
		else
		{
			$order_by_url .= "{$order_by_field} ASC";
		}
		
		$request_uri = preg_replace('/([?&])ordering=[^&]+(&|$)/', '$1', $request_uri);
		$request_uri = str_replace('&&', '&', $request_uri);
		return $request_uri.$url_prefix.$order_by_url;
	}
	
	
	public function get_user_photo_url($identity_id, $photo=null)
	{
		if ( ! $photo)
		{
			$query = $this->db->get_where('core_users', array('identity_id'=>$identity_id));
			if ($query->num_rows() == 0)
			{
				return '';
			}
			$user = $query->row_array();
			if (empty($user['photo']))
			{
				$prof_ids = explode(',', str_replace(';', '', str_replace(';;', ',', trim($user['username']))));
				$PHOTO_PATH = '';
				
				foreach ($prof_ids as &$PI)
				{
					if (empty($PI) || ( ! in_array(substr($PI, 0, 2), array('CP', 'AT', 'FM')) && strlen($PI) != 5))
					{
						continue;
					}
					
					foreach (array('http://192.168.2.4/~icpau/cropper/','http://192.168.2.4/mem_photos/') as $PH)
					{
						$photo_path = $PH.str_replace('/', '', strtolower($PI)).'.jpg';
						$file_headers = @get_headers($photo_path);
						
						if ($file_headers[0] != 'HTTP/1.1 404 Not Found')
						{
							$PHOTO_PATH = $photo_path;
							break;
						}
					}
					
					if ( ! empty($PHOTO_PATH))
					{
						break;
					}
				}
				return $PHOTO_PATH;
			}
			$photo = $user['photo'];
		}
		$photos_upload_config = $this->config->item('photo_upload_config');
		return base_url($photos_upload_config['upload_path'].$photo);
	}
	
	
	public function get_themed_boolean($value)
	{
		if ($value)
		{
			return '<span class="label label-success">Yes</span>';
		}
		else
		{
			return '<span class="label label-danger">No</span>';
		}
	}
	
	
    // CRUD HELPER METHODS
    
	public function count_all_objects($table)
	{
		return $this->db->count_all($table);
	}
	
	
	public function count_objects($table, $where)
	{
		$query = $this->db->get_where($table, $where);
		return $query->num_rows();
	}
	
	
	public function get_object($table, $id=null, $where=null) {
		# process where options
		if($id && is_array($where))
		{
			$where['id'] = $id;
		}
		elseif ($id && ! is_array($where))
		{
			$where = array('id' => $id);
		}
		# get the object
    	$query = $this->db->get_where($table, $where);
    	if ($query->num_rows() == 0)
    	{
    		return null;
    	}
    	return $query->row_array();
	}
	
	
	public function get_last_object($table, $column='id', $return_column_value=false)
	{
		# get the last value in the table & column
		$this->db->select_max($column);
		$query = $this->db->get($table);
		if ($query->num_rows() == 0)
    	{
    		return null;
    	}
		$last_row = $query->row_array();
		
		if ($return_column_value)
		{
			return $last_row[$column];
		}
		
		return $this->get_object($table, null, array($column => $last_row[$column]));
	}
		
	
	public function get_many_objects($table, $where=null, $order_by=null, $start_index=null, $records=null, $where_in=null, $select=null)
    {
		if ($select)
		{
			$this->db->select($select);
		}
		
    	if ($order_by)
    	{
    		$this->db->order_by($order_by);
    	}
    	
    	if ($where_in && is_array($where_in))
    	{
    		foreach ($where_in as $key=>$value)
    		{
    			$this->db->where_in($key, $value);
    		}
    	}
    	
    	if ($where && is_array($where))
    	{
    		$query = $this->db->get_where($table, $where, $records, $start_index);
    	}
    	else
    	{
    		$query = $this->db->get($table, $records, $start_index);
    	}
    	
    	return $query->result_array();
    }
	
	
	public function create_object($table, $values)
    {
    	$success = $this->db->insert($table, $values);
    	if ( ! $success)
    	{
    		return null;
    	}
    	# return ID of saved record
    	return $this->db->insert_id();
    }

    
    public function update_object($table, $id, $values, $where=null)
    {
    	unset($values['id']);
    	$this->db->where('id', $id);
    	// set other where values
    	if(is_array($where))
    	{
    		foreach ($where as $key=>$value)
    		{
    			if ($key == 'id')
    			{
    				continue;
    			}
    			$this->db->where($key, $value);
    		}
    	}
    	
    	if ($this->db->update($table, $values))
    	{
    		return true;
    	}
    	else
    	{
    		return false;
    	}
        // set sync flag if table has sync field & field is not 0
    	$table_fields = $this->db->list_fields($table);
    	if ($table_fields && in_array('sync', $table_fields))
    	{
    		$this->db->where('sync !=', 0);
    		$this->db->update($table, array('sync' => '-1'));
    	}
    }
	
    
    public function update_many_objects($table, $values, $where=null, $where_in=null)
    {
    	// set where values
    	if($where && is_array($where))
    	{
    		foreach ($where as $key=>$value)
    		{
    			$this->db->where($key, $value);
    		}
    	}
    	// set where in values
        if ($where_in && is_array($where_in))
    	{
    		foreach ($where_in as $key=>$value)
    		{
    			$this->db->where_in($key, $value);
    		}
    	}

        $this->db->update($table, $values);
        // set sync flag if table has sync field & field is not 0
    	$table_fields = $this->db->list_fields($table);
    	if ($table_fields && in_array('sync', $table_fields))
    	{
    		$this->db->where('sync !=', 0);
    		$this->db->update($table, array('sync' => '-1'));
    	}
    }
    
    
    public function delete_object($table, $id, $where=null) {
		# process where options
		if( ! is_array($where))
		{
			$where = array('id' => $id);
		}
		else
		{
			$where['id'] = $id;
		}
		# delete object
		$this->db->delete($table, $where);
		return true;
	}
	
	public function delete_many_objects($table, $where=null) {
		# process where options
		if ( ! is_array($where))
		{
			return false;
		}
		if (count($where) == 0)
		{
			return false;
		}
		# delete objects
		$this->db->delete($table, $where);
		return true;
	}
	
	// END CRUD HELPER METHODS
	
	
	public function generic_form_search($select, $from, $where='', $order_by='')
	{
		$SQL = "SELECT {$select} FROM {$from}";
		if ( ! empty($where))
		{
			$SQL .= " WHERE {$where}";
		}
		if ( ! empty($order_by))
		{
			$SQL .= " ORDER BY {$order_by}";
		}
		$SQL .= " LIMIT 100";
		
		$query = $this->db->query($SQL);
		return $query->result_array();
	}
	
	/**
	 * find_duplicates_in_table
	 * 
	 * find duplicate fields in a table
	 *
	 * @param	$table				string
	 * @param	$compare_fields		array	i.e array('table field' => 'value')
	 * @param	$cutoff_percent		integer
	 * @return void
	 **/
    public function find_duplicates_in_table($table, $compare_fields, $cutoff_percent,$skip_id)
    {
    	$sql_concat_fields = '';
    	$compare_string = '';
    	$compare_fields_length = count($compare_fields);
    	$counter = 0;
    	
    	foreach ($compare_fields as $key=>$value)
    	{
    		$counter += 1;
    		if ($compare_fields_length == $counter)
    		{
    			# this is the last value in the array
    			$sql_concat_fields .= "COALESCE(".$key.",'')"; # we use COALESCE to prevent returning null if one of the concated fields is null
    		
    		}
    		else
    		{
    			$sql_concat_fields .= "COALESCE(".$key.",''),"; # we use COALESCE to prevent returning null if one of the concated fields is null
    		}
    		
    		$compare_string .= strtolower($value);
    	}
    	$SQL = "SELECT *, CONCAT(".$sql_concat_fields.") AS compare FROM ".$table." where id NOT IN(".$skip_id.")";
    	$query = $this->db->query($SQL);
    	$duplicates = array();
    	
    	foreach ($query->result_array() as $R)
    	{
    		$similarity_percent = 0;
    		similar_text($compare_string, strtolower($R['compare']), $similarity_percent);
    		if ($similarity_percent >= $cutoff_percent)
    		{
    			$R['similarity'] = $similarity_percent;
    			$duplicates[] = $R;
    		}
    	}
    	
    	# sort duplicates by similarity %
    	usort($duplicates, function($a, $b) {
    		return $b['similarity'] - $a['similarity'];
    	});
    	return $duplicates;
    }
	
	
	/*************************************************
	 * Authentication & Authorisation  Methods
	 *************************************************/
	
	public function create_joomla_hash($password)
	{
		// Use PHPass's portable hashes with a cost of 10.
		require_once(APPPATH.'third_party/PasswordHash.php');
		$phpass = new PasswordHash(10, true);
		$new_hash = $phpass->HashPassword($password);
	
		return $new_hash;
	}
	
	
	public function get_user_with_username($username, $is_active=null)
	{
		$SQL = "SELECT * FROM core_users WHERE identity_id=? OR username=? OR username LIKE '%;".$this->db->escape_like_str($username).";%'";
		$query = $this->db->query($SQL, array($username, $username));
		if ($query->num_rows() == 0)
		{
			return null;
		}
		$user = $query->row_array();
		if ($is_active == null)
		{
			return $user;
		}
		
		if ($is_active == true && $user['is_active'] == true) {
			return $user;
		}
		elseif ($is_active == false && $user['is_active'] == false)
		{
			return $user;
		}
		return null;
	}


	public function get_user_with_identity_id($identity_id, $is_active=null)
	{
		$query = $this->db->get_where('core_users', array('identity_id' => $identity_id));
		if ($query->num_rows() == 0)
		{
			return null;
		}
		
		$user = $query->row_array();
		if ($is_active == null) {
			return $user;
		}
		
		if ($is_active == true && $user['is_active'] == true)
		{
			return $user;
		}
		elseif ($is_active == false && $user['is_active'] == false)
		{
			return $user;
		}
		return null;
	}

	
	public function get_user_with_profile_id($profile_id, $is_active=null)
	{
		$SQL = "SELECT * FROM core_users WHERE profile_ids LIKE '%;".$this->db->escape_like_str($profile_id).";%'";
		$query = $this->db->query($SQL);
		if ($query->num_rows() == 0)
		{
			return null;
		}
		$user = $query->row_array();
		if ($is_active == null) {
			return $user;
		}
		
		if ($is_active == true && $user['is_active'] == true)
		{
			return $user;
		}
		elseif ($is_active == false && $user['is_active'] == false)
		{
			return $user;
		}
		return null;
	}
	
	
	public function check_user_credentials_basic($username, $password)
	{
		$SQL = "SELECT * FROM core_users WHERE (identity_id=? OR username=? OR username LIKE '%;".$this->db->escape_like_str($username).";%') AND password=?";
		$query = $this->db->query($SQL, array($username, $username, $password));
		if ($query->num_rows() == 0)
		{
			return null;
		}
		return $query->row_array();
	}


	public function check_user_credentials_joomla($username, $password)
	{
		require_once(APPPATH.'third_party/PasswordHash.php');
	
		$SQL = "SELECT * FROM core_users WHERE identity_id=? OR username=? OR username LIKE '%;".$this->db->escape_like_str($username).";%'";
		
		$query = $this->db->query($SQL, array($username, $username));
		if ($query->num_rows() == 0)
		{
			return null;
		}
		$user = $query->row_array();

		$rehash = false;
		$match = false;
		
		if (strpos($user['password'], '$P$') === 0)
		{
			// Use PHPass's portable hashes with a cost of 10.
			$phpass = new PasswordHash(10, true);
			$match = $phpass->CheckPassword($password, $user['password']);
			$rehash = false;
			
		}
		else
		{
			// Check the password
			$parts = explode(':', $user['password']);
			$crypt = $parts[0];
			$salt  = @$parts[1];

			$rehash = true;

			$testcrypt = md5($password . $salt) . ($salt ? ':' . $salt : '');
			
			if ($testcrypt == $user['password'])
			{
				
				$match = true;
				
			}
		}

		if ($rehash)
		{
			// Use PHPass's portable hashes with a cost of 10.
			$phpass = new PasswordHash(10, true);
			$new_hash = $phpass->HashPassword($password);
		
			// save new hash
			$this->db->where('id', $user['id']);
			$this->db->update('core_users', array('password' => $new_hash));
		}

		if ($match)
		{
			return $user;
		}

		return null;
	}

	
	public function update_last_login($user_id)
	{
		$this->db->where('id', $user_id);
		$this->db->update('core_users', array('last_login' => date('Y-m-d H:i:s')));
	}

	
	public function get_all_privileges()
	{
		$SQL = "SELECT * FROM core_privileges ORDER BY privilege_type, label";
		$query = $this->db->query($SQL);
		return $query->result_array();
	}
	
	
	public function get_group_privileges($group_id)
	{
		$SQL = "SELECT P.*, GP.id_core_groups AS group_id FROM core_privileges P LEFT JOIN (SELECT id_core_groups, id_core_privileges FROM core_group_privileges WHERE id_core_groups=?) GP ON P.id=GP.id_core_privileges ORDER BY P.privilege_type, P.label";
		$query = $this->db->query($SQL, array($group_id));
		return $query->result_array();
	}
	
	
	public function set_group_privileges($group_id, $privileges)
	{
		# reset privileges
		$this->db->delete('core_group_privileges', array('id_core_groups' => $group_id));
		
		foreach ($privileges as $P)
		{
			$this->db->insert('core_group_privileges', array('id_core_groups' => $group_id, 'id_core_privileges' => $P));
		}
	}
	
	
	public function get_user_modules_and_submodules($user_id)
	{
		$SQL = "SELECT P.* FROM core_group_privileges GP, core_privileges P, (SELECT id_core_groups FROM core_user_groups WHERE id_core_users=?) U WHERE U.id_core_groups=GP.id_core_groups AND GP.id_core_privileges=P.id AND P.privilege_type='M' ORDER BY P.id";
		$query = $this->db->query($SQL, array($user_id));

		$modules = $query->result_array();
		
		$modules_and_submodules = array();
		
		$SUBMODULES_SQL = "SELECT P.* FROM core_group_privileges GP, core_privileges P, (SELECT id_core_groups FROM core_user_groups WHERE id_core_users=?) U WHERE U.id_core_groups=GP.id_core_groups AND GP.id_core_privileges=P.id AND P.privilege_type='SM' AND P.parent_privilege=? ORDER BY P.id";
		
		foreach ($modules as $M)
		{
			$query = $this->db->query($SUBMODULES_SQL, array($user_id, $M['code']));
			if ($query->num_rows() == 0) {
				$submodules = null;
			}
			else
			{
				$submodules = $query->result_array();
			}
			
			$M['submodules'] = $submodules;
			$modules_and_submodules[] = $M;
		}
		
		return $modules_and_submodules;
	}

	public function get_user_privilege_list($user_id)
	{
		$SQL = "SELECT P.code FROM core_group_privileges GP, core_privileges P, (SELECT id_core_groups FROM core_user_groups WHERE id_core_users=?) U WHERE U.id_core_groups=GP.id_core_groups AND GP.id_core_privileges=P.id";
		$query = $this->db->query($SQL, array($user_id));

		$privilege_list = array();
		foreach ($query->result_array() as $P)
		{
			$privilege_list[] = $P['code'];
		}

		return $privilege_list;
	}

	
	public function get_user_profile_list($user_id)
	{
		$SQL = "SELECT profile FROM prof_user_profiles WHERE id_core_users=?";
		$query = $this->db->query($SQL, array($user_id));

		$profile_list = array();
		foreach ($query->result_array() as $P)
		{
			$profile_list[] = $P['profile'];
		}

		return $profile_list;
	}
	
	
	public function user_has_profile($user_id, $profile)
	{
		$query = $this->db->get_where('prof_user_profiles', array('id_core_users' => $user_id, 'profile' => $profile));
		if ($query->num_rows() == 0)
		{
			return false;
		}
		return true;
	}
	
	
	public function store_temporary_data($data)
	{
		$code = $this->generate_random_string(8);
		$json_data = json_encode($data);
		try
		{
			$this->db->insert('core_temp_store', array('code' => $code, 'temp_data' => $json_data, 'modified' => date('Y-m-d H:i:s')));
		} catch (Exception $e)
		{
			return null;
		}
		
		$query = $this->db->get_where('core_temp_store', array('code' => $code,));
		if ($query->num_rows() == 0)
		{
			return null;
		}
		$temp_data = $query->row_array();
		return $temp_data['id'];
	}
	
	
	public function get_temporary_data($data_id)
	{
		$this->order_by('award_date','DESC');
		$query = $this->db->get_where('core_temp_store', array('id' => $data_id,));
		if ($query->num_rows() == 0)
		{
			return null;
		}
		$temp_data = $query->row_array();
		$decoded_data = json_decode($temp_data['temp_data'], true);
		return $decoded_data;
	}

	
	public function delete_temporary_data($data_id)
	{
		$this->db->delete('core_temp_store', array('id' => $data_id));
	}

	
	public function get_and_delete_temporary_data($data_id)
	{
		$temp_data = $this->get_temporary_data($data_id);
		if ($temp_data != null)
		{
			$this->delete_temporary_data($data_id);
		}
		return $temp_data;
	}

	
	/*******************************************************
	* data for form selection boxes from core_lookups table
	********************************************************/
	
	public function get_select_box_options_from_lookup($grouping=null, $parent_grouping=null, $parent_value=null)
	{
		$SQL = "SELECT label, value FROM core_lookups";
		$values = array();
		
		if ($grouping != null)
		{
			$values['grouping'] = $grouping;
		}
		
		if ($parent_grouping != null)
		{
			$values['parent_grouping'] = $parent_grouping;
		}
		
		if ($parent_value != null)
		{
			$values['parent_value'] = $parent_value;
		}
		
		$extra_sql = '';
		$extra_values = array();
		$counter = 0;
		foreach ($values as $key=>$value)
		{
			$counter += 1;
			if ($counter == 1)
			{
				$extra_sql .= ' WHERE '.$key.'=?';
			}
			else
			{
				$extra_sql .= ' AND '.$key.'=?';
			}
			$extra_values[] = $value;
		}
		
		$SQL .= $extra_sql." ORDER BY ordering";
		
		$query = $this->db->query($SQL, $extra_values);

		$results = array('' => '----------');
		
		foreach ($query->result_array() as $O)
		{
			$results[$O['value']] = $O['label'];
		}
		return $results;
	}


}


?>
