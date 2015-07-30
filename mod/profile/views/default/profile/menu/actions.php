<?php

	/**
	 * Elgg profile icon hover over: actions
	 * 
	 * @package ElggProfile
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2010
	 * @link http://elgg.com/
	 * 
	 * @uses $vars['entity'] The user entity. If none specified, the current user is assumed. 
	 */

	if (isloggedin()) {
		if ($_SESSION['user']->getGUID() != $vars['entity']->getGUID()) {
		
		
	
		/*$visitor_guid_value=$_SESSION['user']->getGUID();
		$time_created_value=time();
		
		//echo $time_created_value;
		$url=$_SERVER['REQUEST_URI'];
		echo $url;
		if(isset($_GET['visitorguid'])) {
			echo "counted";	
			//$profileOwner_guid_value=$vars['entity']->getGUID();
			$profileOwner_guid_value=$_GET['visitorguid'];
			$query_result=insert_data("insert into {$CONFIG->dbprefix}profile_visitors set visitor_guid = {$visitor_guid_value}, profile_owner_guid = {$profileOwner_guid_value}, time_created = '$time_created_value'");
		}
		//echo $query_result;	*/
			$ts = time();
			$token = generate_action_token($ts);
					
			if ($vars['entity']->isFriend()) {
				echo "<p class=\"user_menu_removefriend\"><a href=\"{$vars['url']}action/friends/remove?friend={$vars['entity']->getGUID()}&__elgg_token=$token&__elgg_ts=$ts\">" . elgg_echo("friend:remove") . "</a></p>";
			} else {
				echo "<p class=\"user_menu_addfriend\"><a href=\"{$vars['url']}action/friends/add?friend={$vars['entity']->getGUID()}&__elgg_token=$token&__elgg_ts=$ts\">" . elgg_echo("friend:add") . "</a></p>";
			}
		}
	}

?>
