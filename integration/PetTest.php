<?php

/*
 * This file is part of the tests project.
 */

namespace integration;

include('APITestCase.php');

use Psr\Http\Message\ResponseInterface;

/**
 * Tests for checking main flow for API method /pet
 */
class PetTest extends APITestCase
{
    /**
     * Check main flow: add pet, change pet name, delete pet.
     */
    public function testMainPetFlow()
    {
        $json = '{"id":1,"category":{"id":0,"name":"Spiders"},"name":"Argiope Bruennichi","photoUrls":[""],"tags":[{"id":0,"name":"spider"}],"status":"available"}';
        $jsonEdited = '{"id":1,"category":{"id":0,"name":"Spiders"},"name":"Tarantula","photoUrls":[""],"tags":[{"id":0,"name":"spider"}],"status":"available"}';

        $response = $this->request('POST', 'pet', $json);
        $this->assertStatusCode($response, 200);
        $this->assertPet($json);

        $response = $this->request('PUT', 'pet', $jsonEdited);
        $this->assertStatusCode($response, 200);
        $this->assertPet($jsonEdited);

        $response = $this->request('DELETE', 'pet/1');
        $this->assertStatusCode($response, 200);

        $this->assertStatusCode($this->getPet(), 404);
    }

    /**
     * Try to delete Pet by non existent id. Method should return status code 404.
     */
    public function testDeletePetWithInvalidId()
    {
        $response = $this->request('DELETE', 'pet/test');
        $this->assertStatusCode($response, 404);
    }

    /**
     * Try to get Pet by non existent id. Method should return status code 404.
     */
    public function testGetPetByNonExistentId()
    {
        $response = $this->request('GET', 'pet/0');
        $this->assertStatusCode($response, 404);
        $this->assertSame(
            '{"code":1,"type":"error","message":"Pet not found"}',
            $response->getBody()->getContents()
        );
    }

    /**
     * Try to get Pet by invalid id. Method should return status code 405.
     */
    public function testGetPetByInvalidId()
    {
        $response = $this->request('GET', 'pet/abc');
        $this->assertStatusCode($response, 400);
        $this->assertSame(
            '{"code":400,"type":"error","message":"invalid id supplied"}',
            $response->getBody()->getContents()
        );
    }

    /**
     * @return mixed|null|ResponseInterface
     */
    private function getPet()
    {
        return $this->request('GET', 'pet/1');
    }

    /**
     * @param string $json
     *
     * @return mixed|null|ResponseInterface
     */
    private function assertPet($json)
    {
        $petResponse = $this->getPet();
        $this->assertStatusCode($petResponse, 200);
        $this->assertSame($json, $petResponse->getBody()->getContents(), 'Wrong content');

        return $petResponse;
    }
}
