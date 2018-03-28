<?=form_open()?>
<!-- row -->
          <div class="row">
          	<div class="col-md-2"></div>
            <div class="col-md-8">
              <!-- The time line -->
              <ul class="timeline">
               <!-- timeline item -->
                <li>
                  <i class="fa fa-file-text-o bg-maroon"></i>
                  <div class="timeline-item">
                    <h3 class="timeline-header text-light-blue"> Registration Guidelines</h3>
                   	<div class="timeline-body">
                    
					<!-- info -->
<?php $rates = $this->config->item('event_rates');?>
<div style="border:1px solid #ddd;padding:15px;">
	<ol>
	<li>A unique reference number will be sent to your email upon registration and it will be used all through the conference activities.</li>
	<li>Once payments are cleared, an email will be sent to your registered email address.</li>
	</ol><br>
	<h4>Conference Fees</h4>
	<table class="table table-condensed table-striped">
	<tr><th></th><th>Members / Students (UGX)</th><th>Non Members (UGX)</th></tr>
	<tr><td>Imperial Resort Beach Hotel</td><td><?=number_format($rates['MEM_RSRT_BEACH'])?></td><td><?=number_format($rates['NON-MEM_RSRT_BEACH'])?></td></tr>
	<tr><td>Imperial Golf View Hotel</td><td><?=number_format($rates['MEM_GOLF_VW'])?></td><td><?=number_format($rates['NON-MEM_GOLF_VW'])?></td></tr>
	<tr><td>Non-Resident</td><td><?=number_format($rates['MEM_NON_RESIDENT'])?></td><td><?=number_format($rates['NON-MEM_NON_RESIDENT'])?></td></tr>
	<!-- <tr><td>Accompanying Person**</td><td><?=$rates['MEM_ACCOMPANYING_PERSON']?></td><td><?=$rates['NON-MEM_ACCOMPANYING_PERSON']?></td></tr> -->
	</table>
	<br>
	**Accompanying Person is an individual that will not be attending the conference sessions but is eligible for all social functions of the Conference

	<h4>Important Notes</h4>
	<ul>
		<li>Only fully completed registration form will be processed</li>
		<li>Upon registering, delegate will be issued a Confirmation Letter to confirm his/her enrolment for the Conference</li>
		<li>Kindly correctly fill in all the required fields in the registration form in order to serve you better. No alteration will be allowed after the Confirmation Letter is issued</li>
		<li>Any cancellation of enrolment, Cancellation Policy* shall be applied</li>
		<li>Admittance to the Conference will only be permitted upon receipt of full payment by the payment deadline or before the conference day</li>
		<li>Payment should be made within 2 (two) weeks of registration otherwise, registration has to be redone.</li>
	</ul>
<!-- 	<br>
	<a href="<?= site_url('registration/terms')?>" target="blank"><i class="fa fa-info-circle"></i>&nbsp; Terms and conditions</a> -->
</div>
					<?= form_error('guidelines_seen', '<div class="text-red">', '</div>'); ?>
    <div class="checkbox">
	    <label>
	    <?= form_checkbox(array('name'=>'guidelines_seen','value'=>'guidelines_seen', 'required'=>'required'));?>
	    I have read and I understand the guidelines
	    </label>
    </div>
	<!-- end info -->
                    
                    </div>
                  </div>
                </li>
                <!-- END timeline item -->
                <!-- timeline item -->
                <li>
                  <i class="fa fa-bank bg-blue"></i>
                  <div class="timeline-item">
                    <span class="time"><i class="fa fa-tags"></i> mandatory</span>
                    <h3 class="timeline-header text-light-blue">ICPAU Members / Students</h3>
                    <div class="timeline-body">
                    
		            <div class="row">
		             	<div class="col-sm-6">
		             		<label>Are you an ICPAU Member or Student?</label>
		             		<div class="form-group">
		                      <div class="radio">
		                        <label>
		                          <?= form_radio(array('name'=>"icpau_member_option","value"=>"YES", "label"=>"Yes", 'required'=>'required'));?>
		                          Yes
		                        </label>
		                      </div>
		                      <div class="radio">
		                        <label>
		                          <?= form_radio(array('name'=>"icpau_member_option","value"=>"NO","label"=>"No"));?>
		                          No
		                        </label>
		                      </div>
		                    </div>
		             	</div>
		             	<div class="col-sm-6">
		             	</div>
					</div>
					
					<?= form_error('member_number', '<div class="text-red">', '</div>'); ?>
					
					<div class="row" id="non_icpau_member_block" style="display: none;">
						<div class="col-sm-6">
						</div>
					</div>
					
                    <div class="row" id="icpau_member_block" style="display: none;">
                    	<div class="col-sm-6">
		                    <p>Please enter your ICPAU Member or Student number below.</p>
		                    <div class="input-group">
								<span class="input-group-addon"><i class="fa fa-user"></i></span>
								<?= form_input(array('id'=>'id_member_number', 'class'=>'form-control', 'name'=>'member_number', 'value'=>set_value('member_number'), 'placeholder'=>'Member / Student Number')) ?>
							</div>
						</div>
					</div>
					
                    </div>
                  </div>
                </li>
                <!-- END timeline item -->
                <!-- timeline item -->
                <li>
                  <i class="fa fa-check bg-gray"></i>
                  <div class="timeline-item">
                  	<div class="timeline-footer text-right">
                  		<?php #$CAPTCHA ?>
                  		<!--
                  		<input type="text" name="captcha" value="<?php if ( ! empty(form_error('captcha'))){echo set_value('captcha');} ?>" required="required" placeholder="type the characters you see" style="width: 200px;<?php if ( ! empty(form_error('captcha'))){ echo 'background-color:#DD4B39;color:#fff;';}?>" /> -->&nbsp; &nbsp; &nbsp; &nbsp;
                  		<?=form_submit(array('type'=>'submit', 'name'=>'next', 'value'=>'Proceed to Registration', 'class'=>'btn btn-primary'))?>
                  	</div>
                  </div>
                </li>
              </ul>
             </div><!-- /.col -->
            <div class="col-md-2"></div>
          </div><!-- /.row -->
