<?php

namespace Tests\Feature;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    private $endpoint = 'api/auth/auth';

    public function testWithCredentialsInvalid()
    {
        $payload = [
            'email' => 'tester1',
            'password' => 'invalid',
        ];

        $this->json('POST', $this->endpoint, $payload, [ "application/json"])
            ->assertStatus(401)
            ->assertJsonStructure([
                "meta" => [
                    "success",
                    "errors"
                ]
            ]);
    }

    public function testLoginWithCredentialsValid()
    {

        $payload = [
            'username' => 'tester1',
            'password' => 'tester1',
        ];

        $this->json('POST', $this->endpoint, $payload, [ "application/json"])
            ->assertStatus(200)
            ->assertJsonStructure([
                "meta" => [
                    "success" ,
                    "error"
                ],
                "data"=> [
                    "token",
                    "minutes_to_Expire"
                ]
            ]);

    }
}
