<?php

namespace App\Form;

use App\Entity\Api;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ApiFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, [
            'attr' => [
                'pattern' => '[a-zA-Z][a-zA-Z0-9-]{2,40}[a-zA-Z0-9]',
                'title' => 'The endpoint name should have at least 5 characters and made of alphabets. This will be used as API URI. E.g. alibaba, myendpoint, etc'
            ]
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Api::class,
        ]);
    }
}
