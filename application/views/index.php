<!-- begin body -->

<?php #var_dump($cpds['year3_total_hours'][0]); 
	$year3_structured = $cpds['year3_total_hours_icpau'][0]['t_hours'] + $cpds['year3_total_hours'][0]['t_s_other'];
	$year2_structured = $cpds['year2_total_hours_icpau'][0]['t_hours'] + $cpds['year2_total_hours'][0]['t_s_other'];
	$year1_structured = $cpds['year1_total_hours_icpau'][0]['t_hours'] + $cpds['year1_total_hours'][0]['t_s_other'];

	$year3_unstructured = $cpds['year3_total_hours_icpau'][0]['t_us_hours'] + $cpds['year3_total_hours'][0]['t_us_other'];
	$year2_unstructured = $cpds['year2_total_hours_icpau'][0]['t_us_hours'] + $cpds['year2_total_hours'][0]['t_us_other'];
	$year1_unstructured = $cpds['year1_total_hours_icpau'][0]['t_us_hours'] + $cpds['year1_total_hours'][0]['t_us_other'];

	$year3_total = $year3_structured + $year3_unstructured;
	$year2_total = $year2_structured + $year2_unstructured;
	$year1_total = $year1_structured + $year1_unstructured;
?>
<table style="width:100%;">
<tr><td><h3>Home>CPD Declaration</h3></td></tr>
</table> 
  <div style="padding-left:10px;">

<table style="border-collapse:collapse; font-size:12px; color:#000000" border="1" bordercolor="#3F337B" cellpadding="6" cellspacing="0" width="99%">
  <tbody><tr bgcolor="#DEE7EA">
    <td colspan="4" class="cpd" align="center" valign="top" height="29"><strong>CPD Hours in a 3 Year Period</strong></td>
  </tr>
  <tr bgcolor="#DEE7EA">
    <td class="cpd" align="center" valign="top" width="651" height="29">&nbsp;</td>
    <td class="cpd" align="center" valign="top" width="40"><?=date('Y')-2?></td>
    <td class="cpd" align="center" valign="top" width="40"><?=date('Y')-1?></td>
    <td class="cpd" align="center" valign="top" width="33"><?=date('Y')?></td>
    </tr>
 <tr>
    <td class="cpd" valign="top" width="651" height="29">Structured CPD Hours Earned</td>
    <td class="cpd" valign="top" width="40"><?=$year3_structured?></td>
    <td class="cpd" valign="top" width="40"><?=$year2_structured?></td>
    <td class="cpd" valign="top" width="33"><?=$year1_structured?></td>
    </tr>
    <tr>
      <td class="cpd" valign="top" width="651" height="29">Unstructured CPD Hours Earned</td>
    <td class="cpd" valign="top" width="40"><?=$year3_unstructured?></td>
    <td class="cpd" valign="top" width="40"><?=$year2_unstructured?></td>
    <td class="cpd" valign="top" width="33"><?=$year1_unstructured?></td>
    </tr>
  <tr>
    <td class="cpd" valign="top" width="651" height="29"><strong>Total Hours Earned</strong></td>
    <td class="cpd" valign="top" width="40"><strong><?=$year3_total?></strong></td>
    <td class="cpd" valign="top" width="40"><strong><?=$year2_total?></strong></td>
    <td class="cpd" valign="top" width="33"><strong><?=$year1_total?></strong></td>
    </tr>


<tr>
  <td class="cpd" valign="top" width="651" height="29">Minimum Required Hours</td>
  <td class="cpd" valign="top" width="40">40</td>
  <td class="cpd" valign="top" width="40">40</td>
  <td class="cpd" valign="top" width="33">40</td>
</tr>
    
    </tbody></table>

<br />

  
 
   
  
 
 
<br />
<table class="cpd" style="border-collapse:collapse; font-size:12px; color:#000000" border="1" bordercolor="#3F337B" cellpadding="6" cellspacing="0" width="99%">
  <tbody><tr bgcolor="#DEE7EA">
    <td colspan="6" class="cpd" align="center" valign="top" height="32"><strong>CPD for the Year <?=date('Y')?></strong>
    
    <div style="float:right"> <a class="various2" href="<?= $base_url; ?>/cpds/add_cpd/<?= $user['id'] ?>" rel="edit_object_popup"><b style="color:#F00">Add Hours</b></a> | <a href="https://www.icpau.co.ug/icpaumembers/cpd.php?declare=FM331&amp;year=2015"><b style="color:#F00">Declare</b></a> </div>
    
    </td>
  </tr>
  <tr bgcolor="#DEE7EA">
    <td class="cpd" align="center" valign="top" width="298" height="34">Activity</td>
    <td class="cpd" align="center" valign="top" width="167">Organiser</td>
    <td class="cpd" align="center" valign="top" width="101">Date</td>
    <td class="cpd" align="center" valign="top" width="70">Structured<br />
      Units</td>
    <td class="cpd" align="center" valign="top" width="71">Unstructured<br />
      Units</td>
    <td class="cpd" valign="top" width="29">&nbsp;</td>
  </tr>
  
