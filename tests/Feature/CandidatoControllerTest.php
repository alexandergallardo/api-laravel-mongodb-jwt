<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Candidato;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class CandidatoControllerTest extends TestCase
{
    public function testAddNewLeadValid()
    {
        $payload = [
            'username' => 'tester1',
            'password' => 'tester1',
        ];

        $this->json('POST', 'api/auth/auth', $payload, [ "application/json"])
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

        $user = User::where('username', 'tester1')->first();
        $token = JWTAuth::fromUser($user);
        $baseUrl = 'api/lead';
        $data = ["name" => 'Mi Candidato PHPUnit', "source" => "PhpUnit", "owner" => $user->getAuthIdentifier()];

        $this->json('POST', 'api/lead', $data, [ 'Accept' => 'application/json','Authorization' => 'Bearer ' . $token])
            ->assertStatus(201)
            ->assertJsonStructure([
                "meta" => [
                    "success",
                    "errors"
                ],
                "data" => [
                    "id",
                    "name",
                    "source",
                    "owner",
                    "created_at",
                    "created_by"
                ]
            ]);

    }

    public function testAddNewLeadInvalid()
    {
        $user = User::where('username', 'tester1')->first();
        $token = 'invalidTOKENphpunit';
        $data = ["name" => 'Mi Candidato PHPUnit', "source" => "PhpUnit", "owner" => $user->getAuthIdentifier()];

        $this->json('POST', 'api/lead', $data, [ 'Accept' => 'application/json','Authorization' => 'Bearer ' . $token])
            ->assertStatus(401)
            ->assertJsonStructure([
                "meta" => [
                    "success",
                    "errors"
                ]
            ]);
    }

    public function testListLeadInvalidToken()
    {

        $token = 'invalidTOKENphpunit';

        $this->json('GET', 'api/leads', [], [ 'Accept' => 'application/json','Authorization' => 'Bearer ' . $token])
            ->assertStatus(401)
            ->assertJsonStructure([
                "meta" => [
                    "success",
                    "errors"
                ]
            ]);
    }

    public function testListLeadManager()
    {
        $payload = [
            'username' => 'tester1',
            'password' => 'tester1',
        ];

        $this->json('POST', 'api/auth/auth', $payload, [ "application/json"])
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

        $user = User::where('username', 'tester1')->first();
        $token = JWTAuth::fromUser($user);
        $baseUrl = 'api/leads';

        $this->json('GET', 'api/leads', [], [ 'Accept' => 'application/json','Authorization' => 'Bearer ' . $token])
            ->assertStatus(200)
            ->assertJsonStructure([
                "meta",
                "data"
            ]);
    }

    public function testListLeadAgent()
    {
        $payload = [
            'username' => 'tester2',
            'password' => 'tester2',
        ];

        $this->json('POST', 'api/auth/auth', $payload, [ "application/json"])
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

        $user = User::where('username', 'tester2')->first();
        $token = JWTAuth::fromUser($user);
        $baseUrl = 'api/leads';

        $this->json('GET', 'api/leads', [], [ 'Accept' => 'application/json','Authorization' => 'Bearer ' . $token])
            ->assertStatus(200)
            ->assertJsonStructure([
                "meta",
                "data"
            ]);
    }

    public function testGetLeadByIdTokenInvalid()
    {
        $token = 'invalidTOKENphpunit';

        $this->json('GET', 'api/lead/dhjsherixcb', [], [ 'Accept' => 'application/json','Authorization' => 'Bearer ' . $token])
            ->assertStatus(401)
            ->assertJsonStructure([
                "meta" => [
                    "success",
                    "errors"
                ]
            ]);
    }

    public function testGetLeadByIdNoFound()
    {
        $payload = [
            'username' => 'tester2',
            'password' => 'tester2',
        ];

        $this->json('POST', 'api/auth/auth', $payload, [ "application/json"])
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

        $user = User::where('username', 'tester2')->first();
        $token = JWTAuth::fromUser($user);

        $this->json('GET', 'api/lead/dhjsherixcb', [], [ 'Accept' => 'application/json','Authorization' => 'Bearer ' . $token])
            ->assertStatus(404)
            ->assertJsonStructure([
                "meta" => [
                    "success",
                    "errors"
                ]
            ]);
    }
}
