<h2>Account Management</h2><hr style="width:615px; position:relative; right:160px; bottom:20px;"/>


<ul id="account_menu"> 
	<li><?php echo anchor('site/account/' . $account->username, 'Account'); ?></li>
	<?php if($is_user) : ?>
	<li id="selected"><?php echo anchor('site/account/' . $account->username . '/messages', 'Messages'); ?></li>
	<?php endif; ?>
	<li><?php echo anchor('site/account/' . $account->username . '/threads', 'Threads'); ?></li>
</ul>




<img style="padding-right:10px;" height="100" width="100" align="left" src="<?php echo $account->avatar; ?>" />
<h3><?php echo $account->username; ?> - <?php echo $account->member_type; ?></h3>
<span style="font-size:small; position:relative; bottom:22px;">Member Since: <?php echo mdate('%M %d, %Y', $account->join_date); ?></span>
<br /><br /><br /><br />
<table style="width:300px;">
	<tr>
		<th>To:</th><td><?php echo anchor('site/account/' . $message->receiver,$message->receiver); ?></td>
	</tr>
	<tr>
		<th>From:</th><td><?php echo anchor('site/account/' . $message->sender,$message->sender); ?></td>
	</tr>
	<tr>
		<th>Date:</th><td><?php echo mdate('%M %d, %Y - %g:%i %a', gmt_to_local($message->time, 'UM4', TRUE)); ?></td>
	</tr>
	<tr><td>&nbsp;</td></tr>
	<tr>
		<th>Subject:</th><td><?php echo $message->subject; ?></td>
	</tr>
</table>
<hr style="width:615px; position:relative; right:160px; bottom:10px;"/>
<?php echo $message->message; ?>