<?php if (isset($page_heading) && $page_heading):?>
	<div class="page-header-custom"><h4><?=$page_heading?></h4></div>
<?php endif;?>
<?php if (isset($ERROR)):?>
	<div class="alert alert-danger" role="alert">
	    <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
	    <span class="sr-only">Error:<?=$ERROR;?></span>
	    <?=$ERROR;?>
    </div>
<?php endif;?>
<div class="panel panel-default">
	<div class="panel-heading"><?=$title?></div>
	<div class="panel-body">
		<div class="container-fluid">
		  <div class="row">
			<div class="col-sm-3"></div>
			<div class="col-sm-6">
			  <?=$form?>
			</div>
			<div class="col-sm-3"></div>
		  </div>
		</div>
	</div>
</div>
