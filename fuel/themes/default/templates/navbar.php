		<ul id="nav">
			<?php foreach ($navitems as $__nav): ?>
			<li><a href="<?php echo $__nav['link']; ?>" class="<?php echo $__nav['class']?>"><?php echo $__nav['name']?></a></li>
			<?php endforeach; ?>
		</ul>
