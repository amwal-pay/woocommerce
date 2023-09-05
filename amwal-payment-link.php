<?php
/*

Plugin Name: amwal generate payment link

Description: Generate payment link

Version: 1.0.0

Author: amwal

Author URI: http://amwal.io

Text Domain: amwal generate payment

*/

session_start();

echo '<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
 integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>';
echo '<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>';
echo '<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"
 integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>';
echo '<script src="https://cdn.datatables.net/v/dt/dt-1.10.21/datatables.min.js"></script>';


function hexToStr($hex)
{
    $string = '';
    for ($i = 0; $i < strlen($hex) - 1; $i += 2) {
        $string .= chr(hexdec($hex[$i] . $hex[$i + 1]));
    }
    return $string;
}

function generateSecureHash($merchantId, $terminalId, $secretKey, $time)
{

    $hashing = "DateTimeLocalTrxn=$time&MerchantId=$merchantId&TerminalId=$terminalId";
    return hash_hmac('sha256', $hashing, hexToStr($secretKey));
}


function getTime()
{

    $now = new DateTime();
    $time = $now->format('Y-m-d H:i:s');

    $date = strtotime($time);
    $day = date('d', $date);
    $month = date('m', $date);
    $year = date('y', $date);
    $hour = date('H', $date);
    $minutes = date('i', $date);
    $seconds = date('s', $date);
    return $year . $month . $day . $hour . $minutes . $seconds . '';

}


function getTimeNow()
{

    $now = new DateTime();
    $time = $now->format('Y-m-d H:i:s');

    $date = strtotime($time);
    $day = date('d', $date);
    $month = date('m', $date);
    $year = date('Y', $date);
    return $year . $month . $day . '';

}

function saveToSession($key, $val)
{
    $_SESSION[$key] = $val;
}

function getFromSession($key, $defValue)
{
    $val = $_SESSION[$key];
    if ($val) {
        return $val;
    }
    return $defValue;
}

function sendRequest($gateway_url, $request_string)
{

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL, $gateway_url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($request_string));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    $response = curl_exec($ch);
    curl_close($ch);
    return json_decode($response, true);
}


// code that excuted when send post requst to save or load data from pages.
$historyOrders = [];
$fromDate = date('yy-m-d');
$toDate = date('yy-m-d');
// get first page for payment setting data.
$merchantId = getFromSession('merchant_id', '');
$terminalId = getFromSession('terminal_id', '');
$secretKey = getFromSession('secret_key', '');
$callbackUrl = getFromSession('callback_url', '');
$liveServer = getFromSession('live_server', 'no');
// generate payment url data
$errorMessage = null;
$generatedUrl = null;
$pageIndex = $_GET['index'];
/// check if user create post request to save his setting.
if ($pageIndex == 0 && $_POST['action'] == 'save') {
    $merchantId = $_POST['merchant_id'];
    $terminalId = $_POST['terminal_id'];
    $secretKey = $_POST['secret_key'];
    $callbackUrl = $_POST['callback_url'];
    $liveServer = isset($_POST['live_server']) ? 'yes' : 'no';
    saveToSession('merchant_id', $merchantId);
    saveToSession('terminal_id', $terminalId);
    saveToSession('secret_key', $secretKey);
    saveToSession('callback_url', $callbackUrl);
    saveToSession('live_server', $liveServer);
    
} else if ($pageIndex == 1 && $_POST['action'] == 'save') {
    $amount = intval($_POST['amount']) * 100;
    $callbackUrl = $_POST['callback_url'];
    $notificationMethod = $_POST['notification_method'];
    $notValue = $_POST['not_value'];
    $payerName = $_POST['payer_name'];
    $usageCount = $_POST['usage_count'];
    $refNumber = $_POST['ref_number'];
    $messageToCustomer = $_POST['message_to_customer'];
    if ($messageToCustomer == '') {
        $messageToCustomer = null;
    }
    if ($merchantId == '' || $terminalId == '' || $secretKey == '') {
        $errorMessage = "Add Merchant ID , Terminal ID , Secret Key Setting First";
    } else {
        // call web service.
        $time = getTime();
        $body = [
            "MerchantId" => $merchantId,
            "TerminalId" => $terminalId,
            "SecureHash" => generateSecureHash($merchantId, $terminalId, $secretKey, $time),
            "DateTimeLocalTrxn" => $time,
            "AmountTrxn" => $amount,
            "CallBackUrl" => $callbackUrl,
            "Currency" => 818,
            "MaxNumberOfPayment" => $usageCount,
            "NotificationMethod" => $notificationMethod,
            "NotificationValue" => $notValue,
            "PayerName" => $payerName,
            "MerchantReference" => $refNumber,
            "Message" => $messageToCustomer
        ];

        $link = null;
        if ($liveServer != 'no') {
            $link = 'https://checkout.amwalpg.com:8443/api/InitiateOrder';
        } else {
            $link = 'https://checkout.amwalpg.com:8443/api/InitiateOrder';
        }

        $res = sendRequest($link, $body);
        if (!$res) {
            $errorMessage = "Internal Server Error , Try Again Later";
        } else {
            if ($res['Success'] == true) {
                $generatedUrl = $res['OrderURL'];
            } else {
                $errorMessage = $res['Message'];
            }
        }

    }
} else if ($pageIndex == 2 && $_POST['action'] == 'load') {
    // send request.
    $fromDate = $_POST['from_date'];
    $from = strtotime($fromDate);
    $day1 = date('d', $from);
    $month1 = date('m', $from);
    $year1 = date('Y', $from);
    $from= $year1 . $month1 . $day1 . '0000';

    $toDate = $_POST['to_date'];
    $to = strtotime($toDate);
    $day2 = date('d', $to);
    $month2 = date('m', $to);
    $year2 = date('Y', $to);
    $to =  $year2 . $month2 . $day2 . '0000';

    $time = getTime();
    $request_string = array(
        'SecureHash' => generateSecureHash($merchantId, $terminalId, $secretKey, $time),
        'DateTimeLocalTrxn' => $time,
        'TerminalId' => $terminalId,
        'MerchantId' => $merchantId,
        'InsertionDateTimeFrom' => $from,
        'InsertionDateTimeTo' => $to,
        "CurrentPage"=>1,
        "PageSize"=>100000,
    );
    if ($liveServer != 'no') {
        $link = 'https://checkout.amwalpg.com:8443/api/InitiateOrder';
    } else {
        $link = 'https://checkout.amwalpg.com:8443/api/InitiateOrder';
    }
   
    $ordersRes = sendRequest($link, $request_string);
    if (!$ordersRes) {
        $errorMessage = "Failed to get history orders , try again later";
    } else {
     if($ordersRes['Success']==true){
       foreach($ordersRes['OrdersList'] as $order){
           $orderData = ['amount'=>$order['Amount'],'id'=>$order['Id'],'payer_name'=>$order['PayerName'],'date'=>$order['CreationDate'],'status'=>$order['OrderStatus']];
           array_push($historyOrders , $orderData);
       }
     }else{
         $errorMessage = $ordersRes['Message'];
     }
    }
}


