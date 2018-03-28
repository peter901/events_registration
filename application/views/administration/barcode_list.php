<form class="form-horizontal">
	<table class="table table-condensed table-striped table-bordered">
	<tr>
	<td>
		<?php if($user['username'] == 'peter'):?><a href='<?=site_url('administration/barcode_activities')?>' class='btn btn-primary'>Add ACTIVITY</a><?php endif;?>
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
	<th>Activity</th>
	<th>Description</th>
	<th>Created at</th> 
	<th>Updated at</th> 	
	<th>Actions</th>
	
</tr>
<?php foreach ($data as $k => $row):?>
<tr>
	<td><?=($k+1)?></td>
	<td><?=$row['activity']?></td>
	<td><?=$row['description']?></td>
	<td><?=$row['created_at'] ?></td>
	<td><?=$row['updated_at'] ?></td>
	<td>
		<a href='<?=site_url("/administration/barcode_activities_book/{$row['id']}")?>' class="btn btn-primary btn-xs">Book</a> ||
		<a href='<?=site_url("/administration/barcode_activities_details/{$row['id']}")?>' class="btn btn-info btn-xs">Details</a> ||
		<a href='<?=site_url("/administration/barcode_activities/{$row['id']}")?>' class="btn btn-warning btn-xs">Edit</a>
		<?php if($user['username'] == 'peter'):?>
			||<a href='<?=site_url("/administration/barcode_activities_delete/{$row['id']}")?>' onclick="return confirm('Are you sure you want to delete this item? Note: All related information will be deleted.');" class="btn btn-danger btn-xs" >Delete</a>
		<?php endif;?>
	</td>
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
