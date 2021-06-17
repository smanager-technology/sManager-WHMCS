<?php
/**
 * WHMCS Sample Payment Gateway Module
 *
 * Payment Gateway modules allow you to integrate payment solutions with the
 * WHMCS platform.
 *
 * This sample file demonstrates how a payment gateway module for WHMCS should
 * be structured and all supported functionality it can contain.
 *
 * Within the module itself, all functions must be prefixed with the module
 * filename, followed by an underscore, and then the function name. For this
 * example file, the filename is "gatewaymodule" and therefore all functions
 * begin "gatewaymodule_".
 *
 * If your module or third party API does not support a given function, you
 * should not define that function within your module. Only the _config
 * function is required.
 *
 * For more information, please refer to the online documentation.
 *
 * @see https://developers.whmcs.com/payment-gateways/
 *
 * @author Riyad Mohammad riyadmohammadraju@gmail.com
 * @author Saleh Ahmad salehoyon@hotmail.com
 *
 * @copyright Copyright (c) WHMCS Limited 2021
 * @license http://www.whmcs.com/license/ WHMCS Eula
 */

if (!defined("WHMCS")) {
    die("This file cannot be accessed directly");
}

/**
 * Define module related meta data.
 *
 * Values returned here are used to determine module related capabilities and
 * settings.
 *
 * @see https://developers.whmcs.com/payment-gateways/meta-data-params/
 *
 * @return array
 */
function gatewaymodule_MetaData()
{
    return array(
        'DisplayName' => 'sManager Online Payment',
        'APIVersion' => '1.1', // Use API Version 1.0
    );
}

/**
 * Define gateway configuration options.
 *
 * The fields you define here determine the configuration options that are
 * presented to administrator users when activating and configuring your
 * payment gateway module for use.
 *
 * Supported field types include:
 * * text
 * * password
 * * yesno
 * * dropdown
 * * radio
 * * textarea
 *
 * Examples of each field type and their possible configuration parameters are
 * provided in the sample function below.
 *
 * @return array
 */
function sManager_config()
{
    $configarray= array(

        'FriendlyName' => [
            'Type'  => 'System',
            'Value' => 'sManager Online Payment',
        ],
       
        // client id
        'clientID' => [
            'FriendlyName' => 'Client Id',
            'Type'         => 'password',
            'Size'         => '1000',
            'Default'      => '',
            'Description'  => 'Enter Client ID here',
        ],

        // client secret
        'clientSecret' => [
            'FriendlyName' => 'Client Secret',
            'Type'         => 'password',
            'Size'         => '1000',
            'Default'      => '',
            'Description'  => 'Enter Client Secret here',
        ],
    );

    return $configarray;
}

/**
 * Payment link.
 *
 * Required by third party payment gateway modules only.
 *
 * Defines the HTML output displayed on an invoice. Typically consists of an
 * HTML form that will take the user to the payment gateway endpoint.
 *
 * @param array $params Payment Gateway Module Parameters
 *
 * @see  
 *
 * @return string
 */
