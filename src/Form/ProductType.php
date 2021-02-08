<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;


class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
       


        $builder
            ->add('name',null,[
                'attr'=>['class'=>'form-control']
            ])
            ->add('post_thumbnail',null,[
                'attr'=>['class'=>'form-control']
            ])
            ->add('shortDescription',null,[
                'attr'=>['class'=>'form-control']
            ])
            ->add('longDescription',null,[
                'attr'=>['class'=>'form-control']
            ])
            ->add('height',null,[
                'attr'=>['class'=>'form-control']
            ])
            ->add('width',null,[
                'attr'=>['class'=>'form-control']
            ])
            ->add('color',null,[
                'attr'=>['class'=>'form-control']
            ])
            //->add('status')
            ->add('status', ChoiceType::class , [
                'choices' => [
                    'Reviewed' => 'reviewed',
                    'Published' => 'published'
                ]])
            ->add('brand',null,[
                'attr'=>['class'=>'form-control']
            ])
            ->add('price',null,[
                'attr'=>['class'=>'form-control']
            ])
           // ->add('quality')
            ->add('quality', ChoiceType::class , [
                'choices' => [
                    'Low' => 'low',
                    'Average' => 'average',
                    'High' => 'high'
                ]])
            ->add('tax',null,[
                'attr'=>['class'=>'form-control']
            ])
            ->add('deliveryCharges',null,[
                'attr'=>['class'=>'form-control']
            ])
            ->add('discount',null,[
                'attr'=>['class'=>'form-control']
            ])
            // ->add('created',null,[
            //     'attr'=>['class'=>'form-control']
            // ])
            // ->add('updated',null,[
            //     'attr'=>['class'=>'form-control']
            // ])
            //->add('image')
            //->add('category')
            //->add('manager')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
