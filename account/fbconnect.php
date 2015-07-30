<?php
require_once(dirname(dirname(__FILE__)) . "/engine/start.php");
$area1 = elgg_view_title(elgg_echo('friends:new'));
global $SESSION;
$_SESSION['fb'] = 1;
$username = $_GET["user_username"];
$name = $_GET["user_name"];
$email = $_GET["user_mail"];
$password = 'dummy';

$query="SELECT username FROM elgg_users_entity where username='$username'";
$query_result=get_data($query);
if(!($query_result))
{
$guid = register_user($username, $password, $name, $email, false);
}		
forward("pg/dashboard/");

?>
