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
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?=base_url('/media/static/font-awesome/css/font-awesome.min.css')?>">
    <!-- Ionicons -->
    <link rel="stylesheet" href="<?=base_url('/media/static/ionicons/css/ionicons.min.css')?>">
    <!-- improved select fields -->
    <link rel="stylesheet" href="<?=base_url('/media/static/select2/select2.min.css')?>">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?=base_url('/media/static/theme/css/AdminLTE.min.css')?>">
    
    <?php if (isset($template['partials']['custom_css_top'])) {echo $template['partials']['custom_css_top'];} ?>
        
    <!-- Custom styles for this template -->
    <link href="<?=base_url('/media/static/custom_styles.css')?>" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body style="padding-top: 0px;">
	  <div class="row">
		<div class="col-md-2"></div>
		<div class="col-md-8 text-center">
			<div><img height="150px" width="150px" class="img-rounded" src="<?=base_url("media/static/ESAAG_logo.png")?>"/></div>
			<!--<h1>THE 25<sup>th</sup> ESSAG ANNUAL INTERNATIONAL CONFERENCE</h1>
			<div><img height="150px" width="150px" class="img-thumbnail" src="<?=base_url("media/static/Esaag_Logo_2018.png")?>"/></div>-->
	  		<h3>26 February - 02 March 2018</h3>
	  	</div>
	  	<div class="col-md-2"></div>
	  </div>
	<?php if (isset($template['body'])) {echo $template['body'];} ?>
	<div class="text-center">
		Powered by CPA UGANDA &copy; 2018<br>
	<!-- <img class="img-thumbnail" src="<?=base_url('/media/static/logo_big.png')?>">-->
	</div>
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="<?=base_url('/media/static/jq/jquery-1.11.3.min.js')?>"></script>
    <script src="<?=base_url('/media/static/bootstrap/js/bootstrap.min.js')?>"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="<?=base_url('/media/static/bootstrap/js/assets/ie10-viewport-bug-workaround.js')?>"></script>
    <!-- improved select widgets -->
    <script src="<?=base_url('/media/static/select2/select2.min.js')?>"></script>
    
    <?php if (isset($template['partials']['custom_js_bottom'])) {echo $template['partials']['custom_js_bottom'];} ?>
    </body>
</html>
