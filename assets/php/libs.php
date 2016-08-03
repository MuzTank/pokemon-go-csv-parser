<?php

function getCsvFiles($locationpath = "csv") {
  $res = glob($locationpath."/*.csv", GLOB_BRACE);
  foreach($res as &$item) {
    $item = str_replace("csv/", "", $item);
  }
  return $res;
}
function human_filesize($bytes, $decimals = 2) {
  $sz = "BKMGTP";
  $factor = floor((strlen($bytes) - 1) / 3);
  return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor];
}
function getCurrentDirIndex() {
  $url = $_SERVER["REQUEST_URI"]; //returns the current URL
  $parts = explode("/", $url);
  $dir = $_SERVER["SERVER_NAME"];
  for ($i = 0; $i < count($parts) - 1; $i++) {
    $dir .= $parts[$i] . "/";
  }
  return "//".$dir;
}
?>