<?php foreach($cpds['year1_other_cpds'] as $c):?>
    <tr>
    <td class="cpd" valign="top" width="298" height="29"><?=$c['description']?></td>
    <td class="cpd" valign="top" width="167"><?=$c['provider']?></td>
    <td class="cpd" valign="top" width="101"><?=$c['date_of_aquisition']?></td>
    <td class="cpd" valign="top" width="70"><?=$c['structured_hours']?></td>
    <td class="cpd" valign="top" width="71"><?=$c['unstructured_hours']?></td>
    <td class="cpd" valign="top" width="29"><a class="various2" href="<?=$base_url;?>/cpds/edit_cpd/<?=$c['id'] ?>" rel="edit_object_popup">Edit</a></td>
  </tr>
<?php endforeach;?>  

<?php foreach($cpds['year1_icpau_cpds'] as $c):?>
    <tr>
    <td class="cpd" valign="top" width="298" height="29"><?=$c['theme']?></td>
    <td class="cpd" valign="top" width="167">ICPAU</td>
    <td class="cpd" valign="top" width="101"><?=$c['start_date']?></td>
    <td class="cpd" valign="top" width="70"><?=$c['hours']?></td>
    <td class="cpd" valign="top" width="71"><?=$c['us_hours']?></td>
    <td class="cpd" valign="top" width="29"><a class="various2" href="https://www.icpau.co.ug/icpaumembers/editcpd.php?cpd=1029">Edit</a></td>
  </tr>
<?php endforeach;?>  
      
</tbody></table>
<br /> <table class="cpd" style="border-collapse:collapse; font-size:12px; color:#000000" border="1" bordercolor="#3F337B" cellpadding="6" cellspacing="0" width="99%">
  <tbody><tr bgcolor="#DEE7EA">
    <td colspan="6" class="cpd" align="center" valign="top" height="32"><strong>CPD for the Year <?=date('Y')-1?></strong>
    
    <div style="float:right"> <a class="various2" href="<?= $base_url; ?>/cpds/add_cpd/<?= $user['id'] ?>" rel="edit_object_popup"><b style="color:#F00">Add Hours</b></a> | <a href="https://www.icpau.co.ug/icpaumembers/cpd.php?declare=FM331&amp;year=2015"><b style="color:#F00">Declare</b></a> </div>
    
    </td>
  </tr>
  <tr bgcolor="#DEE7EA">
    <td class="cpd" align="center" valign="top" width="298" height="34">Activity</td>
    <td class="cpd" align="center" valign="top" width="167">Organiser</td>
    <td class="cpd" align="center" valign="top" width="101">Date</td>
    <td class="cpd" align="center" valign="top" width="70">Structured<br />
      Units</td>
    <td class="cpd" align="center" valign="top" width="71">Unstructured<br />
      Units</td>
    <td class="cpd" valign="top" width="29">&nbsp;</td>
  </tr>
<?php foreach($cpds['year2_other_cpds'] as $c):?>
    <tr>
    <td class="cpd" valign="top" width="298" height="29"><?=$c['description']?></td>
    <td class="cpd" valign="top" width="167"><?=$c['provider']?></td>
    <td class="cpd" valign="top" width="101"><?=$c['date_of_aquisition']?></td>
    <td class="cpd" valign="top" width="70"><?=$c['structured_hours']?></td>
    <td class="cpd" valign="top" width="71"><?=$c['unstructured_hours']?></td>
    <td class="cpd" valign="top" width="29"><a class="various2" href="<?=$base_url;?>/cpds/edit_cpd/<?=$c['id'] ?>" rel="edit_object_popup">Edit</a></td>
  </tr>
<?php endforeach;?>  

<?php foreach($cpds['year2_icpau_cpds'] as $c):?>
    <tr>
    <td class="cpd" valign="top" width="298" height="29"><?=$c['theme']?></td>
    <td class="cpd" valign="top" width="167">ICPAU</td>
    <td class="cpd" valign="top" width="101"><?=$c['start_date']?></td>
    <td class="cpd" valign="top" width="70"><?=$c['hours']?></td>
    <td class="cpd" valign="top" width="71"><?=$c['us_hours']?></td>
    <td class="cpd" valign="top" width="29"><a class="various2" href="#">#</a></td>
  </tr>
<?php endforeach;?>  
</tbody></table>
<br /> 

