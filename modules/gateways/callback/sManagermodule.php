<?php
/**
 * @author Riyad Mohammad riyadmohammadraju@gmail.com
 * @author Saleh Ahmad salehoyon@hotmail.com
 */
    # Required File Includes
    include("../../../init.php");
    include("../../../includes/functions.php");
    include("../../../includes/gatewayfunctions.php");
    include("../../../includes/invoicefunctions.php");

    # Gateway Module Name
    $gatewaymodule = "sManager";


    $GATEWAY = getGatewayVariables($gatewaymodule);

    # Checks gateway module is active before accepting callback
    if (!$GATEWAY["type"]) die("Module Not Activated");
    if (!isset($_POST)) die("No Post Data To Validate!");

    $username = 'bW7mCshVzbXPpa3yOMbklhmJzLFrsWcC';
    $password = '6qntubds8HlULCpVnbZPVUyIWA0bQoJD';
    
    $invoiceid = $_GET["invoiceid"];
	$transid   = $_GET["transaction_id"];
  
    $clientID       = $GATEWAY["clientID"];
    $clientSecret   = $GATEWAY["clientSecret"];
    $systemurl      = $GATEWAY['systemurl'];
    $url_last_slash = substr($systemurl, strrpos($systemurl, '/') + 0);
    
    if (isset($transid)) {
        $requested_url = 'https://api.sheba.xyz/v1/ecom-payment/details?transaction_id=' . $transid;

        $orderData = mysql_fetch_assoc(select_query('tblinvoices', 'total', ["invoicenum" => $invoiceid]));

        $order_amount = $orderData['total'];

        $headerInfo = array(
            'client-id: ' . $clientID,
            'client-secret: ' . $clientSecret,
            'Accept: application/json'
        );

        $handle = curl_init();
        curl_setopt($handle, CURLOPT_URL, $requested_url);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($handle, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($handle, CURLOPT_HTTPHEADER, $headerInfo);

        $results = curl_exec($handle);
        $code = curl_getinfo($handle, CURLINFO_HTTP_CODE);

        if ($code == 200 && !( curl_errno($handle))) {
            $smanagerResponse = $results;
            $responsejSON = json_decode($smanagerResponse, true );

            $status = $responsejSON['data']['payment_status'];
            $amount = $responsejSON['data']['amount'];

            # Checks invoice ID is a valid invoice number or ends processing
            $invoiceid = checkCbInvoiceID($invoiceid,$GATEWAY["name"]);

            $orderStatus = mysql_fetch_assoc(select_query('tblinvoices', 'status', ["invoicenum" => $invoiceid]));

            if ($orderStatus['status'] == "Paid") {
                # Save to Gateway Log: name, data array, status
                logTransaction($GATEWAY["name"],
                    array(
                        "Gateway Response" => $_POST,
                        "Validation Response" => json_decode($results, true),
                        "Response" => "Already Succeed"
                    ), "Successful");
                if ($url_last_slash == "/") {
                    header("Location: ".$systemurl."clientarea.php?action=services"); /* Redirect browser */
                } else {
                    header("Location: ".$systemurl."/clientarea.php?action=services"); /* Redirect browser */
                }
                exit;
            }

            if ($status === 'completed') {
                $fee = 0;
                $command = 'UpdateInvoice';

                # todo generate username and password
                $postData = [
                    'invoiceid' => $invoiceid,
                    'status'    => 'Paid',
                    'username'  => $username,
                    'password'  => $password,
                ];
                $adminUsername = '';

                $results = localAPI($command, $postData, $adminUsername);
                logTransaction($GATEWAY["name"], $_POST, "Successful"); # Save to Gateway Log: name, data array, status

                if ($url_last_slash == "/") {
                    header("Location: ".$systemurl."clientarea.php?action=services"); /* Redirect browser */
                } else {
                    header("Location: ".$systemurl."/clientarea.php?action=services"); /* Redirect browser */
                }
            } else {
                logTransaction($GATEWAY["name"], $_POST, "Unsuccessful"); # Save to Gateway Log: name, data array, status

                if ($url_last_slash == "/") {
                    header("Location: ".$systemurl."clientarea.php?action=services"); /* Redirect browser */
                } else {
                    header("Location: ".$systemurl."/clientarea.php?action=services"); /* Redirect browser */
                }

                exit;
            }
        } else {
            logTransaction($GATEWAY["name"], $_POST, "Unsuccessful"); # Save to Gateway Log: name, data array, status

            if ($url_last_slash == "/") {
                header("Location: ".$systemurl."clientarea.php?action=services"); /* Redirect browser */
            } else {
                header("Location: ".$systemurl."/clientarea.php?action=services"); /* Redirect browser */
            }

            exit;
        }
    } else {
        echo 'No Transaction ID Found.';
    }
