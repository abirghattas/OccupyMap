<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Mobile Controller
 *
 * PHP version 5
 * LICENSE: This source file is subject to LGPL license 
 * that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/copyleft/lesser.html
 * @author	   Ushahidi Team <team@ushahidi.com> 
 * @package    Ushahidi - http://source.ushahididev.com
 * @module	   Mobile Controller	
 * @copyright  Ushahidi - http://www.ushahidi.com
 * @license    http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL) 
* 
*/

class Mobile_Controller extends Template_Controller {
	
	public $auto_render = TRUE;
	public $mobile = TRUE;
	
	// Cacheable Controller
	public $is_cachable = TRUE;
	
	// Main template
    public $template = 'mobile/layout';

	// Table Prefix
	protected $table_prefix;

    public function __construct()
    {
		parent::__construct();
		
		// Set Table Prefix
		$this->table_prefix = Kohana::config('database.default.table_prefix');
		
		// Load Header & Footer
        $this->template->header  = new View('mobile/header');
        $this->template->footer  = new View('mobile/footer');

		$this->template->header->site_name = Kohana::config('settings.site_name');
		$this->template->header->site_tagline = Kohana::config('settings.site_tagline');

		plugin::add_javascript('mobile/views/js/jquery');
		plugin::add_javascript('mobile/views/js/jquery.treeview');
		plugin::add_javascript('mobile/views/js/expand');
		plugin::add_stylesheet('mobile/views/css/styles');
		plugin::add_stylesheet('mobile/views/css/jquery.treeview');
		
		$this->template->header->show_map = FALSE;
		$this->template->header->js = "";
		$this->template->header->breadcrumbs = "";
		
		// Google Analytics
		$google_analytics = Kohana::config('settings.google_analytics');
		$this->template->footer->google_analytics = $this->_google_analytics($google_analytics);
	}
	
	public function index()
	{
		$this->template->content  = new View('mobile/main');
		
		// Get 10 Most Recent Reports
		$this->template->content->incidents = ORM::factory('incident')
            ->where('incident_active', '1')
			->limit('10')
            ->orderby('incident_date', 'desc')
			->with('location')
            ->find_all();
            
      //get locations near me      
            
		
		// Get all active top level categories
        $parent_categories = array();
        foreach (ORM::factory('category')
				->where('category_visible', '1')
				->where('parent_id', '0')
				->orderby('category_title')
				->find_all() as $category)
        {
            // Get The Children
			$children = array();
			foreach ($category->children as $child)
			{
				$children[$child->id] = array(
					$child->category_title,
					$child->category_color,
					$child->category_image,
					$this->_category_count($child->id)
				);
			}

			// Put it all together
            $parent_categories[$category->id] = array(
				$category->category_title,
				$category->category_color,
				$category->category_image,
				$this->_category_count($category->id),
				$children
			);
			
			if ($category->category_trusted)
			{ // Get Trusted Category Count
				$trusted = ORM::factory("incident")
					->join("incident_category","incident.id","incident_category.incident_id")
					->where("category_id",$category->id);
				if ( ! $trusted->count_all())
				{
					unset($parent_categories[$category->id]);
				}
			}
        }
        $this->template->content->categories = $parent_categories;

		// Get RSS News Feeds
		$this->template->content->feeds = ORM::factory('feed_item')
			->limit('10')
            ->orderby('item_date', 'desc')
            ->find_all();
	}
	
	private function _category_count($category_id = false)
	{
		if ($category_id)
		{
			return ORM::factory('incident_category')
				->join('incident', 'incident_category.incident_id', 'incident.id')
				->where('category_id', $category_id)
				->where('incident_active', '1')
				->count_all();
		}
		else
		{
			return 0;
		}
	}
	
