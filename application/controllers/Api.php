<?php

class Api extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
	}
	
		
	public function payment()
	{
		$json_data = json_encode($this->input->post());
		
		$this->core_model->create_object('card_payments', array('transaction_data' => $json_data));
	}	
}