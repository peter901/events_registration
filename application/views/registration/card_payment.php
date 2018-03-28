<div class="row">
	<div class="col-md-4"></div>
	<div class="col-md-4">
	<br>
		<div class="progress progress-xxs">
			<div class="progress-bar progress-bar-primary" role="progressbar" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100" style="width: 90%">
				<span class="sr-only">90%</span>
			</div>
		</div>
	</div>
	<div class="col-md-4"><span class="badge bg-blue">90%</span></div>
</div>
<?=form_open('https://secureacceptance.cybersource.com/pay')?>
	<?php foreach($payment_params as $name => $value):?>
    	<input type="hidden" id="id_<?=$name?>" name="<?=$name?>" value="<?=$value?>" />
	<?php endforeach;?>
		<!-- row -->
          <div class="row">
          	<div class="col-md-2"></div>
            <div class="col-md-8">
              <!-- The time line -->
              <ul class="timeline">

                <li>
                  <i class="fa fa-credit-card bg-green"></i>
                  <div class="timeline-item">
                  	<h3 class="text-light-blue">
                  		Credit/Debit Card Payment
                  	</h3>
                  	<div class="timeline-body">
                    <?php $rates = $this->config->item('event_rates'); $now = date('d M Y'); ?>
					<!-- info -->
					<?php if ($reg['gender'] == 'MALE'){ $salutation = 'Mr.';}else{ $salutation = 'Ms.';}?>
                    <h4><?=$salutation?> <?=$reg['first_name']?> <?=$reg['last_name']?> <?=$reg['other_names']?></h4>
                    
					<?php $invoice_content = json_decode($invoice_to_pay['content'], true);?>
					
					<table class="table table-bordered table-striped">
						<tr><th>item</th><th>qtty</th><th>amount</th></tr>
						<?php if (isset($invoice_content['reg_rate'])):?>
							<tr><td>Registration:<br><?=$salutation?> <?=$reg['first_name']?> <?=$reg['last_name']?> <?=$reg['other_names']?> , Reg ID:  <?=$reg['registration_code']?><br>
							<?=$invoice_content['reg_rate']?></td><td class="text-center">1</td><td class="text-right">$<?=number_format($invoice_content['reg_cost'])?></td></tr>
						<?php endif;?>
						<tr><td>Accompanying Persons:<?=$invoice_content['acc_persons_desc']?></td><td class="text-center"><?=$invoice_content['acc_persons_count']?></td><td class="text-right">$<?=number_format($invoice_content['acc_persons_cost'])?></td></tr>
						<tr><th colspan="2">TOTAL</th><th class="text-right">$<?=number_format($invoice_to_pay['amount'])?></th></tr>
						<tr><td colspan="3" class="text-right">Amount in Words (US$): <b><?=$invoice_content['grand_total_words']?></b></td></tr>
					</table>
                    
                 	<b>Note:</b><br>
                 	<ul>
                 		<li>The amount charged excludes the cost of accomodation</li>
                 		<li>When making payment, fill in details of the Card Holder</li>
                 	</ul>
					<!-- end info -->
                    </div>
                  </div>
                </li>
              
                <li>
                  <i class="fa fa-check bg-gray"></i>
                  <div class="timeline-item">
                  	<div class="timeline-footer text-right">
                  		<a class="btn btn-default" href="javascript:history.back()" role="button"> Back</a> &nbsp;
                  		<?=form_submit(array('type'=>'submit', 'value'=>'Make Payment', 'class'=>'btn btn-primary'))?>
                  	</div>
                  </div>
                </li>
              
              </ul>
             </div><!-- /.col -->
            <div class="col-md-2"></div>
          </div><!-- /.row -->
<?php form_close()?>
              