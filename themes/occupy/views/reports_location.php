<div id="main" class="report_detail">
	<div style="width:950px;">
	  <h4><?=$location->location_name?></h4>
		<div class="report-media-box-content">
			
			<div id="report-map" class="report-map report-map-location">
				<div class="map-holder" id="map" style="width:935px; height:350px"></div>
          
          <script type="text/javascript">
          $(document).ready(function(){
            $('.incident-location').insertBefore($('.f-col'));
						$('.map_holder_reports').css({"height":"350px", "width": "935px"});
						$('.incident-location h4').css({"margin-left":"10px"});
						$('.location-info').css({"margin-right":"14px"});
						$('a[href=#report-map]').parent().hide();
          })
          </script>
        <div style="clear:both"></div>
			</div>			
		</div>
<!--		<div class="report-additional-reports">
			<h4><?php echo Kohana::lang('ui_main.additional_reports');?></h4>

			<?php 
			$n1 ="";
			$n2 = "";
			$incident_neighbors = array($n1, $n2)?>
			<?php foreach($incident_neighbors as $neighbor) { ?>
			  
			
      <?php } ?>
		</div>
-->
<div id="homepage_slideshow">
  <div class="content-block">
	  <h5>Photos</h5>
		<?php foreach ($slideshow as $slide):?>
		  
        	<?php $prefix = url::base().Kohana::config('upload.relative_directory');?>
        	<?php $prefix = "http://map.occupy.net/media/uploads";?>
        	<?php
        	$s_title = $slide->incident_title;
        	$s_id = $slide->incident_id;
        	$s_date = date("l,  F d, Y",strtotime($slide->incident_date));
        	$s_caption = $s_title . " - " .$s_date;
        	?>
        	<a class="photothumb" title = "<?=$s_caption?>" rel="lightbox-group1" href="<?=$prefix?>/<?=$slide->media_link?>"><img class = "thumb" src="<?=$prefix?>/<?=$slide->media_thumb?>"/></a>
		<?php endforeach?>
    
  </div>
  
</div>
<style type="text/css">
  #video_slideshow {
   margin-bottom:20px;
  }
  #video_slideshow .container {
    overflow-x:auto;
    padding-top:15px;
  }
  #video_slideshow .video {
    width:325px;
    margin-right:4px;
    float:left;
  }
  #video_slideshow .player{
    width:320px;
    height:200px;
    margin-bottom:5px;
    display:block;
  }
</style>

<div id="video_slideshow">
  <div class="content-block">
	  <h5>Videos</h5>
    <div class="container">
    <?php
    $vids = array();
    foreach ($videos as $v) {
      $embed_code = $videos_embed->embed($v->media_link,'', true);
    	if (strpos($embed_code,"<embed")) {
    	  $vids[] = $v;
      }
    }
    ?>
	  <div class="wrapper" style="width:<?=count($vids) * 330?>px">

  		<?php foreach ($vids as $slide):?>
          <?php $embed_code = $videos_embed->embed($slide->media_link,'', true);?>
          
          <div class="video">
            <div class="player">
                <?= $embed_code;?>
            </div>
            <div class="report-when-where">
              <span class="title r_location">
                <a href="/reports/view/<?=$slide->incident_id?>"><?=$slide->incident_title?></a>
              </span >
              <br/>
              <span class="date r_date">
                <?=$slide->incident_date?>
              </span>
            </div>
            
            </div>
  		<?php endforeach?>
	   
	  </div>
	  
      
    </div>
    
  </div>
  
