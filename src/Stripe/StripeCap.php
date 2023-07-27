<?php

namespace Cap\Commercio\Stripe;

use Cap\Commercio\Entity\CommercioCommercant;
use Cap\Commercio\Entity\Settings;
use Cap\Commercio\Repository\SettingRepository;
use Cap\Commercio\Setting\SettingEnum;
use Psr\Log\LoggerInterface;
use Stripe\Customer;
use Stripe\Exception\ApiErrorException;
use Stripe\PaymentIntent;
use Stripe\Stripe;
use Stripe\StripeClient;

class StripeCap
{
    private ?Settings $secret_key;
    private ?StripeClient $stripe = null;

    public function __construct(private SettingRepository $settingRepository, private LoggerInterface $logger)
    {
        $this->secret_key = $this->settingRepository->findValue(SettingEnum::STRIPE_SECRET_KEY->value);
    }

    /**
     * @return Customer[]
     * @throws ApiErrorException
     */
    public function customersAll(int $limit = 100): array
    {
        $customers = [];
        $this->connect();
        $years = range(2016, date('Y'));
        $startDate = new \DateTime();
        foreach ($years as $year) {
            $startDate->setDate($year, 12, 32);
            $data = $this->stripe->customers->all([
                'limit' => $limit,
                'created' => ["lt" => $startDate->getTimestamp()],
            ]);
            foreach ($data as $customer) {
                $customers[$customer->id] = $customer;
            }
        }

        return $customers;
    }

    /**
     * @throws ApiErrorException
     */
    public function customerDetails(string $id): Customer
    {
        $this->connect();

        return $this->stripe->customers->retrieve($id);

    }

    /**
     * @throws ApiErrorException
     */
    public function createCustomer(CommercioCommercant $commercant): Customer
    {
        $stripeData = [
            'email' => $commercant->getLegalEmail(),
            'description' => $commercant->getLegalEntity(),
        ];

        return $this->stripe->customers->create($stripeData);
    }

    /**
     * @param string $idClient
     * @return PaymentIntent[]
     * @throws ApiErrorException
     */
    public function listPayment(string $idClient): iterable
    {
        return $this->stripe->paymentIntents->all(['customer' => $idClient]);
    }

    /**
     * @param string $orderNumber
     * @param float $amount
     * @param string $description
     * @param $createCard
     * @param string $idClient
     * @return PaymentIntent
     * @throws ApiErrorException
     */
    public function createPayment(
        string $orderNumber,
        float $amount,
        string $description,
        $createCard,
        string $idClient
    ): PaymentIntent {
        $params = [
            'id' => $orderNumber,
            'amount' => $amount,
            'description' => $description,
            'createCard' => $createCard,
            'customer' => $idClient,
        ];

        return $this->stripe->paymentIntents->create(
            $params
        );
    }

    private function connect(): void
    {
        if (!$this->stripe) {
            Stripe::setLogger($this->logger);
            $this->stripe = new StripeClient($this->secret_key->getParamValue());
        }
    }
}