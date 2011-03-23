<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Base Site Name
|--------------------------------------------------------------------------
|
| Name of your site/application, e.g:
|
|	My First Website
|
*/
$config['site_name'] = 'FeedMalaya';

/*
|--------------------------------------------------------------------------
| Template Option
|--------------------------------------------------------------------------
|
| Enable you to configure template option
| Default template: <your-site>/public/styles/<theme>/<filename>.html
*/

$config['template']['theme'] = 'feedmalaya2';
$config['template']['filename'] = 'index';

/*
|--------------------------------------------------------------------------
| Option Table Schema
|--------------------------------------------------------------------------
|
| Enable you to set/get option value from your database
|
*/
$config['option']['enable'] = TRUE;
$config['option']['table'] = 'feed_option';
$config['option']['attribute'] = 'option_name';
$config['option']['value'] = 'option_value';

/*
|--------------------------------------------------------------------------
| User Session/Authentication
|--------------------------------------------------------------------------
|
| Enable you to validate logged-in user
|
*/
$config['auth']['enable'] = TRUE;
$config['auth']['table'] = 'feed_user';					// Table: user main table
$config['auth']['table_meta'] = '';						// Table: user meta table (if you store meta data separately)
$config['auth']['column']['id'] = 'user_id';			// Column: user id (INT) PRIMARY KEY
$config['auth']['column']['key'] = '';					// Column: foreign key for meta user id
$config['auth']['column']['name'] = 'user_name';		// Column: user name (VARCHAR) UNIQUE
$config['auth']['column']['email'] = '';				// Column: user email (VARCHAR)
$config['auth']['column']['pass'] = 'user_profile_url';	// Column: user pass (VARCHAR) Encrypted
$config['auth']['column']['fullname'] = '';				// Column: user fullname (VARCHAR)
$config['auth']['column']['role'] = 'user_group';		// Column: user role (INT)
$config['auth']['column']['status'] = '';				// Column: user status (INT)
$config['auth']['expire'] = 0;