</div>



	</div>

	<div class="left-col" style="float:left;width:520px; margin-right:20px">

		<h1 id="incident-title" class="report-title"><?php
			echo $incident_title;
			
			// If Admin is Logged In - Allow For Edit Link
			if ($logged_in)
			{
				echo " [&nbsp;<a href=\"".url::site()."admin/reports/edit/".$incident_id."\">".Kohana::lang('ui_main.edit')."</a>&nbsp;]";
			}
		?></h1>

	
  	  <?php
    	  if ($incident_verified)
    		{
    			echo '<p class="r_verified">'.Kohana::lang('ui_main.verified').'</p>';
    		}
    		else
    		{
    			echo '<p class="r_unverified">'.Kohana::lang('ui_main.unverified').'</p>';
    		}
  	  ?>	
		<p class="report-when-where">
			<span  id="incident-date" class="r_date"><?php echo $incident_time.' '.$incident_date; ?> </span>
			<span  id="incident-location" class="r_location"><?php echo $incident_location; ?></span>
			<?php Event::run('ushahidi_action.report_meta_after_time', $incident_id); ?>
		</p>
	
		<div id="report-categories" class="report-category-list">
		<p>
			<?php
				foreach($incident_category as $category) 
				{

					// don't show hidden categoies
					if($category->category->category_visible == 0)
					{
						continue;
					}

				  if ($category->category->category_image_thumb)
					{
					?>
					<a href="<?php echo url::site()."reports/?c=".$category->category->id; ?>"><span class="r_cat-box" style="background:transparent url(<?php echo url::base().Kohana::config('upload.relative_directory')."/".$category->category->category_image_thumb; ?>) 0 0 no-repeat;">&nbsp;</span> <?php echo $category->category->category_title; ?></a>
					
					<?php 
					}
					else
					{
					?>
					  <a href="<?php echo url::site()."reports/?c=".$category->category->id; ?>"><span class="r_cat-box" style="background-color:#<?php echo $category->category->category_color; ?>">&nbsp;</span> <?php echo $category->category->category_title; ?></a>
				  <?php
				  }
				}
			?>
			</p>
			<?php
			// Action::report_meta - Add Items to the Report Meta (Location/Date/Time etc.)
			Event::run('ushahidi_action.report_meta', $incident_id);
			?>
		</div>
		
		<!-- start report media -->
		<div class="<?php if( count($incident_photos) > 0 || count($incident_videos) > 0){ echo "report-media";}?>">
	    <?php 
	    // if there are images, show them
	      echo '<div id="report-images">';
          foreach ($incident_photos as $photo)
          {
          	$thumb = str_replace(".","_t.",$photo);
          	$prefix = url::base().Kohana::config('upload.relative_directory');
          	echo '<a class="photothumb" rel="lightbox-group1" href="'.$prefix.'/'.$photo.'"><img src="'.$prefix.'/'.$thumb.'"/></a> ';
          };
				echo '</div>';  
	    
      echo '<div id="report-video">';
	    // if there are videos, show those too
	    if( count($incident_videos) > 0 ) 
	    { 
	     echo '<ol>';

          // embed the video codes
          foreach( $incident_videos as $incident_video) 
          {
            echo '<li>';
            $videos_embed->embed($incident_video,'');
            echo '</li>';
          };
  			echo '</ol>';
        
	    } 
	    echo '</div>';
	    ?>
		</div>
		
		<!-- start report description -->
		<div id="incident-description" class="report-description-text">
			<h5><?php echo Kohana::lang('ui_main.reports_description');?></h5>
			<?php echo $incident_description; ?>
			<br/>
			
				
			<!-- start news source link -->
			<div class="credibility">
			<h5><?php echo Kohana::lang('ui_main.reports_news');?></h5>
					<?php
						foreach( $incident_news as $incident_new) 
						{
							?>
							<a href="<?php echo $incident_new; ?>"><?php
							echo $incident_new;?></a>
							<br/>
							<?php	
					} ?>
			<!-- end news source link -->
			</div>

			<!-- start additional fields -->
		
			
			<div class="credibility">
			<h5>Additional Information</a>
			</h5>
			<?php
				echo $custom_forms;

			?>
			<br/>
			<!-- end additional fields -->
			</div>

			<?php if ($features_count)
			{
				?>
				<br /><br /><h5><?php echo Kohana::lang('ui_main.reports_features');?></h5>
				<?php
				foreach ($features as $feature)
				{
					echo ($feature->geometry_label) ?
					 	"<div class=\"feature_label\"><a href=\"javascript:getFeature($feature->id)\">$feature->geometry_label</a></div>" : "";
					echo ($feature->geometry_comment) ?
						"<div class=\"feature_comment\">$feature->geometry_comment</div>" : "";
				}
			}?>
			
			<div class="credibility">
				<table class="rating-table" cellspacing="0" cellpadding="0" border="0">
          <tr>
            <td><?php echo Kohana::lang('ui_main.credibility');?>:</td>
            <td><a href="javascript:rating('<?php echo $incident_id; ?>','add','original','oloader_<?php echo $incident_id; ?>')"><img id="oup_<?php echo $incident_id; ?>" src="<?php echo url::file_loc('img'); ?>media/img/up.png" alt="UP" title="UP" border="0" /></a></td>
            <td><a href="javascript:rating('<?php echo $incident_id; ?>','subtract','original')"><img id="odown_<?php echo $incident_id; ?>" src="<?php echo url::file_loc('img'); ?>media/img/down.png" alt="DOWN" title="DOWN" border="0" /></a></td>
            <td><a href="" class="rating_value" id="orating_<?php echo $incident_id; ?>"><?php echo $incident_rating; ?></a></td>
            <td><a href="" id="oloader_<?php echo $incident_id; ?>" class="rating_loading" ></a></td>
          </tr>
        </table>
			</div>
		</div>
		
		<?php
            // Action::report_extra - Allows you to target an individual report right after the description
            Event::run('ushahidi_action.report_extra', $incident_id);

	?>
	</div>
	<?php
	/*
	use the api to create a page for - nearby incidents - time date title, category
	single instance with media on the left column
	*/


	
	?>
	<script type="text/javascript">
   $(document).ready(function(){
     $("a.load-incident").click(function(){
       var id = $(this).attr("rel");
       $.ajax({
         url:"/api",
         data:{
           "task":"incidents",
           "by":"incidentid",
           "id":id
         },
    	   success:function(data){
    	     console.log(data);
    	    var desc = '<h5><?php echo Kohana::lang('ui_main.reports_description');?></h5>';
    	    var verified = '<?=Kohana::lang("ui_main.verified")?>';
    	    var unverified = '<?=Kohana::lang("ui_main.unverified")?>';
          var incident = data.payload.incidents[0].incident;
          var categories = data.payload.incidents[0].categories; 
          var media = data.payload.incidents[0].media;
          var news = '<div class="credibility"><h5><?php echo Kohana::lang('ui_main.reports_news');?></h5>';
          
    	     $("#incident-title").html(incident.incidenttitle);
    	     $("#incident-date").html(incident.incidentdate);
    	     $("#incident-locaiton").html(incident.locationname);
           if (incident.incidentverified=="1"){
      	     $(".r_verified").html(verified)
           } else {
             $(".r_verified").html(unverified)
           }
           var cats = "";
           $(categories).each(function(i, cat){ 
             var span = '<span class="r_cat-box" style="background:transparent url(http://map.occupy.net/media/uploads/category_1_1321690237_16x16.png) 0 0 no-repeat;">&nbsp;</span>';
             cats = cats + '<p><a href="/reports/?c='+cat.category.id+'">'+cat.category.title+'</a></p>';
           })
    	    $("#report-categories").html(cats);
    	    
    	    
    	    //sort out photos, videos, news
    	    var vids = '<ol>';
    	    var links = "";
    	    var pics = "";
    	    $(media).each(function(i,m){
    	      if (m.type=="1"){
      	          console.log(m);
                  pics = pics + '<img style="width:520px" src="http://map.occupy.net/media/uploads/'+m.link+'"/><br/>';
                	//$thumb = str_replace(".","_t.",$photo);
                	//$prefix = url::base().Kohana::config('upload.relative_directory');
                	//echo '<a class="photothumb" rel="lightbox-group1" href="'.$prefix.'/'.$photo.'"><img src="'.$prefix.'/'.$thumb.'"/></a> ';

              pics = pics +'<a class="photothumb" rel="lightbox-group'+incident.id+' href=""><img src="'+m.link+'" /></a> ';
    	      }
    	      if (m.type=="2"){
              vids = vids+'<li>'+ m.link+'</li>';
    	      }
    	      if (m.type=="4"){
    	        links = links + '<a href="'+m.link+'">'+m.link+"</a>";
    	      }
    	    })
    	    vids = vids+'</ol>';
    	    $("#report-video").html(vids);
    	    $("#report-images").html(pics);
    	    $("#incident-description").html(desc + incident.incidentdescription + news +links + "</div>" + 
    	    "<div class=\"credibility\"><h5>Full Report</h5><a href=\"/reports/view/"+incident.incidentid+"\">"+incident.incidenttitle+"</a></div>");
   	     
    	   }
    	 })
     })
   })

	</script>
  <style type="text/css">
    #incidents_list {
      height:400px;
      overflow:auto;
      padding-top:4px;
    }
    #incidents_list .incident {
      background-color:#eeedea;
      margin-bottom:5px;
      padding:5px;
    }
  </style>
	<div id="incidents_list" class="incidents">
	  <?php foreach ($neighbors as $neighbor): 
	  //has video
    //has photo
    //show category
	  ?>
    <div class="incident">
  	  <div class="title">
  	   <a href="javascript:void(0)" class="load-incident" rel="<?=$neighbor->id?>"><?=$neighbor->incident_title?></a>
  	  </div>
      <div class="date">
        <?=$neighbor->incident_date?>
      </div>
      
    </div>
    
    
	   
	  <?php endforeach ?>
	</div>
	
	
	<div style="clear:both;"></div>
   
	
</div>
