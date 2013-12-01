<?php

/**
 * @author Greedi
 * @copyright 2012
 */
//error_reporting(E_ALL);
include ('core/banned.php');
include_once ("core/wallet.php");
include_once ('templates/header.php');
//include ('core/dnsbl.php');

$donaddress = $btclient->getaccountaddress($don_faucet);
$don = $btclient->getbalance($don_faucet, 0);

?>
<div class="row">
<div class="span10">
<center>
<br />
<?php
$ip = $_SERVER['REMOTE_ADDR'];
$challengeValue = $_POST['adscaptcha_challenge_field'];
$responseValue = $_POST['adscaptcha_response_field'];
$remoteAddress = $_SERVER["REMOTE_ADDR"];
function ordinal($a)
{
    $b = abs($a);
    $c = $b % 10;
    $e = (($b % 100 < 21 && $b % 100 > 4) ? 'th' : (($c < 4) ? ($c < 3) ? ($c < 2) ?
        ($c < 1) ? 'th' : 'st' : 'nd' : 'rd' : 'th'));
    return $a . $e;
}
if (strtolower(ValidateCaptcha($adscaptchaID, $adsprivkey, $challengeValue, $responseValue,
    $remoteAddress)) == "true") {
    $isvalid = $btclient->validateaddress($_POST['DVK']);
    if ($isvalid['isvalid'] != '1') {

        echo "Invalid Address: {$_POST['DVK']}";
        echo "</center></div>";
        include ('templates/sidebar.php');
        include ('templates/footer.php');
        die();
    } else {
    $dvkaddress = $_POST['DVK'];
            mysql_query("INSERT INTO dailydvk (dvkaddress, ip)
    SELECT * FROM (SELECT '$dvkaddress', '$ip') AS tmp
    WHERE NOT EXISTS (
    SELECT ip FROM dailydvk WHERE ip = '$ip'
    ) LIMIT 1;") or die(mysql_error());

            mysql_query("INSERT INTO subtotal (dvkaddress, ip) VALUES('$dvkaddress', '$ip' ) ") or
                die(mysql_error());
            $command = "SELECT * FROM dailydvk";
            $q = mysql_query($command);
            $rows = mysql_num_rows($q);
            $entries_needed = 150;
            if ($rows > $entries_needed) {
                $command = "SELECT * FROM rounddvk";
                $q = mysql_query($command);
                $res = mysql_fetch_array($q);
                $list = mysql_query("SELECT * FROM dailydvk");

                $coins_in_account = $btclient->getbalance("SendOut", 0);
                if ($coins_in_account >= ($res['rounddvk'] * $rows)) {
                    while ($listw = mysql_fetch_array($list)) {
                        $btclient->sendfrom("SendOut", $listw['btcaddres'], $res['rounddvk']);
                    }
                    $n = ordinal(mysql_num_rows($list));
                    echo srsnot("Congratulations, you were the {$n} in the round, the round has been reset and payouts have been sent.");
                    mysql_query("TRUNCATE dailydvk");
                    mysql_query("UPDATE round set round=round+1");
                    $totalc = $res['rounddvk'] * $rows;
                    mysql_query("UPDATE dailytotal set total=total+{$totalc}");
                    echo "</center></div>";
                    include ('templates/sidebar.php');
                    include ('templates/footer.php');
                    die();
                } else {
                    echo srserr("Uh oh, looks like we haven't got enough donations to pay out. The round will continue until there's enough to pay out.");
                    echo "</center></div>";
                    include ('templates/sidebar.php');
                    include ('templates/footer.php');
                    die();
                }
            }

            //echo "printan.";

            //echo "printed.";
            // echo "</table>";
            echo "You will get your DVK at the end of this round<br />There are $rows submitted addresses in this round!<br>";
            echo "<br>If you want to donate to the Faucet: $donaddress (recv: $don)";
        }
    
} else { // Wrong answer, you may display a new AdsCaptcha and add an error message
    echo srserr("INVALID CAPTCHA. <a href='/index.php'>Go back</a>");
}
?>
</center>
</div>
<?php
include ('templates/sidebar.php');
include ('templates/footer.php');
?>




