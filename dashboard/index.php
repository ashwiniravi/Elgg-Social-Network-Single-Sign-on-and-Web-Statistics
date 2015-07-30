
<?php
/**
 * Elgg dashboard
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 */

// Get the Elgg engine
require_once(dirname(dirname(__FILE__)) . "/engine/start.php");
global $SESSION;
// Ensure that only logged-in users can see this page

if(!($_SESSION['fb']))
{
gatekeeper();
}
// Set context and title
set_context('dashboard');
//echo get_loggedin_userid();
set_page_owner(get_loggedin_userid());

$title = elgg_echo('dashboard');
//echo $title;
// wrap intro message in a div
$intro_message = elgg_view('dashboard/blurb');

// Try and get the user from the username and set the page body accordingly
$body = elgg_view_layout('widgets',"","",$intro_message);
page_draw($title, $body);