function my_admin_menu()
{

add_menu_page('amwal Payment', 'amwal Payment', 'manage_options','amwal-payment','my_admin_page_contents','dashicons-cloud', 3);   

add_submenu_page(
    'amwal-payment',       // parent slug
    'PayLink Settings',    // page title
    'PayLink Settings',             // menu title
    'manage_options',           // capability
    'amwal-payment', // slug
    'payment_settings_view' // callback
); 


add_submenu_page(
    'amwal-payment',       // parent slug
    'Send PayLink',    // page title
    'Send PayLink',             // menu title
    'manage_options',           // capability
    'amwal-payment-paylink', // slug
    'send_pay_link_view' // callback
); 

add_submenu_page(
    'amwal-payment',       // parent slug
    'PayLink History',    // page title
    'PayLink History',             // menu title
    'manage_options',           // capability
    'amwal-payment-history', // slug
    'payment_link_history_view' // callback
);  
}

   
add_action('admin_menu', 'my_admin_menu');


function my_admin_page_contents()
{
}


function payment_settings_view(){
    global $merchantId , $terminalId , $secretKey , $callbackUrl , $liveServer;
    ?>
     <div class="container card p-0">
       <div class="card-header">
       <h6 class='text-center'>Payment Settings</h6>
       </div>
       <div class="card-body">
       <form method="post" action="?page=amwal-payment-settings&index=0">
                            <div class="form-group">
                                <label for="merchant_id">Merchant ID</label>
                                <input
                                    id="merchant_id"
                                    name="merchant_id"
                                    required="required"
                                    class='form-control'
                                    value="<?=$merchantId ?>"></div>

                            <div class="form-group">
                                <label for="terminal_id">Terminal ID</label>
                                <input
                                    id="terminal_id"
                                    name="terminal_id"
                                    required="required"
                                    class='form-control'
                                    value="<?=$terminalId ?>"></div>

                            <div class="form-group">
                                <label for="secret_key">Secret Key</label>
                                <input
                                    id="secret_key"
                                    name="secret_key"
                                    required="required"
                                    class='form-control'
                                    value="<?=$secretKey ?>"></div>

                            <div class='form-group'>
                                <label for='callback_url'>Callback Url</label>
                                <input
                                    class='form-control'
                                    name='callback_url'
                                    id='callback_url'
                                    value="<?=$callbackUrl ?>"></div>

                            <div class="form-group">
                                <label for="live_server">Live Server
                                </label>
                                <?php echo $liveServer == 'yes' ? "<input id='live_server' name='live_server' type='checkbox' class='form-control'
                      checked>" : "<input id='live_server' name='live_server' type='checkbox' class='form-control'>" ?>

                            </div>
                            <input type='hidden' value='save' name='action'>

                            <button class="btn btn-primary">SAVE</button>
                        </form>
       </div>
     </div>

    <?php
}

