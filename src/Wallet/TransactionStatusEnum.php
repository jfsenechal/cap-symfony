<?php

namespace Cap\Commercio\Wallet;

enum TransactionStatusEnum: string
{
    case Finished = 'F';
    case Active = 'A';
    case Captured = 'C';
    case Error = 'E';
    case Refunded = 'R';
    case Cancelled = 'X';
    case Claimed = 'M';
    case Claim_Awaiting_Response = 'MA';
    case Claim_In_Progress = 'MI';
    case Claim_Lost = 'ML';
    case Suspected_Claimed = 'MS';
    case Claim_Won = 'MW';

    public function getLabel(): string
    {
        return match ($this) {
            self::Finished => 'Finished',
            self::Active => 'Active',
            self::Captured => 'Captured',
            self::Error => 'Error',
            self::Refunded => 'Refunded',
            self::Cancelled => 'Cancelled',
            self::Claimed => 'Claimed',
            self::Claim_Awaiting_Response => 'Claim Awaiting Response',
            self::Claim_In_Progress => 'Claim In Progress',
            self::Claim_Lost => 'Claim Lost',
            self::Suspected_Claimed => 'Suspected Claimed',
            self::Claim_Won => 'Claim Won',
        };
    }

    public function getDescription(): string
    {
        return match ($this) {
            self::Finished => 'The transaction has been completed successfully (PAYMENT SUCCESSFUL)',
            self::Active => 'The transaction is in progress (PAYMENT PENDING)',
            self::Captured => 'The transaction has been captured (the C status refers to the original pre-auth transaction which has now been captured; the capture will be a separate transaction with status F)',
            self::Error => 'The transaction was not completed successfully (PAYMENT UNSUCCESSFUL)',
            self::Refunded => 'The transaction has been fully or partially refunded',
            self::Cancelled => 'The transaction was cancelled by the merchant',
            self::Claimed => 'The cardholder has disputed the transaction with the issuing Bank',
            self::Claim_Awaiting_Response => 'Dispute Awaiting Response',
            self::Claim_In_Progress => 'Dispute in Progress',
            self::Claim_Lost => 'A disputed transaction has been refunded (Dispute Lost)',
            self::Suspected_Claimed => 'Dispute Won',
            self::Claim_Won => 'Suspected Dispute',
        };
    }
}
