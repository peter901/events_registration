<div class="row">
	<div class="col-md-4"></div>
	<div class="col-md-4">
	<br>
		<div class="progress progress-xxs">
			<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
				<span class="sr-only">100%</span>
			</div>
		</div>
	</div>
	<div class="col-md-4"><span class="badge bg-green">100%</span></div>
</div>
		<!-- row -->
          <div class="row">
          	<div class="col-md-2"></div>
            <div class="col-md-8">
              <!-- The time line -->
              <ul class="timeline">
                <li>
                  <i class="fa fa-check bg-green"></i>
                  <div class="timeline-item">
                  	<?php if ($operation == 1):?>
                  	  <h3 class="timeline-header text-light-blue">Update Complete</h3>
	                  <div class="timeline-body">
	                  	You have successfully updated your registration information.
	                  	<br><br>
	                  </div>
                  	<?php elseif ($operation == 2):?>
                  	  <h3 class="timeline-header text-light-blue">Registration Complete</h3>
	                  <div class="timeline-body">
	                  	You have successfully completed the registration for the conference
	                  	<br><br>
	                  	An email has been sent to you with information regarding your registration.
	                  	<br><br>
	                  	further instructions have been sent to you on how to make payment at a later time.
	                  	<br><br>
	                  	further instructions have been sent to you on how to modify your travel details and hotel preference at a later time.
	                  </div>
                  	<?php else: ?>
                  	  <h3 class="timeline-header text-light-blue">Registration Complete</h3>
	                  <div class="timeline-body">
	                  	You have successfully completed registration for the conference
	                  	<br><br>
	                  	An email will be sent to you with information regarding your registration <!-- and instructions about how to make payments-->
	                  	<br><br>
	                  	further instructions will be sent to you on how to modify your travel details and hotel preference at a later time.
	                  </div>
	                <?php endif;?>
                  </div>
                </li>
              </ul>
             </div><!-- /.col -->
            <div class="col-md-2"></div>
          </div><!-- /.row -->
              
