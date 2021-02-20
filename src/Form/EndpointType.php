<?php

namespace App\Form;

use App\Entity\Api;
use App\Entity\Endpoint;
use App\Form\Common\EntityHiddenType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EndpointType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('api', EntityHiddenType::class, [
                'class' => Api::class,
                'data' => $options['api_id'],
            ])
            ->add('method', ChoiceType::class, [
                'choices' => [
                    'GET' => 'get',
                    'POST' => 'post',
                    'PUT' => 'put',
                    'PATCH' => 'patch',
                    'DELETE' => 'delete',
                    'OPTIONS' => 'options',
                ],
            ])
            ->add('path', TextType::class, [
                'attr' => [
                    'placeholder' => 'e.g: /api/login',
                ],
            ])
            ->add('statusCode', IntegerType::class, [
                'data' => 200,
                'attr' => [
                    'placeholder' => 'Status code',
                ],
            ])
            ->add('responseBody', TextareaType::class, [
                'attr' => [
                    'style' => 'height: 200px',
                    'placeholder' => 'Add html, xml, or json response, e.g: {"message": "Hello world!"}',
                ],
            ])
            ->add('endpointHeaders', CollectionType::class, [
                'label' => false,
                'entry_type' => EndpointHeaderType::class,
                'allow_add' => true,
                'allow_delete' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Endpoint::class,
        ]);

        $resolver->setRequired('api_id');
    }
}
