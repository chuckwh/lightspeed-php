<?php echo $this->form->create(); ?>
    <?php echo $this->form->field('title'); ?>
    <?php echo $this->form->field('body', array('type' => 'textarea')); ?>
	<?php echo $this->form->field('tags'); ?>
	<?php echo $this->form->field('created', array('type' => 'hidden','value' => date('Y-m-d H:i:s', time()))); ?>
    <?php echo $this->form->submit('Add Post', array('class' => 'btn btn-inverse')); ?>
<?php echo $this->form->end(); ?>