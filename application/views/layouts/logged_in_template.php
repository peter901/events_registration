<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="<?=base_url('/media/static/favicon.ico')?>">

    <title><?=$template['title']?></title>
    
    <?php if (isset($template['partials']['custom_js_top'])) {echo $template['partials']['custom_js_top'];} ?>
    
    <!-- Bootstrap core CSS -->
    <link href="<?=base_url('/media/static/bootstrap/css/bootstrap.min.css')?>" rel="stylesheet">

    <?php if (isset($template['partials']['custom_css_top'])) {echo $template['partials']['custom_css_top'];} ?>
    
    <!-- Custom styles for this template -->
    <link href="<?=base_url('/media/static/custom_styles.css')?>" rel="stylesheet">
    
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>
  <?php if (isset($template['partials']['nav'])) {echo $template['partials']['nav'];} ?>

	<div class="container-fluid">
	  <div class="row">
	    <div class="col-sm-1"></div>
	    <div class="col-sm-10">
		  <div class="page-content-area">
		  
		  <?php if (isset($template['partials']['messages'])) {echo $template['partials']['messages'];} ?>
	        <?php 
		        if (isset($breadcrumb)) {
		        	$bc_no = count($breadcrumb);
		        	$counter = 0;
		        	echo '<ul class="breadcrumb">';
		        	foreach ($breadcrumb as $B) {
		        		$counter += 1;
		        		if ($counter == $bc_no) {
		        			echo '<li class="active"><b>'.$B['label'].'</b></li>';
		        		} else {
		        			echo '<li><a href="'.$B['url'].'">'.$B['label'].'</a></li>';
		        		}
		        	}
		        	echo '</ul>';
		        } else {
		        	echo '<h4>'.$template['title'].'</h4>';
		        }
	        ?>
			<!-- begin body -->
			  
			<?php if (isset($template['body'])) {echo $template['body'];} ?>
				
		    <!-- end of body -->
		    <div class="text-center"> <br> Powered by <img src="<?=base_url('/media/static/logo_small.png')?>" ></div>
		  </div>
		</div>
		<div class="col-sm-1"></div>
	  </div>
	</div>
  	
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="<?=base_url('/media/static/jq/jquery-1.11.3.min.js')?>"></script>
    <script src="<?=base_url('/media/static/bootstrap/js/bootstrap.min.js')?>"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="<?=base_url('/media/static/bootstrap/js/assets/ie10-viewport-bug-workaround.js')?>"></script>
    <?php if (isset($template['partials']['custom_js_bottom'])) {echo $template['partials']['custom_js_bottom'];} ?>
    
  </body>
</html>
