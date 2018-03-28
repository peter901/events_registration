<div class="row">
	<div class="col-md-4"></div>
	<div class="col-md-4">
	<br>
		<div class="progress progress-xxs">
			<div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 20%">
				<span class="sr-only">20%</span>
			</div>
		</div>
	</div>
	<div class="col-md-4"><span class="badge bg-orange">20%</span></div>
</div>
<?=form_open(site_url('/registration/register'))?>
	<input type="hidden" name="registration_code" value="<?=$registration_code?>"  id="id_registration_code" />
		<!-- row -->
          <div class="row">
          	<div class="col-md-2"></div>
            <div class="col-md-8">
			<?php if (isset($ERRORS) && $ERRORS > 0):?>
				<div class="callout callout-danger">
					<h4>Registration Failed</h4>
					<p>Some errors were encountered that require your attention.</p>
				</div>
			<?php endif;?>
            <!-- The time line -->
              <ul class="timeline">
                <li>
                  <i class="fa fa-user bg-blue"></i>
                  <div class="timeline-item">
                    <span class="time"><i class="fa fa-tags"></i> mandatory</span>
                    <h3 class="timeline-header text-light-blue">Personal Details</h3>
                    <div class="timeline-body">
			<?= validation_errors();?>
             <?php if (isset($member_information)):?>
             	<h3><?=$member_information['salutation'].' '.$member_information['first_name'].' '.$member_information['surname'].' '.$member_information['other_names']?></h3>
             	<b>Country of Nationality: </b><?=$member_information['country']?><br>
             	
             	<input type="hidden" name="member_number" value="<?=$member_information['member_number']?>"  id="id_member_number" />
             	<input type="hidden" name="first_name" value="<?=$member_information['first_name']?>"  id="id_first_name" />
             	<input type="hidden" name="last_name" value="<?=$member_information['surname']?>"  id="id_last_name" />
             	<input type="hidden" name="other_names" value="<?=$member_information['other_names']?>"  id="id_other_names" />
             	<?php if ($member_information['gender'] == 'M'){$member_information['gender'] = 'MALE';} else {$member_information['gender'] = 'FEMALE';}?>
             	<input type="hidden" name="gender" value="<?=$member_information['gender']?>"  id="id_gender" />
             	<input type="hidden" name="dob" value="<?=$member_information['date_of_birth']?>"  id="id_dob" />
             	<input type="hidden" name="nationality" value="<?=$member_information['country']?>"  id="id_nationality" />
             	
             <?php else:?>
             <!-- form fields -->
             <div class="row">
             	<div class="col-sm-6">
             		<?= form_error('first_name', '<div class="text-red">', '</div>'); ?>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-user"><span class="text-red"> *</span></i></span>
						<?= form_input(array('id'=>'id_first_name', 'class'=>'form-control', 'name'=>'first_name', 'value'=>set_value('first_name'), 'required'=>'required', 'placeholder'=>'first name')) ?>
					</div>
             	</div>
             	<div class="col-sm-6">
             		<?= form_error('last_name', '<div class="text-red">', '</div>'); ?>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-user"><span class="text-red"> *</span></i></span>
						<?= form_input(array('id'=>'id_last_name', 'class'=>'form-control', 'name'=>'last_name', 'value'=>set_value('last_name'), 'required'=>'required', 'placeholder'=>'surname')) ?>
					</div>
             	</div>
			</div>
			<br>
            <div class="row">
             	<div class="col-sm-6">
             		<?= form_error('other_names', '<div class="text-red">', '</div>'); ?>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-user"></i></span>
						<?= form_input(array('id'=>'id_other_names', 'class'=>'form-control', 'name'=>'other_names', 'value'=>set_value('other_names'), 'placeholder'=>'other names')) ?>
					</div>
             	</div>
             	<div class="col-sm-6">
             		<?= form_error('dob', '<div class="text-red">', '</div>'); ?>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-calendar"><span class="text-red"> *</span></i></span>
						<?= form_input(array('id'=>'id_dob', 'class'=>'form-control general_date_select', 'name'=>'dob', 'value'=>set_value('dob'), 'required'=>'required', 'placeholder'=>'date of birth')) ?>
					</div>
             	</div>
			</div>
			<br>
            <div class="row">
             	<div class="col-sm-6">
             		<?= form_error('gender', '<div class="text-red">', '</div>'); ?>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-venus-mars"><span class="text-red"> *</span></i></span>
						<?= form_dropdown('gender', array(set_value('gender')=>ucwords(strtolower(set_value('gender')))), array(set_value('gender')=>ucwords(strtolower(set_value('gender')))), 'id="id_gender" class="form-control gender-dropdown" required="required" data-placeholder="gender"') ?>
					</div>
             	</div>
             	<div class="col-sm-6">
             		<?= form_error('nationality', '<div class="text-red">', '</div>'); ?>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-flag"><span class="text-red"> *</span></i></span>
						<?= form_dropdown('nationality', array(set_value('nationality')=>set_value('nationality')), array(set_value('nationality')=>set_value('nationality')), 'id="id_nationality" class="form-control country_ajax_dropdown" required="required" data-placeholder="country of nationality" onChange="addCountryCode(this, \'id_telephone\');"') ?>
					</div>
             	</div>
			</div>
			<br>
			<div class="row">
             	<div class="col-sm-6">
             		<?= form_error('national_id', '<div class="text-red">', '</div>'); ?>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-tag"></i><span class="text-red"> *Ugandans</span></span>
						<?= form_input(array('id'=>'id_national_id', 'class'=>'form-control', 'name'=>'national_id', 'value'=>set_value('national_id'), 'placeholder'=>'national ID: NIN or Passport No')) ?>
					</div>
             	</div>
             </div>
             <br>
            <!-- end form fields -->
            <?php endif;?>
            
                    </div>
                  </div>
                </li>
                <!-- END timeline item -->
                <?php if ( ! isset($member_information)):?>
                <!-- timeline item -->
                <li>
                  <i class="fa fa-book bg-orange"></i>
                  <div class="timeline-item">
                    <span class="time"><i class="fa fa-tags"></i> mandatory for non-Ugandans</span>
                    <h3 class="timeline-header text-light-blue">Passport Details &nbsp; <span class="text-red"> *non-Ugandans</span></h3>
                    <div class="timeline-body">
                    
                    <!-- end form fields -->
		            <div class="row">
		             	<div class="col-sm-6">
		             		<?= form_error('passport_no', '<div class="text-red">', '</div>'); ?>
							<div class="input-group">
								<span class="input-group-addon"><i class="fa fa-qrcode"><span class="text-red"> *</span></i></span>
								<?= form_input(array('id'=>'id_passport_no', 'class'=>'form-control', 'name'=>'passport_no', 'value'=>set_value('passport_no'), 'placeholder'=>'passport number')) ?>
							</div>
		             	</div>
		             	<div class="col-sm-6">
		             		<?= form_error('passport_issue_place', '<div class="text-red">', '</div>'); ?>
							<div class="input-group">
								<span class="input-group-addon"><i class="fa fa-map-o"><span class="text-red"> *</span></i></span>
								<?= form_input(array('id'=>'id_passport_issue_place', 'class'=>'form-control', 'name'=>'passport_issue_place', 'value'=>set_value('passport_issue_place'), 'placeholder'=>'place of issue')) ?>
							</div>
		             	</div>
					</div>
					<br>
		            <div class="row">
		             	<div class="col-sm-6">
		             		<?= form_error('passport_issue_date', '<div class="text-red">', '</div>'); ?>
							<div class="input-group">
								<span class="input-group-addon"><i class="fa fa-calendar"><span class="text-red"> *</span></i></span>
								<?= form_input(array('id'=>'id_passport_issue_date', 'class'=>'form-control general_date_select', 'name'=>'passport_issue_date', 'value'=>set_value('passport_issue_date'), 'placeholder'=>'date of issue')) ?>
							</div>
		             	</div>
		             	<div class="col-sm-6">
		             		<?= form_error('passport_expiry_date', '<div class="text-red">', '</div>'); ?>
							<div class="input-group">
								<span class="input-group-addon"><i class="fa fa-calendar"><span class="text-red"> *</span></i></span>
								<?= form_input(array('id'=>'id_passport_expiry_date', 'class'=>'form-control general_date_select', 'name'=>'passport_expiry_date', 'value'=>set_value('passport_expiry_date'), 'placeholder'=>'date of expiry')) ?>
							</div>
		             	</div>
					</div>
					<br>
                    <!-- end form fields -->
                    </div>
                  </div>
                </li>
                <!-- END timeline item -->
                <?php endif;?>
                <!-- timeline item -->
                <li>
                  <i class="fa fa-envelope bg-green"></i>
                  <div class="timeline-item">
                    <span class="time"><i class="fa fa-tags"></i> mandatory</span>
                    <h3 class="timeline-header text-light-blue">Contact Information</h3>
                    <div class="timeline-body">
                    
            <!-- form fields -->
            <?php if (isset($member_information)):?>
            <b>Note:</b> If your contact information is shown with some parts hidden with '*', it has been intentionally hidden. If it appears to be correct, you don't have to update it.
           <div class="row">
             	<div class="col-sm-6">
             		<?= form_error('email', '<div class="text-red">', '</div>'); ?>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-envelope"><span class="text-red"> *</span></i></span>
						<?= form_input(array('id'=>'id_email', 'class'=>'form-control', 'name'=>'email', 'value'=>$member_information['obfuscated_email'], 'required'=>'required', 'placeholder'=>'email')) ?>
					</div>
             	</div>
             	<div class="col-sm-6">
             		<?= form_error('telephone', '<div class="text-red">', '</div>'); ?>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-phone"><span class="text-red"> *</span></i></span>
						<?= form_input(array('id'=>'id_telephone', 'class'=>'form-control', 'name'=>'telephone', 'value'=>$member_information['obfuscated_telephone'], 'required'=>'required', 'placeholder'=>'telephone / mobile')) ?>
					</div>
             	</div>
			</div>
			<br>
            <?php else:?>
            <div class="row">
             	<div class="col-sm-6">
             		<?= form_error('email', '<div class="text-red">', '</div>'); ?>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-envelope"><span class="text-red"> *</span></i></span>
						<?= form_input(array('id'=>'id_email', 'class'=>'form-control', 'name'=>'email', 'value'=>set_value('email'), 'required'=>'required', 'placeholder'=>'email')) ?>
					</div>
             	</div>
             	<div class="col-sm-6">
             		<?= form_error('telephone', '<div class="text-red">', '</div>'); ?>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-phone"><span class="text-red"> *</span></i></span>
						<?= form_input(array('id'=>'id_telephone', 'class'=>'form-control', 'name'=>'telephone', 'value'=>set_value('telephone'), 'required'=>'required', 'placeholder'=>'telephone / mobile')) ?>
					</div>
             	</div>
			</div>
			<br>
			<?php endif;?>
            <!-- end form fields -->
                    
                    </div>
                  </div>
                </li>
                <!-- END timeline item -->
                <!-- timeline item -->
                <li>
                  <i class="fa fa-shield bg-yellow"></i>
                  <div class="timeline-item">
                    <span class="time"><i class="fa fa-tags"></i> optional</span>
                    <h3 class="timeline-header text-light-blue">Next of Kin</h3>
                    <div class="timeline-body">
                      
                    <!-- form fields -->
		             <div class="row">
		             	<div class="col-sm-6">
		             		<?= form_error('nok_name', '<div class="text-red">', '</div>'); ?>
							<div class="input-group">
								<span class="input-group-addon"><i class="fa fa-user"></i></span>
								<?= form_input(array('id'=>'id_nok_name', 'class'=>'form-control', 'name'=>'nok_name', 'value'=>set_value('nok_name'), 'placeholder'=>'name')) ?>
							</div>
		             	</div>
		             	<div class="col-sm-6">
		             		<?= form_error('nok_country', '<div class="text-red">', '</div>'); ?>
							<div class="input-group">
								<span class="input-group-addon"><i class="fa fa-flag"></i></span>
								<?= form_dropdown('nok_country', array(set_value('nok_country')=>set_value('nok_country')), array(set_value('nok_country')=>set_value('nok_country')), 'id="id_nok_country" class="form-control country_ajax_dropdown" data-placeholder="country" onChange="addCountryCode(this, \'id_nok_telephone\');"') ?>
							</div>
		             	</div>
					</div>
					<br>
		            <div class="row">
		             	<div class="col-sm-6">
		             		<?= form_error('nok_email', '<div class="text-red">', '</div>'); ?>
							<div class="input-group">
								<span class="input-group-addon"><i class="fa fa-envelope"></i></span>
								<?= form_input(array('id'=>'id_nok_email', 'class'=>'form-control', 'name'=>'nok_email', 'value'=>set_value('nok_email'), 'placeholder'=>'email')) ?>
							</div>
		             	</div>
		             	<div class="col-sm-6">
		             		<?= form_error('nok_telephone', '<div class="text-red">', '</div>'); ?>
							<div class="input-group">
								<span class="input-group-addon"><i class="fa fa-phone"></i></span>
								<?= form_input(array('id'=>'id_nok_telephone', 'class'=>'form-control', 'name'=>'nok_telephone', 'value'=>set_value('nok_telephone'), 'placeholder'=>'telephone / mobile')) ?>
							</div>
		             	</div>
					</div>
					<br>
                    <!-- end form fields -->
                    
                    </div>
                  </div>
                </li>
                <!-- END timeline item -->
                <!-- timeline item -->
                <li>
                  <i class="fa fa-building bg-purple"></i>
                  <div class="timeline-item">
                    <span class="time"><i class="fa fa-tags"></i> mandatory</span>
                    <h3 class="timeline-header text-light-blue">Employment/Business Details</h3>
                    <div class="timeline-body">
                    
             <!-- form fields -->
            <div class="row">
             	<div class="col-sm-6">
             		<?= form_error('emp_organisation', '<div class="text-red">', '</div>'); ?>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-building"><span class="text-red"> *</span></i></span>
						<?= form_dropdown('emp_organisation', array(set_value('emp_organisation') => set_value('emp_organisation')), array(set_value('emp_organisation') => set_value('emp_organisation')), 'id="id_emp_organisation" class="form-control org_ajax_search" data-placeholder="organisation" required="required"') ?>
					</div>
             	</div>
             	<div class="col-sm-6">
             		<?= form_error('emp_industry', '<div class="text-red">', '</div>'); ?>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-tag"></i></span>
						<?= form_dropdown('emp_industry', array(set_value('emp_industry') => set_value('emp_industry')), array(set_value('emp_industry') => set_value('emp_industry')), 'id="id_emp_industry" class="form-control industry_ajax_search" data-placeholder="industry"') ?>
					</div>
             	</div>
			</div>
			<br>
            <div class="row">
             	<div class="col-sm-6">
             		<?= form_error('emp_country', '<div class="text-red">', '</div>'); ?>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-flag"></i></span>
						<?= form_dropdown('emp_country', array(set_value('emp_country')=>set_value('emp_country')), array(set_value('emp_country')=>set_value('emp_country')), 'id="id_emp_country" class="form-control country_ajax_dropdown" data-placeholder="country" onChange="addCountryCode(this, \'id_emp_telephone\');"') ?>
					</div>
             	</div>
             	<div class="col-sm-6">
             		<?= form_error('emp_job_title', '<div class="text-red">', '</div>'); ?>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-gear"><span class="text-red"> *</span></i></span>
						<?= form_dropdown('emp_job_title', array(set_value('emp_job_title') => set_value('emp_job_title')), array(set_value('emp_job_title') => set_value('emp_job_title')), 'id="id_emp_job_title" class="form-control job_title_ajax_search" data-placeholder="job title" required="required"') ?>
					</div>
             	</div>
			</div>
			<br>
            <div class="row">
             	<div class="col-sm-6">
             		<?= form_error('emp_email', '<div class="text-red">', '</div>'); ?>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-envelope"></i></span>
						<?= form_input(array('id'=>'id_emp_email', 'class'=>'form-control', 'name'=>'emp_email', 'value'=>set_value('emp_email'), 'placeholder'=>'email')) ?>
					</div>
             	</div>
             	<div class="col-sm-6">
             		<?= form_error('emp_telephone', '<div class="text-red">', '</div>'); ?>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-phone"></i></span>
						<?= form_input(array('id'=>'id_emp_telephone', 'class'=>'form-control', 'name'=>'emp_telephone', 'value'=>set_value('emp_telephone'), 'placeholder'=>'telephone')) ?>
					</div>
             	</div>
			</div>
			<br>
            <!-- end form fields -->  

                    </div>
                  </div>
                </li>
                <!-- END timeline item -->
                <?php if ( ! isset($member_information)):?>
                <!-- timeline item -->
                <li>
                  <i class="fa fa-bank bg-maroon"></i>
                  <div class="timeline-item">
                    <span class="time"><i class="fa fa-tags"></i> optional</span>
                    <h3 class="timeline-header text-light-blue">Accountancy Body</h3>
                    <div class="timeline-body">
                    <?php 
                    	$acc_body_member = set_value('acc_body_member');
                    	$acc_body_member_checked = array(
                    		'YES' => false,
							'NO' => false,
                    	);
                    	if ($acc_body_member == 'YES')
                    	{
                    		$acc_body_member_checked['YES'] = true;
                    	}
                    	elseif ($acc_body_member == 'NO')
                    	{
                    		$acc_body_member_checked['NO'] = true;
                    	}
                    ?>
                    <!-- form fields -->
		            <div class="row">
		             	<div class="col-sm-6">
		             		<label>are you a member of an accountancy body?</label>
		             		<?= form_error('acc_body_member', '<div class="text-red">', '</div>'); ?>
		             		<div class="form-group">
		                      <div class="radio">
		                        <label>
		                          <?= form_radio(array('name'=>"acc_body_member","value"=>"YES", "label"=>"Yes", 'checked'=>$acc_body_member_checked['YES']));?>
		                          Yes
		                        </label>
		                      </div>
		                      <div class="radio">
		                        <label>
		                          <?= form_radio(array('name'=>"acc_body_member","value"=>"NO","label"=>"No", 'checked'=>$acc_body_member_checked['NO']));?>
		                          No
		                        </label>
		                      </div>
		                    </div>
		             	</div>
		             	<div class="col-sm-6">
		             	</div>
					</div>
					<div class="row" id="acc_body_block">
						<div class="col-sm-12">
		             		<?= form_error('acc_body[]', '<div class="text-red">', '</div>'); ?>
							<div class="input-group">
								<span class="input-group-addon"><i class="fa fa-bank"></i></span>
								<?= form_multiselect('acc_body[]', null, null, 'id="id_acc_body" class="form-control acc_body_ajax_search" data-placeholder="accountancy body"') ?>
							</div>
							<br>
						</div>
					</div>
					
                    <!-- end form fields -->
                    
                    </div>
                  </div>
                </li>
                <!-- END timeline item -->
                <?php endif;?>
                <!-- timeline item -->
                <li>
                  <i class="fa fa-ambulance bg-red"></i>
                  <div class="timeline-item">
                    <span class="time"><i class="fa fa-tags"></i> optional</span>
                    <h3 class="timeline-header text-light-blue">Medical Insurance</h3>
                    <div class="timeline-body">
                    <?php 
                    	$insurance = set_value('insurance');
                    	$insurance_checked = array(
                    		'YES' => false,
							'NO' => false,
                    	);
                    	if ($insurance == 'YES')
                    	{
                    		$insurance_checked['YES'] = true;
                    	}
                    	elseif ($insurance == 'NO')
                    	{
                    		$insurance_checked['NO'] = true;
                    	}
                    ?>
                    <!-- form fields -->
		            <div class="row">
		             	<div class="col-sm-6">
		             		<label>do you have medical insurance?</label>
		             		<div class="form-group">
		                      <div class="radio">
		                        <label>
		                          <?= form_radio(array('name'=>"insurance","value"=>"YES", "label"=>"Yes", 'checked'=>$insurance_checked['YES']));?>
		                          Yes
		                        </label>
		                      </div>
		                      <div class="radio">
		                        <label>
		                          <?= form_radio(array('name'=>"insurance","value"=>"NO","label"=>"No", 'checked'=>$insurance_checked['NO']));?>
		                          No
		                        </label>
		                      </div>
		                    </div>
		             	</div>
		             	<div class="col-sm-6">
		             	</div>
					</div>
					<div id="insurance_warning_block" class="callout callout-danger" style="display: none;">
						Please arrange to get medical insurance and return to indicate it. You will receive instructions by email indicating how to update this information at a later time when you have it.
					</div>
					<div class="row" id="insurance_block">
						<div class="col-sm-12">
		             		<?= form_error('insurance_body', '<div class="text-red">', '</div>'); ?>
							<div class="input-group">
								<span class="input-group-addon"><i class="fa fa-ambulance"></i></span>
								<?= form_dropdown('insurance_body', array(set_value('insurance_body') => set_value('insurance_body')), array(set_value('insurance_body') => set_value('insurance_body')), 'id="id_insurance_body" class="form-control insurance_body_ajax_search" data-placeholder="Insurance Organisation"') ?>
							</div>
							<br>
						</div>
					</div>
                    <!-- end form fields -->
                    
                    </div>
                  </div>
                </li>
                <!-- END timeline item -->
               <!-- timeline item -->
                <li>
                  <i class="fa fa-hotel bg-teal"></i>
                  <div class="timeline-item">
                    <span class="time"><i class="fa fa-tags"></i> mandatory</span>
                    <h3 class="timeline-header text-light-blue">Hotel Details</h3>
                    <div class="timeline-body">
                    <!-- form fields -->
                    You may optionally select a hotel among our partner hotels below.<br><br>
		            <div class="row">
		             	<div class="col-sm-6">
		             		<?= form_error('hotel', '<div class="text-red">', '</div>'); ?>
							<div class="input-group">
								<span class="input-group-addon"><i class="fa fa-hotel"></i></span>
								<?php 
									$event_rates = $this->config->item('event_rates');
									$event_item_labels = $this->config->item('event_item_labels');
									
									if (isset($member_information))
									{
										$hotel_choices = array(
											'MEM_NON_RESIDENT' => $event_item_labels['MEM_NON_RESIDENT'].': '.number_format($event_rates['MEM_NON_RESIDENT']),
											'MEM_RSRT_BEACH' => $event_item_labels['MEM_RSRT_BEACH'].': '.number_format($event_rates['MEM_RSRT_BEACH']),
											'MEM_GOLF_VW' => $event_item_labels['MEM_GOLF_VW'].': '.number_format($event_rates['MEM_GOLF_VW']),
										);
									}
									else
									{
										$hotel_choices = array(
											'NON-MEM_NON_RESIDENT' => $event_item_labels['NON-MEM_NON_RESIDENT'].': '.number_format($event_rates['NON-MEM_NON_RESIDENT']),
											'NON-MEM_RSRT_BEACH' => $event_item_labels['NON-MEM_RSRT_BEACH'].': '.number_format($event_rates['NON-MEM_RSRT_BEACH']),
											'NON-MEM_GOLF_VW' => $event_item_labels['NON-MEM_GOLF_VW'].': '.number_format($event_rates['NON-MEM_GOLF_VW']),
										);
									}
								?>
								<?= form_dropdown('hotel', $hotel_choices, array(set_value('hotel')), 'id="id_hotel" class="form-control" data-placeholder="Residence"') ?>
							</div>
		             	</div>
		             	
		             	<div class="col-sm-6">
		             	<!-- 
		             		<?= form_error('hotel_room_type', '<div class="text-red">', '</div>'); ?>
							<div class="input-group">
								<span class="input-group-addon"><i class="fa fa-key"></i></span>
								<?= form_input(array('id'=>'id_hotel_room_type', 'class'=>'form-control', 'name'=>'hotel_room_type', 'value'=>set_value('hotel_room_type'), 'placeholder'=>'room type')) ?>
							</div>
						-->
		             	</div>
					</div>
					<br>                    
                    <!-- end form fields -->
                    
                    </div>
                  </div>
                </li>
                <!-- END timeline item -->
                <!-- timeline item -->
                <li>
                  <i class="fa fa-users bg-green"></i>
                  <div class="timeline-item">
                    <span class="time"><i class="fa fa-tags"></i> optional</span>
                    <h3 class="timeline-header text-light-blue">Accompanying Persons</h3>
                    <div class="timeline-body">
                    <!-- form fields -->
                    <ul>
                    <li>An accompanying person applies to a person coming with you but will not attend the conference. </li>
                    <li>Any person who will be attending the conference must register on their own.</li>
                    <li>You may add upto 10 accompanying persons you would like to travel with.</li>
                    </ul>
                    
                    <?php if (isset($accompanying_persons) && count($accompanying_persons) > 0):?>
                    <?php $counter = 0;?>
                    <?php foreach ($accompanying_persons as &$A):?>
                    <?php $counter += 1;?>
                        <div id="acc_person_template<?='_f'.$counter?>">
		                    <div class="page-header" style="margin-bottom:15px;padding-bottom:3px;">
		                    	<div class="row">
		                    		<div class="col-sm-6">
		                    			<i class="fa fa-user"></i> &nbsp;&nbsp;&nbsp;<span style="font-size: 18px;color:#777;">Accompanying Person</span>
		                    		</div>
		                    		<div class="col-sm-6 text-right">
		                    			<button class="btn btn-danger btn-xs" type="button" onclick="deleteElement(this.parentNode.parentNode.parentNode.parentNode.id);"><i class="fa fa-user-times"></i></button>
		                    		</div>
		                    	</div>
		                    </div>
				             <div class="row">
				             	<div class="col-sm-6">
				             		<?php if (in_array('name', $A['ERRORS']['missing'])):?><div class="text-red">this information is required</div><?php endif;?>
				             		<?php if (in_array('name', $A['ERRORS']['invalid'])):?><div class="text-red">this information is invalid</div><?php endif;?>
				             		<div class="input-group">
										<span class="input-group-addon"><i class="fa fa-user"><span class="text-red"> *</span></i></span>
										<?= form_input(array('id'=>'id_accomp_name', 'class'=>'form-control', 'name'=>'accomp_name[]', 'value'=>$A['name'], 'placeholder'=>'name')) ?>
									</div>
				             	</div>
				             	<div class="col-sm-6">
				             		<?php if (in_array('dob', $A['ERRORS']['missing'])):?><div class="text-red">this information is required</div><?php endif;?>
				             		<?php if (in_array('dob', $A['ERRORS']['invalid'])):?><div class="text-red">this information is invalid</div><?php endif;?>
				             		<div class="input-group">
										<span class="input-group-addon"><i class="fa fa-calendar"><span class="text-red"> *</span></i></span>
				             			<?= form_input(array('id'=>'id_accomp_dob', 'class'=>'form-control general_date_select', 'name'=>'accomp_dob[]', 'value'=>$A['dob'], 'placeholder'=>'date of birth')) ?>
			             			</div>
				             	</div>
							</div>
							<br>
				             <div class="row">
				             	<div class="col-sm-6">
				             		<?php if (in_array('gender', $A['ERRORS']['missing'])):?><div class="text-red">this information is required</div><?php endif;?>
				             		<?php if (in_array('gender', $A['ERRORS']['invalid'])):?><div class="text-red">this information is invalid</div><?php endif;?>
				             		<div class="input-group">
										<span class="input-group-addon"><i class="fa fa-venus-mars"><span class="text-red"> *</span></i></span>
										<?= form_dropdown('accomp_gender[]', array($A['gender']=>ucwords(strtolower($A['gender']))), array($A['gender']=>ucwords(strtolower($A['gender']))), 'id="id_accomp_gender" class="form-control gender-dropdown" data-placeholder="gender"') ?>
									</div>
				             	</div>
				             	<div class="col-sm-6">
				             		<?php if (in_array('national_id', $A['ERRORS']['missing'])):?><div class="text-red">this information is required</div><?php endif;?>
				             		<?php if (in_array('national_id', $A['ERRORS']['invalid'])):?><div class="text-red">this information is invalid</div><?php endif;?>
				   					<div class="input-group">
										<span class="input-group-addon"><i class="fa fa-tag"></i><span class="text-red"> *Ugandans</span></span>
										<?= form_input(array('id'=>'id_accomp_national_id', 'class'=>'form-control', 'name'=>'accomp_national_id[]', 'value'=>$A['national_id'], 'placeholder'=>'national ID')) ?>
			             			</div>
				             	</div>
							</div>
							<br>
							<label>passport details</label><span class="text-red"> *non-Ugandans</span>
				            <div class="row">
				             	<div class="col-sm-6">
				             		<?php if (in_array('passport_no', $A['ERRORS']['missing'])):?><div class="text-red">this information is required</div><?php endif;?>
				             		<?php if (in_array('passport_no', $A['ERRORS']['invalid'])):?><div class="text-red">this information is invalid</div><?php endif;?>
				             		<div class="input-group">
										<span class="input-group-addon"><i class="fa fa-qrcode"><span class="text-red"> *</span></i></span>
										<?= form_input(array('id'=>'id_accomp_passport_no', 'class'=>'form-control', 'name'=>'accomp_passport_no[]', 'value'=>$A['passport_no'], 'placeholder'=>'passport number')) ?>
									</div>
				             	</div>
				             	<div class="col-sm-6">
				             		<?php if (in_array('passport_issue_place', $A['ERRORS']['missing'])):?><div class="text-red">this information is required</div><?php endif;?>
				             		<?php if (in_array('passport_issue_place', $A['ERRORS']['invalid'])):?><div class="text-red">this information is invalid</div><?php endif;?>
				             		<div class="input-group">
										<span class="input-group-addon"><i class="fa fa-map-o"><span class="text-red"> *</span></i></span>
										<?= form_input(array('id'=>'id_accomp_passport_issue_place', 'class'=>'form-control', 'name'=>'accomp_passport_issue_place[]', 'value'=>$A['passport_issue_place'], 'placeholder'=>'place of issue')) ?>
									</div>
				             	</div>
							</div>
							<br>
				            <div class="row">
				             	<div class="col-sm-6">
				             		<?php if (in_array('passport_issue_date', $A['ERRORS']['missing'])):?><div class="text-red">this information is required</div><?php endif;?>
				             		<?php if (in_array('passport_issue_date', $A['ERRORS']['invalid'])):?><div class="text-red">this information is invalid</div><?php endif;?>
				             		<div class="input-group">
										<span class="input-group-addon"><i class="fa fa-calendar"><span class="text-red"> *</span></i></span>
										<?= form_input(array('id'=>'id_accomp_passport_issue_date', 'class'=>'form-control general_date_select', 'name'=>'accomp_passport_issue_date[]', 'value'=>$A['passport_issue_date'], 'placeholder'=>'date of issue')) ?>
									</div>
				             	</div>
				             	<div class="col-sm-6">
				             		<?php if (in_array('passport_expiry_date', $A['ERRORS']['missing'])):?><div class="text-red">this information is required</div><?php endif;?>
				             		<?php if (in_array('passport_expiry_date', $A['ERRORS']['invalid'])):?><div class="text-red">this information is invalid</div><?php endif;?>
				             		<div class="input-group">
										<span class="input-group-addon"><i class="fa fa-calendar"><span class="text-red"> *</span></i></span>
										<?= form_input(array('id'=>'id_accomp_passport_expiry_date', 'class'=>'form-control general_date_select', 'name'=>'accomp_passport_expiry_date[]', 'value'=>$A['passport_expiry_date'], 'placeholder'=>'date of expiry')) ?>
									</div>
				             	</div>
							</div>
							<br>
						</div>
                    <?php endforeach;?>
                    <?php endif;?>
                    <div id="acc_person_template" style="display:none;">
	                    <div class="page-header" style="margin-bottom:15px;padding-bottom:3px;">
	                    	<div class="row">
	                    		<div class="col-sm-6">
	                    			<i class="fa fa-user"></i> &nbsp;&nbsp;&nbsp;<span style="font-size: 18px;color:#777;">Accompanying Person</span>
	                    		</div>
	                    		<div class="col-sm-6 text-right">
	                    			<button class="btn btn-danger btn-xs" type="button" onclick="deleteElement(this.parentNode.parentNode.parentNode.parentNode.id);"><i class="fa fa-user-times"></i></button>
	                    		</div>
	                    	</div>
	                    </div>
			             <div class="row">
			             	<div class="col-sm-6">
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-user"><span class="text-red"> *</span></i></span>
									<?= form_input(array('id'=>'id_accomp_name', 'class'=>'form-control', 'name'=>'accomp_name[]', 'placeholder'=>'name')) ?>
								</div>
			             	</div>
			             	<div class="col-sm-6">
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-calendar"><span class="text-red"> *</span></i></span>
			             			<?= form_input(array('id'=>'id_accomp_dob', 'class'=>'form-control general_date_select', 'name'=>'accomp_dob[]', 'placeholder'=>'date of birth')) ?>
		             			</div>
			             	</div>
						</div>
						<br>
			             <div class="row">
			             	<div class="col-sm-6">
			             		<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-venus-mars"><span class="text-red"> *</span></i></span>
									<?= form_dropdown('accomp_gender[]', array(), array(), 'id="id_accomp_gender" class="form-control h_gender-dropdown" data-placeholder="gender"') ?>
								</div>
			             	</div>
			             	<div class="col-sm-6">
	             				<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-tag"></i><span class="text-red"> *Ugandans</span></span>
									<?= form_input(array('id'=>'id_accomp_national_id', 'class'=>'form-control', 'name'=>'accomp_national_id[]', 'placeholder'=>'national ID')) ?>
		             			</div>
			             	</div>
						</div>
						<br>
						<label>passport details</label><span class="text-red"> &nbsp; *non-Ugandans</span>
			            <div class="row">
			             	<div class="col-sm-6">
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-qrcode"><span class="text-red"> *</span></i></span>
									<?= form_input(array('id'=>'id_accomp_passport_no', 'class'=>'form-control', 'name'=>'accomp_passport_no[]', 'placeholder'=>'passport number')) ?>
								</div>
			             	</div>
			             	<div class="col-sm-6">
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-map-o"><span class="text-red"> *</span></i></span>
									<?= form_input(array('id'=>'id_accomp_passport_issue_place', 'class'=>'form-control', 'name'=>'accomp_passport_issue_place[]', 'placeholder'=>'place of issue')) ?>
								</div>
			             	</div>
						</div>
						<br>
			            <div class="row">
			             	<div class="col-sm-6">
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-calendar"><span class="text-red"> *</span></i></span>
									<?= form_input(array('id'=>'id_accomp_passport_issue_date', 'class'=>'form-control general_date_select', 'name'=>'accomp_passport_issue_date[]', 'placeholder'=>'date of issue')) ?>
								</div>
			             	</div>
			             	<div class="col-sm-6">
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-calendar"><span class="text-red"> *</span></i></span>
									<?= form_input(array('id'=>'id_accomp_passport_expiry_date', 'class'=>'form-control general_date_select', 'name'=>'accomp_passport_expiry_date[]', 'placeholder'=>'date of expiry')) ?>
								</div>
			             	</div>
						</div>
						<br>
					</div>
					<!-- end form fields -->
                    </div>
                    <div class="timeline-footer">
                		<button class="btn bg-maroon btn-sm" type="button" onclick="copyAndPasteElement('acc_person_template');"><i class="fa fa-user-plus"></i> Add an Accompanying Person</button>
            		</div>
                  </div>
                </li>
                <!-- END timeline item -->

                <li>
                  <i class="fa fa-check bg-gray"></i>
                  <div class="timeline-item">
                  	<div class="timeline-footer text-right">
                  		<?=form_submit(array('type'=>'submit', 'name'=>'register', 'value'=>'Register', 'class'=>'btn btn-primary'))?>
                  	</div>
                  </div>
                </li>
              </ul>
            </div><!-- /.col -->
            <div class="col-md-2"></div>
          </div><!-- /.row -->
	<br><br><br><br><br><br>
