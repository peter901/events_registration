<div class="welcome-banner-box">
<h3><span class="label label-danger">Events Administration</span></h3></div>
<div class="container-fluid">
	<div class="row">
	<div class="col-sm-4"></div>
	<div class="col-sm-4">
	<div class="login-box">
	  <div class="panel panel-default">
	    <div class="panel-heading">
	      <h3 class="panel-title"><?=$title?></h3>
	    </div>
	    <div class="panel-body">
	    
	    <?php if ($errors):?>
	    <div class="alert alert-danger" role="alert">
		    <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
		    <span class="sr-only">Error:</span>
		    <?=$errors;?>
	    </div>
	    <?php endif;?>
	    <?php if ($messages):?>
	    <div class="alert alert-info" role="alert">
		    <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
		    <span class="sr-only">Info:</span>
		    <?=$messages;?>
	    </div>
	    <?php endif;?>
	    
		<?=$form?>
		
	    </div>
	  </div>
	</div>
	</div>
	<div class="col-sm-4"></div>
	</div>
</div>
