<?php

namespace Cap\Commercio\Service;

use OpenAI;
use OpenAI\Responses\Completions\CreateResponse;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use OpenAI\Responses\Models\ListResponse;

class OpenAiCap
{
    public function __construct(
        #[Autowire(env: 'OPENAI_API_KEY')]
        private readonly string $openAiKey
    ) {
    }

    public function test(string $prompt): CreateResponse
    {
        $yourApiKey = getenv($this->openAiKey);
        $client = OpenAi::client($yourApiKey);

        return $client->completions()->create([
            'model' => 'text-davinci-003',
            'prompt' => $prompt,
        ]);
    }

    public function modelList(): ListResponse
    {
        $yourApiKey = getenv($this->openAiKey);
        $client = OpenAi::client($yourApiKey);

        return $client->models()->list();
    }
}