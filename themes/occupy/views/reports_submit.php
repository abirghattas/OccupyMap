<?php
if (strlen($location_name)>1){
  $form["location_name"] = $location_name;
}
?>
<div id="content">
	<div class="content-bg">

		<?php if($site_submit_report_message != '') { ?>
			<div class="green-box" style="margin: 25px 25px 0px 25px">
				<h3><?php echo $site_submit_report_message; ?></h3>
			</div>
		<?php } ?>

		<!-- start report form block -->
		<?php print form::open(NULL, array('enctype' => 'multipart/form-data', 'id' => 'reportForm', 'name' => 'reportForm', 'class' => 'gen_forms')); ?>
		<input type="hidden" name="latitude" id="latitude" value="<?php echo $form['latitude']; ?>">
		<input type="hidden" name="longitude" id="longitude" value="<?php echo $form['longitude']; ?>">
		<input type="hidden" name="country_name" id="country_name" value="<?php echo $form['country_name'];?>" />
		<div class="big-block">
			<h1><?php echo Kohana::lang('ui_main.reports_submit_new'); ?></h1>
			<?php if ($form_error): ?>
			<!-- red-box -->
			<div class="red-box">
				<h3>Error!</h3>
				<ul>
					<?php
						foreach ($errors as $error_item => $error_description)
						{
							// print "<li>" . $error_description . "</li>";
							print (!$error_description) ? '' : "<li>" . $error_description . "</li>";
						}
					?>
				</ul>
			</div>
			<?php endif; ?>
			<div class="row">
				<input type="hidden" name="form_id" id="form_id" value="<?php echo $id?>">
			</div>
			<div class="report_left">
				<div class="report_row">
					<?php if(count($forms) > 1){ ?>
					<div class="row" style="display:none">
						<h4><span><?php echo Kohana::lang('ui_main.select_form_type');?></span>
						<span class="sel-holder">
							<?php print form::dropdown('form_id', $forms, $form['form_id'],
						' onchange="formSwitch(this.options[this.selectedIndex].value, \''.$id.'\')"') ?>
						</span>
						<div id="form_loader" style="float:left;"></div>
						</h4>
					</div>
					<?php } ?>
					<h4 title="If you have a youtube video, just drop it in here and the form will auto-populate with the name and description of the video">Video Link - Youtube or Vimeo (optional)</h4>
					<input type="text" name="check_youtube" class="text long "id="check_youtube" value="" />
					<h4><?php echo Kohana::lang('ui_main.reports_title'); ?></h4>
					<?php print form::input('incident_title', $form['incident_title'], ' class="text long"'); ?>
				</div>
				<div class="report_row">
					<h4><?php echo Kohana::lang('ui_main.reports_description'); ?></h4>
					<?php print form::textarea('incident_description', $form['incident_description'], ' rows="10" class="textarea long" ') ?>
				</div>
				
	
				<div class="report_row" id="datetime_default">
      				<h1>When Did it Happen?</h1>
					
				</div>
				<div class="report_row hide" id="datetime_edit">
					<div class="date-box">
						<h4><?php echo Kohana::lang('ui_main.reports_date'); ?></h4>
						<?php print form::input('incident_date', $form['incident_date'], ' class="text short"'); ?>
						<script type="text/javascript">
							$().ready(function() {
								$("#incident_date").datepicker({ 
									showOn: "both", 
									buttonImage: "<?php echo url::file_loc('img'); ?>media/img/icon-calendar.gif", 
									buttonImageOnly: true 
								});
							});
						</script>
					</div>
					<div class="time">
						<h4><?php echo Kohana::lang('ui_main.reports_time'); ?></h4>
						<?php
							for ($i=1; $i <= 12 ; $i++)
							{ 
								$hour_array[sprintf("%02d", $i)] = sprintf("%02d", $i);	 // Add Leading Zero
							}
							for ($j=0; $j <= 59 ; $j++)
							{ 
								$minute_array[sprintf("%02d", $j)] = sprintf("%02d", $j);	// Add Leading Zero
							}
							$ampm_array = array('pm'=>'pm','am'=>'am');
							print form::dropdown('incident_hour',$hour_array,$form['incident_hour']);
							print '<span class="dots">:</span>';
							print form::dropdown('incident_minute',$minute_array,$form['incident_minute']);
							print '<span class="dots">:</span>';
							print form::dropdown('incident_ampm',$ampm_array,$form['incident_ampm']);
						?>
						<?php if ($site_timezone != NULL): ?>
							<small>(all times are USA/Eastern)</small>
						<?php endif; ?>
					</div>
					<div style="clear:both; display:block;" id="incident_date_time"></div>
				</div>
				<div class="report_row">
					<div class="report_category" id="categories">
					  <h1>What was it?</h1>
  					<h4><?php echo Kohana::lang('ui_main.reports_categories'); ?></h4>

					<?php
					$form['incident_category'] = array(16);
						$selected_categories = (!empty($form['incident_category']) AND is_array($form['incident_category']))
							? $selected_categories = $form['incident_category']
							:array();
							
						$columns = 2;
						echo category::tree($categories, $selected_categories, 'incident_category', $columns);
						?>
					</div>
				</div>


				<?php
				// Action::report_form - Runs right after the report categories
				Event::run('ushahidi_action.report_form');
				?>

				<?php echo $custom_forms ?>

        <?php if ($this->logged_in !=1):?>

				<div class="report_optional">
					<h3><?php echo Kohana::lang('ui_main.reports_optional'); ?></h3>
					<div class="report_row">
						<h4><?php echo Kohana::lang('ui_main.reports_first'); ?></h4>
						<?php print form::input('person_first', $form['person_first'], ' class="text long"'); ?>
					</div>
					<div class="report_row">
						<h4><?php echo Kohana::lang('ui_main.reports_last'); ?></h4>
						<?php print form::input('person_last', $form['person_last'], ' class="text long"'); ?>
					</div>
					<div class="report_row">
						<h4><?php echo Kohana::lang('ui_main.reports_email'); ?></h4>
						<?php print form::input('person_email', $form['person_email'], ' class="text long"'); ?>
					</div>
					<?php
					// Action::report_form_optional - Runs in the optional information of the report form
					Event::run('ushahidi_action.report_form_optional');
					?>
				</div>
		
		    <?php endif;?>
			</div>
			<div class="report_right">
				<?php if ( ! $multi_country AND count($cities) > 1){ ?>
				<!--
				<div class="report_row">
					<h4><?php //echo Kohana::lang('ui_main.reports_find_location'); ?></h4>
					<?php //print form::dropdown('select_city',$cities,'', ' class="select" '); ?>
				</div>
				-->
				<?php } ?>
				<div class="report_row">
					<div id="divMap" class="report_map">
						<div id="geometryLabelerHolder" class="olControlNoSelect">
							<div id="geometryLabeler">
								<div id="geometryLabelComment">
									<span id="geometryLabel"><label><?php echo Kohana::lang('ui_main.geometry_label');?>:</label> <?php print form::input('geometry_label', '', ' class="lbl_text"'); ?></span>
									<span id="geometryComment"><label><?php echo Kohana::lang('ui_main.geometry_comments');?>:</label> <?php print form::input('geometry_comment', '', ' class="lbl_text2"'); ?></span>
								</div>
								<div>
									<span id="geometryColor"><label><?php echo Kohana::lang('ui_main.geometry_color');?>:</label> <?php print form::input('geometry_color', '', ' class="lbl_text"'); ?></span>
									<span id="geometryStrokewidth"><label><?php echo Kohana::lang('ui_main.geometry_strokewidth');?>:</label> <?php print form::dropdown('geometry_strokewidth', $stroke_width_array, ''); ?></span>
									<span id="geometryLat"><label><?php echo Kohana::lang('ui_main.latitude');?>:</label> <?php print form::input('geometry_lat', '', ' class="lbl_text"'); ?></span>
									<span id="geometryLon"><label><?php echo Kohana::lang('ui_main.longitude');?>:</label> <?php print form::input('geometry_lon', '', ' class="lbl_text"'); ?></span>
								</div>
							</div>
							<div id="geometryLabelerClose"></div>
						</div>
					</div>
					<div class="report-find-location">
					    <div id="panel" class="olControlEditingToolbar"></div>
						<div class="btns" style="float:left;">
							<ul style="padding:4px;">
								<li><a href="javascript:void(0)" class="btn_del_last"><?php echo strtoupper(Kohana::lang('ui_main.delete_last'));?></a></li>
								<li><a href="javascript:void(0)" class="btn_del_sel"><?php echo strtoupper(Kohana::lang('ui_main.delete_selected'));?></a></li>
								<li><a href="javascript:void(0)" class="btn_clear"><?php echo strtoupper(Kohana::lang('ui_main.clear_map'));?></a></li>
							</ul>
						</div>
						<div style="clear:both;"></div>
						<?php print form::input('location_find', '', ' title="'.Kohana::lang('ui_main.location_example').'" class="findtext" autocomplete="off"'); ?>
						<div style="float:left;margin:9px 0 0 5px;"><input type="button" name="button" id="button" value="<?php echo Kohana::lang('ui_main.find_location'); ?>" class="btn_find" /></div>
						<div id="find_loading" class="report-find-loading"></div>
						<div style="clear:both;" id="find_text"><?php echo Kohana::lang('ui_main.pinpoint_location'); ?>.</div>
					</div>
				</div>
				<?php Event::run('ushahidi_action.report_form_location', $id); ?>
				<div class="report_row">
					<h4><?php echo Kohana::lang('ui_main.reports_location_name'); ?><br /><span class="example"><?php echo Kohana::lang('ui_main.detailed_location_example'); ?></span></h4>
					
					<?php print form::input('location_name', $form['location_name'], ' class="text long" autocomplete="off"'); ?>
				</div>

				<!-- News Fields -->
				<div id="divNews" class="report_row">
					<h4><?php echo Kohana::lang('ui_main.reports_news'); ?> (optional)</h4>
					<?php
						$this_div = "divNews";
						$this_field = "incident_news";
						$this_startid = "news_id";
						$this_field_type = "text";
						if (empty($form[$this_field]))
						{
							$i = 1;
							print "<div class=\"report_row\">";
							print form::input($this_field . '[]', '', ' class="text long2"');
							print "<a href=\"#\" class=\"add\" onClick=\"addFormField('$this_div','$this_field','$this_startid','$this_field_type'); return false;\">add</a>";
							print "</div>";
						}
						else
						{
							$i = 0;
							foreach ($form[$this_field] as $value) {
							print "<div class=\"report_row\" id=\"$i\">\n";

							print form::input($this_field . '[]', $value, ' class="text long2"');
							print "<a href=\"#\" class=\"add\" onClick=\"addFormField('$this_div','$this_field','$this_startid','$this_field_type'); return false;\">add</a>";
							if ($i != 0)
							{
								print "<a href=\"#\" class=\"rem\"	onClick='removeFormField(\"#" . $this_field . "_" . $i . "\"); return false;'>remove</a>";
							}
							print "</div>\n";
							$i++;
						}
					}
					print "<input type=\"hidden\" name=\"$this_startid\" value=\"$i\" id=\"$this_startid\">";
				?>
				</div>


				<!-- Video Fields -->
				<div id="divVideo" class="report_row">
					<h4><?php echo Kohana::lang('ui_main.reports_video'); ?> (optional)</h4>
					<?php
						$this_div = "divVideo";
						$this_field = "incident_video";
						$this_startid = "video_id";
						$this_field_type = "text";

						if (empty($form[$this_field])):?>
						<?php 	$i = 1;?>
						  <div class="report_row">
							<?=form::input($this_field . '[]', '', ' class="text long2 video"');?>
							<a href="javascript:void(0)" class="add" onClick="addFormField('<?=$this_div?>','<?=$this_field?>','<?=$this_startid?>','<?=$this_field_type?>'); return false;">add</a>
							</div>
						<?php else: ?>
						<?php	$i = 0;?>
							<?php foreach ($form[$this_field] as $value): ?>
								  <div class="report_row" id="<?=$i?>">

								<?=form::input($this_field . '[]', $value, ' class="text long2"');?>
							<a href="javascript:void(0)" class="add" onClick="addFormField('<?=$this_div?>','<?=$this_field?>','<?=$this_startid?>','<?=$this_field_type?>'); return false;">add</a>
								<?php if ($i != 0):?>
								  <a href="#" class="rem"	onClick='removeFormField(\"#<?=$this_field ?>"_"<?=$i?>"); return false;'>remove</a>
								<?php endif?>
								</div>
								<?php $i++;?>
							<?php endforeach?>
						<?php endif?>
						<input type="hidden" name="<?=$this_startid?>" value="<?=$i?>" id="<?=$this_startid?>">
				</div>

				<!-- Photo Fields -->
				<div id="divPhoto" class="report_row">
					<h4><?php echo Kohana::lang('ui_main.reports_photos'); ?> (optional)</h4>
					<?php
						$this_div = "divPhoto";
						$this_field = "incident_photo";
						$this_startid = "photo_id";
						$this_field_type = "file";

						if (empty($form[$this_field]['name'][0]))
						{
							$i = 1;
							print "<div class=\"report_row\">";
							print form::upload($this_field . '[]', '', ' class="file long2"');
							print "<a href=\"#\" class=\"add\" onClick=\"addFormField('$this_div','$this_field','$this_startid','$this_field_type'); return false;\">add</a>";
							print "</div>";
						}
						else
						{
							$i = 0;
							foreach ($form[$this_field]['name'] as $value) 
							{
								print "<div class=\"report_row\" id=\"$i\">\n";

								// print "\"<strong>" . $value . "</strong>\"" . "<BR />";
								print form::upload($this_field . '[]', $value, ' class="file long2"');
								print "<a href=\"#\" class=\"add\" onClick=\"addFormField('$this_div','$this_field','$this_startid','$this_field_type'); return false;\">add</a>";
								if ($i != 0)
								{
									print "<a href=\"#\" class=\"rem\"	onClick='removeFormField(\"#" . $this_field . "_" . $i . "\"); return false;'>remove</a>";
								}
								print "</div>\n";
								$i++;
							}
						}
						print "<input type=\"hidden\" name=\"$this_startid\" value=\"$i\" id=\"$this_startid\">";
					?>

				</div>
									
				<div class="report_row">
					<input name="submit" type="submit" value="<?php echo Kohana::lang('ui_main.reports_btn_submit'); ?>" class="btn_submit" /> 
				</div>
			</div>
		</div>
		<?php print form::close(); ?>
		<!-- end report form block -->
	</div>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        $("div.report_row").show();
            //youtube prepopulate on submit forms
             $("#check_youtube").change(function(){
    				   //do the youtube query, get json and prepopulate
              v_url = $("#check_youtube").val();
              $("#incident_title").css("background-color","#ffc");
              $("#incident_description").css("background-color","#ffc");
              $("#incident_title").val("Loading...");
              $("#incident_description").val("Loading...");

              if ((v_url.split("youtube.com").length >1 )|| (v_url.split("vimeo.com").length >1)) {
    					   $.ajax({
    					     url:"http://map.occupy.net:9494/video/"+v_url,
    					     dataType:'json',
    					     success:function(data) {
    					       $("#incident_title").val(data.title);
    					       $("#incident_description").val(data.url +" \n \n"+ data.description);
    					       $("input.video").first().val(v_url);
                     $("#incident_title").css("background-color","#fff");
                     $("#incident_description").css("background-color","#fff");


                    //set the date also.  would be great to prompt a location
    					     }
    					   })
              }

    				 })
           })


    
</script>