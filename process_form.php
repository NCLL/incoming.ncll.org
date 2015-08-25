<?php
// set timezone to avoid errors
date_default_timezone_set( 'America/New_York' );

// sanitize input before doing anything else
sanitize($_POST);

// development debugging
var_dump($_POST);

// variables
$cost = 35;

// require API libraries
require_once('lib/utils.php');
require_once('lib/nusoap.php');

// instantiate nusoap_client and call login method
$nsc = startEtapestrySession();

// define info
$account = array();
$account["nameFormat"] = 1;
$account["firstName"] = $_POST['firstName'];
$account["lastName"] = $_POST['lastName'];
$account["personaType"] = "Personal";
$account["address"] = $_POST['address'];
$account["address2"] = $_POST['address2'];
$account["city"] = $_POST['city'];
$account["state"] = $_POST['state'];
$account["postalCode"] = $_POST['postalCode'];
$account["country"] = $_POST['country'];
$account["email"] = $_POST['email'];
$phone = array();
$phone["type"] = $_POST['phoneType'];
$phone["number"] = $_POST['phoneNumber'];
$account["phones"] = array($phone);

// set up array to check for duplicates
$duplicates["name"] = $account["firstName"] . $account["lastName"];
$duplicates["address"] = $account["address"];
$duplicates["email"] = $account["email"];
$duplicates["accountRoleTypes"] = 1;
$duplicates["allowEmailOnlyMatch"] = false;

// check for duplicates
echo "Calling for duplicates...";
$checkDuplicatesResponse = $nsc->call( "getDuplicateAccounts", array( $duplicates ) );
echo "Done<br/><br/>";

// did a soap fault occur?
checkStatus($nsc);

// Output result
echo "checkDuplicatesResponse: <pre>";
print_r($checkDuplicatesResponse);
echo "</pre>";

// if no response from duplicates, add a new account
if ( count( $checkDuplicatesResponse ) !== 1 ) {
    echo "Calling addAccount method...";
    $addAccountResponse = $nsc->call( "addAccount", array( $account, false ) );
    echo "Done<br><br>";

    // did a soap fault occur?
    checkStatus($nsc);

    // Output result
    echo "addAccount Response: <pre>";
    print_r($addAccountResponse);
    echo "</pre>";
}

// define gift
$trans = array();
// use account returned from duplicates check if only one exists, else use the newly-created account
if ( count( $checkDuplicatesResponse ) === 1 ) {
    $trans["accountRef"] = $checkDuplicatesResponse;
} else {
    $trans["accountRef"] = $addAccountResponse;
}
$trans["fund"] = "General";
$trans["amount"] = $cost;

// define object name (*)
$trans["eTapestryObjectName"] = "Gift";

// define credit card info
$cc = array();
$cc["number"] = $_POST['cardnumber'];
$cc["expirationMonth"] = $_POST['cc-exp-month'];
$cc["expirationYear"] = $_POST['cc-exp-year'];

// define valuable
$valuable = array();
$valuable["type"] = 3;
$valuable["creditCard"] = $cc;

// add valuable to gift
$trans["valuable"] = $valuable;

// define processTransactionRequest
$request = array();
$request["transaction"] = $trans;

// invoke processTransaction method
echo "Calling processTransaction method...";
$processTransactionResponse = $nsc->call("processTransaction", array($request));
echo "Done<br><br>";

// did a soap fault occur?
checkStatus($nsc);

// output result
echo "addAndProcessPayment Response: <pre>";
print_r($processTransactionResponse);
echo "</pre>";

// Call logout method
stopEtapestrySession($nsc);

// add sanitization functions
function cleanInput($input) {
    $search = array(
        '@<script[^>]*?>.*?</script>@si',   // Strip out javascript
        '@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags
        '@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly
        '@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments
    );

    $output = preg_replace($search, '', $input);
    return $output;
}

function sanitize($input) {
    if (is_array($input)) {
        foreach($input as $var=>$val) {
            $output[$var] = sanitize($val);
        }
    }
    else {
        if (get_magic_quotes_gpc()) {
            $input = stripslashes($input);
        }
        $output  = cleanInput($input);
    }
    return $output;
}
