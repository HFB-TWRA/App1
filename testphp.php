#!/usr/bin/php
<?php

$url="http://aa.usno.navy.mil/rstt/onedaytable?ID=AA&year=2015&month=10&day=7&state=TN&place=Memphis";

$content = file_get_contents($url);

echo $content;

?>
