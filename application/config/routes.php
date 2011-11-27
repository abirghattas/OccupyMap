<?php defined('SYSPATH') or die('No direct access allowed.');
/**
 * @package  Core
 *
 * Sets the default route to "welcome"
 */

$config['_default'] = 'main';
$config['feed/atom'] = 'feed/index/atom';

$config['Philadelphia'] = 'city/local/Philadelphia';
$config['Philly'] = 'city/local/Philadelphia';
$config['philadelphia'] = 'city/local/Philadelphia';
$config['philly'] = 'city/local/Philadelphia';

$config['LA'] = 'city/local/Los Angeles';
$config['LosAngeles'] = 'city/local/Los Angeles';
$config['la'] = 'city/local/Los Angeles';
$config['losangeles'] = 'city/local/Los Angeles';


$config['NYC'] = 'city/local/New York';
$config['nyc'] = 'city/local/New York';
$config['NewYork'] = 'city/local/New York';
$config['newyork'] = 'city/local/New York';


// Action::config - Config Routes
Event::run('ushahidi_action.config_routes', $config);