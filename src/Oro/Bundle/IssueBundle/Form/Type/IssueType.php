<?php

namespace Oro\Bundle\IssueBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormBuilderInterface;

use Doctrine\ORM\EntityRepository;

class IssueType extends AbstractType
{
    const BUG = 'bug';
    const TASK = 'task';
    const STORY = 'story';

    /**
     * @var array
     */
    protected $types = array(
        self::BUG => 'Bug',
        self::TASK => 'Task',
        self::STORY => 'Story'
    );

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'code',
                'text',
                [
                    'required' => true,
                    'label' => 'oro.issue.code.label'
                ]
            )
            ->add(
                'summary',
                'text',
                [
                    'required' => true,
                    'label' => 'oro.issue.summary.label'
                ]
            )
            ->add(
                'type',
                'choice',
                array(
                    'choices' => $this->getIssueTypes(),
                    'label' => 'oro.issue.type.label',
                    'required' => true
                )
            )
            ->add(
                'description',
                'textarea',
                [
                    'required' => false,
                    'label' => 'oro.issue.description.label'
                ]
            )
            ->add(
                'priority',
                'translatable_entity',
                [
                    'label' => 'oro.issue.priority.label',
                    'class' => 'Oro\Bundle\IssueBundle\Entity\IssuePriority',
                    'required' => true,
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('p')
                            ->orderBy('p.order', 'DESC');
                    }
                ]
            )
            ->add(
                'assignee',
                'translatable_entity',
                [
                    'label' => 'oro.issue.assignee.label',
                    'class' => 'Oro\Bundle\UserBundle\Entity\User',
                    'required' => true
                ]
            )
            ->add(
                'reporter',
                'translatable_entity',
                [
                    'label' => 'oro.issue.reporter.label',
                    'class' => 'Oro\Bundle\UserBundle\Entity\User',
                    'required' => true
                ]
            )
            ->add(
                'related',
                'translatable_entity',
                [
                    'label' => 'oro.issue.related.label',
                    'class' => 'Oro\Bundle\IssueBundle\Entity\Issue',
                    'multiple' => true,
                    'required' => false
                ]
            )->add(
                'tags',
                'oro_tag_select',
                array(
                    'label' => 'oro.tag.entity_plural_label'
                )
            );
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => 'Oro\Bundle\IssueBundle\Entity\Issue',
                'intention' => 'issue'
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'oro_issue';
    }

    public function getIssueTypes()
    {
        return $this->types;
    }
}
