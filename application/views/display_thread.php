<?php echo anchor('../', 'Home > '); ?>
<?php echo anchor('site/view_section/' . $thread->section, ucwords($thread->section)); ?>
<h2 style="text-align:left;">
	<?php echo $thread->title; ?>
	<?php
		if($is_admin)
			echo '<span id="admin_tools" style="float:right; position:relative; right:20px; font-size:small;">
					<button onclick="moveThread(' . $thread->id . ');">Move Thread</button>
					<button onclick="deleteThread(' . $thread->id . ');">Delete Thread</button>
				</span>';
	?>
</h2>

<?php if(!$hide_op) : ?>
<div class="post">

	<table style="text-align:left;">
		<tr>
		<th style="width:600px;">&nbsp;<?php echo anchor('site/account/' . $thread->author, $thread->author, 'style="color: #100A3E";'); ?>&nbsp;&nbsp;&nbsp;&nbsp;
			<span style="font-size:x-small;";><?php echo unix_to_human(gmt_to_local($thread->time, 'UM4', TRUE)); ?></span>
			<?php echo anchor('site/view_thread/' . $thread->id . $page . '#reply', 'Quote', 'style="font-size:small; float:right; position:relative; top:3px; right:10px;"
				onclick="quote(\''.$thread->author.'\', \''.$thread->original_post.'\');"'); ?>
		</th>
		</tr>

		<tr>
			<td>
				<div id="avatar" style="height:100px;">
					<img width="80" height="80" src="<?php echo $this->membership_model->get_avatar($thread->author); ?>" />
					<span style="font-size:x-small;">Posts: <?php echo $this->membership_model->get_post_count($thread->author); ?></span>
				</div>

				<p style="float:left; padding-left:10px;"><?php echo $thread->original_post; ?></p>
			</td>
		</tr>
	</table>

</div>
<?php endif; ?>

<?php foreach($posts as $post) : ?>
<?php
	$post_content = $post->post_content;
	$count = 0;

	for($i=7; $i < strlen($post_content); $i++)
	{ 
		$quote = " ";
		$content = " ";
		$author = " ";

		$s = $i - 7;
		$sub = substr($post_content, $s, 8);

		if(strcmp($sub, "[/quote]") == 0)
		{
			
			$s -= 14;
			$sub2 = substr($post_content, $s, 14);

			$j=0;
			while(strcmp($sub2, "[quote author=") != 0)
			{
				$quote[$j] = $post_content[$s+14];
				$j++;

				$s--;
				$sub2 = substr($post_content, $s, 14);
			}
			$end = $i;
			$start = $s;

			$quote[$j] = $post_content[$s+14];
			$quote = strrev($quote);

			$j=0;
			while($quote[$j] != ']')
			{
				$j++;
			}

			$author = substr($quote, 0, $j);

			$j++;
			$content = substr($quote, $j, strlen($quote)-strlen($author)-2);


			$length = 14 + strlen($author) + 1 + strlen($content) + 8;

			$original = substr($post_content, $start, $end-$start+1);

			$new = "<table id='quote' style='width:100%;'>
					<tr><th style='color: #100A3E;'><a style='color: #100A3E;' href='http://localhost/forum/index.php/site/account/".$author."'>" . $author . "</a> said...</th></tr>" . 
					"<tr><td>" . $content . "</td></tr></table>";			

			$post_content = str_replace($original, $new, $post_content);

			$i=7;
			$count++;
		}
	}
?>


<?php if($post->hidden != 1) : ?>
<div class="post">

	<table id="<?php echo $post->id; ?>" style="text-align:left;">
		<tr>
		<th style="width:600px;">&nbsp;<?php echo anchor('site/account/' . $post->author, $post->author, 'style="color: #100A3E";'); ?>&nbsp;&nbsp;&nbsp;&nbsp;
			<span style="font-size:x-small;";><?php echo unix_to_human(gmt_to_local($post->time, 'UM4', TRUE)); ?></span>
			<div style="font-size:small; float:right; position:relative; top:3px; right:10px;">
			<?php echo anchor('site/view_thread/' . $thread->id . ($page ? '/' : '') . $page . '#reply', 'Quote', 'onclick="quote(\''.$post->author.'\', \''.$post->post_content.'\');"'); ?>
				<?php if($is_admin) : ?>
					<?php echo anchor('site/change_hidden/' . $post->id, 'Hide', 'style="padding:5px;"'); ?>
					<a href="" onclick="deletePost('<?php echo $post->id; ?>');return false;">Delete</a>
				<?php endif; ?>
			</div>
		</th>
		</tr>
		<tr>
			<td>
				<div id="avatar" style="height:100px;">
					<img width="80" height="80" src="<?php echo $this->membership_model->get_avatar($post->author); ?>" />
					<span style="font-size:x-small;">Posts: <?php echo $this->membership_model->get_post_count($post->author); ?></span>
				</div>
				<div style="float:left; padding-left:10px; width:80%; font-size:small;"><?php echo $post_content; ?></div>
			</td>
		</tr>	
	</table>
</div>

<?php else : ?>
<div id="hidden_post" class="hidden_post">
	<table id="<?php echo $post->id; ?>" style="text-align:left;">
		<tr>
		<th style="width:600px;">&nbsp;<?php echo anchor('site/account/' . $post->author, $post->author, 'style="color: #100A3E";'); ?>
			<div style="font-size:small; float:right; position:relative; top:3px; right:10px;">
				<?php echo anchor('site/view_thread/' . $thread->id . $page . '#reply', 'Quote', 'onclick="quote(\''.$post->author.'\', \''.$post->post_content.'\');"'); ?>
				<?php if($is_admin) : ?>
					<?php echo anchor('site/change_hidden/' . $post->id, 'UnHide', 'style="padding:5px;"'); ?>
					<a href="" onclick="deletePost('<?php echo $post->id; ?>');">Delete</a>
				<?php endif; ?>
			</div>
		</th>
		</tr>
		<tr>
			<tr><td id="hidden_<?php echo $post->id; ?>" style="color:#999999;">
				<span id="hidden_content_<?php echo $post->id; ?>"><a href="" onclick="showPost('<?php echo $post->id; ?>', '<?php echo $post->post_content; ?>'); return false;">This post has been hidden. Click to show.</a></span>
			</td></tr>
		</tr>	
	</table>
</div>
<?php endif; ?>	

<?php endforeach; ?>

<br />
<?php echo $this->pagination->create_links(); ?>

<div id="reply" class="reply" style="position:relative; top:100px;">
	<h3 style="text-align:left;">Reply to This Thread</h2>
<?php
	
	$address = "site/make_post/" . $thread->id;

	echo form_open($address);
	echo '<textarea name="content" id="post_textarea"></textarea>';
	echo '<br />';
	echo '<input type="submit" value="Post" />';
	echo form_close();

?>
</div>

<script type="text/javascript">

function quote(author,content)
{
	var quoteString = "[quote author=" + author + "]" + content + "[/quote]";

	document.getElementById("post_textarea").innerHTML = quoteString;
	document.getElementById("post_textarea").focus();
}

function moveThread(thread_id)
{
	var form = "<form method=\"post\" action=\"http://localhost/forum/index.php/site/move_thread/" + thread_id + "\" /> \
				<select name=\"section\"> \
				<option value=\"general\">General Discussion</option> \
				<option value=\"news\">News</option> \
				</select> \
				<input type=\"submit\" value=\"Move\" /> \
				</form>";

	document.getElementById("admin_tools").innerHTML = form;
}

function deleteThread(id)
{
	var x = confirm("Delete this thread?");

	if(x==true)
	{
		window.location = "http://localhost/forum/index.php/site/delete_thread/" + id;
	}
}

function deletePost(id)
{
	var x = confirm("Delete this post?");

	if(x==true)
	{	
		window.location = "http://localhost/forum/index.php/site/delete_post/" + id;
	}
}

function showPost(postId, content)
{
	var id = "hidden_content_" + postId;
	var new_html = "<p style='float:left; padding-left:10px; width:80%;'>" + content + "</p>";

	document.getElementById(id).innerHTML = new_html;
}

</script>