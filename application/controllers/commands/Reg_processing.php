<?php

/*******************************************
 * Registrations processing Commands
 * 
 * 1. send registration confirmation emails
 * 2. send payment instruction emails
 * 3. send payment confirmation emails
 * 4. send invitation/visa letters & emails
 * 
 * ***************
 * the commands in this file can be set to run
 * as cron jobs i.e.
 * 
 * php index.php Reg_processing <command>
 * 
 *******************************************/

class Reg_processing extends CI_Controller
{
	
	public function __construct()
	{
		parent::__construct();
		
		# restrict methods here to cli invocation
		if ( ! is_cli())
		{
			#echo "These methods Should only be run from the CLI";
			#exit();
		}
		
		# prevent script from running more than one instance at a time
		$this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
		if ($this->socket === false) 
		{
			throw new Exception("can't create socket: ".socket_last_error($this->socket));
			exit();
		}
		## set $port to something random like 10012
		$port = 12137;
		
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

	
	public function send_registration_confirmations()
	{
		$SQL = "SELECT * FROM core_registrations WHERE parent_registration_code IS NULL AND reg_email_sent=0";
		$results = $this->reports_model->run_sql_query($SQL);
	
		$reg_email_template = 'Your registration for CPA 5th Economic Forum has been received. Your registration code is {registration_code}
		
		You will receive a confirmation of your booking after making payments in the bank.';

		foreach ($results as &$R)
		{
			if ($R['gender'] == 'MALE')
			{
				$salutation = 'Mr.';
			}
			else
			{
				$salutation = 'Ms.';
			}
			$salute_text = 'Dear '.$salutation.' '.$R['first_name'].' '.$R['last_name'].' '.$R['other_names'];
			$reg_email = str_replace('{registration_code}', $R['registration_code'], $reg_email_template);
			$reg_email = str_replace('{info_update_url}', "https://acoa2017.com/registration/index.php/registration/update/?reg={$R['registration_code']}&uc={$R['update_registration_code']}&e=1", $reg_email);
			$reg_email = str_replace('{payment_update_url}', "https://acoa2017.com/registration/index.php/registration/payment_options/{$R['registration_code']}/{$R['update_registration_code']}/1/", $reg_email);
			
			# send email
			$email_db = mysqli_connect("localhost", "icpau", "icpau123", "ICPAU");
						
			mysqli_query($email_db, "INSERT INTO customized_messages (from_email, to_email, subject, salute_text, body) VALUES (
			'noreply@icpau.co.ug', '{$R['email']}', 'CPA 5th Economic Forum Registration', '{$salute_text}', '{$reg_email}')") or die('mysql error :'.mysqli_error($email_db).'<br>');
			
			# flag as sent
			$this->core_model->update_object('core_registrations', $R['id'], array('reg_email_sent'=>1));
		}
	}
	
	#Payment invoice for persons who choose TT/CASH
	public function send_payment_instructions()
	{
		include_once APPPATH.'/third_party/mpdf60/mpdf.php';
		$this->load->helper('path');
		$this->load->helper('text');
		
		$SQL = "SELECT * FROM core_invoices WHERE (payment_type='TT' OR payment_type='CASH') AND (invoice_email_sent=0 OR resend_invoice_email=1)";
		$invoices = $this->reports_model->run_sql_query($SQL);
			
		$payment_instructions_email_template = 'You have chosen to pay by {payment_method}
Kindly find attached your profoma invoice.

{payment_method_information}
				
You will receive a confirmation of your booking after making payments in the bank.';
		
		$now = date('d M Y');
		$pdf_assets_path = './media/PDF/assets/';
		$pdf_save_path = './media/PDF/proformas/';
		$png_save_barcode_path ='./media/PDF/barcodes/';
		
		foreach ($invoices as &$I)
		{
			# get parent registration
			$__regs = $this->core_model->get_many_objects('core_registrations', array('invoice_code'=>$I['code']), 'id');
			$reg = null;
			foreach ($__regs as &$RG)
			{
				if (empty($RG['parent_registration_code']))
				{
					$reg = $RG;
					break;
				}
			}
			
			if ( ! $reg && count($__regs) > 0)
			{
				$reg = $this->core_model->get_object('core_registrations', null, array('registration_code' => $__regs[0]['parent_registration_code']));
			}
			
			if ( ! $reg)
			{
				continue;
			}
			
			if ($reg['gender'] == 'MALE')
			{
				$salutation = 'Mr.';
			}
			else
			{
				$salutation = 'Ms.';
			}
			
			#barcode parameters
			
			$filepath=$png_save_barcode_path.$reg["registration_code"].'.png';
			$text=$reg["registration_code"];
			$size=40;
			$orientation="horizontal";
			$code_type="code128";
			$print=true;
			$sizefactor = "1";
			
			#create barcode
			$this->barcode( $filepath, $text, $size, $orientation, $code_type, $print, $sizefactor );
				
			#path to barcode
			$barcode_png = $filepath;
			
			$salute_text = 'Dear '.$salutation.' '.$reg['first_name'].' '.$reg['last_name'].' '.$reg['other_names'];
			$payment_email = str_replace('{payment_method}', $I['payment_type'], $payment_instructions_email_template);
			$payment_email = str_replace('{payment_method_information}', '', $payment_email);
			$payment_email = str_replace('{payment_update_url}', "https://acoa2017.com/registration/index.php/registration/payment_options/{$reg['registration_code']}/{$reg['update_registration_code']}/1/", $payment_email);
			
			# create proforma invoice
			$invoice_content = json_decode($I['content'], true);
			$residence_labels = $this->config->item('event_item_labels');
			
			$reg_row = '';
			if (isset($invoice_content['reg_rate']))
			{
				$reg_row = "<tr><td>{$now}</td><td>Payment for CPA 5th Economic Forum Conference for<br>
			{$salutation} {$reg['first_name']} {$reg['last_name']} {$reg['other_names']}<br> Registration ID: {$reg['registration_code']}<br>
			Residence: {$residence_labels[$reg['hotel']]}</td>
			<td>1</td><td style=\"text-align:right;\">{$invoice_content['reg_rate']}</td><td style=\"text-align:right;\">{$invoice_content['reg_cost']}</td></tr>";
			}
			
			$pdf  = new mPDF();
			$html = "
			<div style=\"padding-top:90px;\">
			<div style=\"width:250px;float:right;\"><img src=\"{$barcode_png}\"/></div>
			</div>
			
			<div style=\"padding-top:10px;text-align:center;\"><big><b>INVOICE</b></big></div>
			
			<div style=\"padding-top:15px;\">To {$salutation} {$reg['first_name']} {$reg['last_name']} {$reg['other_names']}</div>
			
			<div style=\"padding-top:20px;\">DR: The Institute of Certified Public Accountants of Uganda</div>
			
			<div style=\"padding-top:20px;\">
			<table style=\"width:100%;height:200px;\" border=\"1\" cellspacing=\"0\">
			<tr><th>DATE</th><th>DESCRIPTION</th><th>QTY</th><th>RATE</th><th>AMOUNT</th></tr>{$reg_row}
			<tr><td>{$now}</td><td>Accompanying Persons:{$invoice_content['acc_persons_desc']}</td><td>{$invoice_content['acc_persons_count']}</td>
			<td style=\"text-align:right;\">{$invoice_content['event_rates']['MEM_ACCOMPANYING_PERSON']}</td><td style=\"text-align:right;\">{$invoice_content['acc_persons_cost']}</td></tr>
			<tr><td></td><td><b>TOTAL</b></td><td></td><td></td><td style=\"text-align:right;\"><b>{$invoice_content['grand_total']}</b></td></tr>
			</table>
			Amount in Words (UGX): {$invoice_content['grand_total_words']}
			</div>
				
			<div style=\"padding-top:20px;\">
			Please remit the money to:<br>
			<b>Bank:</b> Stanbic Bank<br>
			<b>Branch:</b> Forest Mall Branch<br>
			<b>Account No:</b> 9030005648709<br>
			<b>Swift Code:</b> SBICUGX<br>
			</div>
			
			<div style=\"padding-top:20px;\">Please remember:<br>
			1. Invoice amount is payable <b>before</b> the seminar date 
			2. To include your REGISTRATION ID when making the payment<br>
			3. Send the Remittance Advice to <b>jyeko@icpau.co.ug</b>
			</div>
			
			<div style=\"padding-top:20px;\">Yours sincerely,</div>
			<div><img src=\"{$pdf_assets_path}fmsig.png\" width=\"135\" /></div>
			Robert Kamoga Tebasuulwa
			<div>FINANCE MANAGER</div>
			";
			
			// set PDF Template
			$pdf->SetImportUse();
			$pdf->SetDocTemplate($pdf_assets_path.'ICPAU_HEADED_PAPER.pdf', true);
			// add a page to PDF
			$pdf->AddPage();
			// write html to the PDF
			$pdf->writeHTML($html);
			
			$pdf->SetCompression(true);
			
			$pdf_file_path = $pdf_save_path.$reg['registration_code'].'_'.$I['code'].'_proforma_invoice.pdf';
			
			$pdf_content = $pdf->Output("","S");
			file_put_contents($pdf_file_path, $pdf_content);
			
			$pdf_full_file_path = set_realpath($pdf_file_path);
			
			# send email
			$email_db = mysqli_connect("localhost", "icpau", "icpau123", "ICPAU");
			mysqli_query($email_db, "INSERT INTO customized_messages (from_email, to_email, subject, salute_text, body, abs_attachment_path) VALUES (
			'noreply@icpau.co.ug', '{$reg['email']}', 'CPA 5th Economic Forum Registration', '{$salute_text}', '{$payment_email}', '{$pdf_full_file_path}')");
			
			# flag as sent
			$this->core_model->update_object('core_invoices', $I['id'], array('invoice_email_sent'=>1, 'resend_invoice_email'=>0));
		}
	}
	
	
	public function send_payment_confirmations()
	{
		include_once APPPATH.'/third_party/mpdf60/mpdf.php';
		$this->load->helper('path');
		
		$SQL = "SELECT * FROM core_invoices WHERE payment_type IS NOT NULL AND payment_made=1 AND payment_email_sent=0";
		$invoices = $this->reports_model->run_sql_query($SQL);
		
		$payment_confirmation_email_template = 'Kindly find attached your CPA 5th Economic Forum reciept';
		
		$now = date('d M Y');
		$pdf_assets_path = './media/PDF/assets/';
		$pdf_save_path = './media/PDF/receipts/';
		$png_save_barcode_path ='./media/PDF/barcodes/'; 
		
		foreach ($invoices as &$I)
		{
			# get parent registration
			$__regs = $this->core_model->get_many_objects('core_registrations', array('invoice_code'=>$I['code']), 'id');
			$reg = null;
			foreach ($__regs as &$RG)
			{
				if (empty($RG['parent_registration_code']))
				{
					$reg = $RG;
					break;
				}
			}
			
			if ( ! $reg && count($__regs) > 0)
			{
				$reg = $this->core_model->get_object('core_registrations', null, array('registration_code' => $__regs[0]['parent_registration_code']));
			}
			
			if ( ! $reg)
			{
				continue;
			}

			$card_payment_details = '';
			
			if ($I['payment_type'] == 'CARD')
			{
				$payment_proof = json_decode($I['payment_proof'], true);
				$card_payment_details = "<b>Card Number:</b> {$payment_proof['req_card_number']}<br>
				<b>Card Expiry:</b> {$payment_proof['req_card_expiry_date']}<br>";
			}
			
			#barcode parameters
			$filepath=$png_save_barcode_path.$reg["registration_code"].'.png';
			$text=$reg["registration_code"];
			$size=40;
			$orientation="horizontal";
			$code_type="code128";
			$print=true;
			$sizefactor = "1";
						
			#create barcode
			$this->barcode( $filepath, $text, $size, $orientation, $code_type, $print, $sizefactor );
			
			#path to barcode
			$barcode_png = $filepath;
				
			if ($reg['gender'] == 'MALE')
			{
				$salutation = 'Mr.';
			}
			else
			{
				$salutation = 'Ms.';
			}
			$salute_text = 'Dear '. $salutation.' '.$reg['first_name'].' '.$reg['last_name'].' '.$reg['other_names'];
			$payment_email = str_replace('{payment_method}', $I['payment_type'], $payment_confirmation_email_template);
			$payment_email = str_replace('{payment_method_information}', '', $payment_email);
			$payment_email = str_replace('{payment_update_url}', "https://acoa2017.com/registration/index.php/registration/payment_options/{$reg['registration_code']}/{$reg['update_registration_code']}/1/", $payment_email);
			
			# create receipt
			$invoice_content = json_decode($I['content'], true);
			
			$item_desc = '';
			if (isset($invoice_content['reg_rate']))
			{
				$item_desc .= "Payment for CPA 5th Economic Forum Conference for<br>
			{$salutation} {$reg['first_name']} {$reg['last_name']} {$reg['other_names']}<br> Registration ID: {$reg['registration_code']}<br>";
			}
			
			if ($invoice_content['acc_persons_count'] > 0)
			{
				$item_desc .= "Accompanying Persons:<br>{$invoice_content['acc_persons_desc']}";
			}
			
			$pdf  = new mPDF();
			$html = "
			<div style=\"padding-top:90px;\">
			<div style=\"width:250px;float:right;\"><img src=\"{$barcode_png}\"/></div>
			</div>
			
			<div style=\"padding-top:10px;text-align:center;\"><big><b>RECEIPT</b></big></div>
			
			<div style=\"padding-top:20px;\">Date: {$now}</div>
			<div style=\"padding-top:15px;\">To {$salutation} {$reg['first_name']} {$reg['last_name']} {$reg['other_names']}</div>
			
			<div style=\"padding-top:20px;\">DR: The Institute of Certified Public Accountants of Uganda</div>
			
			<div style=\"padding-top:20px;\">
			<table style=\"width:100%;height:200px;\" border=\"1\" cellspacing=\"0\">
			<tr><th>DATE</th><th>DESCRIPTION</th><th>AMOUNT</th></tr>
			<tr><td>{$now}</td><td>{$item_desc}</td><td style=\"text-align:right;\">{$I['amount']}</td></tr>
			<tr><td></td><td><b>TOTAL</b></td><td style=\"text-align:right;\"><b>{$I['amount']}</b></td></tr>
			</table>
			</div>
				
			<div style=\"padding-top:20px;\">
			Payment Details:<br>
			<b>Payment Method:</b> {$I['payment_type']}<br>
			{$card_payment_details}
			</div>
			
			<div style=\"padding-top:20px;\">Yours sincerely,</div>
			<div><img src=\"{$pdf_assets_path}fmsig.png\" width=\"135\" /></div>
			Robert Kamoga Tebasuulwa
			<div>FINANCE MANAGER</div>
			";
			
			// set PDF Template
			$pdf->SetImportUse();
			$pdf->SetDocTemplate($pdf_assets_path.'ICPAU_HEADED_PAPER.pdf', true);
			// add a page to PDF
			$pdf->AddPage();
			// write html to the PDF
			$pdf->writeHTML($html);
			
			$pdf->SetCompression(true);
			
			$pdf_file_path = $pdf_save_path.$reg['registration_code'].'_'.$I['code'].'_receipt.pdf';
			
			$pdf_content = $pdf->Output("","S");
			file_put_contents($pdf_file_path, $pdf_content);
			
			$pdf_full_file_path = set_realpath($pdf_file_path);
			
			# send email
			$email_db = mysqli_connect("localhost", "icpau", "icpau123", "ICPAU");
			mysqli_query($email_db, "INSERT INTO customized_messages (from_email, to_email, subject, salute_text, body, abs_attachment_path) VALUES (
			'noreply@icpau.co.ug', '{$reg['email']}', 'CPA 5th Economic Forum Registration Receipt', '{$salute_text}', '{$payment_email}', '{$pdf_full_file_path}')");
			
			# flag as sent
			$this->core_model->update_object('core_invoices', $I['id'], array('payment_email_sent'=>1));
		}
	}
	
	
	public function send_invitations()
	{
		include_once APPPATH.'/third_party/mpdf60/mpdf.php';
		$this->load->helper('path');
		$this->load->helper('text');
		
		$SQL = "SELECT R.*, I.payment_type FROM core_registrations R, core_invoices I WHERE R.invoice_code=I.code AND R.parent_registration_code IS NULL AND 
			I.payment_type IS NOT NULL AND I.payment_made=1 AND I.payment_email_sent=1 AND R.invitation_email_sent=0";
		
		$results = $this->reports_model->run_sql_query($SQL);
			
		$invitation_email_template = '
Kindly find attached your CPA 5th Economic Forum invitation letter and visa application letter

Note: visa application letter applies to only Non-Ugandans';
		
		$now = date('d M Y');
		$pdf_assets_path = './media/PDF/assets/';
		$pdf_save_path = './media/PDF/invitations/';
		$png_save_barcode_path ='./media/PDF/barcodes/';
	
		$attachments = array();
		
		foreach ($results as &$R)
		{
			# get accompanying persons
			$acc_persons = $this->core_model->get_many_objects('core_registrations', array('parent_registration_code' => $R['registration_code']));
			
			foreach ($acc_persons as &$A)
			{
				if ($A['gender'] == 'MALE')
				{
					$acc_salutation = 'Mr.';
				}
				else
				{
					$acc_salutation = 'Ms.';
				}
				
				# create invitation letter for accompanying person
				$pdf  = new mPDF();
				$html = "
				<div style=\"padding-top:90px;\">Our Ref: A/37</div>	
				<div style=\"padding-top:5px;\">Date: {$now}</div>	
				<div style=\"padding-top:5px;\">To {$acc_salutation} {$A['first_name']} {$A['last_name']} {$A['other_names']}</div>	
				<div style=\"padding-top:15px;\">Dear {$acc_salutation} {$A['last_name']},</div>	
				
				<div style=\"padding-top:20px;\"><u><b>CPA 5th ECONOMIC FORUM INVITATION</b></u></div>
				
				......................
				
				<div style=\"padding-top:20px;\">Yours sincerely,</div>
				<div style=\"padding-top:10px;position:fixed;left:10mm;\"><img src=\"{$pdf_assets_path}/sec.jpg\" width=\"70\" /></div>	
				<div style=\"position:fixed;top:182mm;\">Derick Nkajja<br>SECRETARY/CEO</div>	
				";
							
				// set PDF Template
				$pdf->SetImportUse();
				$pdf->SetDocTemplate($pdf_assets_path.'ICPAU_HEADED_PAPER.pdf', true);
				// add a page to PDF
				$pdf->AddPage();
				// write html to the PDF
				$pdf->writeHTML($html);
				
				$pdf->SetCompression(true);
				
				$pdf_file_path = $pdf_save_path.$A['registration_code'].'_invitation.pdf';
				
				$pdf_content = $pdf->Output("","S");
				file_put_contents($pdf_file_path, $pdf_content);
				
				$attachments[] = set_realpath($pdf_file_path);
				
				if ($R['nationality'] != $this->config->item('local_country'))
				{
					# create visa letter for accompanying person
					$pdf  = new mPDF();
					$html = "
					<div style=\"padding-top:90px;\">{$now}</div>	
					<div>Visa Department,</div>	
					<div>Embassy of the Republic of Uganda, </div>	
					<div>Dear Sirs,</div>	
					<div style=\"padding-top:20px;\"><u><b>VISA APPLICATION</b></u></div>	
					<div style=\"padding-top:20px;\">The Institute of Certified Public Accountants of Uganda (ICPAU), 
					.........................</div>
					<div style=\"padding-top:20px;\">We have invited: <b>{$acc_salutation} {$A['first_name']} {$A['last_name']} {$A['other_names']}</b> 
					with details below to attend the event</div><br>
					<div>Organization: <b>{$A['emp_organisation']}</b></div>
					<div>Title: <b>{$A['emp_job_title']}</b></div>
					<div>Passport No: <b>{$A['passport_no']}</b></div>
					<div>Date of Issue: <b>{$A['passport_issue_date']}</b></div>
					<div>Date of Expiry: <b>{$A['passport_expiry_date']}</b></div>	
					<div style=\"padding-top:20px;\">Please use this letter for processing the visa request of the delegate. We also advise 
					that all the related expenses will be borne by the delegate(s). We are 
					aware of the immigration laws and related responsibilities and we request for your assistence with all CPA 5th Economic Forum delegates.</div>	
					<div style=\"padding-top:20px;\">Yours faithfully,</div>
					<div style=\"padding-top:10px;position:fixed;left:10mm;\"><img src=\"{$pdf_assets_path}/sec.jpg\" width=\"70\" /></div>	
					<div style=\"position:fixed;top:201mm;\">Derick Nkajja<br>SECRETARY/CEO</div>	
					";
					
					// set PDF Template
					$pdf->SetImportUse();
					$pdf->SetDocTemplate($pdf_assets_path.'ICPAU_HEADED_PAPER.pdf', true);
					// add a page to PDF
					$pdf->AddPage();
					// write html to the PDF
					$pdf->writeHTML($html);
						
					$pdf->SetCompression(true);
						
					$pdf_file_path = $pdf_save_path.$A['registration_code'].'_visa_request.pdf';
						
					$pdf_content = $pdf->Output("","S");
					file_put_contents($pdf_file_path, $pdf_content);
						
					$attachments[] = set_realpath($pdf_file_path);
				}
			}
			
			if ($R['gender'] == 'MALE')
			{
				$salutation = 'Mr.';
			}
			else
			{
				$salutation = 'Ms.';
			}
			$salute_text = 'Dear '. $salutation.' '.$R['first_name'].' '.$R['last_name'].' '.$R['other_names'];
			$invitation_email = str_replace('{payment_method}', $R['payment_type'], $invitation_email_template);
			$invitation_email = str_replace('{payment_method_information}', '', $invitation_email);
			$invitation_email = str_replace('{payment_update_url}', "https://acoa2017.com/registration/index.php/registration/payment_options/{$R['registration_code']}/{$R['update_registration_code']}/1/", $invitation_email);
			

			# create invitation letter for attendant
			$pdf  = new mPDF();
			$html = "
			<div style=\"padding-top:90px;text-align:right;\"><img src=\"{$png_save_barcode_path}{$R['registration_code']}.png\" /></div>
			<div style=\"padding-top:5px;\">Our Ref: A/37</div>	
			<div style=\"padding-top:5px;\">Date: {$now}</div>	
			<div style=\"padding-top:5px;\">To {$salutation} {$R['first_name']} {$R['last_name']} {$R['other_names']}</div>	
			<div style=\"padding-top:15px;\">Dear {$salutation} {$R['last_name']},</div>	
			
			<div style=\"padding-top:20px;\"><u><b>CPA 5th ECONOMIC FORUM INVITATION</b></u></div>
			
			<div style=\"padding-top:20px;\">......................................</div>
			
			<div style=\"padding-top:20px;\">Yours sincerely,</div>
			<div style=\"padding-top:5px;position:fixed;left:10mm;\"><img src=\"{$pdf_assets_path}/sec.jpg\" width=\"70\" /></div>	
			<div style=\"position:fixed;top:200mm;\">Derick Nkajja<br>SECRETARY/CEO</div>	
			";
						
			// set PDF Template
			$pdf->SetImportUse();
			$pdf->SetDocTemplate($pdf_assets_path.'ICPAU_HEADED_PAPER.pdf', true);
			// add a page to PDF
			$pdf->AddPage();
			// write html to the PDF
			$pdf->writeHTML($html);
			
			$pdf->SetCompression(true);
			
			$pdf_file_path = $pdf_save_path.$R['registration_code'].'_invitation.pdf';
			
			$pdf_content = $pdf->Output("","S");
			file_put_contents($pdf_file_path, $pdf_content);
			
			$attachments[] = set_realpath($pdf_file_path);
			
			if ($R['nationality'] != $this->config->item('local_country'))
			{
				# create visa letter for attendant
				$pdf  = new mPDF();
				$html = "
				<div style=\"padding-top:90px;\">{$now}</div>	
				<div>Visa Department,</div>	
				<div>Embassy of the Republic of Uganda, </div>	
				<div>Dear Sirs,</div>	
				<div style=\"padding-top:20px;\"><u><b>VISA APPLICATION</b></u></div>	
				<div style=\"padding-top:20px;\">The Institute of Certified Public Accountants of Uganda (ICPAU), ............................</div>
				<div style=\"padding-top:20px;\">We have invited: <b>{$salutation} {$R['first_name']} {$R['last_name']} {$R['other_names']}</b> 
				with details below to attend the congress</div><br>
				<div>Organization: <b>{$R['emp_organisation']}</b></div>
				<div>Title: <b>{$R['emp_job_title']}</b></div>
				<div>Passport No: <b>{$R['passport_no']}</b></div>
				<div>Date of Issue: <b>{$R['passport_issue_date']}</b></div>
				<div>Date of Expiry: <b>{$R['passport_expiry_date']}</b></div>	
				<div style=\"padding-top:20px;\">Please use this letter for processing the visa request of the delegate. We also advise 
				that all the related expenses will be borne by the delegate(s). We are 
				aware of the immigration laws and related responsibilities and we request for your assistence with all CPA 5th Economic Forum delegates.</div>	
				<div style=\"padding-top:20px;\">Yours faithfully,</div>
				<div style=\"padding-top:10px;position:fixed;left:10mm;\"><img src=\"{$pdf_assets_path}/sec.jpg\" width=\"70\" /></div>	
				<div style=\"position:fixed;top:201mm;\">Derick Nkajja<br>SECRETARY/CEO</div>	
				";
				
				// set PDF Template
				$pdf->SetImportUse();
				$pdf->SetDocTemplate($pdf_assets_path.'ICPAU_HEADED_PAPER.pdf', true);
				// add a page to PDF
				$pdf->AddPage();
				// write html to the PDF
				$pdf->writeHTML($html);
					
				$pdf->SetCompression(true);
					
				$pdf_file_path = $pdf_save_path.$R['registration_code'].'_visa_request.pdf';
					
				$pdf_content = $pdf->Output("","S");
				file_put_contents($pdf_file_path, $pdf_content);
					
				$attachments[] = set_realpath($pdf_file_path);
			}
			
			# create attachments list
			$pdf_attachments = implode(';', $attachments);
			#add bcc email
			#$bcc_email = "acoainvitations@icpau.co.ug";
print_r($invitation_email);			
			# send email
#			$email_db = mysqli_connect("localhost", "icpau", "icpau123", "ICPAU");
#			mysqli_query($email_db, "INSERT INTO customized_messages (from_email, to_email,bcc_email, subject, salute_text, body, abs_attachment_path) VALUES (
#			'noreply@icpau.co.ug', '{$R['email']}','{$bcc_email}' ,'CPA 5th Economic Forum Registration', '{$salute_text}', '{$invitation_email}', '{$pdf_attachments}')");
			#unset attachements;
			unset($attachments);
			# flag as sent
			$this->core_model->update_object('core_registrations', $R['id'], array('invitation_email_sent'=>1));
		}
	}


	public function process_card_payments()
	{
		include_once APPPATH.'/third_party/CSCP.php';
		
		$card_payments = $this->core_model->get_many_objects('card_payments', array('status' => 'PENDING'));
		
		foreach ($card_payments as &$CP)
		{
			$tx_values = json_decode($CP['transaction_data'], true);
			$status = 'ERROR';
			$comment = 'unsuccessful/invalid transaction';
			
			if ($tx_values && isset($tx_values['decision']) && $tx_values['decision'] == 'ACCEPT')
			{
				# check if data is correct (check generated hash with included hash)
				$signature = sign($tx_values);
				
				if ($signature == $tx_values['signature'])
				{
					# get invoice corresponding to this transaction.
					#  $tx_values['req_reference_number'] is a combination of registration_code and invoice_code
					$ref_number = explode('-', $tx_values['req_reference_number']);
					
					$invoice = $this->core_model->get_object('core_invoices', null, array('code' => $ref_number[1]));
					
					if ($invoice)
					{
						$this->core_model->update_object('core_invoices', $invoice['id'], array('payment_made' => 1, 'payment_date' => date('Y-m-d'), 'payment_proof' => $CP['transaction_data']));
						$status = 'PROCESSED';
						$comment = 'successfully processed';
					}
					else
					{
						$status = 'ERROR';
						$comment = 'corresponding registration could not be found';
					}
				}
				else
				{
					$status = 'ERROR';
					$comment = 'invalid signature';
				}
			}
			
			$this->core_model->update_object('card_payments', $CP['id'], array('status' => $status, 'comment' => $comment));
		}
	}
	
	/*********************************************************
	 * Barcode generation... returns path to the barcode image
	 * source: http://davidscotttufts.com/2009/03/31/how-to-create-barcodes-in-php/
	 *********************************************************/
	public function barcode( $filepath="", $text="0", $size="20", $orientation="horizontal", $code_type="code128", $print=false, $SizeFactor="1" ) 
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


















