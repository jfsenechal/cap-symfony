<?php

namespace Cap\Commercio\Wallet\Response;

class Error400Response
{
    /* [
 "[Negative or zero amount] = an incorrect amount was entered",
 "[RedirectCheckoutCreateOrderValidationFailed Null options] = a type has been incorrectly specified in the request",
 "[5504: OrdersCardVerificationValidationFailed] -or- [2091: PaymentsCardVerificationValidationFailed] = 'isCardVerification' has been used, but a parameter or value has been used incorrectly (e.g. a non-zero amount)"
 */
    public string $message;
}