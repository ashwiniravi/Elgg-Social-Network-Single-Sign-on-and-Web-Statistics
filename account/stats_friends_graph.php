<?php 
require_once(dirname(dirname(__FILE__)) . "/engine/start.php");
require_once ('../../jpgraph-3.0.7/src/jpgraph.php');
require_once ('../../jpgraph-3.0.7/src/jpgraph_bar.php');
$query = " SELECT DISTINCT FROM_UNIXTIME(time_created, '%Y-%m-%d') timecreated,count(distinct e.guid) nooffriends FROM elgg_entities e JOIN elgg_entity_relationships r on r.guid_two = e.guid WHERE (r.relationship = 'friend' AND r.guid_one = '2') AND ((e.type = 'user')) AND (e.site_guid IN (1)) AND ( (1 = 1) and e.enabled='yes') GROUP BY FROM_UNIXTIME(time_created, '%Y-%m-%d') ORDER BY timecreated";
$query_result = get_data($query);

//var_dump($query_result);


//$cnt=count($queryresults);
//echo $cnt;
$friends=array();
$time_created=array();
$no_of_friends=array();
$i=0;
foreach($query_result as $rows)
{
$friends[$i]=array();
$friends[$i]['timecreated']=$rows->timecreated;
$friends[$i++]['nooffriends']=$rows->nooffriends;
}
$cnt=count($friends);
//echo $cnt;
if($cnt>0){
for($i=$cnt-1;$i>=0;$i--){

	array_push($time_created, $friends[$i]['timecreated']);
	array_push($no_of_friends, intval($friends[$i]['nooffriends']));

}

}
$datax=$time_created;
$datay=$no_of_friends;
//var_dump($datax);
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
