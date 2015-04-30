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
<h4>Your Messages:</h4>

<span style="position:relative; bottom:40px; right:45px; float:right;">
	<?php
		if(strcmp($type, 'inbox') == 0)
		{
			echo '<strong style="padding-right:20px;">Inbox</strong> |' . anchor('site/account/' . $account->username . '/messages/sent', 'Sent', 'style="padding-left:20px;"');
			$header_text = 'Sender';
		}
		else
		{
			echo anchor('site/account/' . $account->username . '/messages', 'Inbox', 'style="padding-right:20px;"') . ' | <strong style="padding-left:20px;">Sent</strong>';
			$header_text = 'Receiver';
		}
	?>
</span>

<table style="width:600px;">
	<tr><th>Subject</th><th><?php echo $header_text; ?></th><th>Date Sent</th></tr>
	<?php foreach($messages as $message) : ?>	
		
		<tr>
			<td><?php echo anchor('site/account/' . $account->username . '/messages/' . $message->id,$message->subject); ?></td>
			<?php if(strcmp($type, 'inbox') == 0) : ?>
			<td><?php echo anchor('site/account/' . $message->sender,$message->sender); ?></td>
			<?php else : ?>
			<td><?php echo anchor('site/account/' . $message->receiver,$message->receiver); ?></td>
			<?php endif; ?>
			<td><?php echo mdate('%M %d, %Y - %g:%i %a', gmt_to_local($message->time, 'UM4', TRUE)); ?></td>
			<td><?php echo anchor('site/delete_message/' . $message->id, 'Delete'); ?></td>
		</tr>

	<?php endforeach; ?>
</table>