function sManager_link($params)
{
    # Gateway Specific Variables
    $clientID     = $params['clientID'];
    $clientSecret = $params['clientSecret'];
    $gatewaytype  = $params['gateway_type'];
    $systemUrl    = $params['systemurl'];
    
    $url = 'https://api.sheba.xyz/v1/ecom-payment/initiate';

    # Invoice Variables
    $invoiceid   = $params['invoiceid'];
    $description = $params["description"];
    $amount      = $params['amount']; # Format: ##.##
    $currency    = $params['currency']; # Currency Code
    $product     = $params['type'];

    // Client Parameters

    $customerName    = $params['clientdetails']['name'];
    $customerPhoneNo = $params['clientdetails']['phoneNumber'];
    $uuid            = $params['clientdetails']['uuid'];
    

    // System Parameters
    $companyname = $params['companyname'];
    $systemurl   = $params['systemurl'];
    $currency    = $params['currency'];
    $returnurl   = $params['returnurl'];

    $url_last_slash = substr($systemurl, strrpos($systemurl, '/') + 0);
    $trnxId = uniqid();

    	if ($url_last_slash == "/") {
            $success_url = $systemurl.'/modules/gateways/callback/sManagermodule.php?transaction_id='.$trnxId . '&invoiceid=' . $invoiceid;
            $fail_url    = $systemurl."clientarea.php?action=services";
    	} else {
            $success_url = $systemurl.'/modules/gateways/callback/sManagermodule.php?transaction_id='.$trnxId . '&invoiceid=' . $invoiceid;
            $fail_url = $systemurl."clientarea.php?action=services";

    	}

    $api_endpoint = $url;

    $postfields = [
        'amount'          => $amount,
        'transaction_id'  => $trnxId,
        'success_url'     => $success_url,
        'fail_url'        => $fail_url,
        'customer_name'   => $customerName,
        'customer_mobile' => $customerPhoneNo,
        'purpose'         => 'Online Payment'
    ];

    if($gatewaytype == "on") {
    ?>
    <script type="text/javascript">
        (function (window, document) {
            var loader = function () {
                var script = document.createElement("script"), tag = document.getElementsByTagName("script")[0];
                script.src = "<?php echo $url; ?>?" + Math.random().toString(36).substring(7);
                tag.parentNode.insertBefore(script, tag);
            };
        
            window.addEventListener ? window.addEventListener("load", loader, false) : window.attachEvent("onload", loader);
        })(window, document);
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <?php
            
    return '<button class="btn btn-success" id="sManager pay"
        token="'.$uuid.'"
        postdata=""
        order="'.$invoiceid.'"
        endpoint="'.$api_endpoint.'">'.$gatewaybutton_text.'</button>
        
        <script>
            function changeObj() {
                var obj = {};
                var obj = { clientID: "'.$clientID.'", tran_id: "'.$invoiceid.'", total_amount: "'.$amount.'", success_url: "'.$success_url.'", fail_url: "'.$fail_url.'", cancel_url: "'.$cancel_url.'", currency: "'.$currency.'", cus_name: "'.$firstname.' '.$lastname.'", cus_add1: "'.$address1.'", cus_add2: "'.$address2.'", cus_city: "'.$city.'", cus_state: "'.$state.'", cus_postcode: "'.$postcode.'", cus_country: "'.$country.'", cus_phone: "'.$phone.'", cus_email: "'.$email.'", value_a: "'.$description.'", value_b: "'.$returnurl.'", product_name: "'.$product.'"};
                $("#sManager pay").prop("postdata", obj);
            }
            changeObj();
        </script>';

    } else{
        $headerInfo = array(
            'client-id: ' . $clientID,
            'client-secret: ' . $clientSecret,
            'Accept: application/json'
        );

            $handle = curl_init();
            curl_setopt($handle, CURLOPT_URL, $api_endpoint );
            curl_setopt($handle, CURLOPT_TIMEOUT, 30);
            curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 30);
            curl_setopt($handle, CURLOPT_POST, 1 );
            curl_setopt($handle, CURLOPT_POSTFIELDS, $postfields);
            curl_setopt($handle, CURLOPT_HTTPHEADER, $headerInfo);
            curl_setopt($handle, CURLOPT_CUSTOMREQUEST, 'POST');

            curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, FALSE); # KEEP IT FALSE IF YOU RUN FROM LOCAL PC
            
            
            $content = curl_exec($handle);
            $code = curl_getinfo($handle, CURLINFO_HTTP_CODE);

            if ($code == 200 && !( curl_errno($handle))) {
                curl_close( $handle);
                $smanagerResponse = $content;
                $smanager = json_decode($smanagerResponse, true );

                header("Location: " . $smanager['data']['link']);
            } else {
                curl_close( $handle);

                $smanagerResponse = $content;
                $smanager = json_decode($smanagerResponse, true );
                echo "Code: " . $smanager['code'] . " & Failed Reason: " . $smanager['message'];
                exit;
            } 
            
            # PARSE THE JSON RESPONSE
            $smanager = json_decode($smanagerResponse, true );

            $code = $smanager['code'];

            if ($code !== 200) {
                echo "Code: " . $smanager['code'] . " & Failed Reason: " . $smanager['message'];
            }
            
            if (isset($smanager['data']['link']) && $smanager['data']['link'] != '')  {
                echo "
                <script>
                location.href = ". $smanager['data']['link'] ."
                </script>
                ";
            }
        }
     }
