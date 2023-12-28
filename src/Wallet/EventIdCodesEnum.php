<?php

namespace Cap\Commercio\Wallet;

enum EventIdCodesEnum: int
{
    case Undefined = 0;
    case ThreeDSFlowIncomplete = 2061;
    case ThreeDSValidationFailed = 2062;
    case PaymentsPolicyAcquiringRestriction = 2108;
    case ReferToCardIssuer = 10001;
    case InvalidMerchantNumber = 10003;
    case PickUpCard = 10004;
    case DoNotHonor = 10005;
    case GeneralError = 10006;
    case PickUpCardSpecialCondition = 10007;
    case InvalidTransaction = 10012;
    case InvalidAmount = 10013;
    case InvalidCardNumber = 10014;
    case InvalidIssuer = 10015;
    case FormatError = 10030;
    case NoCreditAccount = 10039;
    case LostCard = 10041;
    case StolenCard = 10043;
    case ClosedAccount = 10046;
    case InsufficientFunds = 10051;
    case NoCheckingAccount = 10052;
    case NoSavingsAccount = 10053;
    case ExpiredCard = 10054;
    case FunctionNotPermittedToCardholder = 10057;
    case FunctionNotPermittedToTerminal = 10058;
    case SuspectedFraud = 10059;
    case WithdrawalLimitExceeded = 10061;
    case RestrictedCard = 10062;
    case IssuerResponseSecurityViolation = 10063;
    case SoftDecline = 10065;
    case CallIssuer = 10070;
    case PINEntryTriesExceeded = 10075;
    case InvalidToAccountSpecified = 10076;
    case InvalidFromAccountSpecified = 10077;
    case LifeCycle = 10079;
    case NoFinancialImpact = 10080;
    case FraudSecurity = 10083;
    case InvalidAuthorizationLifeCycle = 10084;
    case CryptographicFailure = 10088;
    case UnacceptablePINTransactionDeclinedRetry = 10089;
    case TransactionCannotBeCompletedViolationOfLaw = 10093;
    case SystemMalfunction = 10096;
    case GenericError = 10200;
    case InvalidCVV = 10210;
    case NegativeOnlineCAM = 10211;
    case BlockedCard = 10212;
    case RevocationOfAuthorizationOrder = 10213;
    case VerificationDataFailed = 10214;
    case Policy = 10215;
    case InvalidNonexistentAccountSpecified = 10216;
    case SoftDeclineDuplicate = 10301;


    public function reason(): string
    {
        return match ($this) {
            self::Undefined => 'Undefined',
            self::ThreeDSFlowIncomplete => '3DS flow incomplete',
            self::ThreeDSValidationFailed => '3DS validation failed',
            self::PaymentsPolicyAcquiringRestriction => 'Payments Policy Acquiring Restriction',
            self::ReferToCardIssuer => 'Refer to card issuer',
            self::InvalidMerchantNumber => 'Invalid merchant number',
            self::PickUpCard => 'Pick up card',
            self::DoNotHonor => 'Do not honor',
            self::GeneralError => 'General error',
            self::PickUpCardSpecialCondition => 'Pick up card, special condition (fraud account)',
            self::InvalidTransaction => 'Invalid transaction',
            self::InvalidAmount => 'Invalid amount',
            self::InvalidCardNumber => 'Invalid card number',
            self::InvalidIssuer => 'Invalid issuer',
            self::FormatError => 'Format error',
            self::NoCreditAccount => 'No credit account',
            self::LostCard => 'Lost card',
            self::StolenCard => 'Stolen card',
            self::ClosedAccount => 'Closed Account',
            self::InsufficientFunds => 'Insufficient funds',
            self::NoCheckingAccount => 'No checking account',
            self::NoSavingsAccount => 'No savings account',
            self::ExpiredCard => 'Expired card',
            self::FunctionNotPermittedToCardholder => 'Function not permitted to cardholder',
            self::FunctionNotPermittedToTerminal => 'Function not permitted to terminal',
            self::SuspectedFraud => 'Suspected Fraud',
            self::WithdrawalLimitExceeded => 'Withdrawal limit exceeded',
            self::RestrictedCard => 'Restricted card',
            self::IssuerResponseSecurityViolation => 'Issuer response security violation',
            self::SoftDecline => 'Soft decline',
            self::CallIssuer => 'Call issuer',
            self::PINEntryTriesExceeded => 'PIN entry tries exceeded',
            self::InvalidToAccountSpecified => 'Invalid / non-existent "to account" specified',
            self::InvalidFromAccountSpecified => 'Invalid / non-existent "from account" specified',
            self::LifeCycle => 'Life Cycle',
            self::NoFinancialImpact => 'No financial impact',
            self::FraudSecurity => 'Fraud/Security',
            self::InvalidAuthorizationLifeCycle => 'Invalid Authorization Life Cycle',
            self::CryptographicFailure => 'Cryptographic failure',
            self::UnacceptablePINTransactionDeclinedRetry => 'Unacceptable PIN - Transaction Declined - Retry',
            self::TransactionCannotBeCompletedViolationOfLaw => 'Transaction cannot be completed, violation of law',
            self::SystemMalfunction => 'System malfunction',
            self::GenericError => 'Generic error',
            self::InvalidCVV => 'Invalid CVV',
            self::NegativeOnlineCAM => 'Negative Online CAM, dCVV, iCVV, CVV, or CAVV results or Offline PIN Authentication',
            self::BlockedCard => 'Blocked Card',
            self::RevocationOfAuthorizationOrder => 'Revocation of authorization order',
            self::VerificationDataFailed => 'Verification Data Failed',
            self::Policy => 'Policy',
            self::InvalidNonexistentAccountSpecified => 'Invalid/nonexistent account specified (general',
            self::SoftDeclineDuplicate => 'Soft decline Duplicate',
            default => ''
        };
    }

