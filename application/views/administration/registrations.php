<form class="form-horizontal">
	<table class="table table-condensed table-striped table-bordered">
	<tr>
	<td><div class="input-group input-group-sm"><span class="input-group-addon">&nbsp;<span class="glyphicon glyphicon-filter" aria-hidden="true"></span> Filter By Creation Date</span>
	 	<div class="input-group date" id="date_from"><input type="text" name="date_from" class="form-control" aria-label="date_from" placeholder="From: YYYY-MM-DD" value="<?=$filter_values['date_from']?>"><div class="input-group-addon"><span class="glyphicon glyphicon-calendar" aria-hidden="true"></span></div></div>
		<div class="input-group date" id="date_to"><input type="text" name="date_to" class="form-control" aria-label="date_to" placeholder="To: YYYY-MM-DD" value="<?=$filter_values['date_to']?>"><div class="input-group-addon"><span class="glyphicon glyphicon-calendar" aria-hidden="true"></span></div></div></div>
	</td>
	<td><div class="input-group input-group-sm"><span class="input-group-addon">&nbsp;<span class="glyphicon glyphicon-search" aria-hidden="true"></span> Search</span><input type="text" name="search" class="form-control" aria-label="search" placeholder="Search String" value="<?=$filter_values['search']?>">
		<?php if (isset($FILTER)):?><span class="input-group-btn"><a class="btn btn-danger" href="<?=site_url('/administration/')?>"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true"></span></a></span><?php endif;?>
		<span class="input-group-btn"><button type="submit" name="filter" class="btn btn-default">Go</button></span></div>
		<br>
		<div class="button-row">
			<a href="<?=site_url("administration/make_name_tags")?>" target="_blank" class="btn btn-primary ">Print all name tags</a> ||
			<a href="<?=site_url("administration/register_special")?>" class="btn btn-primary ">Add delegate</a> || 
			<a href="<?=site_url("administration/duplicates")?>" class="btn btn-primary ">Check for duplicates</a>
		</div>
	</td>
	</tr>
	</table>
	
	
</form>
<?php $residence_labels = $this->config->item('event_item_labels');?>
<table class="table table-condensed table-bordered table-striped" style="font-size: 12px;">
<tr>
	<th>Reg ID</th>
	<th>name</th>
	<th>Gender</th> 
	<th>Telephone</th> 	
	<th>Email</th>
	<th>Country</th>
	<th>Organisation</th>
	<th>Shirt size</th>
	<th>payment type</th>
	<th>payment made?</th>
	<th>payment_proof</th>
	<th>Actions</th>
	
</tr>
<?php foreach ($registrations as $row):?>
<tr>
	<td><?=$row['registration_code']?></td>
	<td><a class="btn btn-info btn-xs" href="#" role="button" onClick="getRegDetails(<?=$row['id']?>);" data-toggle="modal" data-target="#myModal"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></a> &nbsp; <?=$row['first_name'].' '.$row['last_name'].' '.$row['other_names'] ?></td>
	<td><?=$row['gender'] ?></td>
	<td><?=$row['telephone'] ?></td>
	<td><?=$row['email'] ?></td>
	<td><?=$row['nationality'] ?></td>
	<td><?=$row['emp_organisation'] ?></td>
	<td><?=$row['shirt_size']?></td>
	<td><?=$row['payment_type'] ?></td>
	<td><?=$this->core_model->get_themed_boolean($row['payment_status'])?></td>
	<td><?=$row['payment_status']?></td>
	<td>
		<a href=<?=site_url("/administration/register_special/{$row['id']}")?> >Edit</a> ||
		<a target = '_blank' href=<?=site_url("/administration/make_name_tags/{$row['registration_code']}")?> >Print tag</a> ||
		<a target = '_blank' href=<?=site_url("/administration/make_receipt/{$row['registration_code']}")?> >Receipt</a>
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
