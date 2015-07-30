<?php 
require_once(dirname(dirname(__FILE__)) . "/engine/start.php");

$guid=$page_owner->guid;
$myid=get_loggedin_user()->getGUID();

$area1 = elgg_view_title(elgg_echo('friends:new'));
		
	    //$area2 = elgg_view('friends/forms/edit', array('friends' => get_user_friends($_SESSION['user']->getGUID(),"",9999)));
				
	// Format page
$body = "";
$guid=$page_owner->guid;
$myid=get_loggedin_user()->getGUID();
//print $myid;

$blog_comment=array();
$i=0;
$blog_entity = get_entities("object","blog",get_loggedin_user()->getGUID());
foreach ($blog_entity as $rows) {
$blog_comment[$i]=array();
$blog_comment[$i]['Title']=$rows->title;
$blog_comment[$i]['Description']=$rows->description;
$blog_comment[$i++]['Count']=elgg_count_comments($rows);
}
function compareOrder($val1, $val2)
{
return $val1['Count'] - $val2['Count'];
}
usort($blog_comment, 'compareOrder');
$cnt=count($blog_comment);
//echo $cnt;
$body .= "<h3> Top 3 commented post </h3>";
if($cnt>0){
	$body .= "<table border=1>";
	$body .=  "<tr> <th><b> Post Name &nbsp&nbsp</b></th> <th> <b>Number of blog_comment</b> </th> </tr>";
	$break=0;
	for($i=$cnt-1;$i>=0;$i--){
		$body .= "<tr>";
		if($break==3)
			break;
		$body .= "<td><center>".$blog_comment[$i]['Title']."</center></td>";
		$body .= "<td><center>".$blog_comment[$i]['Count']."</center></td>";
		$break++;
		$body .= "</tr>";
	}
	$body .= "</table>";
}else{
	$body .= "<h3>No Blogs available.</h3>";
}

$body .= "<a href=\"{$vars['url']}stats_comment_graph.php\"> <h6> Click here to view the Comments statistics graph </h6> </a>";



$query = " SELECT DISTINCT FROM_UNIXTIME(time_created, '%Y-%m-%d') timecreated,count(distinct e.guid) nooffriends FROM elgg_entities e JOIN elgg_entity_relationships r on r.guid_two = e.guid WHERE (r.relationship = 'friend' AND r.guid_one = '$myid') AND ((e.type = 'user')) AND (e.site_guid IN (1)) AND ( (1 = 1) and e.enabled='yes') GROUP BY FROM_UNIXTIME(time_created, '%Y-%m-%d') ORDER BY timecreated";
$query_result = get_data($query);
//var_dump($query_result);
$body .= "</br>";
$body .= "<h3> Friend Statisitics </h3>";
$cnt=count($query_result);
//echo $cnt;
if($query_result)
{
$body .= "<table border=1>";
$body .= "<tr> <th> <b> Date </b> &nbsp&nbsp </th> <th> <b> Number of friends </b> </th> </tr>";
foreach($query_result as $rows)
{
$body .= "<tr>";
$body .= "<td> <center>".$rows->timecreated."</center></td>";
$body .= "<td> <center>".$rows->nooffriends."</center> </td>";
$body .= "</tr>";
}
$body .= "</table>";
$body .= "<a href=\"{$vars['url']}stats_friends_graph.php\"> <h6> Click here to view the friend's statistics graph </h6> </a>"; 

}
else
{
$body .= "No Friends added in your profile";
}

$message_count = get_entities_from_metadata("toId", get_loggedin_user()->getGUID(), "object", "messages",$guid,1000);
//print_r($message_count);
$inbox=array();
$inbox['Today']=0;
$cnt= count($message_count);
//print $cnt;
$i=0;
$day="";
foreach ($message_count as $value) {
	//print $messages->title;
	$time_created=friendly_time($value->time_created);
	$time_created=explode(" ",$time_created);
	$day = substr($time_created[1],7).'-'.$time_created[2].'-'.$time_created[3];
	if($count_msgs[$day]) {
		$count_msgs[$day]->day = $day;		
		$count_msgs[$day]->count++;
	} else {
		$count_msgs[$day]->day = $day;
	   $count_msgs[$day]->count =1;
	}
}
//print_r($count_msgs);
$body .= "</br>";

$body .= "<h3> Message Statistics </h3>";
$cnt=count($count_msgs);
//echo $cnt;
if($cnt>0)
{
$body .= "<table border=1>";
$body .= "<tr> <th> <b> Date </b> &nbsp&nbsp </th> <th> <b> Number of Messages Received </b> </th> </tr>";
foreach($count_msgs as $rows)
{
	$body .= "<tr>";
	$body .= "<td> <center>".$rows->day."</center></td>";
	$body .= "<td> <center>".$rows->count."</center></td>";
	$body .= "</tr>";
}
$body .= "</table>";

$body .= "<a href=\"{$vars['url']}stats_inbox_graph.php\"> <h6> Click here to view the Inbox Statistics graph </h6> </a>";
$body .= "<a href=\"{$vars['url']}stats_inbox_graph_bkp.php\"> <h6> Click here to view the Weekly and Monthly Statistics graph </h6> </a>";
}
else
{
$body .= "No messages received";
}

$query="select count(visitor_guid) count,FROM_UNIXTIME(time_created, '%Y-%m-%d') timecreated from elgg_profile_visitors where profile_owner_guid='$myid' group by timecreated";
$query_result=get_data($query);
$body .= "</br>";

$body .= "<h3> Profile Visitors Statistics </h3>";

if($query_result)
{
$body .= "<table border=1>";
$body .= "<tr> <th> <b> Date </b> &nbsp&nbsp </th> <th> <b> Visitor count </b> </th> </tr>";
foreach($query_result as $rows)
{

$body .= "<tr>";
	$body .= "<td> <center>".$rows->timecreated."</center></td>";
	$body .= "<td> <center>".$rows->count."</center></td>";
	$body .= "</tr>";
}
$body .= "</table>";
$body .= "<a href=\"{$vars['url']}stats_visitors_graph.php\"> <h6> Click here to view the Profile Visitors Statistics graph </h6> </a>";
}
else
{
$body .="<h6> No profile visitors </h6>";
}
 page_draw("Stats", $body);

?>


