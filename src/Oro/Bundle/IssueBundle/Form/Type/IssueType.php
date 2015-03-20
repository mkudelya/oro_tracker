<?php

namespace Oro\Bundle\IssueBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormBuilderInterface;

use Oro\Bundle\IssueBundle\Entity\Issue;

class IssueType extends AbstractType
{
    /**
     * @var array
     */
    protected $types = array(
        'bug' => 'Bug',
        'task' => 'Task',
        'story' => 'Story'
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
                    'label' => 'oro.issue.code_label'
                ]
            )
            ->add(
                'summary',
                'text',
                [
                    'required' => true,
                    'label' => 'oro.issue.summary_label'
                ]
            )
            ->add(
                'type',
                'choice',
                array(
                    'choices' => $this->types,
                    'label' => 'oro.issue.type_label',
                    'required' => true
                )
            )
            ->add(
                'description',
                'textarea',
                [
                    'required' => false,
                    'label' => 'oro.issue.description_label'
                ]
            )
            ->add(
                'priority',
                'translatable_entity',
                [
                    'label' => 'oro.issue.priority_label',
                    'class' => 'Oro\Bundle\IssueBundle\Entity\IssuePriority',
                    'required' => true
                ]
            )
            ->add(
                'assignee',
                'translatable_entity',
                [
                    'label' => 'oro.issue.assignee_label',
                    'class' => 'Oro\Bundle\UserBundle\Entity\User',
                    'required' => true
                ]
            )
            ->add(
                'reporter',
                'translatable_entity',
                [
                    'label' => 'oro.issue.reporter_label',
                    'class' => 'Oro\Bundle\UserBundle\Entity\User',
                    'required' => true
                ]
            )
            ->add(
                'related',
                'translatable_entity',
                [
                    'label' => 'oro.issue.related_label',
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
