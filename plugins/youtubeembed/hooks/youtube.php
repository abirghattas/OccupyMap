<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Youtube Links Hook - Load All Events
 *
 * PHP version 5
 * LICENSE: This source file is subject to LGPL license 
 * that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/copyleft/lesser.html
 * @author	   Ushahidi Team <team@ushahidi.com> 
 * @package	   Ushahidi - http://source.ushahididev.com
 * @copyright  Ushahidi - http://www.ushahidi.com
 * @license	   http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL) 
 */

class youtube {
	/**
	 * Registers the main event add method
	 */
	public function __construct()
	{	
		// Hook into routing
		Event::add('system.pre_controller', array($this, 'add'));
	}
	
	/**
	 * Adds all the events to the main Ushahidi application
	 */
	public function add()
	{
		// Only add the events if we are on that controller
		if (Router::$controller == 'reports')
		{
			Event::add('ushahidi_filter.report_description', array($this, '_embed_youtube'));
		}
	}
	
	public function _embed_youtube()
	{
		// Access the report description
		$report_description = Event::$data;
		
		$report_description = $this->_auto_embed($report_description);
		
		// Return new description
		Event::$data = $report_description;
	}
	
	
	/**
	 * Convert the youtube text anchors into links.
	 *
	 * @param   string   text to autoembed
	 * @return  string
	 */
	private function _auto_embed($text)
	{
	    //using the video embed library
	    $video_embed = new VideoEmbed();
	    return  $video_embed->embed($text);
	}
}

new youtube;