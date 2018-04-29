<?php

namespace App\Forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TermDeleteForm extends AbstractType {

  public function buildForm(FormBuilderInterface $builder, array $options) {
    $builder->add('id', HiddenType::class, [
      'data' => $options['delete_id']
    ])->add('submit', SubmitType::class, [
      'label' => 'Delete'
    ]);
  }

  public function configureOptions(OptionsResolver $resolver) {

    $resolver->setDefaults([
      'delete_id' => null
    ]);
  }

  public function buildView(FormView $view, FormInterface $form, array $options) {
    $view->vars['delete_id'] = $options['delete_id'];
  }
}