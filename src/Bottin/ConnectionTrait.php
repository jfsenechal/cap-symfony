<?php

namespace Cap\Commercio\Bottin;

use Exception;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

trait ConnectionTrait
{
    private ?HttpClientInterface $httpClient = null;
    public ?string $urlExecuted = null;

    public function connect(): void
    {
        $headers = [
            'verify_peer' => false,
        ];

        if (!$this->httpClient) {
            $this->httpClient = HttpClient::create($headers);
        }
    }


    /**
     * @throws Exception
     */
    private function executeRequest(string $url, array $options = [], string $method = 'GET'): string
    {
        $this->urlExecuted = $url;
        try {
            $response = $this->httpClient->request(
                $method,
                $url,
                $options
            );

            return $response->getContent();
        } catch (ClientException|ClientExceptionInterface|RedirectionExceptionInterface|ServerExceptionInterface|TransportExceptionInterface $exception) {
            throw new Exception($exception->getMessage(), $exception->getCode(), $exception);
        }
    }

    private function debug(ResponseInterface $response)
    {
        var_dump($response->getInfo('debug'));
    }
}
