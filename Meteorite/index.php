<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
  <title>Meteorite Landings</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>

<img src="MeteoriteImg.jpeg" width="300" height="150" alt="Canyon Diablo meteorite with a whistle-hole" by subarcticmike is licensed under CC BY 2.0">

  <h1>Meteorite Landings</h1>

<p>Source: <a href="https://catalog.data.gov/dataset/meteorite-landings">Data.gov</a></p>



<?php
print ("<form method=\"post\" action=\"index.php\">\n<fieldset>\n");


/*Discovery filter*/
print ("</select><br>\n");
print("<label>Discovery</label><br>\n");
if(isset($_POST['Discovery']))
{
    if($_POST['Discovery'] == 'Both'){
      $filter_discovery = 'Discovery LIKE \'%\'';
      print("<input type=\"radio\" name=\"Discovery\" value=\"Fell\">Fell<br>\n");
      print("<input type=\"radio\" name=\"Discovery\" value=\"Found\">Found<br>\n");
      print("<input type=\"radio\" name=\"Discovery\" value=\"Both\" checked>Both<br>\n");
    }
    if($_POST['Discovery'] == 'Found')
    {
      $filter_discovery = 'Discovery LIKE \'Found\'';
      print("<input type=\"radio\" name=\"Discovery\" value=\"Fell\">Fell<br>\n");
      print("<input type=\"radio\" name=\"Discovery\" value=\"Found\" checked>Found<br>\n");
      print("<input type=\"radio\" name=\"Discovery\" value=\"Both\" >Both<br>\n");
    }
    if($_POST['Discovery'] == 'Fell')
    {
      $filter_discovery = 'Discovery LIKE \'Fell\'';
      print("<input type=\"radio\" name=\"Discovery\" value=\"Fell\" checked>Fell<br>\n");
      print("<input type=\"radio\" name=\"Discovery\" value=\"Found\">Found<br>\n");
      print("<input type=\"radio\" name=\"Discovery\" value=\"Both\" >Both<br>\n");
    }
}
else
{
  $filter_discovery = 'Discovery LIKE \'%\'';
  print("<input type=\"radio\" name=\"Discovery\" value=\"Fell\">Fell<br>\n");
  print("<input type=\"radio\" name=\"Discovery\" value=\"Found\">Found<br>\n");
  print("<input type=\"radio\" name=\"Discovery\" value=\"Both\" checked>Both<br>\n");
}

/*Year Filter*/
print("<label>Year</label><br>\n");
if(filter_var($_POST['Yearmin'], FILTER_VALIDATE_INT)==False){
  $yearmin = 860;
}
else{
  $yearmin=$_POST['Yearmin'];
}
print("<p class=\"minmax\">Minimum</p>");
print ("<input type=\"text\" name=\"Yearmin\" value=\"".$yearmin."\"><br>\n");
if(filter_var($_POST['Yearmax'], FILTER_VALIDATE_INT)==False){
  $yearmax = 2013;
}
else{
  $yearmax=$_POST['Yearmax'];
}
print("<p class=\"minmax\">Maximum</p>");
print ("<input type=\"text\" name=\"Yearmax\" value=\"".$yearmax."\"><br>\n");
$filter_year= 'Year <='.$yearmax.' AND Year >='.$yearmin;


/*Mass Filter*/
print("<label>Mass (g)</label><br>\n");
if(filter_var($_POST['Massmin'], FILTER_VALIDATE_FLOAT)==False){
  $massmin = 0.0;
}
else{
  $massmin=$_POST['Massmin'];
}
print("<p class=\"minmax\">Minimum</p>");
print ("<input type=\"text\" name=\"Massmin\" value=\"".$massmin."\"><br>\n");
if(filter_var($_POST['Massmax'], FILTER_VALIDATE_FLOAT)==False){
  $massmax = 60000000.0;
}
else{
  $massmax=$_POST['Massmax'];
}
print("<p class=\"minmax\">Maximum</p>");
print ("<input type=\"text\" name=\"Massmax\" value=\"".$massmax."\"><br>\n");
$filter_mass= 'Mass <='.$massmax.' AND Mass >='.$massmin;

