<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Customer;
use App\Repository\CustomerRepository;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home(CustomerRepository $customerRep)
    {
        $customers=$customerRep->findBy([], ['name'=>'asc']);
        return $this->render('home/home.html.twig', [
            'customers'=>$customers,
        ]);
    }
}
