<style>
	h2 {text-align: left;}
	label {display: block;}

</style>

<h2>Create New Thread</h2>

<?php

$sections = array(
	'general' => 'General Discussion',
	'news' => 'News'
);


echo form_open('site/create_thread/add');

echo '<label for="section">Section:</label>';
echo form_dropdown('section', $sections);
echo '<br /><br />';

echo '<label for="title">Title:</label>';
echo form_input('title');
echo '<br /><br />';

echo '<label for="post">Post:</label>';
echo form_textarea('post');
echo '<br />';

echo form_submit('submit', 'Create Thread');
echo form_close();

?>



