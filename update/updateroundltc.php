<?
/**
 * @author Greedi
 * @copyright 2012
 */
 ini_set("display_errors", 1);
include('/var/www/faucet/core/wallet.php');

$rounddvk = $_POST['rounddvk'];

    $result = mysql_query("UPDATE rounddvk SET rounddvk = $rounddvk") 
    or die(mysql_error());
    
    header( 'Location: http://beprivate.info/faucet/server.php' ) ;  
?>