	/*
	* Google Analytics
	* @param text mixed  Input google analytics web property ID.
    * @return mixed  Return google analytics HTML code.
	*/
//
function recent_activity($lat = 0, $lon = 0, $page = 0,$interval=30){
  $zoom = 12;
  $db = new Database();
  $z = 20000 / (pow(1.8,$zoom));
  //can't do a group by here because they order incorrectly
  $q = "select l.location_name, l.id, i.incident_date, 
         ((ACOS(SIN(".$lat." * PI() / 180) * SIN(l.`latitude` * PI() / 180) + COS(".$lat." * PI() / 180) * COS(l.`latitude` * PI() / 180) * COS((".$lon." - l.`longitude`) * PI() / 180)) * 180 / PI()) * 60 * 1.1515) AS distance
     from incident i
     left join location l on l.id = i.location_id
    where location_name != 'Unknown' and incident_date BETWEEN (CURDATE() - INTERVAL ".$interval." DAY) AND (CURDATE() + INTERVAL 1 DAY)
     having distance < ".$z."
     order by i.incident_date desc
     limit ".$page.",100";

  $query = $db->query($q);
  $recent_activity = array();
  $group = array();
  foreach ($query as $data) {
    $group[$data->location_name] = $data;
  }
  $group = array_slice($group,0,25);
  foreach ($group as $data ) {
    $recent_activity[]= array("location_name" => $data->location_name, "id"=>$data->id, "distance" =>$data->distance,"date"=>$data->incident_date);
  }
  header('Content-type: application/json');
  echo json_encode($recent_activity);
  exit();
}


	
	function nearby_places($lat = 0, $lon = 0, $page = 0,$interval=30){
    $zoom = 12;
    $db = new Database();
    $z = 20000 / (pow(1.8,$zoom));
    $q = "select l.location_name, l.id, i.incident_date, count(l.id) as num_incidents, 
           ((ACOS(SIN(".$lat." * PI() / 180) * SIN(l.`latitude` * PI() / 180) + COS(".$lat." * PI() / 180) * COS(l.`latitude` * PI() / 180) * COS((".$lon." - l.`longitude`) * PI() / 180)) * 180 / PI()) * 60 * 1.1515) AS distance
       from location l
       join incident i on i.location_id = l.id
          where location_name != 'Unknown'
       group by location_name

       having distance < ".$z."
      order by distance asc
       limit ".$page.",25";

    $query = $db->query($q);
    $active_locations = array();
    foreach ($query as $data ) {
      $active_locations[]= array("location_name" => $data->location_name, "id"=>$data->id, "num_incidents"=>$data->num_incidents,"distance" =>$data->distance);
    }
    
    header('Content-type: application/json');
    echo json_encode($active_locations);
    exit();
	}
	
