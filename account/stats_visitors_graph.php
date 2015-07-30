<?php 
require_once(dirname(dirname(__FILE__)) . "/engine/start.php");
require_once ('../../jpgraph-3.0.7/src/jpgraph.php');
require_once ('../../jpgraph-3.0.7/src/jpgraph_bar.php');
$myid=get_loggedin_user()->getGUID();
$query="select count(visitor_guid) count,FROM_UNIXTIME(time_created, '%Y-%m-%d') timecreated from elgg_profile_visitors where profile_owner_guid='$myid' group by timecreated";
$query_result=get_data($query);
$visitors_date=array();
$visitors_count=array();
foreach($query_result as $rows)
{
array_push($visitors_date,$rows->timecreated);
array_push($visitors_count,$rows->count);
}
$datax=$visitors_date;
$datay=$visitors_count;
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
