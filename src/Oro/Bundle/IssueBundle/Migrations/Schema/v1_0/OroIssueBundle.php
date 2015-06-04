<?php

namespace Oro\Bundle\IssueBundle\Migrations\Schema\v1_0;

use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;

class OroIssueBundle implements Migration
{
    /**
     * {@inheritdoc}
     */
    public function getMigrationVersion()
    {
        return 'v1_0';
    }

    public function up(Schema $schema, QueryBag $queries)
    {
        /** Tables generation **/
        self::createOroIssueIssueTable($schema);
        self::createOroIssueIssueCollaboratorsTable($schema);
        self::createOroIssueIssueRelationTable($schema);
        self::createOroIssuePriorityTable($schema);
        self::createOroIssueResolutionTable($schema);

        /** Foreign keys generation **/
        self::addOroIssueIssueForeignKeys($schema);
        self::addOroIssueIssueCollaboratorsForeignKeys($schema);
        self::addOroIssueIssueRelationForeignKeys($schema);
    }

    /**
     * Create oro_issue_issue table
     *
     * @param Schema $schema
     */
    public static function createOroIssueIssueTable(Schema $schema)
    {
        $table = $schema->createTable('oro_issue_issue');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('organization_id', 'integer', ['notnull' => false]);
        $table->addColumn('workflow_item_id', 'integer', ['notnull' => false]);
        $table->addColumn('resolution_id', 'integer', ['notnull' => false]);
        $table->addColumn('priority_id', 'integer', ['notnull' => false]);
        $table->addColumn('assignee_id', 'integer', ['notnull' => false]);
        $table->addColumn('workflow_step_id', 'integer', ['notnull' => false]);
        $table->addColumn('parent_id', 'integer', ['notnull' => false]);
        $table->addColumn('owner_id', 'integer', ['notnull' => false]);
        $table->addColumn('reporter_id', 'integer', ['notnull' => false]);
        $table->addColumn('code', 'string', ['length' => 255]);
        $table->addColumn('summary', 'string', ['length' => 255]);
        $table->addColumn('type', 'string', ['length' => 255]);
        $table->addColumn('description', 'text', ['notnull' => false]);
        $table->addColumn('createdAt', 'datetime', []);
        $table->addColumn('updatedAt', 'datetime', []);
        $table->setPrimaryKey(['id']);
        $table->addUniqueIndex(['code'], 'UNIQ_46A8C3E677153098');
        $table->addUniqueIndex(['workflow_item_id'], 'UNIQ_46A8C3E61023C4EE');
        $table->addIndex(['priority_id'], 'IDX_46A8C3E6497B19F9', []);
        $table->addIndex(['resolution_id'], 'IDX_46A8C3E612A1C43A', []);
        $table->addIndex(['workflow_step_id'], 'IDX_46A8C3E671FE882C', []);
        $table->addIndex(['reporter_id'], 'IDX_46A8C3E6E1CFE6F5', []);
        $table->addIndex(['assignee_id'], 'IDX_46A8C3E659EC7D60', []);
        $table->addIndex(['parent_id'], 'IDX_46A8C3E6727ACA70', []);
        $table->addIndex(['owner_id'], 'IDX_46A8C3E67E3C61F9', []);
        $table->addIndex(['organization_id'], 'IDX_46A8C3E632C8A3DE', []);
    }

    /**
     * Create oro_issue_issue_collaborators table
     *
     * @param Schema $schema
     */
    public static function createOroIssueIssueCollaboratorsTable(Schema $schema)
    {
        $table = $schema->createTable('oro_issue_issue_collaborators');
        $table->addColumn('issue_id', 'integer', []);
        $table->addColumn('user_id', 'integer', []);
        $table->setPrimaryKey(['issue_id', 'user_id']);
        $table->addIndex(['issue_id'], 'IDX_FACB3D3F5E7AA58C', []);
        $table->addIndex(['user_id'], 'IDX_FACB3D3FA76ED395', []);
    }

