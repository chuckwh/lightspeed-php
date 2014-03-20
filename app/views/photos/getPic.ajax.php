<div id = "pic_meta" class="pic-meta">
<?php
$photoCredit = null;
$status = null;
$topic = null;

if( $photo->credit !=null) {
	$photoCredit = $photo->credit;
	$photoCreditTruncated = substr($photoCredit, 0, 50) . '...';
}
else {
	$photoCredit = 'N/A';
	$photoCreditTruncated = '';
}
if( $photo->status !=null) {
	$status = $photo->status;
}
else {
	$status = ' - - -';
}
if( $photo->title !=null) {
	$title = $photo->title;
}
else {
	$title = 'Untitled';
}
if( $photo->tags !=null) {
	$topic = $photo->tags;
}
else {
	$topic = ' - - -';
}
$photoCreditTruncated = substr($photoCredit, 0, 50) . '...';

?>

	<?php if ($photoCredit == 'N/A') {?>
	<p><strong>Credit: </strong><?=$photoCreditTruncated ?>
	<?php }
	else {
	?>
	<p><strong>Credit: </strong><a target="_blank" title="<?=$photoCredit ?>" href="<?=$photoCredit ?>"><?=$photoCreditTruncated ?></a>
	<?php }?>
	</p>
	<p><strong>Guest Description: </strong><?=$status ?></p>
	<p class="help">
		Look below the picture for mixers related to <strong><?= $topic ?></strong>
	</p>
</div>
//replace xxx with your cloudinary directory associated with your API
<?php if($photo->width > 850) {?>
<?=$this->html->image("https://res.cloudinary.com/xxx/image/upload/w_850/v{$photo->version}/{$photo->public_id}.{$photo->format}", array('width'=> 850, 'id' => 'i_' . $photo->_id)); ?>
<?php } else {?>
<?=$this->html->image("https://res.cloudinary.com/xxx/image/upload/v{$photo->version}/{$photo->public_id}.{$photo->format}", array( 'id' => 'i_' . $photo->_id)); ?>
<?php }?>

