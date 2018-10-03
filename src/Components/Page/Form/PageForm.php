<?php


namespace App\Components\Page\Form;
use App\Components\Page\Model\PageModel;
use App\Entity\Page;
use App\Entity\Term;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\LanguageType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PageForm extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    /** @var EntityManagerInterface $entityManager */
    $entityManager = $options['entity_manager'];
    /** @var PageModel $page */
    $page = $options['data'];

    $builder->add('title', TextType::class, [
      'label' => 'Title',

    ]);
    //ru > save > ru > add > en > disabled
    $pageLanguage = $page->getPage()->getLanguage();
    $isDiabledLanguage = false;
    if(!is_null($pageLanguage) || (!is_null($page->getId()) && $pageLanguage != $page->getLanguage()) ){
      $isDiabledLanguage = true;
    }

    $builder->add('language', ChoiceType::class, [
      'label' => 'Language',
      'choices' => array(
        'English' => 'en',
        'Russian' => 'ru',
      ),
      'data' => $page->getLanguage(),
      'disabled' => $isDiabledLanguage
    ]);
    $builder->add('image', FileType::class, [
      'label' => 'Upload Image'
    ]);
    //PageBodyType::Class
    $builder->add('summary', TextareaType::class, [
      'label' => 'Summary'
    ]);
    $builder->add('body', TextareaType::class, [
      'label' => 'Body'
    ]);
    $builder->add('image', FileType::class, [
      'label' => 'Upload Image'
    ]);
    $terms = $entityManager->getRepository(Term::class)->findAll();

    $builder->add('category', ChoiceType::class, [
      'label' => 'Category',
      'choices' => $terms,
      'choice_label' => function ($value, $key, $index) {
        return "term.".$value->getMachineName();
      },
      'choice_value' => function ($entity = null) {
        return $entity instanceof Term ? $entity->getId() : '';
      },
    ]);
    $builder->add('submit', SubmitType::class, [
      'label' => 'Save'
    ]);
  }

  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setRequired('entity_manager');
    $resolver->setDefaults([
      'data_class' => PageModel::class,
      'entity_manager' => null
    ]);

  }


}