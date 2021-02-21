<?php

namespace App\Controller;

use App\Entity\Api;
use App\Entity\Endpoint;
use App\Form\EndpointType;
use App\Repository\ApiRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ConsoleController extends AbstractController
{
    use ControllerHelper;

    protected ApiRepository $apiRepository;
    protected ParameterBagInterface $appParams;

    public function __construct(ApiRepository $apiRepository, ParameterBagInterface $appParams)
    {
        $this->apiRepository = $apiRepository;
        $this->appParams = $appParams;
    }

    /**
     * @Route("/console/{apiName}", name="console", methods="GET")
     */
    public function index(string $apiName): Response
    {
        $api = $this->apiRepository->findOneBy(['name' => $apiName]);

        if (!($api instanceof Api)) {
            throw $this->createNotFoundException('API not found');
        }

        $endpoints = $api->getEndpoints();

        return $this->render('console/index.html.twig', [
            'api' => $api,
            'endpoints' => $endpoints,
            'mercureHubUrl' => $this->appParams->get('mercure_hub_url'),
        ]);
    }

    /**
     * @Route("/console/{apiName}/new", name="console_new", methods="GET|POST")
     */
    public function new(string $apiName, Request $request)
    {
        $api = $this->apiRepository->findOneBy(['name' => $apiName]);

        if (!($api instanceof Api)) {
            throw $this->createNotFoundException('API not found');
        }

        $endpoint = new Endpoint();

        $form = $this->createForm(EndpointType::class, $endpoint, ['api_id' => $api->getId()]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $endpointHeaders = $endpoint->getEndpointHeaders();

            foreach ($endpointHeaders as $endpointHeader) {
                $endpointHeader->setEndpoint($endpoint);
                $endpoint->addEndpointHeader($endpointHeader);

                $this->getEntityManager()->persist($endpointHeader);
            }

            $this->getEntityManager()->persist($endpoint);
            $this->getEntityManager()->flush();

            return $this->redirectToRoute('console', ['apiName' => $api->getName()]);
        }

        return $this->render('console/new.html.twig', [
            'api' => $api,
            'form' => $form->createView(),
        ]);
    }
}
