<?php
require_once(__DIR__ . '/vendor/autoload.php');

use Xendit\Configuration;
use Xendit\Invoice\InvoiceApi;

Configuration::setXenditKey("YOUR_API_KEY_HERE");

$apiInstance = new InvoiceApi();
$create_invoice_request = new Xendit\Invoice\CreateInvoiceRequest([
    'external_id' => 'test1234',
    'description' => 'Test Invoice',
    'amount' => 10000,
    'invoice_duration' => 172800,
    'currency' => 'IDR',
    'reminder_time' => 1
]); // \Xendit\Invoice\CreateInvoiceRequest
$for_user_id = "62efe4c33e45694d63f585f0"; // string | Business ID of the sub-account merchant (XP feature)

try {
    $result = $apiInstance->createInvoice($create_invoice_request, $for_user_id);
    print_r($result);
} catch (\Xendit\XenditSdkException $e) {
    echo 'Exception when calling InvoiceApi->createInvoice: ', $e->getMessage(), PHP_EOL;
    echo 'Full Error: ', json_encode($e->getFullError()), PHP_EOL;
}