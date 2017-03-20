<?php

    namespace IdeasBundle\Form;
    
    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\Form\Extension\Core\Type\SubmitType;
    use Symfony\Component\Form\Extension\Core\Type\DateType;
    use Symfony\Component\Form\Extension\Core\Type\CountryType;
    use Symfony\Component\Form\Extension\Core\Type\TextareaType;
    use IdeasBundle\Entity\Idea;
    use Symfony\Component\OptionsResolver\OptionsResolver;
    
    class IdeaType extends AbstractType {
        
        public function buildForm(FormBuilderInterface $builder, array $options) {
            
            $builder
                -> add('idea')
                -> add('title', null, [
                    'required' => false,
                    'attr' => [
                        'min_length' => 1,
                        'pattern' => false
                    ]
                ])
                -> add('description', TextareaType::class)
                -> add('created_at', DateType::class)
                -> add('save', SubmitType::class, ['label' => 'Create idea']);

        }

        public function configureOptions(OptionsResolver $resolver){
            $resolver -> setDefaults([
               'data_class' => Idea::class
            ]);
        }

    }