/*Sorted by filter*/
print ("</select><br>\n");
print("<label>Sorted by</label><br>\n");
if(isset($_POST['Sort']))
{
    if($_POST['Sort'] == 'Name'){
      $sort_filter = 'ORDER BY Name';
      print("<input type=\"radio\" name=\"Sort\" value=\"Year\">Year<br>\n");
      print("<input type=\"radio\" name=\"Sort\" value=\"Mass\">Mass<br>\n");
      print("<input type=\"radio\" name=\"Sort\" value=\"Name\" checked>Name<br>\n");
    }
    if($_POST['Sort'] == 'Mass')
    {
      $sort_filter = 'ORDER BY Mass';
      print("<input type=\"radio\" name=\"Sort\" value=\"Year\">Year<br>\n");
      print("<input type=\"radio\" name=\"Sort\" value=\"Mass\" checked>Mass<br>\n");
      print("<input type=\"radio\" name=\"Sort\" value=\"Name\">Name<br>\n");
    }
    if($_POST['Sort'] == 'Year')
    {
      $sort_filter = 'ORDER BY Year';
      print("<input type=\"radio\" name=\"Sort\" value=\"Year\" checked>Year<br>\n");
      print("<input type=\"radio\" name=\"Sort\" value=\"Mass\">Mass<br>\n");
      print("<input type=\"radio\" name=\"Sort\" value=\"Name\">Name<br>\n");
    }
}
else
{
  $sort_filter = 'ORDER BY Name';
  print("<input type=\"radio\" name=\"Sort\" value=\"Year\">Year<br>\n");
  print("<input type=\"radio\" name=\"Sort\" value=\"Mass\">Mass<br>\n");
  print("<input type=\"radio\" name=\"Sort\" value=\"Name\" checked>Name<br>\n");
}
/*Order filter*/
print ("</select><br>\n");
print("<label>Order</label><br>\n");
if(isset($_POST['Order'])){
    if ($_POST['Order'] == 'Ascending'){
      $order_filter='ASC';
      print("<input type=\"radio\" name=\"Order\" value=\"Ascending\" checked>Ascending<br>\n");
      print("<input type=\"radio\" name=\"Order\" value=\"Descending\">Descending<br>\n");
    }
    if ($_POST['Order'] == 'Descending'){
      $order_filter='DESC';
      print("<input type=\"radio\" name=\"Order\" value=\"Ascending\">Ascending<br>\n");
      print("<input type=\"radio\" name=\"Order\" value=\"Descending\" checked>Descending<br>\n");
    }

}
else{
  $order_filter='ASC';
  print("<input type=\"radio\" name=\"Order\" value=\"Ascending\" checked>Ascending<br>\n");
  print("<input type=\"radio\" name=\"Order\" value=\"Descending\">Descending<br>\n");
}


$finalQuery = 'SELECT * FROM meteorite WHERE ('.$filter_discovery.') AND ('.$filter_year.') AND ('.$filter_mass.') '.$sort_filter.' '.$order_filter.'';

print ("<input type=\"submit\" value=\"Filter\">\n");
print ("\n<input type=\"button\" onclick=\"window.location.replace('index.php')\" value=\"Reset\"><br>\n");
print ("</fieldset>\n</form>\n");

print "<!-- ".$finalQuery."; -->\n";
$dbfile = new PDO('sqlite:meteorite.db');
$query = $dbfile->query($finalQuery);
$results = $query->fetchAll();
$dbfile = null;

$resultsCount = count($results);
if ($resultsCount <= 1)
{
  print("<p class=\"resultsCount\"><br>$resultsCount result</p>\n");
}
else
{
  print("<p class=\"resultsCount\"><br>$resultsCount results</p>\n");
}

print("<table>\n");
print("<tr><th>Name</th><th>ID</th><th>Mass (g)</th><th>Discovery</th><th>Year</th><th>Latitude</th><th>Longitude</th><th>See location</th></tr>\n");

foreach ($results as $value)
{
  $link='https://www.google.com/maps?q='.$value['Latitude'].','.$value['Longitude'].'&ll='.$value['Latitude'].','.$value['Longitude'].'&z=5';
  print("<tr><td>".$value['Name']."</td><td>".$value['ID']."</td><td>".$value['Mass']."</td><td>".$value['Discovery']."</td><td>".$value['Year']."</td><td>".$value['Latitude']."</td><td>".$value['Longitude']."</td><td><a href=\"$link\" target='_blank'>Click to see</a></td></tr>\n");
}
print("</table>\n");
?>
<!--</table>-->
</body>
</html>
