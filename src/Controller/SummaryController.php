<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CustomerRepository;
use App\Repository\OrderItemRepository;
use App\Repository\ProductRepository;
use App\Service\Paginator;

class SummaryController extends AbstractController
{
    /**
     * @Route("/summary/user/{customerId}", name="summary_user")
     */
    public function summaryUser(CustomerRepository $customerRep, OrderItemRepository $itemRep, Paginator $paginator, int $customerId)
    {
        $customer=$customerRep->find($customerId)??0;
        if(null==$customer)
        {
            $this->addFlash ('error', 'Chyba načtení uživatele');
            $title='Chyba';
        }
        else
            $title=$customer->getName();
        $items=$itemRep->itemsByCustomer($customer);
        $pag=$paginator->buildPaginer($items, 10);
        $sum=$itemRep->sumByCustomer($customer);
        return $this->render('summary/summaryUser.html.twig', [
            'title' => $title,
            'items'=>$pag->entities(),
            'links'=>$pag->links(),
            'sum'=>$sum,
        ]);
    }
    
    /**
     * @Route("/summary/product/{productId}", name="summary_product")
     */
    public function summaryProduct(ProductRepository $productRep, OrderItemRepository $itemRep, Paginator $paginator, int $productId)
    {
        $product=$productRep->find($productId)??0;
        if(null==$product)
        {
            $this->addFlash ('error', 'Chyba načtení produktu');
            $title='Chyba';
        }
        else
            $title=$product->getName();
        $items=$itemRep->itemsByProduct($product);
        $pag=$paginator->buildPaginer($items,10);
        $sum=$itemRep->sumByProduct($product);
        return $this->render('summary/summaryProduct.html.twig', [
            'title' => $title,
            'items'=>$pag->entities(),
            'links'=>$pag->links(),
            'sum'=>$sum,
        ]);
    }
    
    /**
     * @Route("/summary/all", name="summary_all")
     */
    public function summaryAll(OrderItemRepository $itemRep, Paginator $paginator)
    {
        $items=$itemRep->itemsPerDay();
        $pag=$paginator->buildPaginer($items,10);
        $sum=$itemRep->sumPerDay();
        return $this->render('summary/summaryAll.html.twig', [
            'items'=>$pag->entities(),
            'links'=>$pag->links(),
            'sum'=>$sum,
        ]);
    }
}
