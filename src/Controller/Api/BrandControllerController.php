<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\BrandRepository;
use Symfony\Component\Routing\Attribute\Route;

 #[Route('/api/brands', name: 'api_brands_')]
 class BrandControllerController extends AbstractController
{
    #[Route('/list', name: 'app_brands_list', methods: ['GET'])]
    public function index(Request $request, BrandRepository $brandRepo): JsonResponse
    {

    // Get user's country from CF-IPCountry header, fallback to 'US'(hardcoded version)
    $country = $request->headers->get('CF-IPCountry', 'US');

    // Fetch brands for this country
    $brands = $brandRepo->findByCountry($country);

    // If no brands for this country, return default toplist
    if (empty($brands)) {
        $brands = $brandRepo->findDefaultToplist();
    }

    // Return JSON
    return $this->json($brands);
        // return $this->json([
        //     'message' => 'Welcome to your new controller!',
        //     'path' => 'src/Controller/Api/BrandControllerController.php',
        // ]);
    }

    #[Route('/brands', name: 'app_brands_create', methods: ['POST'])]
     public function create(Request $request, BrandRepository $brandRepo): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $brand = $brandRepo->createBrand($data);

        return $this->json($brand, 201);
    }

    #[Route('/{id}', name: 'app_brands_delete', methods: ['DELETE'])]
     public function delete(int $id, BrandRepository $brandRepo): JsonResponse
    {
        $brand = $brandRepo->find($id);
        if (!$brand) {

            return response()->json([
            'error' => 'Brand not found'], 404);
        }
        
        $brandRepo->remove($brand);
        return $this->json($null, 201);
    }
}
