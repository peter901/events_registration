<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title> {title} </title>

<link rel="stylesheet" href="<?=base_url('/media/static/styles.css')?>" type="text/css">
</head>
<body>

<h1>Information &nbsp;>&nbsp; Change Password</h1>
<br>
<?=form_open('core/change_pass')?>
	<?php 
		if ($user != null) {
			echo form_hidden('user_id', $user['id']);
		}
	?>
<table class="form-table large_lists">
	<tr class="required"><th>Old Password:</th><td><?php echo form_error('password_old', '<div class="messages_error">', '</div>'); ?><?php echo form_password('password_old', ""); ?></td></tr>
	<tr class="required"><th>New Password:</th><td><?php echo form_error('password', '<div class="messages_error">', '</div>'); ?><?php echo form_password('password', ''); ?><br>
	<?php echo form_error('password_re', '<div class="messages_error">', '</div>'); ?><?php echo form_password('password_re', ''); ?><br><span class="helptext">retype password</span></td></tr>

<tr class="buttons"><th>&nbsp;</th><td><input name="cancel" value="Cancel" type="submit">&nbsp;<input name="save" value="Save" type="submit"></td></tr>
</table>
<?=form_close()?>

</body>
</html>
