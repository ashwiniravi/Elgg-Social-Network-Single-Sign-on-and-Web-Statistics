<?php 
require_once(dirname(dirname(__FILE__)) . "/engine/start.php");
require_once ('../../jpgraph-3.0.7/src/jpgraph.php');
require_once ('../../jpgraph-3.0.7/src/jpgraph_bar.php');
$guid=$page_owner->guid;
$myid=get_loggedin_user()->getGUID();

$message_count = get_entities_from_metadata("toId", get_loggedin_user()->getGUID(), "object", "messages",$guid,1000);
//print_r($message_count);
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
$days=array();
$counts=array();
foreach($count_msgs as $rows)
{
	
	array_push($days,$rows->day);
	array_push($counts,$rows->count);
	
}

$datax=$days;
$datay=$counts;

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
