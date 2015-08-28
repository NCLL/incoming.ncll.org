<?php

/**
 * Utility method to determine if a NuSoap fault or error occurred.
 * If so, output any relevant info and stop the code from executing.
 */
function checkStatus($nsc, $ajax)
{
  if ($nsc->fault || $nsc->getError())
  {
    if (!$nsc->fault)
    {
      echo "Error: ".$nsc->getError();
    }
    else
    {
      if ($ajax) {
        header('HTTP/1.1 400 Bad Request');
        header('Content-Type: application/json; charset=UTF-8');
        $errors = array();
        $errors['faultCode'] = $nsc->faultcode;
        $errors['faultString'] = $nsc->faultstring;
        echo json_encode($errors);
      }
      else
      {
        echo "Fault Code: ".$nsc->faultcode."<br>";
        echo "Fault String: ".$nsc->faultstring."<br>";
      }
    }
    exit;
  }
}

/**
 * Start an eTapestry API session by instantiating a
 * nusoap_client instance and calling the login method.
 */
function startEtapestrySession( $api_version, $debugging )
{
  // Set login details and initial endpoint
  require('authentication-eTap.php');
  if ($api_version == 2)
  {
    $endpoint = "https://sna.etapestry.com/v2messaging/service?WSDL";
  } else {
    $endpoint = "https://sna.etapestry.com/v3messaging/service?WSDL";
  }

  // Instantiate nusoap_client
  if ($debugging)
  {
    echo "Establishing NuSoap Client...";
  }
  $nsc = new nusoap_client($endpoint, true);
  if ($debugging)
  {
      echo "Done\n\n";
  }

  // Did an error occur?
  checkStatus($nsc);

  // Invoke login method
  if ($debugging)
  {
    echo "Calling login method...";
  }
  $newEndpoint = $nsc->call("login", array($loginId, $password));
  if ($debugging)
  {
    echo "Done\n\n";
  }

  // Did a soap fault occur?
  checkStatus($nsc);

  // Determine if the login method returned a value...this will occur
  // when the database you are trying to access is located at a different
  // environment that can only be accessed using the provided endpoint
  if ($newEndpoint != "")
  {
    echo "New Endpoint: $newEndpoint\n\n";

    // Instantiate nusoap_client with different endpoint
    echo "Establishing NuSoap Client with new endpoint...";
    $nsc = new nusoap_client($newEndpoint, true);
    echo "Done\n\n";

    // Did an error occur?
    checkStatus($nsc);

    // Invoke login method
    echo "Calling login method...";
    $nsc->call("login", array($loginId, $password));
    echo "Done\n\n";

    // Did a soap fault occur?
    checkStatus($nsc);
  }

  // Output results
  if ($debugging)
  {
    echo "Login Successful\n\n";
  }

  return $nsc;
}

/**
 * Start an eTapestry API session by calling the logout
 * method given a nusoap_client instance.
 */
function stopEtapestrySession($nsc, $debugging)
{
  // Invoke logout method
  if ($debugging)
  {
    echo "Calling logout method...";
  }
  $nsc->call("logout");
  if ($debugging)
  {
    echo "Done\n\n";
  }
}

/**
 * Take a United States formatted date (mm/dd/yyyy) and
 * convert it into a date/time string that NuSoap requires.
 */
function formatDateAsDateTimeString($dateStr)
{
  if ($dateStr == null || $dateStr == "") return "";
  if (substr_count($dateStr, "/") != 2) return "[Invalid Date: $dateStr]";

  $separator1 = stripos($dateStr, "/");
  $separator2 = stripos($dateStr, "/", $separator1 + 1);

  $month = substr($dateStr, 0, $separator1);
  $day = substr($dateStr, $separator1 + 1, $separator2 - $separator1 - 1);
  $year = substr($dateStr, $separator2 + 1);

  return ($month > 0 && $day > 0 && $year > 0) ? date(DATE_ATOM, mktime(0, 0, 0, $month, $day, $year)) : "[Invalid Date: $dateStr]";
}

?>
