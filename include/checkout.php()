
<?php
function print_checkout($costEst)
{
	// This section generates the "Submit Payment" button using PHP          
	// This sample code requires the mhash library for PHP versions older than
	// 5.1.2 - http://hmhash.sourceforge.net/

	// the parameters for the payment can be configured here
	// the API Login ID and Transaction Key must be replaced with valid values
	$loginID		= "7Jk8Ctc8nP";
	$transactionKey = "33V89P3fJypS96Fv";
	$amount 		= $costEst;
	$description 	= "Proofing Transaction";
	$label 			= "Checkout "; // The is the label on the 'submit' button
	$testMode		= "false";
	// By default, this sample code is designed to post to our test server for
	// developer accounts: https://test.authorize.net/gateway/transact.dll
	// for real accounts (even in test mode), please make sure that you are
	// posting to: https://secure.authorize.net/gateway/transact.dll
       $url			= "https://test.authorize.net/gateway/transact.dll";
	//	$url			= "https://developer.authorize.net/tools/paramdump/index.php";


	// If an amount or description were posted to this page, the defaults are overidden
	if (array_key_exists("amount",$_REQUEST))
		{ $amount = $_REQUEST["amount"]; }
	if (array_key_exists("amount",$_REQUEST))
		{ $description = $_REQUEST["description"]; }

	// an invoice is generated using the date and time
	$invoice	= date(YmdHis);
	// a sequence number is randomly generated
	$sequence	= rand(1, 1000);
	// a timestamp is generated
	$timeStamp	= time();

	// The following lines generate the SIM fingerprint.  PHP versions 5.1.2 and
	// newer have the necessary hmac function built in.  For older versions, it
	// will try to use the mhash library.
	if( phpversion() >= '5.1.2' )
		{ $fingerprint = hash_hmac("md5", $loginID . "^" . $sequence . "^" . $timeStamp . "^" . $amount . "^", $transactionKey); }
	else 
		{ $fingerprint = bin2hex(mhash(MHASH_MD5, $loginID . "^" . $sequence . "^" . $timeStamp . "^" . $amount . "^", $transactionKey)); }
	?>

	<!-- Print the Amount and Description to the screen. -->
	Authorize Amount: <?php echo $amount; ?> <br />
	Description: <?php echo $description; ?> <br />
<br></br>
	<!-- Create the HTML form containing necessary SIM post values -->
	<form method='post' action='<?php echo $url; ?>' >
	<!--  Additional fields can be added here as outlined in the SIM integration
	 guide at: http://developer.authorize.net -->
		<input type='hidden' name='x_login' value='<?php echo $loginID; ?>' />
		<input type='hidden' name='x_amount' value='<?php echo $amount; ?>' />
		<input type='hidden' name='x_description' value='<?php echo $description; ?>' />
		<input type='hidden' name='x_invoice_num' value='<?php echo $invoice; ?>' />
		<input type='hidden' name='x_fp_sequence' value='<?php echo $sequence; ?>' />
		<input type='hidden' name='x_fp_timestamp' value='<?php echo $timeStamp; ?>' />
		<input type='hidden' name='x_fp_hash' value='<?php echo $fingerprint; ?>' />
		<input type='hidden' name='x_test_request' value='<?php echo $testMode; ?>' />
		<input type='hidden' name='x_show_form' value='PAYMENT_FORM' />
		<input type='submit' value='<?php echo $label; ?>' />
	</form>
	<?php
}
?>
