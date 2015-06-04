<?php

namespace OroCRM\Bundle\TaskBundle\Migrations\Schema;

use Doctrine\DBAL\Schema\Schema;

use Oro\Bundle\NoteBundle\Migration\Extension\NoteExtension;
use Oro\Bundle\NoteBundle\Migration\Extension\NoteExtensionAwareInterface;
use Oro\Bundle\ActivityBundle\Migration\Extension\ActivityExtension;
use Oro\Bundle\ActivityBundle\Migration\Extension\ActivityExtensionAwareInterface;
use Oro\Bundle\MigrationBundle\Migration\Installation;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;
use Oro\Bundle\IssueBundle\Migrations\Schema\v1_0\OroIssueBundle as v10;
use Oro\Bundle\IssueBundle\Migrations\Schema\v1_1\NoteIssue as v11;
use Oro\Bundle\IssueBundle\Migrations\Schema\v1_2\EmailActivity as v12;

class OroCRMTaskBundleInstaller implements
    Installation,
    NoteExtensionAwareInterface,
    ActivityExtensionAwareInterface
{
    /** @var NoteExtension */
    protected $noteExtension;

    /**
     * @var ActivityExtension
     */
    protected $activityExtension;

    /**
     * {@inheritdoc}
     */
    public function getMigrationVersion()
    {
        return 'v1_2';
    }

    /**
     * {@inheritdoc}
     */
    public function setNoteExtension(NoteExtension $noteExtension)
    {
        $this->noteExtension = $noteExtension;
    }

    /**
     * {@inheritdoc}
     */
    public function setActivityExtension(ActivityExtension $activityExtension)
    {
        $this->activityExtension = $activityExtension;
    }

    /**
     * @param Schema $schema
     * @param QueryBag $queries
     */
    public function up(Schema $schema, QueryBag $queries)
    {
        /** Tables generation **/
        v10::createOroIssueIssueTable($schema);
        v10::createOroIssueIssueCollaboratorsTable($schema);
        v10::createOroIssueIssueRelationTable($schema);
        v10::createOroIssuePriorityTable($schema);
        v10::createOroIssueResolutionTable($schema);

        /** Foreign keys generation **/
        v10::addOroIssueIssueForeignKeys($schema);
        v10::addOroIssueIssueCollaboratorsForeignKeys($schema);
        v10::addOroIssueIssueRelationForeignKeys($schema);

        v11::addNoteAssociations($schema, $this->noteExtension);
        v12::addActivityAssociations($schema, $this->activityExtension);
    }
}