<!-- end of body -->
<?=form_close()?>
<script type="text/javascript">
	 
var MAX_COPY_PASTE_ENTRIES = 10;
var COPY_PASTE_REGISTER = {};

function copyAndPasteElement(reference_node_id)
{
	if ($.inArray(reference_node_id, COPY_PASTE_REGISTER) && COPY_PASTE_REGISTER[reference_node_id] < MAX_COPY_PASTE_ENTRIES)
	{
		COPY_PASTE_REGISTER[reference_node_id] += 1;
	}
	else if ($.inArray(reference_node_id, COPY_PASTE_REGISTER) && COPY_PASTE_REGISTER[reference_node_id] >= MAX_COPY_PASTE_ENTRIES)
	{
		return;
	}
	else
	{
		COPY_PASTE_REGISTER[reference_node_id] = 1;
	}
	
	var referenceNode = document.getElementById(reference_node_id);
	if (referenceNode) {
		$( "#"+reference_node_id ).before('<div id="' + reference_node_id + COPY_PASTE_REGISTER[reference_node_id] + '">' + referenceNode.innerHTML + '</div>');
	}

	// initialise javascript on the new elements
	$( "#"+reference_node_id+COPY_PASTE_REGISTER[reference_node_id]).find('.general_datetime_select').daterangepicker({
		singleDatePicker: true,
		showDropdowns: true, 
		timePicker: true, 
		timePickerIncrement: 5, 
		timePicker12Hour: false, 
		format: 'YYYY-MM-DD HH:mm', 
	});
	$( "#"+reference_node_id+COPY_PASTE_REGISTER[reference_node_id]).find('.general_date_select').daterangepicker({
		singleDatePicker: true, 
		showDropdowns: true, 
		format: 'YYYY-MM-DD', 
	});
	$( "#"+reference_node_id+COPY_PASTE_REGISTER[reference_node_id]).find('.h_country_ajax_dropdown').select2({
		allowClear: true,
		ajax: {
			url: '<?= site_url('registration/generic_form_search_json/?c=country')?>',
			dataType: 'json',
			delay: 250,
		},
	});
	$( "#"+reference_node_id+COPY_PASTE_REGISTER[reference_node_id]).find('.h_gender-dropdown').select2({
		allowClear: true, data: [{id: '', text: ''}, {id: 'FEMALE', text: 'Female'}, {id: 'MALE', text: 'Male'}],
	});
	
}

function deleteElement(node_id)
{
	$( "#"+node_id ).remove();
	
	if ($.inArray(node_id, COPY_PASTE_REGISTER))
	{
		COPY_PASTE_REGISTER[node_id] -= 1;
	}
}

function addCountryCode(country_field, to_field)
{
	var country_id = country_field.value;
	
	$.ajax({
		url: '<?= site_url('registration/generic_form_search_json/?c=country_dial_code_from_name')?>' + '&q=' + country_id,
		cache: false,
		success: function(output){
			$("#"+to_field).val('+' + output[country_id]);
		}
	});
}
</script>
