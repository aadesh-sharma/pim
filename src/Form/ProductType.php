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
            ->add('name')
            ->add('post_thumbnail')
            ->add('shortDescription')
            ->add('longDescription')
            ->add('height')
            ->add('width')
            ->add('color')
            //->add('status')
            ->add('status', ChoiceType::class , [
                'choices' => [
                    'Reviewed' => 'reviewed',
                    'Published' => 'published'
                ]])
            ->add('brand')
            ->add('price')
           // ->add('quality')
            ->add('quality', ChoiceType::class , [
                'choices' => [
                    'Low' => 'low',
                    'Average' => 'average',
                    'High' => 'high'
                ]])
            ->add('tax')
            ->add('deliveryCharges')
            ->add('discount')
            ->add('created')
            ->add('updated')
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
