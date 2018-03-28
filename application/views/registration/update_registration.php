<br>
<!-- row -->
<div class="row">
	<div class="col-md-2"></div>
	<div class="col-md-8">

<?php if ( ! $reg):?>
	<div class="callout callout-danger">
		<h4>Error</h4>
		<p>The registration information requested doesn't exist.</p>
	</div>
<?php endif;?>

<?php if ($reg):?>
	<?=form_open(site_url('/registration/update'))?>
		<input type="hidden" name="reg" value="<?=$reg['registration_code']?>"  id="id_reg" />
		<input type="hidden" name="uc" value="<?=$reg['update_registration_code']?>"  id="id_uc" />

              <!-- The time line -->
              <ul class="timeline">
                <!-- timeline item -->
                <li>
                  <i class="fa fa-edit bg-gray"></i>
                  <div class="timeline-item">
                  	<h3 class="text-light-blue">Update Registration Information</h3>
                  	<div class="timeline-body">
	                  	<!-- info -->
	                  	You may update your registration information now or at a later time when it is available.
	                  	<!-- end info -->
                  	</div>
                  </div>
                </li>
              <!-- END timeline item -->
              <!-- timeline item -->
                <li>
                  <i class="fa fa-user bg-blue"></i>
                  <div class="timeline-item">
                    <h3 class="timeline-header" style="font-size:24px;"><?php if ($reg['gender'] == 'MALE'){ echo 'Mr.';}else{ echo 'Ms.';}?> <?=$reg['first_name']?> <?=$reg['last_name']?> <?=$reg['other_names']?></h3>
                 	<?php if (isset($unpaid_invoices) && count($unpaid_invoices) > 0):?>
	                 	<div id="insurance_warning_block" class="callout callout-danger">
						You have unpaid invoices. <a href="<?=site_url("/registration/payment_options/{$reg['registration_code']}/{$reg['update_registration_code']}/1")?>">Click here</a> to make payment against your unpaid invoices.
						</div>
                 	<?php endif;?>
                  </div>
                </li>
                <!-- END timeline item -->
                <?php if (empty($reg['insurance']) || $reg['insurance'] == 'NO' || empty($reg['insurance_body'])):?>
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
             		<?= form_error('acc_body_member', '<div class="text-red">', '</div>'); ?>
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
				Please arrange to get medical insurance and return to indicate it.
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
 			<?php endif;?>
                
   		<?php if ($reg['nationality'] != $this->config->item('local_country')):?>
              <!-- timeline item -->
                <li>
                  <i class="fa fa-plane bg-red"></i>
                  <div class="timeline-item">
                    <span class="time"><i class="fa fa-tags"></i> optional</span>
                    <h3 class="timeline-header text-light-blue">Travel Details</h3>
                    <div class="timeline-body">
                    
                    <!-- form fields -->
		            <div class="row">
		             	<div class="col-sm-6">
		             		<?= form_error('travel_from_country', '<div class="text-red">', '</div>'); ?>
							<div class="input-group">
								<span class="input-group-addon"><i class="fa fa-flag"></i></span>
								<?php 
									$travel_from_country_val = set_value('travel_from_country');
									if (empty($travel_from_country_val))
									{
										$travel_from_country_val = $reg['travel_from_country'];
									}
								?>
								<?= form_dropdown('travel_from_country', array($travel_from_country_val=>$travel_from_country_val), array($travel_from_country_val=>$travel_from_country_val), 'id="id_travel_from_country" class="form-control country_ajax_dropdown" data-placeholder="country of departure"') ?>
							</div>
		             	</div>
		             	<div class="col-sm-6">
		             		<?= form_error('flight_no', '<div class="text-red">', '</div>'); ?>
							<div class="input-group">
								<span class="input-group-addon"><i class="fa fa-plane"></i></span>
								<?php 
									$flight_no_val = set_value('flight_no');
									if (empty($flight_no_val))
									{
										$flight_no_val = $reg['flight_no'];
									}
								?>
								<?= form_input(array('id'=>'id_flight_no','class'=>'form-control','name'=>'flight_no', 'value'=>$flight_no_val, 'placeholder'=>'Flight number')) ?>
							</div>
		             	</div>
					</div>
					<br>
		            <div class="row">
		             	<div class="col-sm-6">
		             		<?= form_error('travel_arrival_date', '<div class="text-red">', '</div>'); ?>
							<div class="input-group">
								<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
								<?php 
									$travel_arrival_date_val = set_value('travel_arrival_date');
									if (empty($travel_arrival_date_val))
									{
										$travel_arrival_date_val = $reg['travel_arrival_date'];
									}
								?>
								<?= form_input(array('id'=>'id_travel_arrival_date', 'class'=>'form-control general_datetime_select', 'name'=>'travel_arrival_date', 'value'=>$travel_arrival_date_val, 'placeholder'=>'expected arrival date and time')) ?>
							</div>
		             	</div>
		             	<div class="col-sm-6">
		             		<?= form_error('travel_departure_date', '<div class="text-red">', '</div>'); ?>
							<div class="input-group">
								<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
								<?php 
									$travel_departure_date_val = set_value('travel_departure_date');
									if (empty($travel_departure_date_val))
									{
										$travel_departure_date_val = $reg['travel_departure_date'];
									}
								?>
								<?= form_input(array('id'=>'id_travel_departure_date', 'class'=>'form-control general_datetime_select', 'name'=>'travel_departure_date', 'value'=>$travel_departure_date_val, 'placeholder'=>'expected departure date and time')) ?>
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
               <!-- 
                <li>
                  <i class="fa fa-hotel bg-teal"></i>
                  <div class="timeline-item">
                    <span class="time"><i class="fa fa-tags"></i> optional</span>
                    <h3 class="timeline-header text-light-blue">Hotel Details</h3>
                    <div class="timeline-body">
              -->
                    <!-- form fields -->
              <!--       
                    You may optionally select a hotel from the ACOA partner hotels below for a discounted rate.<br><br>
		            <div class="row">
		             	<div class="col-sm-6">
		             		<?= form_error('hotel', '<div class="text-red">', '</div>'); ?>
							<div class="input-group">
								<span class="input-group-addon"><i class="fa fa-hotel"></i></span>
								<?php 
									$hotel_val = set_value('hotel');
									if (empty($hotel_val))
									{
										$hotel_val = $reg['hotel'];
									}
								?>
								<?= form_input(array('id'=>'id_hotel', 'class'=>'form-control', 'name'=>'hotel', 'value'=>$hotel_val, 'placeholder'=>'hotel')) ?>
							</div>
		             	</div>
		             	<div class="col-sm-6">
		             		<?= form_error('hotel_room_type', '<div class="text-red">', '</div>'); ?>
							<div class="input-group">
								<span class="input-group-addon"><i class="fa fa-key"></i></span>
								<?php 
									$hotel_room_type_val = set_value('hotel_room_type');
									if (empty($hotel_room_type_val))
									{
										$hotel_room_type_val = $reg['hotel_room_type'];
									}
								?>
								<?= form_input(array('id'=>'id_hotel_room_type', 'class'=>'form-control', 'name'=>'hotel_room_type', 'value'=>$hotel_room_type_val, 'placeholder'=>'room type')) ?>
							</div>
		             	</div>
					</div>
					<br>
		       -->                
                    <!-- end form fields -->
                <!--     
                    </div>
                  </div>
                </li>
                 -->
                <!-- END timeline item -->
		
	<!-- Accompanying Person -->
	<?php if ($reg['accompanying_persons']):?>
                <!-- timeline item -->
                <li>
                  <i class="fa fa-users bg-green"></i>
                  <div class="timeline-item">
                    <span class="time"><i class="fa fa-tags"></i> optional</span>
                    <h3 class="timeline-header text-light-blue">Accompanying Persons</h3>
                    <div class="timeline-body">
                    
                    <!-- form fields -->
		<?php foreach ($reg['accompanying_persons'] as &$P):?>
			<input type="hidden" name="accomp_reg_code[]" value="<?=$P['registration_code']?>"  id="id_accomp_reg_code" />
                    <div class="page-header" style="margin-bottom:15px;padding-bottom:3px;">
						<i class="fa fa-user"></i> &nbsp;&nbsp;&nbsp;<span style="font-size: 18px;color:#333;"><?=$P['first_name']?> <?=$P['last_name']?> <?=$P['other_names']?></span>
					</div>
			<?php if ($reg['nationality'] != $this->config->item('local_country')):?>
							<label>travel details</label>
				            <div class="row">
				             	<div class="col-sm-6">
				             		<?php if (in_array('travel_from_country', $P['ERRORS']['missing'])):?><div class="text-red">this information is required</div><?php endif;?>
				             		<?php if (in_array('travel_from_country', $P['ERRORS']['invalid'])):?><div class="text-red">this information is invalid</div><?php endif;?>
				             		<div class="input-group">
										<span class="input-group-addon"><i class="fa fa-flag"></i></span>
										<?= form_dropdown('accomp_travel_from_country[]', array($P['travel_from_country']=>$P['travel_from_country']), array($P['travel_from_country']=>$P['travel_from_country']), 'class="form-control country_ajax_dropdown" data-placeholder="country of departure"') ?>
									</div>
				             	</div>
				             	<div class="col-sm-6">
				             	</div>
	       					</div>
							<br>
			            <div class="row">
			             	<div class="col-sm-6">
			             		<?php if (in_array('travel_arrival_date', $P['ERRORS']['missing'])):?><div class="text-red">this information is required</div><?php endif;?>
			             		<?php if (in_array('travel_arrival_date', $P['ERRORS']['invalid'])):?><div class="text-red">this information is invalid</div><?php endif;?>
			             		<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
									<?= form_input(array('id'=>'id_accomp_travel_arrival_date', 'class'=>'form-control general_datetime_select', 'name'=>'accomp_travel_arrival_date[]', 'value'=>$P['travel_arrival_date'], 'placeholder'=>'expected arrival date and time')) ?>
								</div>
			             	</div>
			             	<div class="col-sm-6">
			             		<?php if (in_array('accomp_travel_departure_date', $P['ERRORS']['missing'])):?><div class="text-red">this information is required</div><?php endif;?>
			             		<?php if (in_array('accomp_travel_departure_date', $P['ERRORS']['invalid'])):?><div class="text-red">this information is invalid</div><?php endif;?>
			             		<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
									<?= form_input(array('id'=>'id_accomp_travel_departure_date', 'class'=>'form-control general_datetime_select', 'name'=>'accomp_travel_departure_date[]', 'value'=>$P['travel_departure_date'], 'placeholder'=>'expected departure date and time')) ?>
								</div>
			             	</div>
						</div>
						<br>
			<?php endif;?>
							<label>hotel details</label>
				            <div class="row">
				             	<div class="col-sm-6">
				             		<?php if (in_array('hotel', $P['ERRORS']['missing'])):?><div class="text-red">this information is required</div><?php endif;?>
				             		<?php if (in_array('hotel', $P['ERRORS']['invalid'])):?><div class="text-red">this information is invalid</div><?php endif;?>
				             		<div class="input-group">
										<span class="input-group-addon"><i class="fa fa-hotel"></i></span>
										<?= form_input(array('id'=>'id_accomp_hotel', 'class'=>'form-control', 'name'=>'accomp_hotel[]', 'value'=>$P['hotel'], 'placeholder'=>'hotel')) ?>
									</div>
				             	</div>
				             	<div class="col-sm-6">
				             		<?php if (in_array('hotel_room_type', $P['ERRORS']['missing'])):?><div class="text-red">this information is required</div><?php endif;?>
				             		<?php if (in_array('hotel_room_type', $P['ERRORS']['invalid'])):?><div class="text-red">this information is invalid</div><?php endif;?>
				             		<div class="input-group">
										<span class="input-group-addon"><i class="fa fa-key"></i></span>
										<?= form_input(array('id'=>'id_accomp_hotel_room_type', 'class'=>'form-control', 'name'=>'accomp_hotel_room_type[]', 'value'=>$P['hotel_room_type'], 'placeholder'=>'room type')) ?>
									</div>
				             	</div>
							</div>
							<br>
		<?php endforeach;?>
					<!-- end form fields -->
                    </div>
                  </div>
                </li>
                <!-- END timeline item -->
	<?php endif;?>
	
                <!-- timeline item -->
                <li>
                  <div class="timeline-item">
                    <div class="timeline-body">
                    <!-- form fields -->
                    <?php if (isset($new_accompanying_persons) && count($new_accompanying_persons) > 0):?>
                    <?php $counter = 0;?>
                    <?php foreach ($new_accompanying_persons as &$A):?>
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
										<?= form_input(array('id'=>'id_accomp_name', 'class'=>'form-control', 'name'=>'new_accomp_name[]', 'value'=>$A['name'], 'placeholder'=>'name')) ?>
									</div>
				             	</div>
				             	<div class="col-sm-6">
				             		<?php if (in_array('dob', $A['ERRORS']['missing'])):?><div class="text-red">this information is required</div><?php endif;?>
				             		<?php if (in_array('dob', $A['ERRORS']['invalid'])):?><div class="text-red">this information is invalid</div><?php endif;?>
				             		<div class="input-group">
										<span class="input-group-addon"><i class="fa fa-calendar"><span class="text-red"> *</span></i></span>
				             			<?= form_input(array('id'=>'id_accomp_dob', 'class'=>'form-control general_date_select', 'name'=>'new_accomp_dob[]', 'value'=>$A['dob'], 'placeholder'=>'date of birth')) ?>
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
										<?= form_dropdown('new_accomp_gender[]', array($A['gender']=>ucwords(strtolower($A['gender']))), array($A['gender']=>ucwords(strtolower($A['gender']))), 'id="id_accomp_gender" class="form-control gender-dropdown" data-placeholder="gender"') ?>
									</div>
				             	</div>
				             	<div class="col-sm-6">
				             		<?php if (in_array('national_id', $A['ERRORS']['missing'])):?><div class="text-red">this information is required</div><?php endif;?>
				             		<?php if (in_array('national_id', $A['ERRORS']['invalid'])):?><div class="text-red">this information is invalid</div><?php endif;?>
				   					<div class="input-group">
										<span class="input-group-addon"><i class="fa fa-tag"></i><span class="text-red"> *Ugandans</span></span>
										<?= form_input(array('id'=>'id_accomp_national_id', 'class'=>'form-control', 'name'=>'new_accomp_national_id[]', 'value'=>$A['national_id'], 'placeholder'=>'national ID')) ?>
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
										<?= form_input(array('id'=>'id_accomp_passport_no', 'class'=>'form-control', 'name'=>'new_accomp_passport_no[]', 'value'=>$A['passport_no'], 'placeholder'=>'passport number')) ?>
									</div>
				             	</div>
				             	<div class="col-sm-6">
				             		<?php if (in_array('passport_issue_place', $A['ERRORS']['missing'])):?><div class="text-red">this information is required</div><?php endif;?>
				             		<?php if (in_array('passport_issue_place', $A['ERRORS']['invalid'])):?><div class="text-red">this information is invalid</div><?php endif;?>
				             		<div class="input-group">
										<span class="input-group-addon"><i class="fa fa-map-o"><span class="text-red"> *</span></i></span>
										<?= form_input(array('id'=>'id_accomp_passport_issue_place', 'class'=>'form-control', 'name'=>'new_accomp_passport_issue_place[]', 'value'=>$A['passport_issue_place'], 'placeholder'=>'place of issue')) ?>
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
										<?= form_input(array('id'=>'id_accomp_passport_issue_date', 'class'=>'form-control general_date_select', 'name'=>'new_accomp_passport_issue_date[]', 'value'=>$A['passport_issue_date'], 'placeholder'=>'date of issue')) ?>
									</div>
				             	</div>
				             	<div class="col-sm-6">
				             		<?php if (in_array('passport_expiry_date', $A['ERRORS']['missing'])):?><div class="text-red">this information is required</div><?php endif;?>
				             		<?php if (in_array('passport_expiry_date', $A['ERRORS']['invalid'])):?><div class="text-red">this information is invalid</div><?php endif;?>
				             		<div class="input-group">
										<span class="input-group-addon"><i class="fa fa-calendar"><span class="text-red"> *</span></i></span>
										<?= form_input(array('id'=>'id_accomp_passport_expiry_date', 'class'=>'form-control general_date_select', 'name'=>'new_accomp_passport_expiry_date[]', 'value'=>$A['passport_expiry_date'], 'placeholder'=>'date of expiry')) ?>
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
									<?= form_input(array('id'=>'id_accomp_name', 'class'=>'form-control', 'name'=>'new_accomp_name[]', 'placeholder'=>'name')) ?>
								</div>
			             	</div>
			             	<div class="col-sm-6">
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-calendar"><span class="text-red"> *</span></i></span>
			             			<?= form_input(array('id'=>'id_accomp_dob', 'class'=>'form-control general_date_select', 'name'=>'new_accomp_dob[]', 'placeholder'=>'date of birth')) ?>
		             			</div>
			             	</div>
						</div>
						<br>
			             <div class="row">
			             	<div class="col-sm-6">
			             		<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-venus-mars"><span class="text-red"> *</span></i></span>
									<?= form_dropdown('new_accomp_gender[]', array(), array(), 'id="id_accomp_gender" class="form-control h_gender-dropdown" data-placeholder="gender"') ?>
								</div>
			             	</div>
			             	<div class="col-sm-6">
	             				<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-tag"></i><span class="text-red"> *Ugandans</span></span>
									<?= form_input(array('id'=>'id_accomp_national_id', 'class'=>'form-control', 'name'=>'new_accomp_national_id[]', 'placeholder'=>'national ID')) ?>
		             			</div>
			             	</div>
						</div>
						<br>
						<label>passport details</label><span class="text-red"> &nbsp; *non-Ugandans</span>
			            <div class="row">
			             	<div class="col-sm-6">
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-qrcode"><span class="text-red"> *</span></i></span>
									<?= form_input(array('id'=>'id_accomp_passport_no', 'class'=>'form-control', 'name'=>'new_accomp_passport_no[]', 'placeholder'=>'passport number')) ?>
								</div>
			             	</div>
			             	<div class="col-sm-6">
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-map-o"><span class="text-red"> *</span></i></span>
									<?= form_input(array('id'=>'id_accomp_passport_issue_place', 'class'=>'form-control', 'name'=>'new_accomp_passport_issue_place[]', 'placeholder'=>'place of issue')) ?>
								</div>
			             	</div>
						</div>
						<br>
			            <div class="row">
			             	<div class="col-sm-6">
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-calendar"><span class="text-red"> *</span></i></span>
									<?= form_input(array('id'=>'id_accomp_passport_issue_date', 'class'=>'form-control general_date_select', 'name'=>'new_accomp_passport_issue_date[]', 'placeholder'=>'date of issue')) ?>
								</div>
			             	</div>
			             	<div class="col-sm-6">
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-calendar"><span class="text-red"> *</span></i></span>
									<?= form_input(array('id'=>'id_accomp_passport_expiry_date', 'class'=>'form-control general_date_select', 'name'=>'new_accomp_passport_expiry_date[]', 'placeholder'=>'date of expiry')) ?>
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
                  		<?php #$CAPTCHA?>
                  		<!-- <input type="text" name="captcha" value="<?php if ( ! empty(form_error('captcha'))){echo set_value('captcha');} ?>" required="required" placeholder="type the characters you see" style="width: 200px;<?php if ( ! empty(form_error('captcha'))){ echo 'background-color:#DD4B39;color:#fff;';}?>" /> -->&nbsp; &nbsp; &nbsp; &nbsp;
                  		<?php if (isset($ext_req) && $ext_req):?>
                  			<input type="hidden" name="ext_req" value="1"  id="id_ext_req" />
                  		<?php else:?>
                  			<?=form_submit(array('type'=>'submit', 'name'=>'later', 'value'=>'Update Later', 'class'=>'btn btn-default'))?> &nbsp;&nbsp;
                  		<?php endif;?>
                  		<?=form_submit(array('type'=>'submit', 'name'=>'update', 'value'=>'Update Information', 'class'=>'btn btn-primary'))?>
                  	</div>
                  </div>
                </li>
			</ul>
	<?=form_close()?>
<?php endif;?>
	</div><!-- /.col -->
	<div class="col-md-2"></div>
</div><!-- /.row -->

<script type="text/javascript">
	 
var MAX_COPY_PASTE_ENTRIES = <?=10 - count($reg['accompanying_persons'])?>;
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
