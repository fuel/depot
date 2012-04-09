<h3>View the difference between the selected versions</h3>
<?php
	echo \Form::open(array('class' => 'form left'));
	echo \Form::submit('back', 'Back', array('class' => 'btn purple'));
	echo \Form::submit('cancel', 'Cancel', array('class' => 'btn '));
	echo '<hr /';
	echo \Form::close();
	echo $diff;
?>
