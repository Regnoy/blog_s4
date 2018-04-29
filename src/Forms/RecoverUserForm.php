<?php
/**
 * Created by PhpStorm.
 * User: pavel
 * Date: 10/30/2017
 * Time: 8:50 AM
 */

namespace App\Forms;


use App\Components\Users\Models\RecoverUserModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class RecoverUserForm extends AbstractType {

  public function buildForm(FormBuilderInterface $builder, array $options) {
    $builder->add('email' , EmailType::class, [
      'label' => 'Email'
    ]);
    $builder->add('submit', SubmitType::class,[
      'label' => 'Recover'
    ]);
  }

  public function configureOptions(OptionsResolver $resolver) {
    $resolver->setDefaults([
      'data_class' => RecoverUserModel::class
    ]);
  }
}