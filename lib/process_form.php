<?php
// sanitize input before doing anything else
sanitize( $_POST );

// variables
$api_version = 2;
$cost = 35;
$notification_address = 'andrew@andrewrminion.com';
$debugging = 'true'; // false = off; true = some; 'heavy' = everything possible;

// output template if not called via AJAX
if ( ! $_POST['ajax'] ) {
    include('../template/header.html');
    ?>
    <section class="main-container">
        <section class="main wrapper clearfix">
            <article class="main-content">
                <h1>Thank you!</h1>

    <?php
}

// development debugging
if ( $debugging == 'heavy' ) {
    var_dump( $_POST );
}

// require API libraries
require_once('utils.php');
require_once('nusoap.php');

// instantiate nusoap_client and call login method
$nsc = startEtapestrySession( $api_version, $debugging );

// define info
$account = array();
if ( $api_version == 2 ) {
    $account["name"] = $_POST['firstName'] . ' ' . $_POST['lastName'];
    $account["firstName"] = $_POST['firstName'];
    $account["lastName"] = $_POST['lastName'];
} elseif ( $api_version == 3 ) {
    $account["nameFormat"] = 1;
    $account["firstName"] = $_POST['firstName'];
    $account["lastName"] = $_POST['lastName'];
}
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
$account["phones"] = array( $phone );

// set up array to check for duplicates
if ( $api_version == 2 ) {
    $duplicates["name"] = $account["name"];
} elseif ( $api_version == 3 ) {
    $duplicates["name"] = $account["firstName"] . ' ' . $account["lastName"];
}
$duplicates["address"] = $account["address"];
$duplicates["email"] = $account["email"];
$duplicates["accountRoleTypes"] = 1;
$duplicates["allowEmailOnlyMatch"] = 'false';

// check for duplicates
if ( $debugging ) {
    echo "Calling for duplicates...";
}
if ( $debugging == 'heavy' ) {
    echo '<pre>';print_r( $duplicates );echo '</pre>';
}
$checkDuplicatesResponse = $nsc->call( "getDuplicateAccounts", array( $duplicates ) );
if ( $debugging ) {
    echo "Done"."\n\n";
}

// did a soap fault occur?
checkStatus( $nsc );

// Output result
if ( $debugging == 'heavy' ) {
    echo "checkDuplicatesResponse: <pre>";
    print_r( $checkDuplicatesResponse );
    echo "</pre>";
}
if ( $debugging ) {
    echo "Count of checkDuplicatesResponse: " . count( $checkDuplicatesResponse ) . "\n\n";
}

// if no response from duplicates, add a new account
if ( count( $checkDuplicatesResponse ) != 1 ) {
    if ( $debugging ) {
        echo "Calling addAccount method...";
    }
    if ( $debugging == 'heavy' ) {
        echo '<pre>'; print_r( $account ); echo '</pre>';
    }
    $addAccountResponse = $nsc->call( "addAccount", array( $account, false ) );
    if ( $debugging ) {
        echo "Done"."\n\n";
    }

    // did a soap fault occur?
    checkStatus( $nsc );

    // output result
    if ( $debugging ) {
        echo "addAccount Response: <pre>";
        print_r( $addAccountResponse );
        echo "</pre>";
    }
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
if ( $debugging ) {
    echo "Calling processTransaction method...";
}
if ( $debugging == 'heavy' ) {
    echo '<pre>'; print_r( $request ); echo '</pre>';
}
#$processTransactionResponse = $nsc->call( "processTransaction", array( $request ) );
if ( $debugging ) {
    echo "Done"."\n\n";
}

// did a soap fault occur?
checkStatus( $nsc );

// output result
if ( $debugging ) {
    echo "addAndProcessPayment Response: <pre>";
    print_r( $processTransactionResponse );
    echo "</pre>";
}

// Call logout method
stopEtapestrySession( $nsc, $debugging );

if ( $debugging ) {
    echo 'Sending email...';
}
send_email_summary( array_merge( $account, $request ), $notification_address, $checkDuplicatesResponse, $addAccountResponse, $processTransactionResponse );
if ( $debugging ) {
    echo 'Done'."\n\n";
}

// output template if not called via AJAX
if ( ! $_POST['ajax'] ) {
    ?>
            </article>
        </section>
    </section>
    <?php
    // include template footer
    include('../template/footer.html');
}

// send summary email
function send_email_summary( $data, $notification_address, $checkDuplicatesResponse, $addAccountResponse, $processTransactionResponse ) {

    // set up message content
    $message_content = NULL;
    $message_content .= '<h1>New NCLL Account</h1>';
    $message_content .= '<p>Generated from ' . $_SERVER['HTTP_REFERER'] . ' page</p>';

    if ( count( $checkDuplicatesResponse ) >= 1 ) {
        $message_content .= '<h2>Possible duplicate accounts&hellip;</h2>';
        $message_content .= '<p>A new account was created to be safe. Please manually check for other accounts matching this name, email address, phone, and/or address.</p>';
    }

    $message_content .= '<h2>Personal Information</h2>';
    $message_content .= '<p><strong>Name:</strong> ' . $data['firstName'] . ' ' . $data['lastName'] . '<br/>';
    $message_content .= '<strong>Address:</strong> ' . $data['address'] . '<br/>';
    if ( $data['address2'] ) { $message_content .= $data['address2'] . '<br/>'; }
    $message_content .= '<strong>City/State/Zip:</strong> ' . $data['city'] . ', ' . $data['state'] . ' ' . $data['postalCode'] . '<br/>';
    $message_content .= '<strong>Email:</strong> ' . $data['email'] . '<br/>';
    $message_content .= '<strong>Phone:</strong> ' . $data['phones'][0]['number'] . ' (' . $data['phones'][0]['type'] . ')' . '<br/>';
    $message_content .= '<strong>Account Reference Number:</strong> ' . $addAccountResponse . '</p>';

    $message_content .= '<h2>Transaction Information</h2>';
    $message_content .= '<p><strong>Amount:</strong> $' . $data['transaction']['amount'] . '<br/>';
    $message_content .= '<strong>Fund:</strong> ' . $data['transaction']['fund'] . '<br/>';
    $message_content .= '<strong>Donation Reference Number:</strong> ' . $processTransactionResponse . '</p>';

    // set up and send email via Mailgun
    $ch = curl_init();
    curl_setopt( $ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC );
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
    curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'POST' );
    curl_setopt( $ch, CURLOPT_URL, 'https://api.mailgun.net/v3/mg.ncll.org/messages' );
    require( 'authentication-curl.php' ); // curl_setopt($ch, CURLOPT_USERPWD, 'api:key-sample');
    curl_setopt( $ch, CURLOPT_POSTFIELDS,
        array('from' => $data['firstName'] . ' ' . $data['lastName'] . ' <' . $data['email'] . '>',
            'to' => $notification_address,
            'subject' => 'New Account',
            'html' => print_r( $message_content, true )
        ) );
    $result = curl_exec( $ch );
    curl_close( $ch );
    return $result;
}

// add sanitization functions
function cleanInput( $input ) {
    $search = array(
        '@<script[^>]*?>.*?</script>@si',   // Strip out javascript
        '@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags
        '@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly
        '@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments
    );

    $output = preg_replace( $search, '', $input );
    return $output;
}

function sanitize( $input ) {
    if ( is_array( $input ) ) {
        foreach ( $input as $var => $val ) {
            $output[$var] = sanitize( $val );
        }
    }
    else {
        if ( get_magic_quotes_gpc() ) {
            $input = stripslashes( $input );
        }
        $output  = cleanInput( $input );
    }
    return $output;
}
