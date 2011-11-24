<div class="slider-holder">
	<form action="">
		<input type="hidden" value="0" name="currentCat" id="currentCat"/>
		<fieldset>
			<div class="play"><a href="javascript:void(0)" id="playTimeline"><?php echo Kohana::lang('ui_main.play'); ?></a></div>
			<label for="timeInterval">Speed</label>
			<select id="timeInterval" name="timeInterval" value="86400">
			 <option value="86400">1 sec : 1 day</option>
			 <option value="3600">1 sec : 1 hr</option>
			 <option value="60">1 sec : 1 min</option>
			</select>
            
			<label for="startDate"><?php echo Kohana::lang('ui_main.from'); ?>:</label>
			<select name="startDate" id="startDate"><?php echo $startDate; ?></select>
			<label for="endDate"><?php echo Kohana::lang('ui_main.to'); ?>:</label>
			<select name="endDate" id="endDate"><?php echo $endDate; ?></select>
	        
	    </fieldset>
	</form>
</div>
<div id="graph" class="graph-holder"></div>

