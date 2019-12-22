<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\DrinkMenuType;
use App\Repository\ProductRepository;
use App\Entity\OrderItem;
use App\Entity\Customer;
use App\Entity\DrinkOrder;
use App\Repository\DrinkOrderRepository;
use App\Repository\CustomerRepository;

class DrinkMenuController extends AbstractController
{
    /**
     * @Route("/drink-menu", name="drink_menu")
     */
    public function drinkMenu(Request $request, ProductRepository $productRep, DrinkOrderRepository $orderRep, CustomerRepository $customerRep)
    {
        $customerId=$request->query->get('customer')??0;
        $customer=$customerRep->find($customerId);
        if(null==$customer)
        {   
            $this->addFlash('error', 'Vyberte uživatele');
            return $this->redirectToRoute('home');
        }
        
        $products= $productRep->findBy([],['name'=>'asc']);
        $form = $this->createForm(DrinkMenuType::class, $products);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) 
        {
            $alcoholProduct=[];
            $order=new DrinkOrder();
            $order->setCustomer($customer);
            foreach ($products as $product)
            {
                if($form['product_'.$product->getId()]->getData())
                {
                    $orderItem=new OrderItem();
                    $orderItem->setProduct($product);
                    $orderItem->setPrice($product->getPrice());
                    $order->addOrderItem($orderItem);
                    if($customer->getAge()<18 && $product->getAlcohol())
                        $alcoholProduct[]=$product->getName();
                }
            }
            if(!count($order->getOrderItems()))
            {
                $this->addFlash ('error', 'Vyberte nápoj');
                return $this->render('drink_menu/drinkMenu.html.twig', [
                    'form' => $form->createView(),
                    'products'=>$products,
                ]);
            }
            if(null!=$alcoholProduct)
            {
                $this->addFlash('error', 'Nejste plnoletý, nemůžete objednávat tyto nápoje:');
                foreach ($alcoholProduct as $item)
                    $this->addFlash ('error', $item);
                return $this->render('drink_menu/drinkMenu.html.twig', [
                    'form' => $form->createView(),
                    'products'=>$products,
                ]);
            }
//            $diff=$order->getSum()-$customer->getWallet();
            if($order->getCustomer()->getWallet()<0)
            {
                $this->addFlash ('error', 'Chybí Vám '.$diff.' Kč');
                return $this->render('drink_menu/drinkMenu.html.twig', [
                    'form' => $form->createView(),
                    'products'=>$products,
                ]);
            }
//            $customer->decreaseWallet($diff);
//            $customerRep->save($customer);
            $orderRep->save($order);
            $this->addFlash('success', 'Objednávka přijata');
            return $this->redirectToRoute('thanks', ['order'=>$order->getId()]);
        }
        return $this->render('drink_menu/drinkMenu.html.twig', [
            'form' => $form->createView(),
            'products'=>$products,
        ]);
    }
}
