<?php
/**
 * Pokemon GO Bot CSV Parser
 * Precaution! The $_GET is not sanitized!
 * Note: php_short_tag must be on.
 *
 * @author Edwin (www.yohanesedwin.com)
**/
if(empty($_GET["file"]))
  $section = "list";
else {
  $section = "view";
  $filename = $_GET["file"];
}
include_once("assets/php/libs.php");
$xx = getCurrentDirIndex();
?>
<!DOCTYPE HTML>
<html>
<head>
<title>Pokemon GO CSV Parser</title>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<script src="assets/js/jquery.tablesorter.min.js"></script>
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<link rel="stylesheet" type="text/css" href="assets/css/styles.css">
</head>
<body>
<div id="container">
<?php
if($section == "list") {
  $csvlist = getCsvFiles();
?>
  <h1>CSV Files List</h1>
  <div id="listingcontainer">
    <div id="listingheader"> 
      <div id="headerfile"><a href="javascript:void(0)">File</a></div>
      <div id="headersize"><a href="javascript:void(0)">Size</a></div>
      <div id="headermodified"><a href="javascript:void(0)">Last Modified</a></div>
    </div>
    <div id="listing">
      <?php
      $i = 0;
      foreach($csvlist as $item) {
        $filesize = filesize("csv/".$item);
        $filemtime = filemtime("csv/".$item);
        if(strlen($item) > 40) $sitem = substr($item, 0, 40)."..."; else $sitem = $item;
        $checkerclass = ($i%2 ? "w":"b");
        echo "<div class=\"$checkerclass\"><a class=\"pointerhand\" href=\"?file=$item\" title=\"$item\">$sitem</a><span><em>".human_filesize($filesize)."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".date("F d Y H:i:s", $filemtime)."</em></span></div>";
        $i++;
      }
      ?>
    </div>
  </div>
<?php
}
else if($section == "view") {
  if(file_exists("csv/".$filename)) {
    $pokelist = array_map("str_getcsv", file("csv/".$filename));
    array_walk($pokelist, function(&$a) use ($pokelist) {
      $a = array_combine($pokelist[0], $a);
    });
    array_shift($pokelist);
?>
  <h1><?= $filename; ?></h1>
  <div id="breadcrumbs">
    <a href="<?= getCurrentDirIndex() ?>">Index</a> - 
    <a href="javascript:void(0)" id="bread_showall">Show All</a> - 
    IV > <input type="text" class="searchiv" value="100" maxlength="3" /> <button id="bread_perfectiv">Search</button>
  </div>
  <table class="table table-striped table-bordered" style="display:none;">
    <thead>
      <tr>
        <th class="pointerhand">ID</th>
        <th class="pointerhand">Pokemon Name</th>
        <th class="pointerhand">CP</th>
        <th class="pointerhand">IV</th>
        <th class="pointerhand">HP</th>
        <th class="pointerhand">Atk</th>
        <th class="pointerhand">Def</th>
        <th class="pointerhand">Stamina</th>
        <th class="pointerhand">Candy</th>
      </tr>
    </thead>
    <tbody>
<?php
    foreach($pokelist as $key => $val) {
      if((int)$val["IV Perfection in %"] == 100) $classiv = "info"; else $classiv = "";
?>
      <tr class="<?= $classiv ?>">
        <td><?= $val["PokemonID"] ?></th>
        <td><img src="assets/img/pokemon_<?= $val["PokemonID"] ?>.png" /><?= $val["Name"] ?></th>
        <td><?= explode(" / ", $val["CP / MaxCP"])[0] ?></th>
        <td><?= $val["IV Perfection in %"] ?>%</th>
        <td><?= $val["HP"] ?></th>
        <td><?= $val["Attk"] ?></th>
        <td><?= $val["Def"] ?></th>
        <td><?= $val["Stamina"] ?></th>
        <td><?= $val["Familie Candies"] ?></th>
      </tr>
<?php
    }
?>
    </tbody>
  </table>
<?php
  }
  else {
    echo "<h1>File '$filename' does not exists!</h1><div id=\"breadcrumbs\"><a href=\"".getCurrentDirIndex()."\">Index</a></div><div id=\"listingcontainer\"><span class=\"error\">[Error] Could not parse the csv.</span></div>";
  }
}
?>
</div>
<script>
$("document").ready(function() {
  $("table").tablesorter({sortList: [[0,0], [3,1]]});
  $("table").show();
  $("#bread_showall").click(function(e) {
    e.preventDefault();
    $("table tbody tr").show();
  });
  $("#bread_perfectiv").click(function(e) {
    e.preventDefault();
    var morethan = parseInt($(".searchiv").val());
    if(isNaN(morethan) || morethan < 1 || morethan > 100)
      alert("Only Integer allowed (between 1 - 100)!");
    else {
      $("table tbody tr").hide();
      $("table tbody tr.info").show();
    }
  });
});
</script>
</body>
</html>