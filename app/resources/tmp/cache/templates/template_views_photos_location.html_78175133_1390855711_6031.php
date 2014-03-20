<?php $this->title('Mixers in the ' . $requestId . ' area: CreateAMixer'); ?>

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
<div class="row-fluid" id="error_alert" data-view="location">
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
	<h1 class="photos">Events in the <?php echo $h($requestId); ?> area: Top-Pics</h1>
	</div>
	<div class="span4"></div>
</div>
<div class="row-fluid">
	<div class="span12">

		<?php if ($message != '') {?>
		    <div class="alert">
		    <button type="button" class="close" data-dismiss="alert">&times;</button>
		    <strong>Whoops!</strong> <?php echo $h($message); ?>
		    </div>
		<?php } ?>

		<?php if (!count($photos)): ?>
			<em>No photos</em>. <?php echo $this->html->link('Add one', 'Photos::add'); ?>.
		<?php endif ?>
		<div class="js-masonry"
		  data-masonry-options='{ "columnWidth": 235, "gutter": 10, "itemSelector": ".cm-item" }'>
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

			<div class="cm-item">
				<div class="topix">
					<div class="align-right">
						<a href="/photos/location/<?php echo $h($photo->location); ?>" class="btn btn-link view-location" data-location="<?php echo $h($photo->location); ?>">
							<span class="glyphicon glyphicon-map-marker location"></span>
						</a>
					</div>
					<div class="topix-description lh12">
						<?php echo $this->html->link($titleTruncated, array('Photos::view', 'id' => $photo->_id)); ?>
					</div>

					<div class="topix-img">
						<a href="#topix_modal" role="button" class="pic-loader" data-pic-id="<?php echo $h($photo->_id); ?>" data-pic-topic="<?php echo $h($photo->tags); ?>" data-pic-title="<?php echo $h($title); ?>" data-toggle="modal">
						<?php if($photo->public_id != null) {?>
								<?php echo $this->html->image("https://res.cloudinary.com/hchbvnig8/image/upload/w_150/v{$photo->version}/{$photo->public_id}.{$photo->format}", array('width'=> 150, 'id' => 'i_' . $photo->_id)); ?>
						<?php }
							else {?>
								<?php echo $this->html->image("https://res.cloudinary.com/hchbvnig8/image/upload/w_150/" . $defaultImage, array('width'=> 150)); ?>
							<?php }?>
						</a>
					</div>
					<div class="topix-description help lh12">
						<?php echo $h($statusTruncated); ?>
					</div>
					<div class="topix-description archivo lh12">
						<span class="archivo">Topic:</span> <span class="archivo-narrow"><?php echo $h($photo->tags); ?></span>
					</div>
					<div class="topix-user lh12">Posted by <a href="/photos/guestPosts/<?php echo $h($photo->user_id); ?>"><?php echo $h($posterTruncated); ?></a>
						<?php if($credit) {?>
						<br>Photo credit:
						<?php
						if(strpos($credit, "http://") !== false || strpos($credit, "https://") !== false) {
						?>
						<a target="_blank" title="<?php echo $h($credit); ?>" href="<?php echo $h($credit); ?>"><?php echo $h($creditTruncated); ?></a>
						<?php } else {?>
						<?php echo $h($creditTruncated); ?>
						<?php }
						}?>
					</div>

					<div class="topix-footer">
						<div class="fl">
							<button class="btn btn-link post-push-up" data-signature="<?php echo $h($photo->signature); ?>"><span class="glyphicon glyphicon-thumbs-up"></span></button>
							<span  id="vote_total_<?php echo $h($photo->_id); ?>"><?php echo $h($photo->votes); ?></span>
						</div>
						<div class="fr">
							<button class="btn btn-link red flag-post" data-signature="<?php echo $h($photo->signature); ?>"><span class="glyphicon glyphicon-flag "></span></button>
							<span id="flag_total_<?php echo $h($photo->_id); ?>"><?php echo $h($flags); ?></span>
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
<div id="topix_modal" class="modal hide fade modal-override" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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


