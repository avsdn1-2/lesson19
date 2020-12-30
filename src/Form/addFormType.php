<?php


namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class AddFormType extends AbstractType
{
    public function buildForm (FormBuilderInterface $builder, array $options)
    {
        //parent::buildForm($builder,$options);
        $builder->add('title');
    }
}