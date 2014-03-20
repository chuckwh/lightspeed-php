<div><a href="/posts/add">Add a Post</a> | <a href="/posts/edit">Edit a Post</a></div>
<? foreach($posts as $post): ?>
<article>
    <h2>
		<? //TODO: No hard paths ?>
        <?php echo $this->html->link($post->title,'/posts/'.$post->slug); ?>
        
    </h2>
    <p><?php echo $this->markdown->render($post->body); ?></p>
</article>
<? endforeach; ?>
<?/*BootstrapPaginator helper*/?>
<?php echo $this->BootstrapPaginator->paginate(); ?>