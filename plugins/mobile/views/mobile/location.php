<script type="text/javascript">
  function initialize() {
  	var myLatlng = new google.maps.LatLng(<?php echo $location->latitude; ?>,<?php echo $location->longitude; ?>);
  	var myOptions = {
  	  zoom: 15,
  	  center: myLatlng,
  	  mapTypeId: google.maps.MapTypeId.ROADMAP
  	}

  	var map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);

  	var marker = new google.maps.Marker({
  	    position: myLatlng,
  	    map: map,
  		title: 'Marker'
  	});

  	marker.setMap(map);
  }

</script>
<h2><?=$location->location_name?></h2>
<div id="map_canvas"></div>
<div class="block">
	<h2 class="submit"><a href="<?php echo url::site()."mobile/reports/submit/?location_id=".$location->id ?>">Add a Story about <?=$location->location_name?></a></h2>
</div>

<ul id="neighbors">
  <?php foreach ($neighbors as $neighbor):?>
    <li><h3><a href="/mobile/reports/view/<?=$neighbor->id?>"><?=$neighbor->incident_title?></a></h3>
      <div class="date">
        (<?=$neighbor->incident_date?>)
      </div>
      <div class="description">
        <?=$neighbor->incident_description?>
      </div>
    </li>
  <?php endforeach;?>
</ul>



