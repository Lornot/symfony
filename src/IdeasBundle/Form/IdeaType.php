<?php

    namespace IdeasBundle\Form;
    
    //use Doctrine\DBAL\Types\IntegerType;
    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\Form\Extension\Core\Type\SubmitType;
    use Symfony\Component\Form\Extension\Core\Type\DateType;
    use Symfony\Component\Form\Extension\Core\Type\IntegerType;
    use Symfony\Component\Form\Extension\Core\Type\FileType;
    use Symfony\Component\Form\Extension\Core\Type\CountryType;
    use Symfony\Component\Form\Extension\Core\Type\TextareaType;
    use IdeasBundle\Entity\Idea;
    use Symfony\Component\OptionsResolver\OptionsResolver;
    
    class IdeaType extends AbstractType {
        
        public function buildForm(FormBuilderInterface $builder, array $options) {
            
            $builder
                ->add('title', null, [
                    'required' => false,
                    'attr' => [
                        'min_length' => 1,
                        'pattern' => false
                    ]
                ])
                ->add('description', TextareaType::class, [
                    'required' => true,
                    'attr' => [
                        'min_length' => 2,
                        'pattern' => false
                    ]
                ])
                ->add('attractiveness', IntegerType::class, [
                    'required' => false,
                    'attr' => [
                        'max' => 10,
                        'pattern' => false
                    ]
                ])
                ->add('created_at', DateType::class)
                ->add('image', FileType::class, [
                    'required' => false
                ])
                ->add('save', SubmitType::class, ['label' => 'Save']);

        }

        public function configureOptions(OptionsResolver $resolver){
            $resolver -> setDefaults([
                'data_class' => Idea::class,
                'allow_extra_fields' => true
            ]);
        }

    }