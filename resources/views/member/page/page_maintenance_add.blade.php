<style type="text/css">

</style>
<link rel="stylesheet" type="text/css" href="/assets/custom_plugin/myDropList/css/myDropList.css">
	<form class="global-submit" role="form" action="/member/page/content/submit-maintenance/-1" method="post">
	<input type="hidden" name="_token" value="{{ csrf_token() }}">
	<input type="hidden" name="key" value="{{ $key }}">
	<div class="modal-header">
	    <button type="button" class="close" data-dismiss="modal">&times;</button>
	    <h4 class="modal-title layout-modallarge-title item_title">Add</h4>
	</div>
	<div class="modal-body modallarge-body-layout background-white">
		<div class="text-right">
			<button class="btn btn-primary" button="submit">Submit</button>
		</div>
		@if(isset($field) && count($field) > 0)
		<div style="margin-top: 15px;">
			@foreach($field as $fields)
			<div class="form-group {{ $fields->type == "hidden" ? "hide" : "" }}">
				<label>{{ ucwords(str_replace('_', ' ', $fields->name)) }}</label>
				@if($fields->type == "textarea")
					<textarea class="form-control mce" name="{{ $fields->name }}"></textarea>
				@elseif($fields->type == "image_gallery")
					<input type="hidden" name="{{ $fields->name }}" class="maintenance-image-input" key="{{ $key }}-{{ $fields->type }}-{{ $fields->name }}">
					<div class="maintenance-image-multiple-holder" key="{{ $key }}-{{ $fields->type }}-{{ $fields->name }}"></div>
					<div><button class="image-gallery btn btn-primary" key="{{ $key }}-{{ $fields->type }}-{{ $fields->name }}"> Upload Image</button></div>
				@elseif($fields->type == "textbox")
					<textarea class="form-control" name="{{ $fields->name }}"></textarea>
				@elseif($fields->type == "image")
					<input type="hidden" name="{{ $fields->name }}" class="maintenance-image-input" key="{{ $key }}-{{ $fields->type }}-{{ $fields->name }}">
					<div class="maintenance-image-holder" key="{{ $key }}-{{ $fields->type }}-{{ $fields->name }}"></div>
					<div><button class="image-gallery image-gallery-single btn btn-primary" key="{{ $key }}-{{ $fields->type }}-{{ $fields->name }}"> Upload Image</button></div>
				@elseif($fields->type == "timestamp")
					<input type="hidden" name="{{ $fields->name }}" value="{{ date('Y-m-d H:i:s') }}">
				@elseif($fields->type == "map")
					<div class="map-info"></div>
					<div id="map" style="height: 300px;"></div>
					<script>
				      // In the following example, markers appear when the user clicks on the map.
				      // Each marker is labeled with a single alphabetical character.
				      var labels = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
				      var labelIndex = 0;
				      var map;
				      var markers = [];

				      function initialize() 
				      {
				        var bangalore = { lat: 14.5995, lng: 120.9842 };
				        var map = new google.maps.Map(document.getElementById('map'), 
				        {
				          zoom: 12,
				          center: bangalore
				        });

				        var geocoder = new google.maps.Geocoder();

				        // This event listener calls addMarker() when the map is clicked.
				        google.maps.event.addListener(map, 'click', function(event) 
				        {
				          deleteMarkers();

				          $('input[name="latitude"]').val(event.latLng.lat());
				          $('input[name="longitude"]').val(event.latLng.lng());

				          geocoder.geocode({
						    'latLng': event.latLng
						  }, function(results, status) {
						    if (status == google.maps.GeocoderStatus.OK) {
						      if (results[0]) {
						        $('input[name="address"]').val(results[0].formatted_address);
						        $('.map-info').text( "(" + results[0].formatted_address + ")" );
						      }
						    }
						  });

				          addMarker(event.latLng, map);
				        });
				      }

				      // Adds a marker to the map and push to the array.
				      function addMarker(location, map) {
				        var marker = new google.maps.Marker({
				          position: location,
				          map: map
				        });
				        markers.push(marker);
				      }

				      // Sets the map on all markers in the array.
				      function setMapOnAll(map) {
				        for (var i = 0; i < markers.length; i++) {
				          markers[i].setMap(map);
				        }
				      }

				      // Removes the markers from the map, but keeps them in the array.
				      function clearMarkers() {
				        setMapOnAll(null);
				      }

				      // Shows any markers currently in the array.
				      function showMarkers() {
				        setMapOnAll(map);
				      }

				      // Deletes all markers in the array by removing references to them.
				      function deleteMarkers() {
				        clearMarkers();
				        markers = [];
				      }
				    </script>
				    <script async defer
				    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDntbTzqtnwMA7hdMBAeX37YwTjRJi6cDY&callback=initialize">
				    </script>
				@else
					<input step="any" class="form-control" type="{{ $fields->type }}" name="{{ $fields->name }}">
				@endif
			</div>
			@endforeach
		</div>
		@endif
	</div>
</form>
@include("member.page.page_assets")