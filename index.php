<?php
 // connect to sep and create token
$requestPayload = [
    'action'      => 'token',
    'TerminalId'  => $merchant_id, // TerminalId
    'RedirectUrl' => $RedirectUrl, // callback url
    'Amount'      => $Amount, // amount pay
    'ResNum'      => $Hashcode, // hash code for gate
    'CellNumber'  => $CellNumber, // customer phone
];

$jsonData = json_encode($requestPayload);
$ch = curl_init('https://sep.shaparak.ir/onlinepg/onlinepg');
curl_setopt_array($ch, [
    CURLOPT_USERAGENT      => 'Shoyrad Rest Api v1',
    CURLOPT_CUSTOMREQUEST  => 'POST',
    CURLOPT_POSTFIELDS     => $jsonData,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER     => ['Content-Type: application/json','Referer: https://shoyrad.com/' ],
    CURLOPT_TIMEOUT        => 30,
]);
$response = curl_exec($ch);
$result = json_decode($response, true);
curl_close($ch);

if (isset($result['status']) && $result['status'] == 1 && isset($result['token'])) {
    // move to gateway
    $authority = 'https://sep.shaparak.ir/OnlinePG/SendToken?token='.$result['token'];
      ?>
        <form id="EGh_pay_form" action="https://sep.shaparak.ir/OnlinePG/OnlinePG" method="post" style="display: none;">
            <input type="hidden" name="Token" value="<?= $result['token'] ?>"/>
            <input name="GetMethod" type="text" value="">
        </form>
        <script type="text/javascript"> document.getElementById("EGh_pay_form").submit(); </script>
        <?php
    exit;
} else {
    Failgate($response);
}
