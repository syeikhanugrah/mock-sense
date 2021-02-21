<?php

namespace App\Controller;

use App\Entity\Api;
use App\Entity\Endpoint;
use App\Repository\ApiRepository;
use App\Repository\EndpointRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\PublisherInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Annotation\Route;

class RouteController extends AbstractController
{
    protected ApiRepository $apiRepository;
    protected EndpointRepository $endpointRepository;

    public function __construct(ApiRepository $apiRepository, EndpointRepository $endpointRepository)
    {
        $this->apiRepository = $apiRepository;
        $this->endpointRepository = $endpointRepository;
    }

    /**
     * @Route("/api/{apiName}/{path}", name="api_router", requirements={"path"=".+"})
     */
    public function index(Request $request, PublisherInterface $publisher, string $apiName = '', string $path = ''): Response
    {
        $api = $this->apiRepository->findOneBy(['name' => $apiName]);

        $defaultResponse = 'Nothing is configured for this request path. Create a rule and start building a mock API.';

        if (!($api instanceof Api)) {
            return new Response($defaultResponse, 200);
        }

        $endpoint = $this->endpointRepository->findOneBy([
            'api' => $api,
            'method' => $request->getMethod(),
            'path' => '/'.$path,
        ]);

        if (!($endpoint instanceof Endpoint)) {
            $updateTopic = sprintf('api/%s', $apiName);
            $qs = $request->getQueryString() ?? '';
            $completeQs = $qs ? '?'.$qs : '';

            $updateValue = [
                'path' => $path . $completeQs,
                'method' => $request->getMethod(),
                'requestHeaders' => $request->headers->__toString(),
                'responseBody' => $defaultResponse,
                'definedRoute' => false,
            ];

            $update = new Update($updateTopic, json_encode($updateValue));

            $publisher($update);

            return new Response($defaultResponse, 200);
        }

        $endpointHeadersArrayCollection = $endpoint->getEndpointHeaders();
        $endpointHeadersArray = [];

        foreach ($endpointHeadersArrayCollection as $endpointHeader) {
            $endpointHeadersArray[$endpointHeader->getName()] = $endpointHeader->getValue();
        }

        $updateTopic = sprintf('api/%s', $apiName);

        $updateValue = [
            'path' => $path,
            'method' => $request->getMethod(),
            'requestHeaders' => $request->headers->__toString(),
            'responseBody' => $endpoint->getResponseBody(),
            'definedRoute' => true,
        ];

        $update = new Update($updateTopic, json_encode($updateValue));

        $publisher($update);

        return new Response($endpoint->getResponseBody(), $endpoint->getStatusCode(), $endpointHeadersArray);
    }
}
