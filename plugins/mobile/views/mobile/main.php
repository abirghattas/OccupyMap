<div class="block">
<a href="<?php echo url::site() ?>?full=1">View Full Website</a>
</div>
<h2>Welcome to OccupyMap Mobile</h2>

<div class="block">
  <h2 class="expand">Help</h2>
  <div class="collapse">
      <div>
        Select "Nearby Places" to see the 25 places nearest to you that have had Occupy activity in the past 30 days.   
      </div>
      <div>
        Select "Recent Activity" to see the most active places near you today. 
      </div>
      <div>
         <strong>These features require that your location services are on. </strong> If you are in a city that has not had any OccupyMap activity, this app may not show any nearby activity.
      </div>
      <div>
        Clicking on a place name will take you to the homepage from that place.  There you can view activity for that place, or add your own story about that place. 
        "Nearby Places" and "Recent Activity" have not been tested on Android devices.<br/>
        "Recent Reports" will show you the most recent reports world-wide (not location-based, works on any device)<Br/>
      </div>
    </div>
</div>

<div class="block">
  <h2 class="expand" id="nearby-places-trigger">Nearby Places</h2>
  <div class="collapse">
    <ul id="nearby-places">
    </ul>
  </div>
</div>
<div class="block">
  <h2 class="expand" id="recent-activity-trigger">Recent Activity</h2>
  <div class="collapse">
    <ul id="recent-activity">
      
    </ul>
  </div>
  
</div>

<div class="block">
  <h2 class="expand">Recent Reports</h2>
  <div class="collapse">
      <ul>
          <?php
          foreach ($incidents as $incident)
          {
              $incident_date = $incident->incident_date;
              $incident_date = date('M j Y', strtotime($incident->incident_date));
              echo "<li><strong><a href=\"".url::site()."mobile/reports/view/".$incident->id."\">".$incident->incident_title."</a></strong>";
              echo "&nbsp;&nbsp;<i>$incident_date</i></li>";
          }
          ?>
      </ul>
  </div>


<div class="block">
    <h2 class="expand">Mobile Apps</h2>
    <div class="collapse">
        

        	<li><a href="http://itunes.apple.com/us/app/ushahidi-ios/id410609585?mt=8">iOS App</a></li>
			<li><a href="https://market.android.com/details?id=com.ushahidi.android.app">Android App</a></li>
    </div>
    
</div>


<h2 class="block_title">More</h2>

<div class="block">
	<h2 class="other"><a href="#">Contact Us</a></h2>
	<h2 class="other"><a href="#">About Us</a></h2>
</div>