<?php 
require_once(dirname(dirname(__FILE__)) . "/engine/start.php");
require_once ('../../jpgraph-3.0.7/src/jpgraph.php');
require_once ('../../jpgraph-3.0.7/src/jpgraph_bar.php');

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
$count = array();
$title = array();
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
if($cnt>0){
$break=0;
for($i=$cnt-1;$i>=0;$i--){

	if($break==3)
		break;
	array_push($count, intval($comments[$i]['Count']));
	array_push($title, $comments[$i]['Title']);
	$break++;

}

}

//$count = array(1,2,3);
//$title = array('Jan', 'Feb', 'Mar');
$datax=$title;
$datay=$count;
// Setup the graph.
$graph = new Graph(400,300,'auto',10,true);
$graph->img->SetMargin(60,20,35,75);
$graph->SetScale("textlin");
$graph->SetMarginColor("lightblue:1.1");
$graph->SetShadow();

// Set up the title for the graph
$graph->title->Set("Bar gradient with left reflection");
$graph->title->SetMargin(8);
$graph->title->SetFont(FF_FONT1,FS_BOLD);
$graph->title->SetColor("darkred");

// Setup font for axis
$graph->xaxis->SetFont(FF_FONT1,FS_BOLD);
$graph->yaxis->SetFont(FF_FONT1,FS_BOLD);

// Show 0 label on Y-axis (default is not to show)
$graph->yscale->ticks->SupressZeroLabel(false);

// Setup X-axis labels
$graph->xaxis->SetTickLabels($datax);
//$graph->xaxis->SetLabelAngle(50);

// Create the bar pot
$bplot = new BarPlot($datay);
$bplot->SetWidth(0.6);

// Setup color for gradient fill style
$bplot->SetFillGradient("navy:0.9","navy:1.85",GRAD_LEFT_REFLECTION);

// Set color for the frame of each bar
$bplot->SetColor("white");
$graph->Add($bplot);

// Finally send the graph to the browser
$graph->Stroke();

?>
