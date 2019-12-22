<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Repository\ProductRepository;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class DrinkMenuType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $products=$builder->getData();
        foreach($products as $product)
        {
            $builder->add('product_'.$product->getId(), CheckboxType::class,[
                'mapped'=>false,
                'required'=>false,
                'label'=>$product->getName(),
            ]);
        }
    }
}
