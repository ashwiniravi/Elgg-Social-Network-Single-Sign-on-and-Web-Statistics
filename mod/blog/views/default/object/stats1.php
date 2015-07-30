<?php
echo "baby";
$rows=get_data("SELECT 'title' FROM {$dbprefix}object_entity where guid=16");
echo $rows->title;
?>
