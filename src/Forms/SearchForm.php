<?php


namespace App\Forms;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class SearchForm extends AbstractType {
  public function buildForm(FormBuilderInterface $builder, array $options) {
    $builder->add('search', TextType::class);
    $builder->add('submit', SubmitType::class,[
      'label' => 'Search'
    ]);
  }
}