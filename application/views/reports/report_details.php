<div class="page-header-custom">
	<div class="row">
	    <div class="col-sm-7">
	    	<h3><?=$report['name']?></h3>
			<small>
			<b>Description: </b><?=$report['description']?><br>
			<b>Created By: </b><?=$report['created_by']?><br>
			<b>Created At: </b><?=$report['created_at']?>
			</small>
	    </div>
	    <div class="col-sm-5 text-right">
	    	<div class="btn-group btn-group-sm" role="group">
 				<?php if ($this->custom_auth->has_privilege('RUN_REPORTS')):?><a class="btn btn-primary" href="<?=site_url('/reports/run_report/'.$report['id'])?>" role="button"><span class="glyphicon glyphicon-repeat" aria-hidden="true"></span> Run Report</a><?php endif;?>
				<?php if ($this->custom_auth->has_privilege('REPORTS_ADMIN')):?><a class="btn btn-default" href="<?=site_url('/reports/modify_report/'.$report['id'])?>" role="button"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span> Edit Report</a><?php endif;?>
				<?php if ($this->custom_auth->has_privilege('REPORTS_ADMIN')):?><a class="btn btn-danger" href="<?=site_url('/reports/delete_report/'.$report['id'])?>" role="button" onclick="return confirm('Are you sure you want to delete this report and all related data?');"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true"></span> Delete Report</a><?php endif;?>
		  	</div>
	    </div>
    </div>
</div>

<div class="table-responsive">
	<table class="table table-condensed table-striped table-bordered">
	<tr><th>label</th><th>created by</th><th>created at</th><th>updated at</th><th>status</th><th>comment</th><th></th></tr>
	<?php foreach ($report_output as &$row):?>
		<tr><td><?=$row['label']?></td><td><?=$row['actor']?></td><td><?=$row['created_at']?></td><td><?=$row['updated_at']?></td><td><?=$row['status']?></td><td><?=$row['comment']?></td>
		<td class="text-right">
			<div class="btn-group btn-group-xs" role="group">
				<?php if (( ! empty($row['status']) && $row['status'] == 'COMPLETE') || empty($row['status'])):?><a class="btn btn-success" href="<?=base_url('/media/DOWNLOADS/REPORTS/'.$row['file_name'])?>" role="button"><span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span> Download Report Output</a><?php endif;?>
				<?php if ((( ! empty($row['status']) && in_array($row['status'], ['ERROR', 'COMPLETE'])) || empty($row['status'])) && $this->custom_auth->has_privilege('REPORTS_ADMIN')):?><a class="btn btn-primary" href="<?=site_url('/reports/run_report/'.$report['id'].'/'.$row['output_id'])?>" role="button"><span class="glyphicon glyphicon-repeat" aria-hidden="true"></span> Re-run this Report</a><?php endif;?>
				<?php if ((( ! empty($row['status']) && in_array($row['status'], ['ERROR', 'COMPLETE'])) || empty($row['status'])) && $this->custom_auth->has_privilege('REPORTS_ADMIN')):?><a class="btn btn-danger" href="<?=site_url('/reports/delete_report_output/'.$report['id'].'/'.$row['output_id'])?>" role="button" onclick="return confirm('Are you sure you want to delete this report output?');"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true"></span> Delete Report Output</a><?php endif;?>
			</div>
		</td>
		</tr>
	<?php endforeach;?>
	</table>
</div>
<?php if (isset($pagination)){ echo $pagination; }?>