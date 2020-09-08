<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThan;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null, [                  // null permet de ne pas typer ce champ mais d'aller au 3ème paramètre quand même pour modifier les attributs du champ
                'label' => 'Titre du produit',
                'attr' => [
                    'style' => 'color: brown'
                ]
            ])
            ->add('description')
            ->add('sellerType', ChoiceType::class, [
                'choices' => [
                    'foo' => true,
                    'bar' => false
                ],
                'mapped' => false,                      // Permet d'indiquer que ce champ n'est pas rattaché à la classe Product (pour rajouter un champ supplémentaire qui n'a rien à voir avec Product par exemple)
            ])
            ->add('price', MoneyType::class, [
                'currency' => 'EUR',                    // MoneyType

                'attr' => [                             // FormType (parent)
                    'placeholder' => '1000000',
                    'class' => 'text-danger'
                ],

                'label' => "Prix du produit",           // FormType (parent)
                'label_attr' => [                       // FormType (parent)
                    'class' => 'text-success'
                ]
            ])
            ->add('quantity',  null, [
                'constraints' => [
                    new GreaterThan([
                        'value' => 10,
                        'message' => 'La valeur saisie {{ value }} est incorrecte : elle doit être strictement supérieure à {{ compared_value }}.',
                    ])
                ]
            ])
            ->add('createdAt', DateTimeType::class, [
                'date_widget' => 'single_text',
                'time_widget' => 'single_text'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
