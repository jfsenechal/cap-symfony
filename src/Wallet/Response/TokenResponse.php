<?php

namespace Cap\Commercio\Wallet\Response;

class TokenResponse
{
    /**
     * {
     * "access_token": "eyJhbGciOiJSUzI1NiIsImtpZCI6IjBEOEZCOEQ2RURFQ0Y1Qzk3RUY1MjdDMDYxNkJCMjMzM0FCNjVGOUZSUzI1NiIsInR5cCI6ImF0K2p3dCIsIng1dC",
     * "expires_in": 3600,
     * "token_type": "Bearer",
     * "scope": "urn:viva:payments:core:api:redirectcheckout"
     * }*/
    public function __construct(
        public readonly string $access_token,
        public readonly int $expires_in,
        public readonly string $token_type,
        public readonly string $scope
    ) {
    }

    public static function newFromResponse(string $data): self
    {
        $data = json_decode($data);

        return new self($data->access_token, $data->expires_in, $data->token_type, $data->scope);
    }
}