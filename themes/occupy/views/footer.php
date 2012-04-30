			</div>
		</div>
		<!-- / main body -->

	</div>
	<!-- / wrapper -->
	
	<!-- footer -->
	<div id="footer" class="clearingfix">
 
		<div id="underfooter"></div>
				
		<!-- footer content -->
		<div class="rapidxwpr floatholder">
 
			<!-- footer credits -->
			<div class="footer-credits">
				<div class="ushahidi-credits">Powered by the &nbsp;<a href="http://www.ushahidi.com/"><img src="<?php echo url::file_loc('img'); ?>media/img/footer-logo.png" alt="Ushahidi" style="vertical-align:middle" /></a>&nbsp; Platform</div>
			</div>
			<!-- / footer credits -->
		
			<!-- footer menu -->
			<div class="footermenu">
				<ul class="clearingfix">
					<li><a class="item1" href="<?php echo url::site(); ?>"><?php echo Kohana::lang('ui_main.home'); ?></a></li>
					<li><a href="<?php echo url::site()."reports/submit"; ?>"><?php echo Kohana::lang('ui_main.submit'); ?></a></li>
					<li><a href="<?php echo url::site()."alerts"; ?>"><?php echo Kohana::lang('ui_main.alerts'); ?></a></li>
					<li><a href="<?php echo url::site()."contact"; ?>"><?php echo Kohana::lang('ui_main.contact'); ?></a></li>
					<?php
					// Action::nav_main_bottom - Add items to the bottom links
					Event::run('ushahidi_action.nav_main_bottom');
					?>
				</ul>
				<?php if($site_copyright_statement != '') { ?>
      		<p><?php echo $site_copyright_statement; ?></p>
      	<?php } ?>
			</div>
			<!-- / footer menu -->

      
			<h2 class="feedback_title" style="clear:both">
				<a href="http://feedback.ushahidi.com/fillsurvey.php?sid=2"><?php echo Kohana::lang('ui_main.feedback'); ?></a>
			</h2>

 
		</div>
		<!-- / footer content -->
 
	</div>
	<!-- / footer -->
 
	<?php echo $ushahidi_stats; ?>
	<?php //echo $google_analytics; ?>
	<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-25680260-3']);
  _gaq.push(['_setDomainName', '.occupy.net']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
	
	<!-- Task Scheduler --><script type="text/javascript">$(document).ready(function(){
	    $.ajax({
        url:site_url + "scheduler"
      })
	  });</script><div id="schedulerholder"></div><!-- End Task Scheduler -->
 
	<?php
	// Action::main_footer - Add items before the </body> tag
	Event::run('ushahidi_action.main_footer');
	
	/* google analytics doesn't work ?  log stats directly*/
	$server_json ="";
  $timestamp = date("Y-m-d H:i:s",time());
  $db = new Database();
  $ref = (isset($_SERVER["HTTP_REFERER"])) ? $_SERVER["HTTP_REFERER"] : "";
  $ip = $_SERVER["REMOTE_ADDR"];
  $host = gethostbyaddr($_SERVER["REMOTE_ADDR"]);
  $page = isset($_SERVER["REQUEST_URI"]) ? $_SERVER["REQUEST_URI"] : "";
  $db->query("insert into stats (server_json, read_time, referer, ip, rhost, page) values ('".$server_json."','".$timestamp."','".$ref."','".$ip."','".$host."','".$page."')");
	?>
	
</body>
</html>
