<?php
/**
 * Created by PhpStorm.
 * User: eduardo
 * Date: 31/07/15
 * Time: 11:57
 */

namespace Cacic\CommonBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class NotificationsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add(
            'subject',
            'text',
            array(
                'label' => "Assunto"
            )
        );

        $builder->add(
            'creationDate',
            'datetime',
            array(
                'label' => "Data"
            )
        );

        $builder->add(
            'fromAddr',
            'text',
            array(
                'label' => "Remetente"
            )
        );

        $builder->add(
            'toAddr',
            'text',
            array(
                'label' => "Destinatário"
            )
        );

        $builder->add(
            'object',
            'text',
            array(
                'label' => "Objeto"
            )
        );

        $builder->add(
            'notificationAcao',
            'text',
            array(
                'label' => "Ação"
            )
        );

        /*
        $builder->add(
            'body',
            'textarea',
            array(
                'label' => "Corpo"
            )
        );
        */

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Cacic\CommonBundle\Entity\Notifications',
        ));
    }



    public function getName() {
        return 'notifications';
    }

}