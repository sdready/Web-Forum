<div id="sidebar">

<table cols="1">

	<tr><th>Recent Threads</th></tr>
	<tr><td style="font-size:small;">Title</td><td style="font-size:small;">Author</td><td style="font-size:small;">Replies</td></tr>
		<?php foreach($sb_recent_threads as $thread): ?>
			<tr>				
				<td width="180"><?php echo anchor('site/view_thread/' . $thread->id, $thread->title, 'style="font-size:small;"'); ?></td>				
				<td width="100"><?php echo anchor('site/account/' . $thread->author, $thread->author, 'style="font-size:small;"'); ?></td>
				<td width="20" style="text-align:center; font-size:small;"><?php echo $thread->num_posts; ?></td>
			</tr>
		<?php endforeach; ?>
	<tr><td>&nbsp;</td></tr>
	
	<tr><th>Most Replies</th></tr>
	<tr><td style="font-size:small;">Title</td><td style="font-size:small;">Author</td><td style="font-size:small;">Replies</td></tr>
		<?php foreach($sb_most_replies as $thread): ?>
			<tr>
				<td width="180"><?php echo anchor('site/view_thread/' . $thread->id, $thread->title, 'style="font-size:small;"'); ?></td>				
				<td width="100"><?php echo anchor('site/account/' . $thread->author, $thread->author, 'style="font-size:small;"'); ?></td>
				<td width="20" style="text-align:center; font-size:small;"><?php echo $thread->num_posts; ?></td>
			</tr>
		<?php endforeach; ?>
</table>

</div>