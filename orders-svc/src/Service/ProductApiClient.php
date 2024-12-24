<?php

namespace App\Service;

use App\DTO\ApiProductResponse;
use App\Service\Parser;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Uid\Uuid;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ProductApiClient
{
    public function __construct(
        private HttpClientInterface $client,
        private Parser $parser,
        private string $productApiHost
    ) {}

    public function getProduct(Uuid $id): ApiProductResponse
    {
        $response = $this->client->request(
            'GET',
            $this->productApiHost . '/products/' . $id
        );

        $responseStatus = $response->getStatusCode();
        $result = new ApiProductResponse(httpCode: $responseStatus);

        if ($responseStatus === 200) {
            $productData = json_decode($response->getContent());
            $product = $this->parser->parseProduct($productData->data);

            $result->setProduct($product);
        } else {
            $message = match ($responseStatus) {
                JsonResponse::HTTP_NOT_FOUND => 'Product with this id does not exist',
                JsonResponse::HTTP_BAD_REQUEST => 'Wrong product ID format - not UUID',
                default => 'Something went wrong.',
            };

            $result->setMessage($message);
        }

        return $result;
    }

    public function updateProductInventory(Uuid $productId, int $qty): bool
    {
        $response = $this->client->request(
            'PUT',
            $this->productApiHost . '/products/' . $id, [
                'body' => [ "qty" => $qty ]
            ]
        );
    }
}