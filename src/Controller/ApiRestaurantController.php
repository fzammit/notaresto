<?php

namespace App\Controller;

use App\Entity\City;
use App\Entity\Restaurant;
use App\Entity\User;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class ApiRestaurantController extends AbstractController
{
    public $serializer;

    public function __construct()
    {
        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer($classMetadataFactory)];

        $this->serializer = new Serializer($normalizers, $encoders);
    }

    /**
     * @Route("/api/restaurant", name="api_restaurant", methods={"GET"})
     */
    public function index()
    {
        $restaurants = $this->getDoctrine()->getRepository(Restaurant::class)->findAll();

        $data = $this->serializer->normalize($restaurants, null, ['groups' => 'al_restaurants']);

        $jsonContent = $this->serializer->serialize($data, 'json');

        $response = new Response($jsonContent);
        $response->headers->set('content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route("/api/restaurants", name="api_restaurant_create", methods={"POST"})
     */
    public function create( Request $request)
    {

        /**
         * On lit les données de la requête :
         */
        dump($request->request);

        /**
         * Pour lire un champ de la requête, parmi les champs donnés dans Postman (ici, le
         * champ 'name' par exemple)
         */
        dd($request->request->get('name'));

        /**
         * On créée un restaurant en prenant les données de la requête
         */
        $restaurant = new Restaurant;
        $restaurant->setName('Nouveau resto');
        $restaurant->setDescription('Une description !');

        /**
         * On récupère les users 1 et city 1 (car l'objet Restaurant s'attend à des objets)
         */
        $user = $this->getDoctrine()->getRepository(User::class)->find(1);
        $city = $this->getDoctrine()->getRepository(City::class)->find(1);

        $restaurant->setUser($user);
        $restaurant->setCity($city);

        /**
         * On enregistre en base de données
         */
        $manager = $this->getDoctrine()->getManager();
        $manager->persist($restaurant);
        $manager->flush();
    }
}
