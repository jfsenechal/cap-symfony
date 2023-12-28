<?php

namespace Cap\Commercio\Wallet\Response;

class RedirectResponse
{
    /**
     * Code corresponding to the outcome of the payment. See Event ID codes below for full details.
     * https://developer.vivawallet.com/integration-reference/response-codes/#event-id-codes
     */
    public readonly string $EventId;
    /**
     * ECI code. See Electronic Commerce Indicator below for full details
     * https://developer.vivawallet.com/integration-reference/response-codes/#electronic-commerce-indicator
     */
    public readonly string $ECI;
    /**
     * Specifies the Viva Wallet transaction ID generated during checkout.
     * The variable name is configurable and can be updated from the Source Code dialog box in Viva Wallet banking app
     * https://developer.vivawallet.com/getting-started/create-a-payment-source/payment-source-for-online-payments/
     */
    public readonly string $t;
    /**
     * Specifies the payment order code. The variable name is configurable and can be updated from the Source Code dialog box
     * in Viva Wallet banking app
     * https://developer.vivawallet.com/getting-started/create-a-payment-source/payment-source-for-online-payments/
     */
    public readonly string $s;
    /**
     * The language code in which the payment order is created.
     * Consists of two-letter ISO 639-1 language code combined with two-letter ISO 3166-1 alpha-2 country code
     */
    public readonly string $lang;
}