<div class="form-horizontal">
	<?php 
		echo form_error('registration_code', '<div class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span> ', '</div>'); 
	?>
		<table class="table table-condensed table-striped table-bordered">
		<tr>
		<td>
			<?= form_open("/administration/session_attendance/{$id}");?>
			<?= form_input(array("type"=>"hidden","name"=>"session_id","value"=>$id)); ?>
			<div class="input-group input-group-sm"><span class="input-group-addon">&nbsp;<span class="glyphicon glyphicon-barcode" aria-hidden="true"></span> Scan barcode</span>
			<input type="text" maxlength="12" autofocus="autofocus" name="registration_code" class="form-control" aria-label="search" placeholder="Scan barcode" value="" />
			<span class="input-group-btn"><input type="submit" value="Go" class="btn btn-default"/></span></div></td>
			<?= form_close() ;?>
		</tr>
		</table>

</div>
<?php
	$num = 1;
?>
<table class="table table-condensed table-bordered table-striped" style="font-size: 12px;">
<tr>
	<th>S/N</th>
	<th>Events ID</th>
	<th>Shirt size</th>
	<th>Name</th>
	<th>Gender</th> 
	<th>Email</th>	
	<th>Country</th> 	
	<th>Date time</th> 	
	<th>Action</th> 	
</tr>
<?php foreach ($data as $row):?>
<tr>
	<td><?=$num?></td>
	<td><?=$row['registration_code']?></td>
	<td><?=$row['shirt_size']?></td>
	<td><a class="btn btn-info btn-xs" href="#" role="button" onClick="getRegDetails(<?=$row['id']?>);" data-toggle="modal" data-target="#myModal"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></a> &nbsp; 
	<?=$row['first_name'].' '.$row['last_name'].' '.$row['other_names'] ?></td>
	<td><?=$row['gender'] ?></td>
	<td><?=$row['email'] ?></td>
	<td><?=$row['nationality'] ?></td>
	<td><?=$row['date'] ?></td>
	<td>&nbsp;</td>
	<?php $num =$num+1;?>
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
