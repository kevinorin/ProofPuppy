<?php
session_start();
require_once 'emailer.php';

    $CLIid = escapeshellarg(session_id());

    $bgScript = shell_exec("php backjobscript.php '$CLIid' >> word_count_files/".date('m-d-y')."-".time().".txt &");
$email = new email();
$email->ServerPage($bgScript);

echo '<h4>Your request has been sent to the background for computing. Do not worry our server is hard at work crunching the words and counting away!</h4>';
echo '<h4>You will be redirected to the home page in 5 seconds</h4>';
echo '<script type="text/JavaScript">
<!--
setTimeout("location.href = \'http://www.proofpuppy.com\';",5000);
-->
</script>';
?>
