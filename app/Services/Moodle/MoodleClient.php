<?php

namespace App\Services\Moodle;

use Illuminate\Support\Facades\Http;

class MoodleClient
{
    protected $baseUrl;
    protected $token;

    public function __construct()
    {
        $this->baseUrl = config('services.moodle.url');
        $this->token = config('services.moodle.token');
    }

    /**
     * Faz uma requisição para a API REST do Moodle.
     *
     * @param string $function Nome da função do Moodle
     * @param array $parameters Parâmetros da requisição
     * @return array
     */
    public function request($function, array $parameters = [])
    {
        $url = $this->baseUrl . '/webservice/rest/server.php';

        $parameters['wstoken'] = $this->token;
        $parameters['wsfunction'] = $function;
        $parameters['moodlewsrestformat'] = 'json';

        $response = Http::get($url, $parameters)->throw();

        return $response->json();
    }
}
