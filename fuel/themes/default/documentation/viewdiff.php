<h3>View the difference between the selected versions</h3>
<?php
	echo \Form::open(array('class' => 'form left'));
	echo \Form::submit('back', 'Back', array('class' => 'btn purple'));
	echo \Form::submit('cancel', 'Cancel', array('class' => 'btn '));
	echo '	<hr />
	<p>Legend:&nbsp;&nbsp;<ins>Text marked like this has been added to "Source"</ins>&nbsp;&nbsp;&nbsp;<del>Text marked like this has been removed from "Target"</del></p>
	<hr />';
	echo \Form::close();
	echo $diff;
?>
