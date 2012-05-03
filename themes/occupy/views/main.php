

<link href="http://map.occupy.net/media/css/picbox/picbox.css" type="text/css" rel="stylesheet">
<script src="http://map.occupy.net/media/js/picbox.js" type="text/javascript">
<script type="text/javascript">
$(function(){
  
  // show/hide report filters and layers boxes on home page map
  $("a.toggle").toggle(
    function() { 
      $($(this).attr("href")).show();
      $(this).find(".btn-icon").addClass("ic-down");
    },
    function() { 
      $($(this).attr("href")).hide();
      $(this).find(".btn-icon").removeClass("ic-down");
    }
  );
  $(document).ready(function(){
     $("#activity-menu-box").css("margin-right",(( -1 * $("#main").width())+240)+"px");
     
    $(window).on('resize',function(){
      console.log($('#main').width());
      $("#activity-menu-box").css("margin-right",(( -1 * $("#main").width())+240)+"px");
    })
    
  })
  
});

</script>
<style>
body .rapidxwpr, div#mainmenu { margin:0; width:100%; min-width:960px; }
</style>
<!-- main body -->
<div id="main" class="clearingfix">
	<div id="mainmiddle">

	<?php if($site_message != '' && 1==2) { ?>
		<div class="green-box">
		  <div class="close">
		    [x]
		  </div>
			<h3><?php echo $site_message; ?></h3>
		</div>
	<?php } ?>

   
		
      <div id="activity-menu-box" style="position:relative; float:right; top:40px;margin-left:-240px;  padding-right:40px; height:0px; margin-top:0px">
        <!-- submit incident -->
    		<div class="btns" style="margin-bottom:-15px">
      		<?php echo $submit_btn; ?>
      		<div class="submit-incident clearingfix" style="margin-top:30px">
      		  <a id="share-map" style="background-color:#336699" href="javascript:void(0)">Share Map</a>
      		</div>
  				  <div id="share-map-link" style="display:none; background:#fff; width:640px; height:30px; padding:10px;border-radius:5px; float:right; right:-230px; top:65px; box-shadow: 0 2px 0 #948F82; position:absolute;">
              Use this link to share your stories from this time and place.
              <div class="link" style="font-weight:bold;">
                
              </div>
              
  				  </div>
    		</div>
    	  
    		
    		
				
    		
    		<!-- / submit incident -->
    		
           <a class="btn toggle" style="margin-left:-60px" id="activity-menu-toggle" href="javascript:void(0)">Activity <span class="btn-icon ic-right" style="padding-right:45px; margin-top:-12px;">&raquo;</span></a>
             <div class="map-menu-box" id="activity-menu"  style="padding:10px; margin-left:-60px">
               <?php if (count($active_locations)>0):?>
                 <h5>Places</h5>
              <p>Most active places in the past 30 days</p>
              <br/>
               <ul class="category-filters">
                 <?php foreach($active_locations as $location):?>
                   <li><a href="/reports/view_location/<?=$location["id"]?>"><?=$location["location_name"]?></a> (<?=$location["num_incidents"]?>)</li>
                 <?php endforeach?>
               </ul>
             <?php endif?>
             <?php if (count($key_dates)>0):?>
               <h5>Dates</h5>
               <ul class="category-filters">
                   <?php foreach ($key_dates as $day): ?>
                      <li><a class="datepick" rel="<?=$day["timestamp"]?>" href="javascript:void(0)"?><?=$day["date"]?></a>(<?=$day["num_incidents"]?>)</li>
                    <?php endforeach ?>
               </ul>
               <?php endif?>
            </div>
             
    				
      </div>




      <script type="text/javascript">
      $(document).ready(function(){
        $("#share-map").click(function(){
          //build the link
          console.log(map);
      		var myPoint = new OpenLayers.LonLat(parseFloat(map.center.lon), parseFloat(map.center.lat));
        	var proj_900913 = new OpenLayers.Projection('EPSG:900913');
        	var proj_4326 = new OpenLayers.Projection('EPSG:4326');
      		myPoint.transform( proj_900913,proj_4326);
    
          console.log(myPoint);
          var site = "<?php echo url::site()?>";
          var start = "startTime="+$("#startDate").val();
          var end = "endTime="+$("#endDate").val();
          var lat = "lat="+myPoint.lat;
          var lon = "lon="+myPoint.lon;
          var zoom = "zoom="+map.zoom;
          $("#share-map-link").toggle();
          $("#activity-menu").hide();
          $("#share-map-link").find(".link").html(site+"?"+start+"&"+end+"&"+lat+"&"+lon+"&"+zoom);
        })
        
        
        $("a.datepick").click(function(){
          var day = parseInt($(this).attr("rel")) - (86400);
          var nextDay = parseInt(day) +(86400*2);
          var stop=false;
          $("#activity-menu").find("li").removeClass("active");
          $(this).parent().addClass("active");
          $("#startDate").find("option").each(function(i,e){
            var v = ($(e).attr("value"));
            if (v>=day && stop==false){
              stop = true;
              $("#startDate").trigger('click');
              $("#startDate").val(v);
              $(e).select()
            }
          })
          stop=false;
          $("#endDate").find("option").each(function(i,e){
            var v = ($(e).attr("value"));
            if (v>=nextDay && stop==false){
              $("#endDate").trigger('click');
              $("#endDate").val(v);
              $(e).select();
              stop = true;

            }
          })
    
          //set start date to day value
          //set end date to day val + 1 day
          //trigger change handlers for start day and end day on map
          $("#startDate").trigger('change');
          $("#endDate").trigger('change');
        })
      })

      </script>


		<!-- right column -->
		<div id="report-map-filter-box" class="clearingfix">
	    <a class="btn toggle" id="filter-menu-toggle" class="" href="javascript:void(0)">Filter Reports By <span class="btn-icon ic-right">&raquo;</span></a>
	    
	    <!-- filters box -->
	    <div id="the-filters" class="map-menu-box">
	      
        <!-- report category filters -->
        <div id="report-category-filter">
    			<h3><?php echo Kohana::lang('ui_main.category');?></h3>
			
    			<ul id="category_switch" class="category-filters">
    				<li><a class="active" id="cat_0" href="javascript:void(0)"><span class="swatch" style="background-color:<?php echo "#".$default_map_all;?>"></span><span class="category-title"><?php echo Kohana::lang('ui_main.all_categories');?></span></a></li>
    				<?php
    					foreach ($categories as $category => $category_info)
    					{
    						$category_title = $category_info[0];
    						$category_color = $category_info[1];
    						$category_image = '';
    						$color_css = 'class="swatch" style="background-color:#'.$category_color.'"';
    						if($category_info[2] != NULL && file_exists(Kohana::config('upload.relative_directory').'/'.$category_info[2])) {
    							$category_image = html::image(array(
    								'src'=>Kohana::config('upload.relative_directory').'/'.$category_info[2],
    								'style'=>'float:left;padding-right:5px;'
    								));
    							$color_css = '';
    						}
    						echo '<li><a href="#" id="cat_'. $category .'"><span '.$color_css.'>'.$category_image.'</span><span class="category-title">'.$category_title.'</span></a>';
    						// Get Children
    						echo '<div class="hide" id="child_'. $category .'">';
                                                    if( sizeof($category_info[3]) != 0)
                                                    {
                                                        echo '<ul>';
                                                        foreach ($category_info[3] as $child => $child_info)
                                                        {
                                                                $child_title = $child_info[0];
                                                                $child_color = $child_info[1];
                                                                $child_image = '';
                                                                $color_css = 'class="swatch" style="background-color:#'.$child_color.'"';
                                                                if($child_info[2] != NULL && file_exists(Kohana::config('upload.relative_directory').'/'.$child_info[2])) {
                                                                        $child_image = html::image(array(
                                                                                'src'=>Kohana::config('upload.relative_directory').'/'.$child_info[2],
                                                                                'style'=>'float:left;padding-right:5px;'
                                                                                ));
                                                                        $color_css = '';
                                                                }
                                                                echo '<li style="padding-left:10px;"><a href="#" id="cat_'. $child .'"><span '.$color_css.'>'.$child_image.'</span><span class="category-title">'.$child_title.'</span></a></li>';
                                                        }
                                                        echo '</ul>';
                                                    }
    						echo '</div></li>';
    					}
    				?>
    			</ul>

			  </div>
			  <!-- / report category filters -->
			  
  			<!-- report type filters -->
  			<div id="report-type-filter" class="filters">
  				<h3><?php echo Kohana::lang('ui_main.type'); ?></h3>
  					<ul>
  						<li><a id="media_0" class="active" href="#"><span><?php echo Kohana::lang('ui_main.reports'); ?></span></a></li>
  						<li><a id="media_4" href="#"><span><?php echo Kohana::lang('ui_main.news'); ?></span></a></li>
  						<li><a id="media_1" href="#"><span><?php echo Kohana::lang('ui_main.pictures'); ?></span></a></li>
  						<li><a id="media_2" href="#"><span><?php echo Kohana::lang('ui_main.video'); ?></span></a></li>
  						<li><a id="media_0" href="#"><span><?php echo Kohana::lang('ui_main.all'); ?></span></a></li>
  					</ul>
  					<div class="floatbox">
      					<?php
      					// Action::main_filters - Add items to the main_filters
      					Event::run('ushahidi_action.map_main_filters');
      					?>
      				</div>
      				<!-- / report type filters -->
  			</div>
      			
			</div>
			<!-- / filters box -->
			
			<?php
			if ($layers)
			{
				?>
				<div id="layers-box">
				  <a class="btn toggle" id="layers-menu-toggle" class="" href="#kml_switch"><?php echo Kohana::lang('ui_main.layers');?> <span class="btn-icon ic-right">&raquo;</span></a>
  				<!-- Layers (KML/KMZ) -->
  				<ul id="kml_switch" class="category-filters map-menu-box">
  					<?php
  					foreach ($layers as $layer => $layer_info)
  					{
  						$layer_name = $layer_info[0];
  						$layer_color = $layer_info[1];
  						$layer_url = $layer_info[2];
  						$layer_file = $layer_info[3];
  						$layer_link = (!$layer_url) ?
  							url::base().Kohana::config('upload.relative_directory').'/'.$layer_file :
  							$layer_url;
  						echo '<li><a href="#" id="layer_'. $layer .'"
  						onclick="switchLayer(\''.$layer.'\',\''.$layer_link.'\',\''.$layer_color.'\'); return false;"><span class="swatch" style="background-color:#'.$layer_color.'"></span>
  						<span class="category-title">'.$layer_name.'</span></a></li>';
  					}
  					?>
  				</ul>
				</div>
				<!-- /Layers -->
				<?php
			}
			?>
			
			
			<!-- additional content -->
			<?php
			if (Kohana::config('settings.allow_reports'))
			{
				?>
				<div class="additional-content">
					<h5><?php echo Kohana::lang('ui_main.how_to_report'); ?></h5>
					<ol>
						<?php if (!empty($phone_array)) 
						{ ?><li><?php echo Kohana::lang('ui_main.report_option_1')." "; ?> <?php foreach ($phone_array as $phone) {
							echo "<strong>". $phone ."</strong>";
							if ($phone != end($phone_array)) {
								echo " or ";
							}
						} ?></li><?php } ?>
						<?php if (!empty($report_email)) 
						{ ?><li><?php echo Kohana::lang('ui_main.report_option_2')." "; ?> <a href="mailto:<?php echo $report_email?>"><?php echo $report_email?></a></li><?php } ?>
						<?php if (!empty($twitter_hashtag_array)) 
									{ ?><li><?php echo Kohana::lang('ui_main.report_option_3')." "; ?> <?php foreach ($twitter_hashtag_array as $twitter_hashtag) {
						echo "<strong>". $twitter_hashtag ."</strong>";
						if ($twitter_hashtag != end($twitter_hashtag_array)) {
							echo " or ";
						}
						} ?></li>
						<?php
						} ?><li><a href="<?php echo url::site() . 'reports/submit/'; ?>"><?php echo Kohana::lang('ui_main.report_option_4'); ?></a></li>
					</ol>

				</div>
			<?php } ?>
			<!-- / additional content -->
			
			<?php
			// Action::main_sidebar - Add Items to the Entry Page Sidebar
			Event::run('ushahidi_action.main_sidebar');
			?>
	
		</div>
		<!-- / right column -->
	
		<!-- content column -->
		<div id="content" class="clearingfix">
		<?php								
		// Map and Timeline Blocks
		  echo $div_map;
      
			echo $div_timeline;
		?>
		</div>
		
		<div id="homepage_slideshow">
		  <div class="content-block">
  		  <h5>Photos</h5>
    		<?php foreach ($slideshow as $slide):?>
    		  
            	<?php $prefix = url::base().Kohana::config('upload.relative_directory');?>
            	<?php $prefix = "http://map.occupy.net/media/uploads";?>
            	<?php
            	$s_title = $slide->incident_title;
            	$s_date = date("l,  F d, Y",strtotime($slide->incident_date));
            	$s_caption = $s_title . " - " .$s_date;
            	?>
            	<a class="photothumb" title = "<?=$s_caption?>" rel="lightbox-group1" href="<?=$prefix?>/<?=$slide->media_link?>"><img class = "thumb" src="<?=$prefix?>/<?=$slide->media_thumb?>"/></a>
    		<?php endforeach?>
		    
		  </div>
		  
    </div>
		<!-- / content column -->

	</div>
