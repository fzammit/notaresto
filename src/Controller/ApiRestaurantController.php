<?php

namespace App\Controller;

use App\Entity\Restaurant;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class ApiRestaurantController extends AbstractController
{
    public $serializer;

    public function __construct()
    {
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];

        $this->serializer = new Serializer($normalizers, $encoders);
    }

    /**
     * @Route("/api/restaurant", name="api_restaurant")
     */
    public function index()
    {
        $restaurants = $this->getDoctrine()->getRepository(Restaurant::class)->findAll();

        $jsonContent = $this->serializer->serialize($restaurants, 'json');

        echo $jsonContent;
    }
}
