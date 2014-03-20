
<div class="row" data-view="searchEvents">
	<div class="col-md-12">
		<h1>Search for Events by entering your postal code</h1>
	</div>
</div>


<div class="row" id="search">  
	<div class="col-md-12">.
		<div id="searchByLocation">

			<?php echo $this->form->create(null, array('url' => '/search/searchLocal', 'name' => 'search_local_', 'id' => 'search_local')); ?>
				<?php echo $this->form->field('locationInput', array('label'=>'Postal Code:', 'id' => 'locationInput', 'placeholder' => 'Enter Your Postal Code', 'class' => 'form-control')); ?>
						<?php echo $this->form->submit('Search for Events', array('id' => 'submit_local_search', 'name' => 'submit_local_search', 'class' => 'btn btn-large btn-primary mt10') ); ?>
			<?php echo $this->form->end(); ?>
		</div>
		<div  id="search_result"></div>
	</div>
</div>