function send_pay_link_view(){
    global $errorMessage , $generatedUrl;
  ?>
  
    <div class='container card p-0'>
     <div class="card-header">
     <h6 class='text-center'>Generate PayLink</h6>
     </div> 

     <div class="card-body">
    <form action="?page=amwal-payment-paylink&index=1" method="post">

<?php
if ($errorMessage) {
    echo "<div class='alert alert-danger'>$errorMessage</div>";
} else if (!$errorMessage && $generatedUrl) {
    echo "<div class='alert alert-success'>Payment link generated :-  <a target='_blank' class='alert-link' href='$generatedUrl'>$generatedUrl</a></div>";
}
?>

<div class='form-group'>
    <label for='payer_name'>Payer Name</label>
    <input class='form-control' id='payer_name' name='payer_name' type='text'></div>

<div class='form-group'>
    <label for='amount'>Amount *</label>
    <input
        class='form-control'
        name='amount'
        id='amount'
        value='1'
        type='number'
        min='1'
        required="required"></div>

<div>

    <div class="row">
        <div class='form-group col-md-6'>
            <label for='usage_select'>Number Of Usage *</label>
            <select id='usage_select' name='usage' class='form-control'>
                <option value='1' selected="selected">One time</option>
                <option value='-1'>Many times</option>
            </select>
        </div>

        <div class='form-group col-md-6' id="usage_count" style='visibility:hidden'>
            <label for='usage_count'>Usage Count *</label>
            <input
                class='form-control'
                name='usage_count'
                id='usage_count'
                value='1'
                type='number'
                required="required"
                min='1'></div>
    </div>

    <div class="row">
        <div class='form-group col-md-6'>
            <label for='notification_method'>Notification Method</label>
            <select
                id='notification_method'
                name='notification_method'
                class='form-control'>
                <option value='0' selected="selected">Email</option>
                <option value='1'>Phone Number</option>
            </select>
        </div>

        <div class='form-group col-md-6'>
            <label for='not_value'>Email/Phone Value</label>
            <input
                class='form-control'
                id='not_value'
                name='not_value'
                type='text'
                required="required"></div>
    </div>

    <div class='form-group'>
        <label for='ref_number'>Reference Number</label>
        <input class='form-control' id='ref_number' name='ref_number'></div>

    <div class='form-group'>
        <label for='message_to_customer'>Message To Customer</label>
        <textarea
            class='form-control'
            id='message_to_customer'
            name='message_to_customer'
            rows='4'></textarea>
    </div>

    <input type='hidden' value='save' name='action'>

    <button class='btn btn-primary' type='submit'>Generate</button>
</form>
</div>
    </div>

    <script>
$('#usage_select').on('change', function() {
  var selectedVal =  $(this).find(":selected").val() ;
  if(selectedVal==-1){
   // show number of usage input.
   $("#usage_count").css('visibility','visible');
  }else{
    $("#usage_count").css('visibility','hidden');
  }
});
    </script>
    <?php
}


function payment_link_history_view(){
    global $fromDate , $toDate , $historyOrders , $errorMessage;
?>

<div class="container card p-0">

<div class="card-header p-2">
<h6 class='text-center'>PayLink History</h6> 
<form method="post" action="?page=amwal-payment-history&index=2" class='mb-0 mt-4'>
                            <div class="row">

                            <div class="form-group col-md-3">
                        <div class="input-group">
                            <div class="input-group-prepend">
                            <span class="input-group-text">From</span>
                            </div>
                            <input id="from_date" name="from_date" required class='form-control'
                                                                value="<?= $fromDate ?>" type='date'>
                        </div>
                        </div>


                        <div class="form-group col-md-3">
                        <div class="input-group">
                            <div class="input-group-prepend">
                            <span class="input-group-text">To</span>
                            </div>
                            <input id="to_date" name="to_date" required class='form-control'
                                           value="<?= $toDate ?>"
                                           type='date'>
                        </div>
                        </div>
                        <input type='hidden' value='load' name='action'>
                                <div class="form-group col-md-2">
                                    <button class='btn btn-primary'>Load</button>
                                </div>
                            </div>
                        </form>
</div>
<div class="card-body">
<?php
             if ($errorMessage) {
                echo "<div class='alert alert-danger'>$errorMessage</div>";
             }?>
                        <table id="my-table" class="display" style="width:100%"> </table>

<script>
        var information = <?=json_encode($historyOrders)?>;

        $(document).ready(function () {
            $('#my-table').dataTable({
                data: information,
                columns: [
                    {
                        data: 'id',
                        title: 'ID'
                    }, {
                        data: 'payer_name',
                        title: 'Payer Name'
                    }, {
                        data: 'amount',
                        title: 'Amount'
                    }, {
                        data: 'date',
                        title: 'Date'
                    }, {
                        data: 'status',
                        title: 'Status'
                    }
                ]
            });
        });
    </script>
</div>
</div>
<?php
}
/**
 * Enqueue scripts and styles
 */
function theme_enqueue_scripts()
{
    // all styles
    wp_enqueue_style('bootstrap-css', plugins_url( '/css/bootstrap.min.css' , __FILE__ ));
    wp_enqueue_style('datatable-css',  plugins_url( '/css/datatables.min.css' , __FILE__ ));
}

add_action('admin_enqueue_scripts', 'theme_enqueue_scripts');

?>