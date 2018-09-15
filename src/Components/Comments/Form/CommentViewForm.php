<?php


namespace App\Components\Comments\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class CommentViewForm extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder->add('workflow', ChoiceType::class, [
      'label' => 'Action',
      'choices' => [
        'Publish' => 'published',
        'Ignore' => 'ignored'
      ]
    ]);
    $builder->add('submit', SubmitType::class, [
      'label' =>   'Move to'
    ]);
  }

}