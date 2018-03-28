<?php 
	$error = $this->session->flashdata('ERROR');
	$warning = $this->session->flashdata('WARNING');
	$info = $this->session->flashdata('INFO');
	$success = $this->session->flashdata('SUCCESS');
?>
<?php if ($error):?>
	<div class="alert alert-danger" role="alert">
	    <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
	    <span class="sr-only">Error:<?=$error;?></span>
	    <?=$error;?>
    </div>
<?php endif;?>
<?php if ($warning):?>
	<div class="alert alert-warning" role="alert">
	    <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
	    <span class="sr-only">Warning:<?=$warning;?></span>
	    <?=$warning;?>
    </div>
<?php endif;?>
<?php if ($info):?>
	<div class="alert alert-info" role="alert">
	    <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
	    <span class="sr-only">Info:<?=$info;?></span>
	    <?=$info;?>
    </div>
<?php endif;?>
<?php if ($success):?>
	<div class="alert alert-success" role="alert">
	    <span class="glyphicon glyphicon-ok-sign" aria-hidden="true"></span>
	    <span class="sr-only">Success:<?=$success;?></span>
	    <?=$success;?>
    </div>
<?php endif;?>
