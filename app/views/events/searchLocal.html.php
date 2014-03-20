<?php
use li3_geo\data\Geocoder;
use li3_geo\data\Location;

?>
<div id="searchcontent" itemscope="itemscope"
	itemtype="http://schema.org/SearchResultsPage">

	<h1 itemprop="headline">Here are Events within 60 miles of the <?= $location ?> area</h1>
<?php
$results = null;
$timezone = new DateTimeZone ( 'UTC' );
$now = new DateTime ();
$place1 = null;
$count = $events->count ();
$usrTimeZone = null;

?>
		<table class="table table-striped fs09" itemscope="itemscope"
		itemtype="http://schema.org/SearchResultsPage">
		<thead>
			<tr>
				<th>Event Name</th>
				<th>Date <?php if(!($userSession['username'])) {?>(EST)<?php }?></th>
				<th>#Guests</th>
				<th>City</th>
				<th>Distance</th>

			</tr>
		</thead>

		<tbody>

<?php
if ($events->count () > 0) {
	
	$distanceCheck = null;
	foreach ( $events as $event ) :
		$coords1 = null;
		$coords2 = null;
		$address2 = null;
		$geocode_distance = null;
		$distance = 'N/A';
		$place1 = array (
				Geocoder::find ( 'google', array (
						'address' => $location 
				) ) 
		);
		$place2 = array (
				Geocoder::find ( 'google', array (
						'address' => $event->location 
				) ) 
		);
		if (is_object ( $place1 [0] )) {
			$coords1 = $place1 [0]->coordinates ();
		}
		if (is_object ( $place2 [0] )) {
			$coords2 = $place2 [0]->coordinates ();
			$address2 = $place2 [0]->address ();
		}
		if (is_object ( $place1 [0] ) && is_object ( $place2 [0] )) {
			$geocode_distance = Geocoder::distance ( $coords1, $coords2 );
			$distance = round ( $geocode_distance, 1 );
			$distanceCheck = $distance <= 60;
		}
		
		/*
		 * TODO -enter real dates into the MongoDB and use this to convert it to
		 * workable date objects $mongoDate = $event->date; $startingTime = new
		 * DateTime($mongoDate, new DateTimeZone('UTC'));
		 * if($userSession['username']) { $usrTimeZone = new
		 * DateTimeZone($userSession['timezone']); } else { //America/New_York
		 * timezone for non logged in users $usrTimeZone = new
		 * DateTimeZone('America/New_York'); }
		 * //	$startingTime->setTimeZone($user->timezone);
		 * $startingTime->setTimezone($usrTimeZone); $dateStr =
		 * $startingTime->format('m/d/Y g:i A');
		 */
		// TODO swap out the $dateStr variable after you have changed the
		// MongoDB data entries
		$dateStr = $event->date;
		?>

				<tr>
				<td itemprop="name" content="Event <?=$event->name ?>"><?=$event->name ?></td>
				<td><?=$dateStr?></td>
				<td><span id="badge_id_<?=$event->_id ?>" class="badge">
								<?= count($event->guests)?>
						</span></td>
				<td itemprop="description"
					content="A Event for people in the <?=$event->location ?> area"><?= $address2['city']?>, <?= $address2['state']?></td>
				<td><?= $distance ?></td>

<?php
	endforeach
	;
} // TODO ADD a table row here and include what you want to display if there are
  // no results
?>
			
		
		</tbody>

	</table>
	<?php //TODO - pagination not currently implemented ?>
    <div
		class="pagination pagination-small pagination-links pagination-local"></div>

</div>