<?php

namespace App\Controller;

use App\Entity\Userb;
use App\Repository\ClientbRepository;
use App\Repository\UserbRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;


//use Nelmio\ApiDocBundle\Annotation\ApiDoc;


class UserbController extends AbstractController
{


    protected $encoder;
    protected $authorizationChecker;
    protected $clientbRepository;
    protected $userbRepository;
    protected $manager;
    protected $serializer;
    protected $paginator;

    public function __construct(

        UserPasswordEncoderInterface $encoder,
        SerializerInterface $serializer,
        AuthorizationCheckerInterface $authorizationChecker,
        ClientbRepository $clientbRepository,
        UserbRepository $userbRepository,
        EntityManagerInterface $manager,
        PaginatorInterface $paginator
    ) {
        $this->encoder = $encoder;
        $this->authorizationChecker = $authorizationChecker;
        $this->clientbRepository = $clientbRepository;
        $this->userbRepository = $userbRepository;
        $this->manager = $manager;
        $this->serializer = $serializer;
        $this->paginator = $paginator;
    }


    /**
     * @Route("/api/{name}/users", name="user_index", methods={"GET"})
     *
     */
    public function index($name, Request $request)
    {
        $client = $this->clientbRepository->findBy(['name' => $name]);
        if (!$client) {
            $data = [
                'status' => 404,
                'errors' => "Client (" . $name . ") non trouv?? ",
            ];
            return $this->json($data, 404);
        }

        $users = $this->userbRepository->findByCustomer($client[0]);
        if (!$users) {
            $data = [
                'status' => 404,
                'errors' => "Pas d'utilisateurs pour ce client" . $name,
            ];
            return $this->json($data, 404);
        }

        $userslist = $this->paginator->paginate(
            $users, // Requ??te contenant les donn??es ?? paginer (ici nos utilisateurs)
            $request->query->getInt('page', 1), // Num??ro de la page en cours, pass?? dans l'URL, 1 si aucune page
            6 // Nombre de r??sultats par page
        );
        $response = $this->json($userslist, 200, [], ["groups" => "user:read"]);

        return $response;

    }

    /**
     * @Route("/api/{name}/users/{id}", name="user_detail", methods={"GET"})
     */
    public function detail($name, $id)
    {
        $client = $this->clientbRepository->findBy(['name' => $name]);
        if (!$client) {
            $data = [
                'status' => 404,
                'errors' => "Client (" . $name . ") non trouv?? ",
            ];
            return $this->json($data, 404);
        }


        $user = $this->userbRepository->findByCustomerAndUser($client[0], $id);
        if (!$user) {
            $data = [
                'status' => 404,
                'errors' => "Utilisateur non trouv??",
            ];
            return $this->json($data, 404);
        }

        $response = $this->json($user, 200, [], ["groups" => "user:read"]);

        return $response;

    }


    /**
     * @Route("/api/{name}/user", name="user_add", methods={"POST"})
     */
    public function add(Request $request, ValidatorInterface $validator, $name)
    {
        $json = $request->getContent();

        $client = $this->clientbRepository->findBy(['name' => $name]);
        if (!$client) {
            $data = [
                'status' => 404,
                'errors' => "Client (" . $name . ") non trouv?? ",
            ];
            return $this->json($data, 404);
        }
        try {
            $user = $this->serializer->deserialize($json, Userb::class, 'json');
            $errors = $validator->validate($user);
            if (count($errors) > 0) {
                return $this->json($errors, 400);
            }


            $hash = $this->encoder->encodePassword($user, "password");
            $user->setPassword($hash)
                ->setClientb($client[0]);

            try {
                $this->manager->persist($user);
                $this->manager->flush();
                $response = $this->json($user, 201, [], ["groups" => "user:read"]);

                return $response;
            } catch (NotEncodableValueException $error) {

                return $this->json([
                    'status' => 400,
                    'errors' => $error->getMessage(),
                ], 400);
            }


        } catch (NotEncodableValueException $e) {

            return $this->json([
                'status' => 400,
                'errors' => $e->getMessage(),
            ], 400);
        }


    }

    /**
     * @Route("/api/users/{id}", name="user_delete", methods={"DELETE"})
     */
    public function delete($id)
    {
        $user = $this->userbRepository->find($id);
        if (!$user) {
            $data = [
                'status' => 404,
                'errors' => "Utilisateur non trouv??",
            ];
            return $this->json($data, 404);
        }

        $this->manager->remove($user);
        $this->manager->flush();

        $response = $this->json('', 204, [], []);

        return $response;

    }
}
