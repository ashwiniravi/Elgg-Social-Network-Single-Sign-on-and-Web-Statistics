<?php 
require_once(dirname(dirname(__FILE__)) . "/engine/start.php");

//forward("/pg/dashboard");
/*$query = 'select * from '. $CONFIG->dbprefix . 'users_entity';
$query_result = get_data($query);
if($query_result)
{
//echo "hello";
}

foreach($query_result as $row) {
$guid_list[]=$row->guid;
$name_list[]=$row->name;
}

print_r($name_list);
echo '<br>';
echo $name_list;*/


$blogs = get_entities("object","blog",get_loggedin_user()->getGUID());
//print_r(array_values($blogs));
$comments=array();
$loop=0;
foreach ($blogs as $value) {
$comments[$loop]=array();
$comments[$loop]['Title']=$value->title;
$comments[$loop]['Description']=$value->description;
$comments[$loop++]['Count']=$bcount=elgg_count_comments($value);;
}
function compareOrder($a, $b)
{
return $a['Count'] - $b['Count'];
}
usort($comments, 'compareOrder');
$cnt=count($comments);
//echo $cnt;
print "<h3> Top 3 commented post </h3>";
if($cnt>0){
print "<table border=1>";
print "<tr> <th> POST NAME </th> <th> Number of comments </th> </tr>";
$break=0;
for($i=$cnt-1;$i>=0;$i--){
print "<tr>";
if($break==3)
break;
print "<td>".$comments[$i]['Title']."</td>";
print "<td>".$comments[$i]['Count']."</td>";
$break++;
print "</tr>";
}
print "</table>";
}else{
print "No Blogs available.";
}

$options = array(
    'relationship' => 'friend',
    'relationship_guid' => 2,
    'inverse_relationship' => FALSE,
    'type' => 'user',
    'count' => TRUE,
);
//print_r($options);
$number = elgg_get_entities_from_relationship($options);
print "<br>";
print" <h3> Total no of friends :</h3>".$number;
print "<br>";
print" <h3> Unread Messages:</h3>";
echo sprintf(elgg_echo("you have %s unread messages"), count_unread_messages());
?>
