<?php
/**
 * Video embedding libary
 * Provides a feature for embedding videos (YouTube, Google Video, Revver, Metacafe, LiveLeak, 
 * Dostub and Vimeo) in a report
 * 
 * @package	   VideoEmbed
 * @author	   Ushahidi Team
 * @copyright  (c) 2008 Ushahidi Team
 * @license	   http://www.ushahidi.com/license.html
 */
class VideoEmbed 
{
	/**
	 * Generates the HTML for embedding a video in a report
	 *
	 * @param string $raw URL of the video to be embedded
	 * @param string $auto Autoplays the video as soon as its loaded
	 */
	public function embed($raw, $auto=null)
	{
		// To hold the name of the video service
		$service_name = "";
		
		
		// Array of the supportted video services
		$services = array(
			"youtube" => "http://www.youtube.com/watch?v=",
			"google" => "http://video.google.com/videoplay?docid=-",
			"revver" => "http://one.revver.com/watch/", 
			"metacafe" => "http://www.metacafe.com/watch/", 
			"lieveleak" => "http://www.liveleak.com/view?i=",
			"dotsub" => "http://dotsub.com/media/",
			"vimeo" => "http://vimeo.com/",
			"youtube-shortlink" => "http://youtu.be/"
		);
		
		
		//the string to return;
		$return = "";
		// Determine the video service to use
		foreach ($services as $key => $value)
		{
			// Extract the domain name of the service and check if it exists in the provided video URL   
        $check = explode($value,$raw);
        if (count($check) > 1){
            $zhek = $check;
            unset($zhek[0]);
            foreach ($zhek as $chk){
                $code = preg_split("/ |\<br\>|\<br \/>|\n|\t/",$chk);
                $code = $code[0];
                $return = "";
        	    switch ($key) {
        	    case "youtube":
        	    case "youtube-shortlink":

        		// Check for autoplay
        		$you_auto = ($auto == "play")? "&autoplay=1" : "";
		
        		$return .= "<object width='500' height='315'>"
        			. "	<param name='movie' value='http://www.youtube.com/v/$code$you_auto'></param>"
        			. "	<param name='wmode' value='transparent'></param>"
        			. "	<embed src='http://www.youtube.com/v/$code$you_auto' type='application/x-shockwave-flash' "
        			. "		wmode='transparent' width='500' height='315'>"
        			. "	</embed>"
        			. "</object>";
        		break;
                case "google":
        			// Check for autoplay
        			$google_auto = ($auto == "play")? "&autoPlay=true" : "";

        			$return .= "<embed style='width:320px; height:265px;' id='VideoPlayback' type='application/x-shockwave-flash'"
        				. "	src='http://video.google.com/googleplayer.swf?docId=-$code$google_auto&hl=en' flashvars=''>"
        				. "</embed>";
        		break;
                case "revver":
        			// Sanitization
        			$code = str_replace("/flv", "", $code);

        			// Check for autoplay
        			$rev_auto = ($auto == "play")? "&autoStart=true" : "";

        			$return .= "<script src='http://flash.revver.com/player/1.0/player.js?mediaId:$code;affiliateId:0;height:320;width:265;'"
        				. "	type='text/javascript'>"
        				. "</script>";
                break;
                case "metacafe":
        			// Sanitize input
        			$code = strrev(trim(strrev($code), "/"));
			
        			$retrurn .= "<embed src='http://www.metacafe.com/fplayer/$code.swf'"
        				. "	width='500' height='315' wmode='transparent' pluginspage='http://get.adobe.com/flashplayer/'"
        				. "	type='application/x-shockwave-flash'> "
        				. "</embed>";
        		break;
                case "liveleak":
        			$return .= "<object type='application/x-shockwave-flash' width='500' height='272'='transparent'"
        				. "	data='http://www.liveleak.com/e/$code'>"
        				. "	<param name='movie' value='http://www.liveleak.com/e/$code'>"
        				. "	<param name='wmode' value='transparent'><param name='quality' value='high'>"
        				. "</object>";
                break;
                case "dotsub":
        			$return .= "<iframe src='http://dotsub.com/media/$code' frameborder='0' width='500' height='500'></iframe>";
        		break;
        		case "vimeo":
                	$return .= "<iframe src=\"http://player.vimeo.com/video/$code\" width=\"500\" height=\"300\" frameborder=\"0\"></iframe>";
        		break;
        	    }
        	    $replace=$value.$code;
        	    $raw = str_replace($replace,$return,$raw);

                }
	        }
		}
        
	    return $raw;
    
	}
	
}
?>