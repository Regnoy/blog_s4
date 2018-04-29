<?php
/**
 * Created by PhpStorm.
 * User: pavel
 * Date: 10/25/2017
 * Time: 9:12 AM
 */

namespace App\Forms;



use App\Components\Users\Models\RegisterUserModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class RegisterUserForm extends AbstractType {

  public function buildForm(FormBuilderInterface $builder, array $options) {
    $builder->add('firstName', TextType::class, [
      'label' => 'First Name'
    ]);
    $builder->add('lastName', TextType::class, [
      'label' => 'Last Name'
    ]);
    $builder->add('email', RepeatedType::class, array(
      'type' => EmailType::class,
      'invalid_message' => 'The email address is invalid.',
      'options' => array('attr' => array('class' => 'email-field')),
      'required' => true,
      'first_options'  => array('label' => 'Email'),
      'second_options' => array('label' => 'Repeat Email'),
    ));

    $builder->add('password', RepeatedType::class, array(
      'type' => PasswordType::class,
      'invalid_message' => 'The password fields must match.',
      'options' => array('attr' => array('class' => 'password-field')),
      'required' => true,
      'first_options'  => array('label' => 'Password'),
      'second_options' => array('label' => 'Repeat Password'),
    ));
    $builder->add('birthday', BirthdayType::class, [
      'label' => 'Birthday'
    ]);
    $builder->add('region', CountryType::class, [
      'label' => 'Country'
    ]);
    $builder->add('submit', SubmitType::class,[
      'label' => 'Register'
    ]);
  }

  public function configureOptions(OptionsResolver $resolver) {
    $resolver->setDefaults([
      'data_class' => RegisterUserModel::class
    ]);
  }
}