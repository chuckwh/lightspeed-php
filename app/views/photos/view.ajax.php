<div id = "pic_meta" class="pic-meta">
	<p><strong>Guest Description: </strong><?=$photo->description; ?></p>
	<p>Look below the picture for events related to the following <strong>Topic(s): </strong>
	<?php foreach ($photo->tags as $tag): ?>
		<?=$this->html->link($tag, array('Photos::index', 'args' => array($tag))); ?>
	<?php endforeach ?>
	</p>
</div>

// <?=$this->html->image("/photos/view/{$photo->_id}.jpg", array('alt' => $photo->title, 'id' => 'p_' . $photo->_id)); ?>
<?=$this->html->image("/photos/image/{$photo->_id}.jpg", array('alt' => $photo->title, 'id' => 'p_' . $photo->_id)); ?>