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
$account["name"] = "Larry Bird";
$account["sortName"] = "Bird, Larry";
$account["firstName"] = "Larry";
$account["lastName"] = "Bird";
$account["personaType"] = "Business";
$account["address"] = "125 S. Pennsylvania Street";
$account["city"] = "Indianapolis";
$account["state"] = "IN";
$account["postalCode"] = "46204";
$account["country"] = "US";
$account["email"] = "larry.bird@pacers.com";
$account["webAddress"] = "www.pacers.com";
$account["shortSalutation"] = "Larry";
$account["longSalutation"] = "Mr. Bird";
$account["envelopeSalutation"] = "Mr. Larry Bird";

// Define Phone
$phone = array();
$phone["type"] = "Voice";
$phone["number"] = "(317) 817-2500";

// Add Phone to Account (optional)
$account["phones"] = array($phone);

// Define DefinedValue #1
$dv1 = array();
$dv1["fieldName"] = "Company";
$dv1["value"] = "Indiana Pacers";

// Add persona based DefinedValue #1 to Account (optional)
$account["personaDefinedValues"] = array($dv1);

// Define DefinedValue #2
$dv2 = array();
$dv2["fieldName"] = "Gender";
$dv2["value"] = "Male";

// Define DefinedValue #3
$dv3 = array();
$dv3["fieldName"] = "Date of Birth";
$dv3["value"] = "12/7/1956";

// Add account based DefinedValue #2 and #3 to Account (optional)
$account["accountDefinedValues"] = array($dv2, $dv3);

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
