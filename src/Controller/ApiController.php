<?php

namespace App\Controller;

use App\Entity\Api;
use App\Form\ApiFormType;
use App\Repository\ApiRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    use ControllerHelper;

    protected ApiRepository $apiRepository;

    public function __construct(ApiRepository $apiRepository)
    {
        $this->apiRepository = $apiRepository;
    }

    /**
     * @Route("/", name="new_api", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $api = new Api();

        $form = $this->createForm(ApiFormType::class, $api);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $existingApi = $this->apiRepository->findOneBy(['name' => $api->getName()]);

            if ($existingApi) {
                $this->addFlash('error', sprintf('API with name %s already exist', $api->getName()));

                return $this->redirectToRoute('new_api');
            }

            $api->setToken(bin2hex(random_bytes(20)));

            $this->getEntityManager()->persist($api);
            $this->getEntityManager()->flush();

            return $this->redirectToRoute('console', ['apiName' => $api->getName()]);
        }

        return $this->render('api/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/api-info", name="get_api_info", methods="GET")
     */
    public function getApiInfo(Request $request)
    {
        $token = $request->get('token');
        $api = $this->apiRepository->findOneBy(['token' => $token]);

        if (!($api instanceof Api)) {
            return $this->json(['valid' => false]);
        }

        return $this->json([
            'valid' => true,
            'apiName' => $api->getName(),
        ]);
    }
}
