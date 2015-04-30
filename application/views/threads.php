<h2>Account Management</h2><hr style="width:615px; position:relative; right:160px; bottom:20px;"/>


<ul id="account_menu"> 
	<li><?php echo anchor('site/account/' . $account->username, 'Account'); ?></li>
	<?php if($is_user) : ?>
	<li><?php echo anchor('site/account/' . $account->username . '/messages', 'Messages'); ?></li>
	<?php endif; ?>
	<li id="selected"><?php echo anchor('site/account/' . $account->username . '/threads', 'Threads'); ?></li>
</ul>

<img style="padding-right:10px;" height="100" width="100" align="left" src="<?php echo $account->avatar; ?>" />

<h3>
	<?php echo $account->username; ?> - <?php echo $account->member_type; ?></h3>
	 <span style="font-size:small; position:relative; bottom:22px;">Member Since: <?php echo mdate('%M %d, %Y', $account->join_date); ?></span>
	<?php if(!$is_user) : ?>
		<button id="compose_button" onclick="sendMessage('<?php echo $account->username; ?>');">Send Message</button>
	<?php endif; ?>

<br /><br /><br /><br />

<form id="message_form" action="http://localhost/forum/index.php/site/send_message" method="post">

</form>

<div id="account_content">
	<table>
		<tr><th><?php echo $account->username; ?>'s Threads</th></tr>
		<?php if($recent_threads != NULL) : ?>
		<?php foreach ($recent_threads as $thread) : ?>
			<tr>
				<td><?php echo anchor('site/view_thread/' . $thread->id, $thread->title); ?></td>
				<td style="font-size:small;">Replies: <?php echo $thread->num_posts; ?></td>
				<td style="font-size:x-small;"><?php echo  unix_to_human(gmt_to_local($thread->time, 'UM4', TRUE)); ?></td>
			</tr>
		<?php endforeach; ?>
		<tr><td><?php echo $this->pagination->create_links(); ?></td></tr>
		<?php endif; ?>
		<tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr>

		<tr><th>Most Popular</th></tr>
		<tr>
			<td style="width:150px;"><?php echo anchor('site/view_thread/' . $most_commented_thread->id, $most_commented_thread->title); ?></td>
			<td style="font-size:small; width:150px;">Replies: <?php echo $thread->num_posts; ?></td>
			<td style="font-size:x-small;"><?php echo  unix_to_human(gmt_to_local($most_commented_thread->time, 'UM4', TRUE)); ?></td>
		</tr>
	</table>
</div>

<script type="text/javascript">
	function sendMessage(username)
	{
		document.getElementById("message_form").innerHTML = " \
			<input type='hidden' name='receiver' value="+username+" /> \
			Subject: <input type='text' name='subject' /> \
			Message: <textarea cols='20' name='message'></textarea> \
			<input type='submit' value='Send' /> <br /><br /><br /><br />";

		document.getElementById("compose_button").style.display = "none";

	}
</script>