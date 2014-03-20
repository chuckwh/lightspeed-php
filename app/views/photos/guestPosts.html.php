<?php $this->title('Top-Pics by ' . $userName. ': CreateAMixer'); ?>
<?php
$data = null;
$counter = 0;
$picNumber = rand(1, 4);
$defaultImage = "v1381957561/sccfinn1zrxxqu3f2q2x.jpg";
if($picNumber == 1)
{
	$defaultImage = "v1381957561/sccfinn1zrxxqu3f2q2x.jpg";
}
else if ($picNumber == 2) {
	$defaultImage ="v1381958265/mfmt1j3t6oai4lpwerbi.jpg";
}
else if ($picNumber == 3) {
	$defaultImage ="v1381950557/jfd1lj6k0cvboltmhnom.png";;
}
else if ($picNumber == 4) {
	$defaultImage ="v1381958197/wrfx9gitblmoqebpw8fh.jpg";;
}



?>
<div class="row-fluid" id="error_alert"  data-view="guestPosts">
	<div class="span12">
	    <div class="alert">
		    <button type="button" class="close" data-dismiss="alert">&times;</button>
		    <strong>Whoops!</strong>
		    <span id="error_text"></span>
	    </div>
	</div>
</div>
<div class="row-fluid">
	<div class="span4">
		<div class="ml10">
			<h5><a href="/photos/add/"><span class="glyphicon glyphicon-share"></span> Create a Post/Add a Pic</a></h5>
		</div>
	</div>
	<div class="span4">
	<h1 class="photos">Top-Pics by <?= $userName ?></h1>
	</div>
	<div class="span4"></div>
