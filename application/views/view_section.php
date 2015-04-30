<style type="text/css">
	#pagination {
		position: relative;
		left: 535px;
		bottom: 39px;
	}
</style>

<a href="http://localhost/forum/">Home ></a>
<h3 style="text-align:left;"><?php echo ucwords($section . ' - '); ?><small><?php echo anchor('site/create_thread','(Create New Thread)'); ?></small></h3>

<?php echo $this->pagination->create_links(); ?>

<?php foreach($recent_threads as $thread): ?>
<div style="width:600px; height: 100px; border-top:1px solid #C6D6F8;">
<?php echo anchor('site/view_thread/' . $thread->id, $thread->title); ?>
</div>

<?php endforeach; ?>


