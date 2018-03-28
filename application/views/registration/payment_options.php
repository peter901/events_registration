<div class="row">
	<div class="col-md-4"></div>
	<div class="col-md-4">
	<br>
		<div class="progress progress-xxs">
			<div class="progress-bar progress-bar-primary" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width: 80%">
				<span class="sr-only">80%</span>
			</div>
		</div>
	</div>
	<div class="col-md-4"><span class="badge bg-blue">80%</span></div>
</div>
<?=form_open(site_url('/registration/payment_options'))?>
	<input type="hidden" name="registration_code" value="<?=$reg['registration_code']?>"  id="id_registration_code" />
	<input type="hidden" name="update_registration_code" value="<?=$reg['update_registration_code']?>"  id="id_update_registration_code" />
		<!-- row -->
          <div class="row">
          	<div class="col-md-2"></div>
            <div class="col-md-8">
              <!-- The time line -->
              <ul class="timeline">
               <!-- timeline item -->
                <li>
                  <i class="fa fa-money bg-green"></i>
                  <div class="timeline-item">
                  	<h3 class="text-light-blue">
                  		Payment
                  	</h3>
                  	<div class="timeline-body">
                    <?php $rates = $this->config->item('event_rates');?>
					<!-- info -->
					<?php $residence_labels = $this->config->item('event_item_labels');?>
					
					<?php if ($reg['gender'] == 'MALE'){ $salutation = 'Mr.';}else{ $salutation = 'Ms.';}?>
                    <h4><?=$salutation?> <?=$reg['first_name']?> <?=$reg['last_name']?> <?=$reg['other_names']?></h4>

                    <!-- Please choose the invoice you would like to pay and how you would like to pay. -->
					<!-- <br> -->
					<!-- <?= form_error('invoice_to_pay', '<div class="text-red">', '</div>'); ?> -->
					<?php $counter = 0; foreach ($unpaid_invoices as &$I):?>
					<br>
					<div style="border: 1px solid #ccc;padding:20px;">
						<?php $counter +=1; $invoice_content = json_decode($I['content'], true);?>
						<div class="radio">
	                        <label>
	                          <!-- <?= form_radio(array('name'=>"invoice_to_pay","value"=>$I['id']));?> -->
	                          <input type="hidden" name="invoice_to_pay" value="<?=$I['id']?>"  id="id_invoice_to_pay" />
	                          
	                          <big>Invoice <?=$counter?></big>
	                        </label>
	                    </div>
						<table class="table table-bordered table-striped">
							<tr><th>item</th><th>qtty</th><th>amount</th></tr>
							<?php if (isset($invoice_content['reg_rate'])):?>
								<tr><td>Registration:<br><?=$salutation?> <?=$reg['first_name']?> <?=$reg['last_name']?> <?=$reg['other_names']?> , Reg ID:  <?=$reg['registration_code']?><br>
								Residence: <?=$residence_labels[$reg['hotel']]?><br>
								<?=$invoice_content['reg_rate']?></td><td class="text-center">1</td><td class="text-right"><?=$invoice_content['reg_cost']?></td></tr>
							<?php endif;?>
							<tr><td>Accompanying Persons:<?=$invoice_content['acc_persons_desc']?></td><td class="text-center"><?=$invoice_content['acc_persons_count']?></td><td class="text-right"><?=$invoice_content['acc_persons_cost']?></td></tr>
							<tr><th colspan="2">TOTAL</th><th class="text-right"><?=$invoice_content['grand_total']?></th></tr>
							<tr><td colspan="3" class="text-right">Amount in Words: <b><?=$invoice_content['grand_total_words']?></b></td></tr>
						</table>
						
						<?= form_error('payment_option', '<div class="text-red">', '</div>'); ?>
						<div class="radio"><i class="fa fa-exchange"></i> &nbsp;&nbsp;
	                        <label>
	                          <?= form_radio(array('name'=>"payment_option","value"=>"TT", "label"=>"TT"));?>
	                          Pay by Telegraphic Transfer (TT) / Wire Transfer
	                        </label>
	                    </div>
						<br>
						<div class="radio"><i class="fa fa-money"></i> &nbsp;&nbsp;
	                        <label>
	                          <?= form_radio(array('name'=>"payment_option","value"=>"CASH", "label"=>"CASH"));?>
	                          Pay by Direct Cash Deposit into the ICPAU Bank Account
	                        </label>
	                    </div>
						<br>
						<!--        <div class="radio"><i class="fa fa-credit-card"></i> &nbsp;&nbsp;
	                      <label>
	                        <?= form_radio(array('name'=>"payment_option","value"=>"CARD", "label"=>"CARD"));?>
	                         Pay by Credit Card / Debit Card
	                      </label>
	                    </div>
			-->
	                </div>
	                <?php break;?>
                    <?php endforeach;?>
                    
                    <div class="checkbox"><i class="fa fa-envelope"></i> &nbsp;&nbsp;
                        <label>
                          <?= form_checkbox(array('name'=>"resend_invoice_email","value"=>'YES'));?>
                          Resend Invoice to my email?
                        </label>
                    </div>
					<!-- end info -->
                    
                    </div>
                  </div>
                </li>
                <li>
                  <i class="fa fa-check bg-gray"></i>
                  <div class="timeline-item">
                  	<div class="timeline-footer text-right">
                  	    <?php if (isset($ext_req) && $ext_req):?>
                  			<input type="hidden" name="ext_req" value="1"  id="id_ext_req" />
                  		<?php else:?>
                  		<!-- <?=form_submit(array('type'=>'submit', 'name'=>'later', 'value'=>'Pay Later', 'class'=>'btn btn-default'))?> &nbsp;&nbsp; -->
                  		<?php endif;?>
                  		<?=form_submit(array('type'=>'submit', 'name'=>'payment', 'value'=>'Proceed to Payment', 'class'=>'btn btn-primary'))?>
                  	</div>
                  </div>
                </li>
              </ul>
             </div><!-- /.col -->
            <div class="col-md-2"></div>
          </div><!-- /.row -->
<?php form_close()?>
