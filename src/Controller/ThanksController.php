<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\DrinkOrderRepository;
use Symfony\Component\HttpFoundation\Request;

class ThanksController extends AbstractController
{
    /**
     * @Route("/thanks", name="thanks")
     */
    public function index(Request $request, DrinkOrderRepository $orderRep)
    {
        $orderId=$request->query->get('order')??0;
        $order=$orderRep->find($orderId);
        if(null==$order)
            $this->addFlash ('error', 'Cyba načtení objednávky');
        return $this->render('thanks/thanks.html.twig', [
            'order' => $order,
        ]);
    }
}