    public function explanation(): string
    {
        return match ($this) {
            self::Undefined => 'Undefined',
            self::ThreeDSFlowIncomplete => 'Browser closed before authentication finished',
            self::ThreeDSValidationFailed => 'Wrong password or two-factor auth code entered',
            self::PaymentsPolicyAcquiringRestriction => 'Payments Policy Acquiring Restriction',
            self::ReferToCardIssuer => 'The issuing bank prevented the transaction',
            self::InvalidMerchantNumber => 'Security violation (source is not correct issuer)',
            self::PickUpCard => 'The card has been designated as lost or stolen',
            self::DoNotHonor => 'The issuing bank declined the transaction without an explanation',
            self::GeneralError => 'The card issuer has declined the transaction as there is a problem with the card number',
            self::PickUpCardSpecialCondition => 'This usually means the customer’s bank has stopped the transaction because the card has been marked as fraudulent',
            self::InvalidTransaction => 'The bank has declined the transaction because of an invalid format or field. This indicates the card details were incorrect',
            self::InvalidAmount => 'The card issuer has declined the transaction because of an invalid format or field',
            self::InvalidCardNumber => 'The card issuer has declined the transaction as the credit card number is incorrectly entered or does not exist',
            self::InvalidIssuer => 'The card issuer doesn\'t exist',
            self::FormatError => 'The card issuer does not recognise the transaction details being entered. This is due to a format error',
            self::NoCreditAccount => 'The issuer has declined the transaction as the card number used is not a credit account',
            self::LostCard => 'The card issuer has declined the transaction as the card has been reported lost',
            self::StolenCard => 'The card has been designated as lost or stolen',
            self::ClosedAccount => 'The transaction has been refused as the account is closed',
            self::InsufficientFunds => 'The card has insufficient funds to cover the cost of the transaction',
            self::NoCheckingAccount => 'The issuer has declined the transaction as the credit card number is associated with a checking account that does not exist',
            self::NoSavingsAccount => 'The issuer has declined the transaction as the credit card number is associated with a savings account that does not exist',
            self::ExpiredCard => 'The payment gateway declined the transaction because the expiration date is expired or does not match',
            self::FunctionNotPermittedToCardholder => 'The card issuer has declined the transaction as the credit card cannot be used for this type of transaction',
            self::FunctionNotPermittedToTerminal => 'The card issuer has declined the transaction as the credit card cannot be used for this type of transaction',
            self::SuspectedFraud => 'The issuing bank has declined the transaction as a result of suspected fraud',
            self::WithdrawalLimitExceeded => 'Exceeds withdrawal amount limit',
            self::RestrictedCard => 'The customer\'s bank has declined their card',
            self::IssuerResponseSecurityViolation => 'Flag raised due to security validation problem',
            self::SoftDecline => 'The issuer requests Strong Customer Authentication. The merchant should retry the transaction after successfully authenticating customer with 3DS first',
            self::CallIssuer => 'Contact card issuer',
            self::PINEntryTriesExceeded => 'Allowable number of PIN tries exceeded',
            self::InvalidToAccountSpecified => 'An invalid or non-existent "to" account has been specified',
            self::InvalidFromAccountSpecified => 'An invalid or non-existent "from" account has been specified',
            self::LifeCycle => 'Issuer response: \'Life Cycle\' issue',
            self::NoFinancialImpact => 'This is usually returned when a reversal is sent for an authorization message that was already declined',
            self::FraudSecurity => 'Issuer response: \'Fraud/Security\' issue',
            self::InvalidAuthorizationLifeCycle => 'Issuer response: \'Invalid Authorization Life Cycle\' issue',
            self::CryptographicFailure => 'Issuer response: \'Cryptographic failure\' issue',
            self::UnacceptablePINTransactionDeclinedRetry => 'The entered PIN code was incorrect',
            self::TransactionCannotBeCompletedViolationOfLaw => 'The issuing bank has recognized (or has been informed of) a legal violation on the part of the credit card user, and assets have been frozen',
            self::SystemMalfunction => 'A temporary error occurred during the transaction',
            self::GenericError => 'A temporary error occurred during the transaction',
            self::InvalidCVV => 'The CVV2 code entered is incorrect',
            self::NegativeOnlineCAM => 'Issuer response: \'Negative Online CAM, dCVV, iCVV, CVV, or CAVV results or Offline PIN Authentication\' issue',
            self::BlockedCard => 'Transaction from new cardholder, and card not properly unblocked',
            self::RevocationOfAuthorizationOrder => 'The cardholder has revoked authorization for future payments to a particular merchant',
            self::VerificationDataFailed => 'Issuer response: \'Verification Data Failed\' issue',
            self::Policy => 'The transaction has been refused due to a policy reason',
            self::InvalidNonexistentAccountSpecified => 'The issuing bank of the credit card could not find an account for the card',
            self::SoftDeclineDuplicate => 'The issuer requests Strong Customer Authentication. The merchant should retry the transaction after successfully authenticating customer with 3DS first',
        };
    }
}