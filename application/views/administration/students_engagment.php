
<?php $residence_labels = $this->config->item('event_item_labels');?>
<table class="table table-condensed table-bordered table-striped" style="font-size: 12px;">

<?php foreach ($registrations as $k=>$row):?>

<tr><th colspan=4><?=$k?></th><tr>
<tr>
	<th>sn</th>	
	<th>ICPAU ID/Reg ID</th>
	<th>name</th>
	<th>Track</th> 	
</tr>
<?php foreach($row as $v => $r):?>
<tr>
	<td><?=$v+1?></td>	
	<td><?=$r['icpau_id'] ?></td>
	<td><?=$r['full_name'] ?></td>
	<td><?=$k ?></td>
</tr>

<?php endforeach;?>

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


