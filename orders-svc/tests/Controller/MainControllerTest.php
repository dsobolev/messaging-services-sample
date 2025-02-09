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

        $productId = Uuid::v4();
        $qty = 0;
        $this->setProductApiForProductQty($productId, $qty);

        $client->request('POST', '/orders', [
            'qty' => 20,
            'product' => $productId,
        ]);

        $this->assertResponseStatusCodeSame(400);

        $this->assertResponseText($client->getResponse(), 'Product is out of stock');
    }

    // public function testNotPossibleToOrderTheAmountGiven(): void
    // {
    //     $client = static::createClient();
    // }

    private function setProductApiForProductQty(Uuid $productId, int $productQty): void
    {
        $container = static::getContainer();

        $productResponse = $this->buildProductApiResponseWithQty($productId, $productQty);

        $productApi = $this->prophesize(ProductApiClient::class);
        $productApi
            ->getProduct(/*Argument::type(Uuid::class)*/$productId)
            ->willReturn($productResponse)
        ;
        $container->set(ProductApiClient::class, $productApi->reveal());
    }

    private function buildProductApiResponseWithQty(Uuid $productId, int $productQty): ApiProductResponse
    {
        $product = new Product(
            id: $productId,
            name: 'product #1',
            price: 1.00,
            qty: $productQty,
        );

        return new ApiProductResponse(
            httpCode: 200,
            product: $product
        );
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
