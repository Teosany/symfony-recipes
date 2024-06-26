<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Recipe;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Sequentially;

class RecipeType extends AbstractType
{
    public function __construct(private readonly FormListenerFactory $listenerFactory)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'empty_data' => ''
            ])
            ->add('slug', TextType::class, [
                'required' => false,
//                'constraints' => new Sequentially([
//                        new Length(min: 10),
//                        new Regex('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', message: "ceci n'est pas un slug valide"),
//                ])
//                    [
//                    new Length(min: 10),
//                    new Regex('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', message: "ceci n'est pas un slug valide"),
//                    ],
            ])
            ->add('thumbnailFile', FileType::class, [
                'required' => false,
                'empty_data' => null,
//                'mapped' => false,
//                'constraints' => [
//                    new Image()
//                ]
            ])
            ->add('content', TextareaType::class, [
                'empty_data' => ''
            ])
            ->add('duration', NumberType::class, [
                'required' => false,
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Envoyer',
                'attr' => ['class' => 'btn-blue'],
            ])
//            ->addEventListener(FormEvents::PRE_SUBMIT, $this->autoSlug(...))
            ->addEventListener(FormEvents::PRE_SUBMIT, $this->listenerFactory->autoSlug('title'))
//            ->addEventListener(FormEvents::PRE_SUBMIT, [$this, 'autoSlug'])
//            ->addEventListener(FormEvents::POST_SUBMIT, $this->autoDateTimeImmutable(...));
            ->addEventListener(FormEvents::POST_SUBMIT, $this->listenerFactory->timestamp());
    }

//    public function autoDateTimeImmutable(PostSubmitEvent $event): void
//    {
//        $data = $event->getData();
//        $now = new \DateTimeImmutable();
//
//        if (!($data instanceof Recipe)) {
//            return;
//        }
//
//        $data->setUpdatedAt($now);
//        if (!($data->getId())) {
//            $data->setCreatedAt($now);
//        }
//    }

//    public function autoSlug(PreSubmitEvent $event): void
//    {
//        $data = $event->getData();
//
//        if (empty($data['slug'])) {
//            $slugger = new AsciiSlugger();
//            $data['slug'] = strtolower($slugger->slug($data['title']));
//
//            $event->setData($data);
//        }
//    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class,
            'validation_groups' => ['Default', 'Extra']
        ]);
    }
}
