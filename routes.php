<?php

use OFFLINE\Cashier\Models\Settings;

/**
 * Invoice Downloads
 *
 * @see \OFFLINE\Cashier\Components\InvoicesList
 */
Route::get('/cashier/invoice/{user}/{invoice}', function ($user, $invoice) {
    $userId    = decrypt($user);
    $invoiceId = decrypt($invoice);

    $user = \OFFLINE\Cashier\Models\User::whereId($userId)->firstOrFail();

    return $user->downloadInvoice($invoiceId, [
        'vendor'  => Settings::get('invoice_vendor', 'Your Vendor'),
        'product' => Settings::get('invoice_product', 'Your Product'),
    ]);
});

/**
 * Webhook Handler
 */
$webhookUrl        = config('services.stripe.webhook.url', '\'stripe/webhook\'');
$webhookController = config('services.stripe.webhook.handler',
    '\OFFLINE\Cashier\Classes\WebhookController@handleWebhook');

Route::post($webhookUrl, $webhookController);
