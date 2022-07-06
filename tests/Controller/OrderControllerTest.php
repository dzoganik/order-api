<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use DateTime;
use Exception;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class OrderControllerTest
 * @package App\Tests\Controller
 */
class OrderControllerTest extends WebTestCase
{
    /**
     * @return void
     */
    public function testCreate(): void
    {
        $partnerId = '12345p';
        $orderId = 'asdf456';
        $data = [
            'partnerId' => $partnerId,
            'orderId' => $orderId,
            'deliveryDate' => '2022-08-01',
            'orderValue' => '5500.50',
            'orderItems' => [
                [
                    'productId' => 'p789',
                    'title' => 'Posteľ 456',
                    'price' => '5000',
                    'quantity' => '2',
                ],
                [
                    'productId' => 'p7890',
                    'title' => 'Názov produktu 55',
                    'price' => '500.50',
                    'quantity' => '1.5',
                ],
            ]
        ];

        $client = static::createClient();
        $client->request('POST', '/orders', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($data));
        $response = $client->getResponse();
        $responseContent = json_decode($response->getContent(), true);

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertArrayHasKey('orderId', $responseContent);
        $this->assertEquals($orderId, $responseContent['orderId']);
        $this->assertArrayHasKey('partnerId', $responseContent);
        $this->assertEquals($partnerId, $responseContent['partnerId']);
    }

    /**
     * @return void
     * @throws Exception
     */
    public function testUpdate(): void
    {
        $deliveryDate = '2022-08-02';
        $partnerId = 'partner77777';
        $orderId = '777777';
        $data = ['deliveryDate' => $deliveryDate];

        $client = static::createClient();
        $client->request(
            'PATCH',
            '/orders/' . $orderId,
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_PARTNER_ID' => $partnerId,
            ],
            json_encode($data)
        );

        $response = $client->getResponse();
        $responseContent = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertArrayHasKey('orderId', $responseContent);
        $this->assertEquals($orderId, $responseContent['orderId']);
        $this->assertArrayHasKey('partnerId', $responseContent);
        $this->assertEquals($partnerId, $responseContent['partnerId']);
        $this->assertArrayHasKey('deliveryDate', $responseContent);
        $this->assertEquals($deliveryDate, (new DateTime($responseContent['deliveryDate']))->format('Y-m-d'));
    }
}
