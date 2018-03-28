
<?php $residence_labels = $this->config->item('event_item_labels');?>
<table class="table table-condensed table-bordered table-striped" style="font-size: 12px;">
<tr>
	<th>sn</th>	
	<th>ICPAU ID/Reg ID</th>
	<th>name</th>
	<th>Track</th> 	
</tr>

<?php 
	foreach ($registrations as $k=>$row):

	$track = json_decode($row['track'],true);

	#print_r($track);
	#echo "<br><br>";
	$track_array =array();
	
	foreach($track as $t)
	{
		$track_array[] =trim(substr($t,4,2),'-');
				
	}
	
	asort($track_array);
?>

	<tr>
	<td><?=$k+1?></td>	
	<td><?=$row['icpau_id'] ?></td>
	<td><?=$row['full_name'] ?></td>
	<td><?=implode(",",$track_array) ?></td>
	</tr>
<?php 
	

	endforeach;
?>
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