  	//displays a modified incident page centered around a report and 
  	public function locations($id = FALSE)
  	{
  		$this->template->content = new View('mobile/location');

      //location id, then grab incidents from that location (text match / time space match)

  		if ( ! Location_Model::is_valid_location($id, TRUE))
  		{
  			url::redirect('index');
  		}
  		else
  		{
  		  $location = ORM::factory('location')
  		    ->where('id',$id)
  		    ->find();
  			if ( $location->id == 0 )	// Not Found
  			{
  				url::redirect('reports/');
  			}
        $this->template->content->location = $location;
        
        //base incident is the first / newest one
        $incidents = array();
        foreach ($location->incident as $incident) {
          $incidents[] = $incident;
        }
        $an_incident = $incidents[0];

        $neighbors = Incident_Model::get_neighbouring_incidents($an_incident->id, FALSE, 0.125, 100);
        $nabs = array();
        foreach ($neighbors as $neighbor) {
          $nabs[] = $neighbor;
        }
        if (count($nabs) >0)
        {
          $most_recent_incident = $nabs[0];
        } 
        else 
        {
          $most_recent_incident = $an_incident;
        }
        $incident = ORM::factory('incident')
          ->where('id',$most_recent_incident->id)
          ->find();
        $this->template->content->an_incident = $an_incident;

  //      $this->template->content->incident_neighbors =Incident_Model::get_neighbouring_incidents($an_incident->id, TRUE, 0,25);
        $this->template->content->neighbors = $neighbors;

        
    		// Add Neighbors
    		$this->template->content->incident_neighbors = Incident_Model::get_neighbouring_incidents($incident->id, TRUE, 0, 5);

    	
        //build an array of tags from tags table
        //for each incident
        //get tags
        //tally and build tag cloud



  			// Filters
  			$incident_title = $incident->incident_title;
  			$incident_description = nl2br($incident->incident_description);
  			Event::run('ushahidi_filter.report_title', $incident_title);
  			Event::run('ushahidi_filter.report_description', $incident_description);

  			// Add Features
  			$this->template->content->features_count = $incident->geometry->count();
  			$this->template->content->features = $incident->geometry;
  			$this->template->content->incident_id = $incident->id;
  			$this->template->content->incident_title = $incident_title;
  			$this->template->content->incident_description = $incident_description;
  			$this->template->content->incident_location = $location->location_name;
  			$this->template->content->incident_latitude = $location->latitude;
  			$this->template->content->incident_longitude = $location->longitude;
  			$this->template->content->incident_date = date('M j Y', strtotime($incident->incident_date));
  			$this->template->content->incident_time = date('H:i', strtotime($incident->incident_date));
  			$this->template->content->incident_category = $incident->incident_category;
  			
        
        $category_images = array();
        $cats = ORM::factory('category')
    	    ->where('category_visible', '1')
    	    ->where('parent_id', '0')
    	    ->where('category_trusted != 1')
    	    ->orderby('category_title', 'ASC')
    	    ->find_all();


        //load category icons
        foreach ($cats as $c) {
          $image_url = $c->category_image;
          if (strlen($c->category_image) > 1) {
            $image_url = $c->category_image;
            $image_url = url::file_loc('img').'media/uploads/'.$image_url;

          } else {
            $image_url =  url::file_loc('img').'media/img/openlayers/marker-gold.png';
          }

          $category_images[$c->id] = $image_url;

          $subcats = ORM::factory('category')
      	    ->where('category_visible', '1')
      	    ->where('parent_id', $c->id)
      	    ->where('category_trusted != 1')
      	    ->orderby('category_title', 'ASC')
      	    ->find_all();


          foreach ($subcats as $sc) {
            $image_url = $c->category_image;
            $image_url = url::file_loc('img').'media/uploads/'.$image_url;

            if (strlen($sc->category_image) > 1) {
              $image_url = $sc->category_image;
              $image_url = url::file_loc('img').'media/uploads/'.$image_url;
            }
              $category_images[$sc->id] = $image_url;
          }
        }
        $this->template->content->category_images = $category_images;
  			// Incident rating
  			$this->template->content->incident_rating = ($incident->incident_rating == '')
  				? 0
  				: $incident->incident_rating;

  			// Retrieve Media
  			$incident_news = array();
  			$incident_video = array();
  			$incident_photo = array();

  			foreach($incident->media as $media)
  			{
  				if ($media->media_type == 4)
  				{
  					$incident_news[] = $media->media_link;
  				}
  				elseif ($media->media_type == 2)
  				{
  					$incident_video[] = $media->media_link;
  				}
  				elseif ($media->media_type == 1)
  				{
  					$incident_photo[] = $media->media_link;
  				}
  			}

  			$this->template->content->incident_verified = $incident->incident_verified;

  			// Retrieve Comments (Additional Information)
  			$this->template->content->comments = "";
  			if (Kohana::config('settings.allow_comments'))
  			{
  				$this->template->content->comments = new View('reports_comments');
  				$incident_comments = array();
  				if ($id)
  				{
  					$incident_comments = Incident_Model::get_comments($id);
  				}
  				$this->template->content->comments->incident_comments = $incident_comments;
  			}
  		}

  		// Add Neighbors
  		$this->template->content->incident_neighbors = Incident_Model::get_neighbouring_incidents($id, TRUE, 0, 5);
  		$this->template->header->show_map = TRUE;
      return true;
  		// News Source links
  		$this->template->content->incident_news = $incident_news;


  		// Video links
  		$this->template->content->incident_videos = $incident_video;

  		// Images
  		$this->template->content->incident_photos = $incident_photo;

  		// Create object of the video embed class
  		$video_embed = new VideoEmbed();
  		$this->template->content->videos_embed = $video_embed;

  		// Javascript Header
  		$this->themes->map_enabled = TRUE;
  		$this->themes->photoslider_enabled = TRUE;
  		$this->themes->videoslider_enabled = TRUE;
  		$this->themes->js = new View('reports_view_js');
  		$this->themes->js->incident_id = $incident->id;
  		$this->themes->js->default_map = Kohana::config('settings.default_map');
  		$this->themes->js->default_zoom = Kohana::config('settings.default_zoom');

  		$this->themes->js->default_zoom = 16;
      $this->themes->js->latitude = $incident->location->latitude;
  		$this->themes->js->longitude = $incident->location->longitude;
  		$this->themes->js->incident_zoom = 16;
  		$this->themes->js->incident_photos = $incident_photo;

  		// Initialize custom field array
  		$this->template->content->custom_forms = new View('reports_view_custom_forms');
  		$form_field_names = customforms::get_custom_form_fields($id, $incident->form_id, FALSE, "view");
  		$this->template->content->custom_forms->form_field_names = $form_field_names;


  		// If the Admin is Logged in - Allow for an edit link
  		$this->template->content->logged_in = $this->logged_in;

  		// Rebuild Header Block
  		$this->template->header->header_block = $this->themes->header_block();
  	}

	
	private function _google_analytics($google_analytics = false)
	{
		$html = "";
		if (!empty($google_analytics)) {
			$html = "<script type=\"text/javascript\">
				var gaJsHost = ((\"https:\" == document.location.protocol) ? \"https://ssl.\" : \"http://www.\");
				document.write(unescape(\"%3Cscript src='\" + gaJsHost + \"google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E\"));
				</script>
				<script type=\"text/javascript\">
				var pageTracker = _gat._getTracker(\"" . $google_analytics . "\");
				pageTracker._trackPageview();
				</script>";
		}
		return $html;
	}	
}