    /**
     * Create oro_issue_issue_relation table
     *
     * @param Schema $schema
     */
    public static function createOroIssueIssueRelationTable(Schema $schema)
    {
        $table = $schema->createTable('oro_issue_issue_relation');
        $table->addColumn('issue_source', 'integer', []);
        $table->addColumn('issue_target', 'integer', []);
        $table->setPrimaryKey(['issue_source', 'issue_target']);
        $table->addIndex(['issue_source'], 'IDX_5C09CEE4AD7AF554', []);
        $table->addIndex(['issue_target'], 'IDX_5C09CEE4B49FA5DB', []);
    }

    /**
     * Create oro_issue_priority table
     *
     * @param Schema $schema
     */
    public static function createOroIssuePriorityTable(Schema $schema)
    {
        $table = $schema->createTable('oro_issue_priority');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('name', 'string', ['length' => 255]);
        $table->addColumn('label', 'string', ['length' => 255]);
        $table->addColumn('order', 'integer', ['default' => '1']);
        $table->setPrimaryKey(['id']);
    }

    /**
     * Create oro_issue_resolution table
     *
     * @param Schema $schema
     */
    public static function createOroIssueResolutionTable(Schema $schema)
    {
        $table = $schema->createTable('oro_issue_resolution');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('name', 'string', ['length' => 255]);
        $table->addColumn('label', 'string', ['length' => 255]);
        $table->setPrimaryKey(['id']);
    }

    /**
     * Add oro_issue_issue foreign keys.
     *
     * @param Schema $schema
     */
    public static function addOroIssueIssueForeignKeys(Schema $schema)
    {
        $table = $schema->getTable('oro_issue_issue');
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_organization'),
            ['organization_id'],
            ['id'],
            ['onDelete' => null, 'onUpdate' => null]
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_workflow_item'),
            ['workflow_item_id'],
            ['id'],
            ['onDelete' => 'SET NULL', 'onUpdate' => null]
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_issue_resolution'),
            ['resolution_id'],
            ['id'],
            ['onDelete' => null, 'onUpdate' => null]
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_issue_priority'),
            ['priority_id'],
            ['id'],
            ['onDelete' => null, 'onUpdate' => null]
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_user'),
            ['assignee_id'],
            ['id'],
            ['onDelete' => null, 'onUpdate' => null]
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_workflow_step'),
            ['workflow_step_id'],
            ['id'],
            ['onDelete' => 'SET NULL', 'onUpdate' => null]
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_issue_issue'),
            ['parent_id'],
            ['id'],
            ['onDelete' => null, 'onUpdate' => null]
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_user'),
            ['owner_id'],
            ['id'],
            ['onDelete' => null, 'onUpdate' => null]
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_user'),
            ['reporter_id'],
            ['id'],
            ['onDelete' => null, 'onUpdate' => null]
        );
    }

    /**
     * Add oro_issue_issue_collaborators foreign keys.
     *
     * @param Schema $schema
     */
    public static function addOroIssueIssueCollaboratorsForeignKeys(Schema $schema)
    {
        $table = $schema->getTable('oro_issue_issue_collaborators');
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_user'),
            ['user_id'],
            ['id'],
            ['onDelete' => 'CASCADE', 'onUpdate' => null]
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_issue_issue'),
            ['issue_id'],
            ['id'],
            ['onDelete' => 'CASCADE', 'onUpdate' => null]
        );
    }

    /**
     * Add oro_issue_issue_relation foreign keys.
     *
     * @param Schema $schema
     */
    public static function addOroIssueIssueRelationForeignKeys(Schema $schema)
    {
        $table = $schema->getTable('oro_issue_issue_relation');
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_issue_issue'),
            ['issue_target'],
            ['id'],
            ['onDelete' => 'CASCADE', 'onUpdate' => null]
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_issue_issue'),
            ['issue_source'],
            ['id'],
            ['onDelete' => 'CASCADE', 'onUpdate' => null]
        );
    }
}
