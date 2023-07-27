<?php

namespace Cap\Commercio\Stripe;

use Cap\Commercio\Entity\CommercioCommercant;
use Cap\Commercio\Entity\Settings;
use Cap\Commercio\Repository\SettingRepository;
use Cap\Commercio\Setting\SettingEnum;
use Psr\Log\LoggerInterface;
use Stripe\Customer;
use Stripe\Exception\ApiErrorException;
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

    public function createPaymentCustomer(CommercioCommercant $commercant)
    {
        $result = false;

        //si le commercant possède une référence chez stripe
        if ($commercant['stripe_user_ref']) {
            $stripeCustomer = $this->customerDetails($commercant->getStripeUserRef());
            $this->currentCustomer = $stripeCustomer;
            $result = $stripeCustomer->id;
        } else {
            $stripeData = [];
            $stripeData['email'] = $commercant->getLegalEmail();
            $stripeData['description'] = $commercant->getLegalEntity();

            $stripeCustomer = $this->stripe->customers->create($stripeData);

            $commercant->setStripeUserRef($stripeCustomer->id);

            $stripeCustomer = $this->customerDetails($stripeCustomer['id']);
            $this->currentCustomer = $stripeCustomer;
            $result = $stripeCustomer['id'];
        }

        return $result;

    }

    public function getCreditCardListForCustomer()
    {
        $result = false;
        if ($this->currentCustomer) {
            $list = $this->currentCustomer->sources->all(array("object" => "card"));
            if (isset($list['data'])) {
                $result = $list['data'];
            }
        }

        return $result;
    }

    public function deleteAllCards()
    {
        $result = false;

        $list = $this->getCreditCardListForCustomer();
        if (is_array($list) & count($list) > 0) {
            foreach ($list as $card) {
                $card->delete();
            }
            $result = true;
        } else {
            $result = true;
        }


        return $result;
    }

    private function connect(): void
    {
        if (!$this->stripe) {
            Stripe::setLogger($this->logger);
            $this->stripe = new StripeClient($this->secret_key->getParamValue());
        }
    }
}