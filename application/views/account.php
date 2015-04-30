<h2>Account Management</h2><hr style="width:615px; position:relative; right:160px; bottom:20px;"/>


<ul id="account_menu"> 
	<li id="selected"><?php echo anchor('site/account/' . $account->username, 'Account'); ?></li>
	<?php if($is_user) : ?>
	<li><?php echo anchor('site/account/' . $account->username . '/messages', 'Messages'); ?></li>
	<?php endif; ?>
	<li><?php echo anchor('site/account/' . $account->username . '/threads', 'Threads'); ?></li>
</ul>


<img style="padding-right:10px;" height="100" width="100" align="left" src="<?php echo $account->avatar; ?>" />

<h3>
	<?php echo $account->username; ?> - <?php echo $account->member_type; ?></h3>
	 <span style="font-size:small; position:relative; bottom:22px;">Member Since: <?php echo mdate('%M %d, %Y', $account->join_date); ?></span>
	<?php if(!$is_user && $is_logged_in) : ?>
		<button id="compose_button" onclick="sendMessage('<?php echo $account->username; ?>');">Send Message</button>
	<?php endif; ?>

<br /><br /><br /><br />

<form id="message_form" action="http://localhost/forum/index.php/site/send_message" method="post">

</form>


<div id="account_content">
	<table style="width:300px; float:left;">
		<tr><th>Personal Info</th></tr>
		<tr>
			<td>Name:</td><td><?php echo $account->first_name . ' ' . $account->last_name; ?></td>
		</tr>
		<tr>
			<td>Email:</td><td><?php echo $account->email_address; ?></td>
		</tr>
	</table>

	<?php echo $this->encrypt->sha1('shaun-209'); ?>

	<table style="width:300px; float:right; border-left: 1px solid black; padding-left:10px;">
		<tr><th>Comment History (<?php echo $account->num_posts; ?>)</th></tr>
		<?php foreach ($recent_posts as $post) : ?>
			<?php
				$page = (ceil($post->post_num/15.0) - 1) * 14;
			?>
			<?php if($page == 0) : ?>
				<tr><td><?php echo anchor('site/view_thread/' . $post->thread_id . '#' . $post->id, $post->thread_title); ?></td><td style="font-size:x-small;"><?php echo  unix_to_human(gmt_to_local($post->time, 'UM4', TRUE)); ?></td></tr>
			<?php else : ?>
				<tr><td><?php echo anchor('site/view_thread/' . $post->thread_id . '/' . $page . '#' . $post->id, $post->thread_title); ?></td><td style="font-size:x-small;"><?php echo  unix_to_human(gmt_to_local($post->time, 'UM4', TRUE)); ?></td></tr>
			<?php endif; ?>			
		<?php endforeach; ?>
		<tr><td>&nbsp;</td></tr>
		<tr><td><?php echo $this->pagination->create_links(); ?></td></tr>
	</table>

	<table style="width:300px; float:left; position:relative; top:40px;">
		<tr><th>Friends</th></tr>

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

