<?php

namespace Cap\Commercio\Wallet;

use Exception;
use Symfony\Component\HttpClient\DecoratorTrait;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

trait ConnectionTrait
{
    use DecoratorTrait;

    private ?string $url = null;
    private ?string $url_token = null;
    public ?string $url_executed = null;
    public ?string $data_raw = null;

    public function connect( array $options): void
    {
        //$options['headers']['Accept'] = 'application/json';
        $this->client = HttpClient::create($options);
    }

    /**
     * @throws Exception
     */
    private function executeRequest(string $url, array $options = [], string $method = 'GET'): string
    {
        $this->url_executed = $url;
        try {
            $response = $this->request(
                $method,
                $url,
                $options
            );

            $this->data_raw = $response->getContent();

            return $this->data_raw;
        } catch (ExceptionInterface|ClientException|ClientExceptionInterface|RedirectionExceptionInterface|ServerExceptionInterface|TransportExceptionInterface $exception) {
            throw new Exception($exception->getMessage(), $exception->getCode(), $exception);
        }
    }

    private function debug(ResponseInterface $response)
    {
        var_dump($response->getInfo('debug'));
    }
}
