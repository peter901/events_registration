    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <div class="navbar-brand">ESAAG<!--<img src="<?=base_url('/media/static/logo_small.png')?>" height="40">--></div>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
          <li<?php if ('HOME' == $MODULE):?> class="active"<?php endif;?>><a href="<?=site_url("/administration/") ?>">Registrations</a></li>
          <?php foreach ($modules as $M):?>
          	<?php if ($M['submodules']):?>
          		<li class="dropdown<?php if ($M['code'] == $MODULE):?> active<?php endif;?>">
          			<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?=$M['label']?> <span class="caret"></span></a>
          			<ul class="dropdown-menu">
          				<?php foreach ($M['submodules'] as $SM):?>
          					<li<?php if ($SM['code'] == $SUBMODULE):?> class="active"<?php endif;?>><a href="<?=site_url($SM['url'])?>"><?=$SM['label']?></a></li>
          				<?php endforeach;?>
          			</ul>
          		</li>
          	<?php else:?>
            <li<?php if ($M['code'] == $MODULE):?> class="active"<?php endif;?>><a href="<?=site_url($M['url'])?>"><?=$M['label']?></a></li>
            <?php endif;?>
           <?php endforeach;?>
          </ul>
          
          <?php if (isset($user)): ?>
		  <ul class="nav navbar-nav navbar-right">
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?=$user['name']?>  <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="glyphicon glyphicon-calendar" aria-hidden="true"></span>&nbsp; <strong><?=date('d M Y')?></strong></li>
                <li role="separator" class="divider"></li>
                <!-- 
				<li class="dropdown-header">Profile</li>
                <li><a href="<?=site_url($this->config->item('user_profile_base_path'))?>"><span class="glyphicon glyphicon-user" aria-hidden="true"></span>&nbsp; My Profile</a></li>
                <li><a href="<?=site_url($this->config->item('change_password_base_path'))?>"><span class="glyphicon glyphicon-lock" aria-hidden="true"></span>&nbsp; Change Password</a></li>
                <li role="separator" class="divider"></li>
                 -->
                <li><a href="<?=site_url('/core/logout/')?>"><span class="glyphicon glyphicon-log-out" aria-hidden="true"></span>&nbsp; Logout</a></li>
              </ul>
            </li>
          </ul>
          <?php endif; ?>
        </div><!--/.nav-collapse -->
      </div>
    </nav>
