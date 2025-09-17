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

    // Fetch brands for this country and if no brands for this country, return default toplist
    $brands = $brandRepo->findByCountry($country) ?: $brandRepo->findDefaultToplist();
   
    return $this->json($brands);
    }

    #[Route('/create', name: 'app_brands_create', methods: ['POST'])]
     public function create(Request $request, BrandRepository $brandRepo): JsonResponse
    {
        if (!$this->checkAdmin($request)) {
            return $this->json(['error' => 'Unauthorized'], 401);
        }

        $data = json_decode($request->getContent(), true);
        $brand = $brandRepo->createBrand($data);

        return $this->json($brand, 201);
    }

    #[Route('/{id}', name: 'app_brands_delete', methods: ['DELETE'])]
     public function delete(int $id, BrandRepository $brandRepo): JsonResponse
    {
        if (!$this->checkAdmin($request)) {
            return $this->json(['error' => 'Unauthorized'], 401);
        } 

        $brand = $brandRepo->find($id);
        if (!$brand) {
            return response()->json(['error' => 'Brand not found'], 404);
        }
        
        $brandRepo->remove($brand);
        return $this->json($null, 201);
    }

    #[Route('/update', name: 'app_brands_update', methods: ['POST'])]
     public function update(Request $request, BrandRepository $brandRepo): JsonResponse
    {
        if (!$this->checkAdmin($request)) {
            return $this->json(['error' => 'Unauthorized'], 401);
        }

        $brand = $brandRepo->find($id);
         if (!$brand) {
            return response()->json(['error' => 'Brand not found'], 404);
        }

        $data = json_decode($request->getContent(), true);
        $brand = $brandRepo->updateBrand($brand, $data);

        return $this->json($brand);
 
    }

      private function checkAdmin(Request $request): bool
    {
        $auth = $request->headers->get('Authorization');
        if (!$auth || !str_starts_with($auth, 'Basic ')) {
            return false;
        }

        $encoded = substr($auth, 6);
        $decoded = base64_decode($encoded);
        [$user, $pass] = explode(':', $decoded, 2);

        return $user === $_ENV['ADMIN_USER'] && $pass === $_ENV['ADMIN_PASSWORD'];
    }
}