<table class="cpd" style="border-collapse:collapse; font-size:12px; color:#000000" border="1" bordercolor="#3F337B" cellpadding="6" cellspacing="0" width="99%">
  <tbody><tr bgcolor="#DEE7EA">
    <td colspan="6" class="cpd" align="center" valign="top" height="32"><strong>CPD for the Year <?=date('Y')-2?></strong>
    
    <div style="float:right"> <a class="various2" href="<?= $base_url; ?>/cpds/add_cpd/<?= $user['id'] ?>" rel="edit_object_popup"><b style="color:#F00">Add Hours</b></a> | <a href="https://www.icpau.co.ug/icpaumembers/cpd.php?declare=FM331&amp;year=2015"><b style="color:#F00">Declare</b></a> </div>
    
    </td>
  </tr>
  <tr bgcolor="#DEE7EA">
    <td class="cpd" align="center" valign="top" width="298" height="34">Activity</td>
    <td class="cpd" align="center" valign="top" width="167">Organiser</td>
    <td class="cpd" align="center" valign="top" width="101">Date</td>
    <td class="cpd" align="center" valign="top" width="70">Structured<br />
      Units</td>
    <td class="cpd" align="center" valign="top" width="71">Unstructured<br />
      Units</td>
    <td class="cpd" valign="top" width="29">&nbsp;</td>
  </tr>
<?php foreach($cpds['year3_other_cpds'] as $c):?>
    <tr>
    <td class="cpd" valign="top" width="298" height="29"><?=$c['description']?></td>
    <td class="cpd" valign="top" width="167"><?=$c['provider']?></td>
    <td class="cpd" valign="top" width="101"><?=$c['date_of_aquisition']?></td>
    <td class="cpd" valign="top" width="70"><?=$c['structured_hours']?></td>
    <td class="cpd" valign="top" width="71"><?=$c['unstructured_hours']?></td>
    <td class="cpd" valign="top" width="29"><a class="various2" href="<?=$base_url;?>/cpds/edit_cpd/<?=$c['id'] ?>" rel="edit_object_popup">Edit</a></td>
  </tr>
<?php endforeach;?>  

<?php foreach($cpds['year3_icpau_cpds'] as $c):?>
    <tr>
    <td class="cpd" valign="top" width="298" height="29"><?=$c['theme']?></td>
    <td class="cpd" valign="top" width="167">ICPAU</td>
    <td class="cpd" valign="top" width="101"><?=$c['start_date']?></td>
    <td class="cpd" valign="top" width="70"><?=$c['hours']?></td>
    <td class="cpd" valign="top" width="71"><?=$c['us_hours']?></td>
    <td class="cpd" valign="top" width="29"><a class="various2" href="#">#</a></td>
  </tr>
<?php endforeach;?>  
      
</tbody></table>
<br />             
</div>
<!--
<ul class="accordion">                     

  <li class="active"><a href="#" class="opener"><h2>CPD Declaration</h2></a></li>
<a style="margin-left:10px;" href="<?#= $base_url; ?>/cpds/add_cpd/<?#= $user['id'] ?>" rel="edit_object_popup">(Add Cpd)</a>
</table>



<table style="border-collapse:collapse; font-size:12px; color:#000000" border="1" bordercolor="#3F337B" cellpadding="6" cellspacing="0" width="99%">
<tr>
<th>Sn</th>
<th>Provider</th>
<th>Description</th>
<th>Type</th>
<th>hours</th>
<th>Date</th>
<th>File</th>
<th>Action</th>
</tr>
<?php# foreach ($cpds as $key =>$e): ?>
<?php#  $color  = (($key%2)==0)?'bgcolor="#B0B6F7"':'bgcolor="#E2E2E2"'; ?>
<tr <?#=$color?>>
<td><?php# echo $key + 1; ?> </td>
<td><?php# echo $e['provider'] ?></td>
<td><?php# echo $e['description'] ?></td>
<td><?php# echo $e['type'] ?></td>
<td><?php# echo $e['hours'] ?></td>
<td><?php# echo $e['date_of_aquisition'] ?></td>
<td><a href="<?php# echo $media_url.$e['upload_path'] ?>" target="_blank">Download</a></td>
<td><a href="<?php# echo $base_url; ?>/cpds/delete_cpd/<?php# echo $e['id']?>" rel="edit_object_popup">Delete</a> ||
    <a href="<?php# echo $base_url; ?>/cpds/edit_cpd/<?php# echo $e['id'] ?>" rel="edit_object_popup">Edit</a></td>
</tr>

<?php# endforeach ?>

</table>
<br><br><br>
<table style="border-collapse:collapse; font-size:12px; color:#000000" border="1" bordercolor="#3F337B" cellpadding="6" cellspacing="0" width="99%">
<tbody>
<tr>
<th>theme</th>
<th>venue</th>
<th>Date</th>
<th>hours</th>
<th>Action</th>
</tr>
<?php#foreach ($icpau_themes as $key =>$e): ?>
<?php# $color  = (($key%2)==0)?'bgcolor="#B0B6F7"':'bgcolor="#E2E2E2"'; ?>
<tr <?#=$color?>>
<td><?php# echo substr($e['theme'],0,16); ?></td>
<td><?php# echo substr($e['venue'],0,16) ?></td>
<td><?php# echo $e['start_date'] ?></td>
<td><?php# echo $e['hours'] ?></td>
<td><a href="<?php# echo $base_url; ?>/cpds/delete_icpau_theme/<?php# echo $e['id']?>" rel="edit_object_popup">Remove</a></td>
</tr>

<?php##endforeach ?>
</tbody>
</table>


</ul>
-->
<!-- end of body -->                  
