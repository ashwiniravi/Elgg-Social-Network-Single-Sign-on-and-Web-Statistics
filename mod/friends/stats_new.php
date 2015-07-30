<?php 
require_once(dirname(dirname(__FILE__)) . "/engine/start.php");
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
print "<h3> Top 3 commented post </h3>";
if($cnt>0){
	print "<table border=1>";
	print "<tr> <th> POST NAME </th> <th> Number of blog_comment </th> </tr>";
	$break=0;
	for($i=$cnt-1;$i>=0;$i--){
		print "<tr>";
		if($break==3)
			break;
		print "<td>".$blog_comment[$i]['Title']."</td>";
		print "<td>".$blog_comment[$i]['Count']."</td>";
		$break++;
		print "</tr>";
	}
	print "</table>";
}else{
	print "No Blogs available.";
}

print "<a href=\"{$vars['url']}stats_comment_graph.php\"> <h6> Click here to view the Comments statistics graph </h6> </a>";


$query = " SELECT DISTINCT FROM_UNIXTIME(time_created, '%Y-%m-%d') timecreated,count(distinct e.guid) nooffriends FROM elgg_entities e JOIN elgg_entity_relationships r on r.guid_two = e.guid WHERE (r.relationship = 'friend' AND r.guid_one = '$myid') AND ((e.type = 'user')) AND (e.site_guid IN (1)) AND ( (1 = 1) and e.enabled='yes') GROUP BY FROM_UNIXTIME(time_created, '%Y-%m-%d') ORDER BY timecreated";
$query_result = get_data($query);
//var_dump($query_result);
print "</br>";
print "<h3> Friend Statisitics </h3>";
$cnt=count($query_result);
//echo $cnt;
if($query_result)
{
print "<table border=1>";
print "<tr> <th> Date </th> <th> Number of friends </th> </tr>";
foreach($query_result as $rows)
{
print "<tr>";
print "<td>".$rows->timecreated."</td>";
print "<td>".$rows->nooffriends."</td>";
print "</tr>";
}
print "</table>";
print "<a href=\"{$vars['url']}stats_friends_graph.php\"> <h6> Click here to view the friend's statistics graph </h6> </a>"; 

}
else
{
print "No Friends added in your profile";
}


$message_count = get_entities_from_metadata("toId", get_loggedin_user()->getGUID(), "object", "messages",$guid,1000);
//print_r($message_count);
$inbox=array();
$inbox['Today']=0;
//$inbox['YESTERDAY']=0;
$inbox['CurrentWeek']=0;
$inbox['CurrentMonth']=0;
$inbox['PreviousWeek']=0;
//$count_msgs['day']=0;
//$count_msgs['count'] = 0;
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
print "<h3> Message Statistics </h3>";
$cnt=count($count_msgs);
//echo $cnt;
print "<table border=1>";
print "<tr> <th> Date </th> <th> Number of Messages Received </th> </tr>";
foreach($count_msgs as $rows)
{
	print "<tr>";
	print "<td>".$rows->day."</td>";
	print "<td>".$rows->count."</td>";
	//var_dump($rows);
	//break;
	print "</tr>";
}
print "</table>";

print "<a href=\"{$vars['url']}stats_inbox_graph.php\"> <h6> Click here to view the Inbox Statistics graph </h6> </a>";


//print" <h3> Unread Messages:</h3>";
//echo sprintf(elgg_echo("you have %s messages"), count_unread_messages()); 
?>


