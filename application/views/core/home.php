<table class="table table-condensed">
<tr><td>
<div class = "text-info">Notices:</div>

<?php if (empty($exams_confirmation_exists)):?>
	<ol>
	<li>Dear <?=$user['first_name']?>, your ICPAU ID is: <?=$user['identity_id'];?></li>
	<li>You can browse through your personalized content using the above menu to familiarize yourself with current developments.</li>
	<!--<li>If you are a continuing CTA / CPA / ATD student AND you successfully register/registered for your exam papers - your online financial statement will reflect the paper(s) registered for as provisional. Your final provisional papers will get CONFIRMED within the first two weeks after the late registration date.</li>-->
	</ol>
<?php else: ?>
	<ul>
	<li>Dear <?=$user['first_name']?>, you can view your <b><u><i>exams registration confirmation</i></u></b> by clicking on the `Exams` link under the `Registration` menu above.</li>
	</ul>
<?php endif; ?>

</td></tr>
</table>
