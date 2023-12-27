<?php

namespace Cap\Commercio\Wallet;

use Psr\Cache\InvalidArgumentException;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Contracts\Cache\CacheInterface;

class WalletApi
{
    use ConnectionTrait;

    public function __construct(
        #[Autowire(env: 'WALLET_API_URL')] string $url,
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
        return $this->cache->get('token', function () {
            $auth = base64_encode($this->clientId.':'.$this->clientKey);
            $options = [
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'Authorization' => 'Basic '.$auth,
                ],
                'body' => [
                    'grant_type' => 'client_credentials',
                ],
            ];

            $this->connect($options);

            $responseString = $this->executeRequest($this->urlToken, $options, "POST");

            return json_decode($responseString);
        });
    }


}
