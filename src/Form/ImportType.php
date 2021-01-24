<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class ImportType extends AbstractType
{   
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('import_file', FileType::class, [
                'mapped' => false,
                'required' => false,
                'attr' => [
                    'class' => 'form-control' 
                ],
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Please upload a json file',
                    ])
                ],
            ])
            ->add('import', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary ' 
                ]
            ])
        ;
    }

    

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);


        // $resolver->setDefaults([
        //     'data_class' => Category::class,
        // ]);
    }
}


    
