<?php

namespace Cap\Commercio\Bottin;

use Symfony\Component\DependencyInjection\Attribute\Autowire;

class BottinApiRepository
{
    use ConnectionTrait;

    public function __construct(#[Autowire(env: 'BOTTIN_API_URL')] private readonly string $base_uri)
    {
    }

    /**
     * @return \stdClass[]
     * @throws \Exception
     */
    public function getCommerces(): array
    {
        $this->connect();
        try {
            $response = $this->executeRequest($this->base_uri.'/bottin/fiches');

            return json_decode($response);
        } catch (\Exception $e) {
            throw new $e;
        }
    }

    public function findCommerceById(int $id)
    {
        $this->connect();
        try {
            $response = $this->executeRequest($this->base_uri.'/bottin/fichebyid/'.$id);

            return json_decode($response);
        } catch (\Exception $e) {
            throw new $e;
        }
    }
}