<?php if (isset($report)):?>
	<div class="page-header-custom">
	  <div class="row">
	    <div class="col-sm-6"><h4><?=$report['name']?></h4></div>
	    <div class="col-sm-6 text-right"></div>
	  </div>
	</div>
<?php endif;?>
<?=form_open('reports/modify_report', 'class="form-horizontal"')?>

<div class="container-fluid">
  <div class="row">
    <div class="col-sm-1"></div>
    <div class="col-sm-10">
      <?=$partial_form?>
    </div>
    <div class="col-sm-1"></div>
  </div>
</div>

<h4>Custom Report Fields</h4>
<div class="table-responsive">
<table class="table table-condensed table-striped table-bordered" id="custom_fields_table">
<tr><th>field code</th><th>field type</th><th>is mandatory</th><th>default value</th><th>placeholder text</th><th>help text</th><th>selection field source type</th><th>selection field source</th></tr>
<?php foreach ($report_input_fields as &$F):?>
	<tr>
	<td><div class="form-group-sm"><?=form_input('field[]', $F['field'], 'class="form-control"')?></div></td>
	<td><div class="form-group-sm"><?=form_dropdown('field_type[]', array('INPUT'=>'Input', 'SELECT'=>'Selection'), $F['field_type'], 'class="form-control"')?></div></td>
	<td><div class="form-group-sm"><?=form_dropdown('is_mandatory[]', $this->core_model->get_select_box_options_from_lookup('BOOL_YES_NO'), $F['is_mandatory'], 'class="form-control"')?></div></td>
	<td><div class="form-group-sm"><?=form_input('default_value[]', $F['default_value'], 'class="form-control"')?></div></td>
	<td><div class="form-group-sm"><?=form_input('placeholder_text[]', $F['placeholder_text'], 'class="form-control"')?></div></td>
	<td><div class="form-group-sm"><?=form_input('help_text[]', $F['help_text'], 'class="form-control"')?></div></td>
	<td><div class="form-group-sm"><?=form_dropdown('select_field_source_type[]', array(''=>'---------', 'LIST'=>'List (i.e A,B,C,D)', 'RANGE'=>'Number Range (i.e 1990-2016:1)', 'SQL'=>'SQL Query', 'LOOKUP'=>'Lookup Grouping'), $F['select_field_source_type'], 'class="form-control"')?></div></td>
	<td><div class="form-group-sm"><?=form_textarea(array('name'=>'select_field_source[]', 'rows'=>2), $F['select_field_source'], 'class="form-control"')?></div></td>
	</tr>
<?php endforeach;?>
	<tr>
	<td><div class="form-group-sm"><?=form_input('field[]', null, 'class="form-control"')?></div></td>
	<td><div class="form-group-sm"><?=form_dropdown('field_type[]', array('INPUT'=>'Input', 'SELECT'=>'Selection'), null, 'class="form-control"')?></div></td>
	<td><div class="form-group-sm"><?=form_dropdown('is_mandatory[]', $this->core_model->get_select_box_options_from_lookup('BOOL_YES_NO'), '0', 'class="form-control"')?></div></td>
	<td><div class="form-group-sm"><?=form_input('default_value[]', null, 'class="form-control"')?></div></td>
	<td><div class="form-group-sm"><?=form_input('placeholder_text[]', null, 'class="form-control"')?></div></td>
	<td><div class="form-group-sm"><?=form_input('help_text[]', null, 'class="form-control"')?></div></td>
	<td><div class="form-group-sm"><?=form_dropdown('select_field_source_type[]', array(''=>'---------', 'LIST'=>'List (i.e A,B,C,D)', 'RANGE'=>'Number Range (i.e 1990-2016:1)', 'SQL'=>'SQL Query', 'LOOKUP'=>'Lookup Grouping'), null, 'class="form-control"')?></div></td>
	<td><div class="form-group-sm"><?=form_textarea(array('name'=>'select_field_source[]', 'rows'=>2), null, 'class="form-control"')?></div></td>
	</tr>

	<tr><td colspan="8" class="text-right"><?=form_submit(array('type'=>'button', 'value'=>'+ Add Extra Fields', 'class'=>'btn btn-primary btn-xs', 'onClick'=>'duplicateTableRow(\'custom_fields_table\');'))?></td></tr>
</table>
		
<div class="text-right">
	<a class="btn btn-default" href="<?php if (isset($report)){ echo site_url('/reports/report_details/'.$report['id']); } else { echo site_url('/reports/'); }?>" role="button">Cancel</a>&nbsp; 
	<?=form_submit(array('type'=>'submit', 'name'=>'save_report', 'value'=>'Save', 'class'=>'btn btn-primary'))?>
</div>
<?=form_close()?>
</div>
