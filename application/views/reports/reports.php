<form class="form-inline">
	<div class="input-group input-group-sm">
	  <span class="input-group-btn"><a class="btn btn-default" href="<?=site_url('/reports/modify_report/')?>" role="button"><span class="glyphicon glyphicon-file" aria-hidden="true"></span> Create New Report</a></span>
	  <span class="input-group-addon">&nbsp;<span class="glyphicon glyphicon-search" aria-hidden="true"></span></span>
	  <input type="text" name="search" class="form-control" aria-label="search" placeholder="Search Reports" <?php if (isset($SEARCH)) {echo 'value="'.$SEARCH.'"';}?>>
	  <?php if (isset($SEARCH)):?><span class="input-group-btn"><a class="btn btn-danger" href="<?=site_url('/reports/')?>"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true"></span></a></span><?php endif;?>
	  <span class="input-group-btn"><button type="submit" class="btn btn-default">Go</button></span>
	</div>
</form>
<br>

<div class="table-responsive">
	<table class="table table-condensed table-striped table-bordered table-hover">
		<tr><th style="width:30%;">report summary</th><th>created by</th><th>created at</th><?php if ($this->custom_auth->has_privilege('REPORTS_ADMIN')):?><th>user can run?</th><th>system can run?</th><th>system run schedule</th><?php endif;?><th></th></tr>
		<?php $old_grouping = null;?>
		<?php foreach ($reports as $row):?>
		<?php if ($row['grouping'] != $old_grouping):?>
			<?php $old_grouping = $row['grouping'];?>
			<tr><th colspan="3" style="border-bottom: 1px solid #ff6600;"><h4><?=$row['grouping']?></h4></th></tr>
		<?php endif;?>
		<tr><td><a href="<?=site_url('/reports/report_details/'.$row['id'])?>"><?=$row['name']?></a><br><small><span style="color:#555;"><?=$row['description']?></span></small></td>
		<td><?=$row['created_by']?></td><td><?=$row['created_at']?></td>
		<?php if ($this->custom_auth->has_privilege('REPORTS_ADMIN')):?>
			<td><?=$this->core_model->get_themed_boolean($row['user_can_run'])?></td><td><?=$this->core_model->get_themed_boolean($row['system_can_run'])?></td><?=$row['system_run_schedule']?><td></td>
		<?php endif;?>
		<td class="text-center">
			<div class="btn-group btn-group-xs" role="group">
				<a class="btn btn-info" href="<?=site_url('/reports/report_details/'.$row['id'])?>" role="button"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span> Report Details</a>
			</div>
		</td>
		</tr>
	<?php endforeach;?>
	</table>
</div>
<?php if (isset($pagination)){ echo $pagination; }?>
