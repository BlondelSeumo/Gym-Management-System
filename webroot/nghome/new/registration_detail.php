<?php
$lastid = $this->GymMember->find("all",["fields"=>"id"])->last();
$lastid = ($lastid != null) ? $lastid->id + 1 : 01 ;
$member = $this->GymMember->newEntity();
$m = date("d");
$y = date("y");
$prefix = "M".$lastid;
$member_id = $prefix.$m.$y;
echo "sffsf";
?>