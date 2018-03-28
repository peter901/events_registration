<form class="form-horizontal">
	<table class="table table-condensed table-striped table-bordered">
	<tr>
	<td>
		<?php if($user['username'] == 'peter'):?><a href="<?=site_url("administration/modify_session")?>" class="btn btn-primary">CREATE NEW SESSION</a><?php endif; ?>
	</td>
	<td><div class="input-group input-group-sm"><span class="input-group-addon">&nbsp;<span class="glyphicon glyphicon-search" aria-hidden="true"></span> Search</span><input type="text" name="search" class="form-control" aria-label="search" placeholder="Search String" value="">
		<span class="input-group-btn"><a class="btn btn-danger" href="<?=site_url('/administration/')?>"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true"></span></a></span>
		<span class="input-group-btn"><button type="submit" name="filter" class="btn btn-default">Go</button></span></div></td>
	</tr>
	</table>
</form>

<table class="table table-condensed table-bordered table-striped" style="font-size: 12px;">
<tr>
	<th>SN</th>
	<th>Session</th>
	<th>Date</th>
	<th>Action</th> 	
</tr>
<?php foreach ($data as $k =>$row):?>
<tr>
	<td><?=($k+1)?></td>
	<td><?=$row['session']?></td>
	<td><?=$row['date'] ?></td>
	<td><a href="<?=site_url('/administration/session_attendance/'.$row['id'])?>" class="btn btn-xs btn-primary">Add delegates</a> || 
		<a href="<?=site_url('/administration/modify_session/'.$row['id'])?>" class="btn btn-xs btn-warning">Edit</a> 
		<?php if($user['username'] == 'peter'):?>
			||<a href="<?=site_url('/administration/delete_session/'.$row['id'])?>" class="btn btn-xs btn-danger" onclick="return confirm('Are you sure you want to delete this item? Note: All related information will be deleted.');">Delete</td>
		<?php endif; ?>
</tr>
<?php endforeach;?>
</table>

<div class="modal" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body" id="id_reg_details">
        ...
      </div>
    </div>
  </div>
</div>

<?php if (isset($pagination)){ echo $pagination; }?>
