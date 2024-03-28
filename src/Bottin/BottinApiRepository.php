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
            $this->response = $response;

            return json_decode($response);
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage(), $exception->getCode(), $exception);
        }
    }

    /**
     * @param int $id
     * @return mixed
     * @throws \Exception
     */
    public function findCommerceById(int $id)
    {
        $this->connect();
        try {
            $response = $this->executeRequest($this->base_uri.'/bottin/fichebyid/'.$id);
            $this->response = $response;

            return json_decode($response);
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage(), $exception->getCode(), $exception);
        }
    }

    public function findCommerceBySociete(string $name)
    {
         $this->connect();
        try {
            $response = $this->executeRequest($this->base_uri.'/bottin/fichebyname/'.$name);
            $this->response = $response;

            return json_decode($response);
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage(), $exception->getCode(), $exception);
        }
    }

    public function findCommerceByEmail(string $email)
    {
         $this->connect();
        try {
            $response = $this->executeRequest($this->base_uri.'/bottin/fichebyemail/'.$email);
            $this->response = $response;

            return json_decode($response);
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage(), $exception->getCode(), $exception);
        }
    }
}