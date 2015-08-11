<?php
// sanitize input before doing anything else
$_POST = sanitize($_POST);

// development debugging
var_dump($_POST);

// variables
$cost = 35;
$firstName = $_POST['firstName'];
$lastName = $_POST['lastName'];
$email = $_POST['email'];
$phoneNumber = $_POST['phoneNumber'];
$phoneType = $_POST['phoneType'];
$address = $_POST['address'];
$address2 = $_POST['address2'];
$city = $_POST['city'];
$state = $_POST['state'];
$postalCode = $_POST['postalCode'];
$country = $_POST['country'];

// require API libraries
require_once('lib/utils.php');
require_once('lib/nusoap.php');

// Instantiate nusoap_client and call login method
$nsc = startEtapestrySession();

// Define Account
$account = array();
$account["nameFormat"] = 1;
$account["firstName"] = $firstName;
$account["lastName"] = $lastName;
$account["personaType"] = "Personal";
$account["address"] = $address;
$account["address2"] = $address2;
$account["city"] = $city;
$account["state"] = $state;
$account["postalCode"] = $postalCode;
$account["country"] = $country;
$account["email"] = $email;

// Define Phone
$phone = array();
$phone["type"] = $phoneType;
$phone["number"] = $phoneNumber;

// Add Phone to Account (optional)
$account["phones"] = array($phone);

// Invoke addAccount method
echo "Calling addAccount method...";
$addAccountResponse = $nsc->call("addAccount", array($account, false));
echo "Done<br><br>";

// Did a soap fault occur?
checkStatus($nsc);

// Output result
echo "addAccount Response: <pre>";
print_r($addAccountResponse);
echo "</pre>";

// Define Gift
$trans = array();
$trans["accountRef"] = $addAccountResponse;
$trans["fund"] = "General";
$trans["amount"] = $cost;

// Define Object Name (*)
$trans["eTapestryObjectName"] = "Gift";

// Define CreditCard
$cc = array();
$cc["number"] = "9999888877776666";
$cc["expirationMonth"] = 12;
$cc["expirationYear"] = 2018;

// Define Valuable
$valuable = array();
$valuable["type"] = 3;
$valuable["creditCard"] = $cc;

// Add Valuable to Gift
$trans["valuable"] = $valuable;

// Define ProcessTransactionRequest
$request = array();
$request["transaction"] = $trans;

// Invoke processTransaction method
echo "Calling processTransaction method...";
$processTransactionResponse = $nsc->call("processTransaction", array($request));
echo "Done<br><br>";

// Did a soap fault occur?
checkStatus($nsc);

// Output result
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
        $input  = cleanInput($input);
        $output = mysql_real_escape_string($input);
    }
    return $output;
}