</div>
<!-- / main body -->

<!-- content -->
<div class="content-container" style='display:none'>

	<!-- content blocks -->
	<div class="content-blocks clearingfix">
		<ul class="content-column">
			<?php blocks::render(); ?>
		</ul>
	</div>
	<!-- /content blocks -->

</div>
<!-- content -->

<script type="text/javascript">
  $(document).ready(function(){
    $(".green-box").each(function(i,e){
      $(e).find(".close").click(function(){ $(e).hide("slow")});
    })
  })
</script>
<!-- Piwik --> 
<script type="text/javascript">
var pkBaseURL = (("https:" == document.location.protocol) ? "https://analytics.occupy.net/" : "http://analytics.occupy.net/");
document.write(unescape("%3Cscript src='" + pkBaseURL + "piwik.js' type='text/javascript'%3E%3C/script%3E"));
</script><script type="text/javascript">
try {
var piwikTracker = Piwik.getTracker(pkBaseURL + "piwik.php", 19);
piwikTracker.trackPageView();
piwikTracker.enableLinkTracking();
} catch( err ) {}
</script><noscript><p><img src="http://analytics.occupy.net/piwik.php?idsite=19" style="border:0" alt="" /></p></noscript>
<!-- End Piwik Tracking Code -->
<script type="text/javascript">
	  var _gaq = _gaq || [];
	  _gaq.push(['_setAccount', 'UA-25680260-2']);
	  _gaq.push(['_trackPageview']);

	  (function() {
		var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	  })();

</script>

