<?php

// development debugging
var_dump($_POST);

// require API libraries
require_once('lib/utils.php');
require_once('lib/nusoap.php');

// Instantiate nusoap_client and call login method
$nsc = startEtapestrySession();

// Define Account
$account = array();
$account["nameFormat"] = 1;
$account["firstName"] = "Larry";
$account["lastName"] = "Bird";
$account["personaType"] = "Personal";
$account["address"] = "125 S. Pennsylvania Street";
$account["city"] = "Indianapolis";
$account["state"] = "IN";
$account["postalCode"] = "46204";
$account["country"] = "US";
$account["email"] = "larry.bird@pacers.com";

// Define Phone
$phone = array();
$phone["type"] = "Voice";
$phone["number"] = "(317) 817-2500";

// Add Phone to Account (optional)
$account["phones"] = array($phone);

// Invoke addAccount method
echo "Calling addAccount method...";
$response = $nsc->call("addAccount", array($account, false));
echo "Done<br><br>";

// Did a soap fault occur?
checkStatus($nsc);

// Output result
echo "Response: <pre>";
print_r($response);
echo "</pre>";

// Call logout method
stopEtapestrySession($nsc);
