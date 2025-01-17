<?php

namespace App\Tests\Controller;

use App\DTO\Product;
use App\DTO\ApiProductResponse;
use App\Service\ProductApiClient;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Uuid;

class MainControllerTest extends WebTestCase
{
    use ProphecyTrait;

    /**
     * @expectedException \Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     */
    public function testProductOutOfStockResponse(): void
    {
        $client = static::createClient();
        $container = static::getContainer();

        $productId = Uuid::v4();

        $product = new Product(
            id: $productId,
            name: 'product #1',
            price: 1.00,
            qty: 0,
        );

        $productResponse = new ApiProductResponse(
            httpCode: 200,
            product: $product
        );

        $productApi = $this->prophesize(ProductApiClient::class);
        $productApi
            ->getProduct(Argument::type(Uuid::class))
            ->willReturn($productResponse)
        ;
        $container->set(ProductApiClient::class, $productApi->reveal());

        $client->request('POST', '/orders', [
            'qty' => 20,
            'product' => $productId,
        ]);

        $this->assertResponseStatusCodeSame(400);

        $this->assertResponseText($client->getResponse(), 'Product is out of stock');
    }

    private function assertResponseText(Response $response, string $message): void
    {
        // Do not like the way the message is handled.
        // I'll better use direct error response instead of throwing an exception in MainController
        $content = json_decode($response->getContent());
        $this->assertObjectHasProperty('detail', $content);
        $this->assertEquals($message, $content->detail);
    }
}
