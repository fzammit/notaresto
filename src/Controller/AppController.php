<?php

namespace App\Controller;

use App\Entity\Restaurant;
use App\Entity\Review;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController
{
    /**
     * @Route("/", name="app_index", methods={"GET"})
     */
    public function index()
    {
        dd(
            $this->getDoctrine()->getRepository(Review::class)->findBestTenRatings()
        );

        return $this->render('app/index.html.twig', [
            'restaurants' => $this->getDoctrine()->getRepository(Restaurant::class)->findLastTenElements(),
        ]);
    }
}
