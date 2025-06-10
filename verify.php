<?php
$hash = filter_input(INPUT_POST, 'ResNum', FILTER_SANITIZE_STRING) ?? null; //your hash code in create pay/
$RefNum = filter_input(INPUT_POST, 'RefNum', FILTER_SANITIZE_STRING) ?? null; // sep POST Digital receipt and save in your database
$requestPayload = [
    'RefNum'      => $RefNum ,
    'TerminalNumber'  => "TerminalNumber", // TerminalNumber
 
];
// 
$jsonData = json_encode($requestPayload);
$ch = curl_init('https://sep.shaparak.ir/verifyTxnRandomSessionkey/ipg/VerifyTransaction');
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => $jsonData,
    CURLOPT_HTTPHEADER => array(
        "cache-control: no-cache",
        "content-type: application/json",
    ),

]);
$response = curl_exec($ch);
$result = json_decode($response, true);
curl_close($ch);
if ($result['Success'] == true) {
    // PaymentSuccess
    PaymentSuccess($result['TransactionDetail']['StraceNo'], $result['TransactionDetail']['MaskedPan']);
} else{
    exit('error verify your pay.');
}
