<?php

namespace App\Controller;

use App\Entity\Productb;
use App\Repository\ProductbRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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

    /**
     * @Route("/api/products/{id}", name="product_update", methods={"PUT"})
     */
    public function update(
        $id,
        Request $request,
        EntityManagerInterface $manager,
        ProductbRepository $productbRepository,
        SerializerInterface $serializer,
        ValidatorInterface $validator
    ) {
//        $product = new Productb();
        $productExist = $productbRepository->find($id);
        if (!$productExist) {
            $data = [
                'status' => 404,
                'errors' => "Produit non trouvé",
            ];
            return $this->json($data, 404);
        }

        $json = $request->getContent();
        $product = $serializer->deserialize($json, Productb::class, 'json');

        $errors = $validator->validate($product);
        if (count($errors) > 0) {
            return $this->json($errors, 400);
        }

        $productExist->setName($product->getName())
            ->setDescription($product->getDescription())
            ->setPrice($product->getPrice())
            ->setBrand($product->getBrand());
        $manager->flush();

        $response = $this->json($productExist, 200, [], []);

        return $response;


    }
}
