<?php
class City_Controller extends Main_Controller {

    public function local($city) {
        $city = urldecode($city);
        $city = ORM::factory("city")->where("city",$city)->find();
        Kohana::config_set('settings.local_city',$city);
        Kohana::config_set('settings.default_lat',$city->city_lat);
        Kohana::config_set('settings.default_lon',$city->city_lon);
        $this->session->set('city_local',array("city"=>$city->city,
            "city_lat"=>$city->city_lat,"city_lon"=>$city->city_lon));
    		
//redirect, don't run controller method - need to get all the plugins + hooks
//add the hash so that the cached page is unique for that city
        url::redirect(url::site()."#".$city->city);
    }
   
    public function none() {
        $this->session->set('city_local',null);
        url::redirect(url::site());

    }
}
?>
