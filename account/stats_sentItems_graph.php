<?php 
require_once(dirname(dirname(__FILE__)) . "/engine/start.php");
require_once ('../../jpgraph-3.0.7/src/jpgraph.php');
require_once ('../../jpgraph-3.0.7/src/jpgraph_bar.php');

$guid=$page_owner->guid;
$message_count = get_entities_from_metadata("fromId", get_loggedin_user()->getGUID(), "object", "messages",$guid,1000);
$sentItems=array();
$sentItems['Today']=0;
//$sentItems['YESTERDAY']=0;
$sentItems['CurrentWeek']=0;
$sentItems['CurrentMonth']=0;
$sentItems['PreviousWeek']=0;
$cnt= count($message_count);
$i=0;
for($i=$cnt-1;$i>=0;$i--){
foreach ($message_count as $value) {
//print $messages->title;
$time_created=friendly_time($value->time_created);
$time_created=explode(" ",$time_created);
//print_r($time_created);
if((strpos($time_created[6],'hours') !== false) || (strpos($time_created[6],'now') !== false) || (strpos($time_created[6],'minute') !== false) || (strpos($time_created[6],'hour') !== false) || (strpos($time_created[6],'minutes') !== false)){
$sentItems['Today']+=1;
$sentItems['CurrentWeek']+=1;
$sentItems['CurrentMonth']+=1;
}
elseif($time_created[5]<'7')
{
$sentItems['CurrentWeek']+=1;
$sentItems['CurrentMonth']+=1;
}
elseif($time_created[5]<'14')
{
$sentItems['PreviousWeek']+=1;
$sentItems['CurrentMonth']+=1;
}
elseif($time_created[5]<'30')
{
$sentItems['CurrentMonth']+=1;
}
}
}
$sentItems['stats']=$sentItems['CurrentMonth'].','.$sentItems['PreviousWeek'].','.$sentItems['CurrentWeek'].','.$sentItems['Today'];
$datax=array("CurrentMonth","LastWeek","CurrentWeek","Today");
//$datay=($sentItems['stats']);
$datay=array(3,0,3,3);
//var_dump($datay);

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