</div>
<div class="row-fluid">
	<div class="span12">

		<?php if ($message != '') {?>
		    <div class="alert">
		    <button type="button" class="close" data-dismiss="alert">&times;</button>
		    <strong>Whoops!</strong> <?= $message ?>
		    </div>
		<?php } ?>

		<?php if (!count($photos)): ?>
			<em>No photos</em>. <?=$this->html->link('Add one', 'Photos::add'); ?>.
		<?php endif ?>
		<div class="js-masonry"
		  data-masonry-options='{ "columnWidth": 235, "itemSelector": ".cm-item" }'>
			<?php foreach ($photos as $key => $photo):
			$counter++;
			$credit = null;
			$status = null;
			$title = null;
			$poster = null;

			$flags = 0;
			if($photo->flags == null) {
				$flags = 0;
			}
			else {
				$flags = $photo->flags;
			}

			if( $photo->credit !=null) {
				$credit = $photo->credit;
				if(strlen($credit) >= 20) {
				$creditTruncated = substr($credit, 0, 20) . '...';
				}
				else {
					$creditTruncated = $credit;
				}
			}
			else {
				$credit = 'N/A';
			}

			if( $photo->user_name !=null) {
				$poster = $photo->user_name;
				if(strlen($poster) >= 20) {
				$posterTruncated = substr($poster, 0, 20) . '...';
				}
				else {
					$posterTruncated = $poster;
				}
			}


			if( $photo->title !=null) {
				$title = $photo->title;
				if(strlen($title) >= 20) {
				$titleTruncated = substr($title, 0, 30) . '...';
				}
				else {
					$titleTruncated = $title;
				}
			}
			else {
				$title = 'Untitled';
			}

			if( $photo->status !=null) {
				$status = $photo->status;
				if(strlen($status) >= 60) {
				$statusTruncated = substr($status, 0, 60) . '...';
				}
				else {
					$statusTruncated = $status;
					}
			}
			else {
				$status = '---';
			}
			?>

			<div class="cm-item" id="cm_item_<?= $photo->_id ?>">
				<div class="topix">
					<div class="topix-description lh12">
						<?=$this->html->link($titleTruncated, array('Photos::view', 'id' => $photo->_id)); ?>
					</div>

					<div class="topix-img">
						<a href="#topix_modal" role="button" class="pic-loader" data-pic-id="<?=$photo->_id ?>" data-pic-topic="<?=$photo->tags ?>" data-pic-title="<?=$title ?>" data-toggle="modal">
						<?php if($photo->public_id != null) { //replace xxx with your cloudinary directory ?>
								<?=$this->html->image("https://res.cloudinary.com/xxx/image/upload/w_150/v{$photo->version}/{$photo->public_id}.{$photo->format}", array('width'=> 150, 'id' => 'i_' . $photo->_id)); ?>
						<?php }
							else {?>
								<?=$this->html->image("https://res.cloudinary.com/xxx/image/upload/w_150/" . $defaultImage, array('width'=> 150)); ?>
							<?php }?>
						</a>
					</div>
					<div class="topix-description help lh12">
						<?= $statusTruncated ?>
					</div>
					<div class="topix-description archivo lh12">
						<span class="archivo">Topic:</span> <span class="archivo-narrow"><?=$photo->tags ?></span>
					</div>
					<div class="topix-user lh12">Posted by <a href="/photos/guestPosts/<?=$photo->user_id ?>"><?=$posterTruncated ?></a>
						<?php if($credit) {?>
						<br>Photo credit:
						<?php
						if(strpos($credit, "http://") !== false || strpos($credit, "https://") !== false) {
						?>
						<a target="_blank" title="<?=$credit ?>" href="<?=$credit ?>"><?=$creditTruncated ?></a>
						<?php } else {?>
						<?=$creditTruncated ?>
						<?php }
						}?>
					</div>
					<div class="topix-footer">
						<div class="fl">
							<button class="btn btn-link post-push-up" data-signature="<?= $photo->signature ?>"><span class="glyphicon glyphicon-thumbs-up"></span></button>
							<span  id="vote_total_<?=$photo->_id ?>"><?= $photo->votes ?></span>
						</div>
						<?php if($canEdit) {?>
							<div style="width:50px; margin-left:40px;"  class="fl">
								<a href="/photos/edit/<?= $photo->_id ?>" class="btn btn-link  edit-post" data-signature="<?= $photo->_id ?>"><span class="glyphicon glyphicon-pencil "></span></a>
								<a href="#delete_modal"  data-toggle="modal" class="btn btn-link delete-post" data-id="<?= $photo->_id ?>"><span class="glyphicon glyphicon-trash "></span></a>
							</div>
						<?php }?>
						<div class="fr">
							<button class="btn btn-link red flag-post" data-id="<?= $photo->_id ?>"><span class="glyphicon glyphicon-flag "></span></button>
							<span id="flag_total_<?=$photo->_id ?>"><?= $flags ?></span>
						</div>
					</div>
				</div>
			</div>

		<?php

		endforeach ?>
		</div>
	</div>
</div>


<!-- Modal -->
<div id="topix_modal" class="modal hide fade modal-override" tabindex="-1" role="dialog" aria-labelledby="topix_modal" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="icon-remove"></i></button>
			<h3 id="modal-event-topics-head" data-pic-title=""></h3>
		</div>
	<div class="modal-body modal-body-override">
		<div data-pic-id="" id="topix-pic-loader" class="topix-pic-loader"></div>
		<div id="topix-events-loader"  data-pic-topic = "" class="topix-events-loader"></div>
	</div>
	<div class="modal-footer">
	<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
	</div>
</div>

<?php //delete modal ?>
<!-- Modal -->
<div id="delete_modal" class="modal hide fade modal-override" tabindex="-1" role="dialog" aria-labelledby="delete_modal" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="icon-remove"></i></button>
			<h3 id="modal-delete-head" data-pic-title="">Are you sure you want to delete this post?</h3>
		</div>
	<div class="modal-body modal-body-override">
		This will permanently remove your post.
	</div>
	<div class="modal-footer">
	<button class="btn" data-dismiss="modal" aria-hidden="true">Nope, My Bad</button>
	<button class="btn btn-danger" data-dismiss="modal" aria-hidden="true" id="modal_delete_btn" data-id="">Delete</button>
	</div>
</div>


