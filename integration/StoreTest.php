<?php

/*
 * This file is part of the Connect project.
 */

namespace integration;

//include('APITestCase.php');

/**
 * Tests for checking API methods for Store
 */
class StoreTest extends APITestCase
{
    /**
     * Check inventory data
     */
    public function testGetStoreInventory()
    {
        $response = $this->request('GET', 'store/inventory');
        $this->assertStatusCode($response, 200);
        $this->assertNotEmpty($response->getBody()->getContents(), 'Method should return not empty response');
    }

    /**
     * Check main order flow: add order, get order, delete order
     */
    public function testMainOrderFlow()
    {
        $json = '{"id":123,"petId":55,"quantity":1,"shipDate":"2018-05-11T05:42:48.281+0000","status":"placed","complete":true}';

        $response = $this->request('POST', 'store/order', $json);
        $this->assertStatusCode($response, 200);
        $this->assertSame($json, $response->getBody()->getContents(), 'Wrong content');

        $response = $this->request('GET', 'store/order/123');
        $this->assertStatusCode($response, 200);
        $this->assertSame($json, $response->getBody()->getContents(), 'Wrong content');

        $response = $this->request('DELETE', 'store/order/123');
        $this->assertStatusCode($response, 200);
        $this->assertEmpty($response->getBody()->getContents(), 'Wrong content');

        $response = $this->request('GET', 'store/order/123');
        $this->assertStatusCode($response, 404);
    }

    /**
     * Add order with empty body. Method should return status code 400.
     * @ticket BUG-001
     */
    public function testPostOrderWithEmptyBody()
    {
        $response = $this->request('POST', 'store/order', '');
        $this->assertStatusCode($response, 400);
        $this->assertSame(
            '{"code":400,"type":"invalid","message":"invalid body"}',
            $response->getBody()->getContents(),
            'Wrong content'
        );
    }

    /**
     * Get order by invalid id. Method should return status code 404.
     */
    public function testGetOrderByInvalidId()
    {
        $response = $this->request('GET', 'store/order/test');
        $this->assertStatusCode($response, 404);
        $this->assertSame(
            '{"code":404,"type":"unknown","message":"java.lang.NumberFormatException: For input string: \"test\""}',
            $response->getBody()->getContents(),
            'Wrong content'
        );
    }

    /**
     * Get order without id. Method should return status code 400.
     * @ticket BUG-002
     */
    public function testGetOrderWithoutId()
    {
        $response = $this->request('GET', 'store/order/');
        $this->assertStatusCode($response, 400);
        $this->assertSame(
            '{"code":400,"type":"error","message":"invalid id supplied"}',
            $response->getBody()->getContents(),
            'Wrong content'
        );
    }

    /**
     * Delete order by invalid id. Method should return status code 404.
     */
    public function testDeleteOrderByInvalidId()
    {
        $response = $this->request('DELETE', 'store/order/test');
        $this->assertStatusCode($response, 404);
        $this->assertSame(
            '{"code":404,"type":"unknown","message":"java.lang.NumberFormatException: For input string: \"test\""}',
            $response->getBody()->getContents(),
            'Wrong content'
        );
    }
}
