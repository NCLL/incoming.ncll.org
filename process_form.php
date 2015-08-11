<?php

// development debugging
$debug = true;
if ($debug) {var_dump($_POST);}

// require API libraries
require_once('lib/utils.php');
require_once('lib/nusoap.php');
require_once('lib/authentication.php');

// Set login details and initial endpoint
$endpoint = "https://sna.etapestry.com/v2messaging/service?WSDL";

// Instantiate nusoap_client
if ($debug) {echo "Establishing NuSoap Client...";}
$nsc = new nusoap_client($endpoint, true);
if ($debug) {echo "Done<br><br>";}

// Did an error occur?
checkStatus($nsc);

// Invoke login method
if ($debug) {echo "Calling login method...";}
$newEndpoint = $nsc->call("login", array($loginId, $password));
if ($debug) {echo "Done<br><br>";}

// Did a soap fault occur?
checkStatus($nsc);

// Determine if the login method returned a value...this will occur
// when the database you are trying to access is located at a different
// environment that can only be accessed using the provided endpoint
if ($newEndpoint != "") {
  if ($debug) {echo "New Endpoint: $newEndpoint<br><br>";}

  // Instantiate nusoap_client with different endpoint
  if ($debug) {echo "Establishing NuSoap Client with new endpoint...";}
  $nsc = new nusoap_client($newEndpoint, true);
  if ($debug) {echo "Done<br><br>";}

  // Did an error occur?
  checkStatus($nsc);

  // Invoke login method
  if ($debug) {echo "Calling login method...";}
  $nsc->call("login", array($loginId, $password));
  if ($debug) {echo "Done<br><br>";}

  // Did a soap fault occur?
  checkStatus($nsc);
}

// Output results
if ($debug) {echo "Login Successful<br><br>";}

// Call logout method
stopEtapestrySession($nsc);
