<div class="page-header-custom">
	<div class="row">
	    <div class="col-sm-7">
	    	<h3><?=$report['name']?></h3>
			<small>
			<b>Description: </b><?=$report['description']?><br>
			<b>Created By: </b><?=$report['created_by']?><br>
			<b>Created At: </b><?=$report['created_at']?>
			</small>
	    </div>
	    <div class="col-sm-5 text-right">
	    </div>
    </div>
</div>

<?php if (isset($ERROR)):?>
	<div class="alert alert-danger" role="alert">
	    <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
	    <span class="sr-only">Error:<?=$ERROR;?></span>
	    <?=$ERROR;?>
    </div>
<?php endif;?>
<div class="page-header-custom" style="padding-bottom: 10px;">
	<div class="container-fluid">
	  <div class="row">
	    <div class="col-sm-12">
	      <?= $form ?>
	    </div>
	  </div>
	</div>
</div>

<?php if (isset($report_results)):?>
	<h4>Report Results</h4>
	
	<div class="container-fluid">
		<?=$report_results?>
	</div>
<?php endif;?>
