<?php

namespace App\Controller;

use App\Repository\ProductbRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;

class ProductbController extends AbstractController
{
    /**
     * @Route("/api/products", name="product_index", methods={"GET"})
     */
    public function index(
        ProductbRepository $repository,
        PaginatorInterface $paginator,
        Request $request,
        NormalizerInterface $normalizer
    ) {
        $products = $repository->findAll();
        if (!$products) {
            $data = [
                'status' => 404,
                'errors' => "Produits non trouvés",
            ];
            return $this->json($data, 404);
        }

//        $productNormalizer = $normalizer->normalize($products);

        $productslist = $paginator->paginate($products, $request->query->getInt('page', 1), 3);
        $response = $this->json($productslist, 200, [], []);
//        $response = $this->json($productslist, 200, [], ['groups' => 'product:read']);


        return $response;

    }


    /**
     * @Route("/api/products/{id}", name="product_detail", methods={"GET"})
     */
    public function detail(ProductbRepository $repository, $id)
    {
        $product = $repository->find($id);
        if (!$product) {
            $data = [
                'status' => 404,
                'errors' => "Produit non trouvé",
            ];
            return $this->json($data, 404);
        }

        $response = $this->json($product, 200, [], []);

        return $response;

    }
}
