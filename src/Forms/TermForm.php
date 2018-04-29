<?php
/**
 * Created by PhpStorm.
 * User: pavel
 * Date: 12/19/2017
 * Time: 9:51 AM
 */

namespace App\Forms;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Term;

class TermForm extends AbstractType {

  public function buildForm(FormBuilderInterface $builder, array $options) {

    $builder->add('name');
    $builder->add('description');
    $builder->add('submit', SubmitType::class,[
      'label' => 'Save'
    ]);

  }

  public function configureOptions(OptionsResolver $resolver) {
    $resolver->setDefault('data_class', Term::class);
  }
}