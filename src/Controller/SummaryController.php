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
     * @Route("/summary/user", name="summary_all_users")
     */
    public function summaryAllUsers(CustomerRepository $customerRep, OrderItemRepository $itemRep, Paginator $paginator)
    {
        $customers=$customerRep->findby([],['name'=>'asc']);
        foreach ($customers as $customer)
        {
            $customer->items=$itemRep->itemsByCustomer($customer)->getResult();
            $customer->sum=$itemRep->sumByCustomer($customer);
        }
        return $this->render('summary/summaryAllUsers.html.twig', [
            'customers' => $customers,
        ]);
    }
    
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
     * @Route("/summary/product", name="summary_all_products")
     */
    public function summaryAllProducts(ProductRepository $productRep, OrderItemRepository $itemRep, Paginator $paginator)
    {
        $products=$productRep->findBy([],['name'=>'asc']);
        foreach ($products as $product)
        {
            $product->items=$itemRep->itemsByProduct($product)->getResult();
            $product->sum=$itemRep->sumByProduct($product);
        }
        return $this->render('summary/summaryAllProducts.html.twig', [
            'products' => $products,
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
