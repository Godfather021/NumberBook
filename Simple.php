<?php
/**
 * Find numbers and names | Arabs | API PHP .
 *
 * @package : NumberBook;
 * @author : Saleh Bin Homud;
 * @version : 1.0;
 * @link : https://github.com/Saleh7;
 */

$Key    = 'test1day1X'; // API keys | The key here is https://telegram.me/Pain7
$Search = 'test'; // Numbers OR Names
$Cntry  = ''; // 966 or 967 .. | Cntry is not currently enabled
$Count  = ''; // Control Number of Results | Count is not currently enabled

$uRLApi = 'https://api.igeek.io/v1/'; //
$Results = @file_get_contents("$uRLApi?key=$Key&str=$Search");
print_r($Results);

/* Results ..
status:200 | Success
status:304 | Empty String
status:404 | Sorry, no results
status:400 | The key is not valid
*/

 ?>
