<?
$command = "SELECT * FROM rounddvk,dailytotal,round";
$q = mysql_query($command);
$ddvk = mysql_query("SELECT * FROM `dailydvk`");
$rows = mysql_num_rows($q);
$rows2 = mysql_num_rows($ddvk);
$i = 0;
while ($i < $rows) {
    $rounddvk = mysql_result($q, $i, "rounddvk");
    $dailytotal = mysql_result($q, $i, "dailytotal");
    $round = mysql_result($q, $i, "round");
    $i++;
    }
?>