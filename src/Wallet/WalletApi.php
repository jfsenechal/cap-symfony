<?php

namespace Cap\Commercio\Wallet;

use Psr\Cache\InvalidArgumentException;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class WalletApi
{
    use ConnectionTrait;

    public function __construct(
        #[Autowire(env: 'WALLET_API_URL')] private readonly string $url,
        #[Autowire(env: 'WALLET_API_URL_TOKEN')] private readonly string $urlToken,
        #[Autowire(env: 'WALLET_API_ID')] private readonly string $merchantId,
        #[Autowire(env: 'WALLET_API_KEY')] private readonly string $key,
        #[Autowire(env: 'WALLET_CLIENT_ID')] private readonly string $clientId,
        #[Autowire(env: 'WALLET_CLIENT_KEY')] private readonly string $clientKey,
        private readonly CacheInterface $cache
    ) {

    }

    /**
     * @return \stdClass
     * @throws \Exception
     * @throws InvalidArgumentException
     */
    public function getToken(): \stdClass
    {
        return $this->cache->get('tokenWallet', function (ItemInterface $item) {
            $options = [
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ],
                'auth_basic' => $this->clientId.':'.$this->clientKey,
                'body' => [
                    'grant_type' => 'client_credentials',
                ],
            ];

            $this->connect($options);
            $responseString = $this->executeRequest($this->urlToken, $options, "POST");

            try {
                $data = json_decode($responseString, flags: JSON_THROW_ON_ERROR);
                $item->expiresAfter($data->expires_in);

                return $data;
            } catch (\Exception $exception) {
                throw new \Exception('error json_decode '.$responseString.' '.$exception->getMessage());
            }
        });
    }

    /**
     * @throws \Exception
     */
    public function createOrder(WalletOrder $order, string $token, array $options = []): ?string
    {
        $options = [
            'auth_bearer' => $token,
            'headers' => [
                'Content-Type' => 'application/json',
            ],
        ];

        $postFields = (array)$order;
        $postFields['customer'] = (array)$order->customer;

        $data['json'] = $postFields;

        $this->connect($options);

        return $this->executeRequest($this->url.'/orders', $data, "POST");
    }

    /**
     * @throws \Exception
     */
    public function retrieveTransaction(WalletOrder $order, string $token, array $options = []): ?string
    {
        $options = [
            'auth_bearer' => $token,
            'headers' => [
                'Content-Type' => 'application/json',
            ],
        ];

        $postFields = (array)$order;
        $postFields['customer'] = (array)$order->customer;

        $data['json'] = $postFields;

        $this->connect($options);

        return $this->executeRequest($this->url.'/orders', $data, "POST");
    }
}
