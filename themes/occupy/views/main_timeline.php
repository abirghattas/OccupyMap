<div id="timeline-container" style="display:none">
  <div class="slider-holder">
  	<form action="">
  		<input type="hidden" value="0" name="currentCat" id="currentCat"/>
  		<fieldset>
  			<div class="play"><a href="javascript:void(0)" id="playTimeline"><?php echo Kohana::lang('ui_main.play'); ?></a></div>
  			<label for="startDate"><?php echo Kohana::lang('ui_main.from'); ?>:</label>
  			<select name="startDate" id="startDate"><?php echo $startDate; ?></select>
  			<label for="endDate"><?php echo Kohana::lang('ui_main.to'); ?>:</label>
  			<select name="endDate" id="endDate"><?php echo $endDate; ?></select>
  		</fieldset>
  	</form>
  </div>
  <div id="graph" class="graph-holder"></div>  
</div>

<div id="show-timeline" style="background-color:#ccc">
  <div class="content-block" style="width:120px; margin-left:auto; margin-right:auto; background:transparent">
    <div style="width:120px">
      <h5 class="label">Show Timeline</h5>
      <h5 class="label" style="display:none">Hide Timeline</h5>
    </div>
  </div>
</div>
<script type="text/javascript">
//timeline has to be visible on page load or else errorz
$(document).ready(function(){
  $("#timeline-container").fadeOut('slow');
  $("#show-timeline").click(function(){
    $("#timeline-container").toggle(400);
    $("#show-timeline").find(".label").toggle(400);
  })  
})</script>