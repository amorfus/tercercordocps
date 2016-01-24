<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Form\DataTransformer\DateTimeTransformer;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date','date',array(
                'widget' => 'single_text',
                'format' => 'dd-MM-yyyy',
                'attr' => array('class' => 'date')
            ))
            ->add('description', 'textarea')
            ->add('type', 'choice', array(
                'choices'  => array(
                    'Assaig' => 'assaig',
                    'Diada' => 'diada'
                )
            )
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Event'
        ));
    }

    public function getName()
    {
        return 'event';
    }
}
?>