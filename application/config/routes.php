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
$config['boston'] = 'city/local/Boston';
$config['Boston'] = 'city/local/Boston';
$config['Toronto'] = 'city/local/Toronto';
$config['toronto'] = 'city/local/Toronto';
$config['Baltimore'] = 'city/local/Baltimore';
$config['baltimore'] = 'city/local/Baltimore';
$config['Chicago'] = 'city/local/Chicago';
$config['chicago'] = 'city/local/Chicago';
$config['oakland'] = 'city/local/Oakland';
$config['Oakland'] = 'city/local/Oakland';
$config['Portland'] = 'city/local/Portland';
$config['portland'] = 'city/local/Portland';
$config['PDX'] = 'city/local/Portland';
$config['DesMoines'] = 'city/local/DesMoines';
$config['desmoines'] = 'city/local/Desmoines';
$config['DSM'] = 'city/local/DesMoines';
$config['Austin'] = 'city/local/Austin';
$config['austin'] = 'city/local/Austin';


// Action::config - Config Routes
Event::run('ushahidi_action.config_routes', $config);
