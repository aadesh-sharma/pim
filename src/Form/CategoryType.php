<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('countryOrigin')
            ->add('status')
            //->add('size')
            ->add('size', ChoiceType::class , [
                'choices' => [
                'Small' => 'small',
                'Medium' => 'medium',
                'Large' => 'large'
                ]])     
            ->add('popularity', ChoiceType::class , [
                'choices' => [
                    'Low' => 'low',
                    'Medium' => 'medium',
                    'High' => 'high',
                ]])
            ->add('language')
            ->add('specialNotes')
            ->add('created')
            ->add('updated')
            ->add('att1')
            ->add('att2')
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}
