<div class="panel panel-default">
	<div class="panel-heading"><h3><?= $barcode_activities['activity'];?></h3></div>
<?php
	// -- options --
	$columns = 2;
	$values = &$barcode_activities;
	$skip_fields = array('id','activity','remote_id','sync');
	// -- end options --
	
	$bstrp_col_width = (int)(12/$columns);
?>
	<div class="panel-body">
		<div class="row">
			<div class="col-sm-<?=$bstrp_col_width?>"><table class="table table-condensed">
			<?php 
				$values_per_column = (int)((count($values) - count($skip_fields))/$columns);
				$counter = 0;
				$column_count = 1;
				foreach ($values as $key=>$value) {
					if (in_array($key, $skip_fields)) {
						continue;
					}
					$counter += 1;
					if ($counter > $values_per_column && $column_count < $columns) {
						echo '</table></div><div class="col-sm-'.$bstrp_col_width.'"><table class="table table-condensed">';
						$column_count += 1;
						$counter = 1;
					}
					echo '<tr><th>'.ucwords(preg_replace("/[_-]/",' ', $key)).': </th><td>'.$value.'</td></tr>';
				}
			?>
			</table>
			</div>
		</div>
	</div>
</div>

<div class="panel panel-default">
	<div class="panel-body">
		<div class="page-header-custom">
		  <div class="row">
			<div class="col-sm-6">
				<h4>Sessions 
					<?php if($user['username'] == 'peter'):?>
						<a href='<?=site_url("administration/barcode_activity_session/".$barcode_activities['id'])?>' class='btn btn-primary'> Add session</a>
					<?php endif;?>
					<a href='<?=site_url("administration/generate_session_barcodes/".$barcode_activities['id'])?>' target='_blank' class='btn btn-default'>Generate session barcodes</a>
				</h4>
			</div>
			<div class="col-sm-6 text-right"></div>
		  </div>
		</div>

		<div class="table-responsive">
			<table class="table table-striped table-bordered table-condensed">
			<tr style="white-space: nowrap;">
				<th>Session</th>
				<th>description</th>
				<th>Booked</th>
				<th>Max no</th>
				<th>Remaining</th>
				<th>&nbsp;</th>
				<th>Ug slots</th>
				<th>ug booked</th>
				<th>Ug remaining</th>
				<th>Actions</th>
			</tr>
			<?php foreach ($barcode_sessions as $R): 			
				#overal remaining slots
				$booked_slots = $this->core_model->count_objects('barcode_session_bookings',array('id_barcode_sessions'=>$R['id']));
				
				$remaining_slots = $R['maximum_number'] - $booked_slots;
				
				#slots for ugandans
			
				#Count ugandans booked for this activity
				$SQL = "select count(*) num 
						from core_registrations c JOIN barcode_session_bookings bsb ON c.id = bsb.id_core_registrations
						where c.nationality = 'UGANDA' and bsb.id_barcode_sessions={$R['id']}";
				$ug_count  = $this->reports_model->run_sql_query($SQL)[0]['num'];
				$ug_remainig_slots = $R['uganda_slots'] -$ug_count;
				?>
				<tr>
					<td><?=$R['session']?></td>
					<td><?=$R['description']?></td>
					<td><?=$booked_slots?></td>
					<td><?=$R['maximum_number']?></td>
					<td><?=$remaining_slots?></td>
					<td>&nbsp;</td>
					<td><?=$R['uganda_slots']?></td>
					<td><?=$ug_count?></td>
					<td><?=$ug_remainig_slots?></td>
					<td>
						<?php if($user['username'] == 'peter'):?>
							<a href="<?=site_url('administration/barcode_activity_session_details/'.$R['id'])?>" class="btn btn-info btn-xs" >Details</a>
						<?php endif;?>
						<a href="<?=site_url('administration/barcode_activity_session/'.$barcode_activities['id'].'/'.$R['id'])?>" class="btn btn-warning btn-xs" >Edit</a>
						<?php if($user['username'] == 'peter'):?>
							<a href="<?=site_url('administration/barcode_activity_session_delete/'.$barcode_activities['id'].'/'.$R['id'])?>" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure you want to delete this item? Note: All related information will be deleted.');">Delete</a>
						<?php endif;?>
					</td>
				</tr>
			<?php endforeach;?>
			</table>
		</div>
	</div>
</div>
