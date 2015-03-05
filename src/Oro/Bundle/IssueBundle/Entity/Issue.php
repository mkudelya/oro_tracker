<?php

namespace Oro\Bundle\IssueBundle\Entity;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Oro\Bundle\UserBundle\Entity\User;
use Oro\Bundle\IssueBundle\Model\ExtendIssue;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\Config;
use Oro\Bundle\WorkflowBundle\Entity\WorkflowItem;
use Oro\Bundle\WorkflowBundle\Entity\WorkflowStep;
use Oro\Bundle\TagBundle\Entity\Tag;
use Oro\Bundle\TagBundle\Entity\Taggable;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @UniqueEntity("code")
 * @ORM\Table(name="oro_issue_issue")
 * @Config(
 *      routeName="oro_issue_index",
 *      routeView="oro_issue_view",
 *      defaultValues={
 *          "entity"={
 *              "icon"="icon-list-alt"
 *          },
 *          "ownership"={
 *              "owner_type"="USER",
 *              "owner_field_name"="owner",
 *              "owner_column_name"="owner_id",
 *              "organization_field_name"="organization",
 *              "organization_column_name"="organization_id"
 *          },
 *          "security"={
 *              "type"="ACL"
 *          },
 *          "dataaudit"={
 *              "auditable"=true
 *          },
 *          "workflow"={
 *              "active_workflow"="oro_issue_flow"
 *          }
 *      }
 * )
 */
class Issue extends ExtendIssue implements Taggable
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    protected $code;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $summary;

    /**
     * @ORM\Column(type="text")
     */
    protected $description;

    /**
     * @ORM\ManyToOne(targetEntity="IssuePriority")
     * @ORM\JoinColumn(name="priority_id", referencedColumnName="id")
     **/
    protected $priority;

    /**
     * @ORM\ManyToOne(targetEntity="IssueResolution")
     * @ORM\JoinColumn(name="resolution_id", referencedColumnName="id")
     **/
    protected $resolution;

    /**
     * @var WorkflowItem
     *
     * @ORM\OneToOne(targetEntity="Oro\Bundle\WorkflowBundle\Entity\WorkflowItem")
     * @ORM\JoinColumn(name="workflow_item_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $workflowItem;

    /**
     * @var WorkflowStep
     *
     * @ORM\ManyToOne(targetEntity="Oro\Bundle\WorkflowBundle\Entity\WorkflowStep")
     * @ORM\JoinColumn(name="workflow_step_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $workflowStep;

    /**
     * @var Tag[]
     */
    protected $tags;

    /**
     * @ORM\ManyToOne(targetEntity="Oro\Bundle\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="reporter_id", referencedColumnName="id")
     **/
    protected $reporter;

    /**
     * @ORM\ManyToOne(targetEntity="Oro\Bundle\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="assignee_id", referencedColumnName="id")
     **/
    protected $assignee;

    /**
     * @ORM\ManyToMany(targetEntity="Issue", inversedBy="related")
     * @ORM\JoinTable(name="oro_issue_issue_relation")
     **/
    protected $related;

    /**
     * @ORM\ManyToMany(targetEntity="Oro\Bundle\UserBundle\Entity\User", inversedBy="issue")
     * @ORM\JoinTable(name="oro_issue_issue_collaborators")
     **/
    protected $collaborator;

    /**
     * @ORM\ManyToOne(targetEntity="Issue", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     **/
    protected $parent;

    /**
     * @ORM\OneToMany(targetEntity="Issue", mappedBy="parent")
     */
    protected $children;

    /**
     * @var \stdClass
     */
    protected $notes;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $created;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $updated;

    /**
     * @ORM\ManyToOne(targetEntity="Oro\Bundle\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="owner_id", referencedColumnName="id")
     */
    protected $owner;

    /**
     * @ORM\ManyToOne(targetEntity="Oro\Bundle\OrganizationBundle\Entity\Organization")
     * @ORM\JoinColumn(name="organization_id", referencedColumnName="id")
     */
    protected $organization;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->related = new \Doctrine\Common\Collections\ArrayCollection();
        $this->collaborator = new \Doctrine\Common\Collections\ArrayCollection();
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * {@inheritdoc}
     */
    public function getTaggableId()
    {
        return $this->getId();
    }

    /**
     * {@inheritdoc}
     */
    public function getTags()
    {
        $this->tags = $this->tags ?: new ArrayCollection();

        return $this->tags;
    }

    /**
     * {@inheritdoc}
     */
    public function setTags($tags)
    {
        $this->tags = $tags;

        return $this;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set code
     *
     * @param string $code
     * @return Issue
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string 
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set summary
     *
     * @param string $summary
     * @return Issue
     */
    public function setSummary($summary)
    {
        $this->summary = $summary;

        return $this;
    }

    /**
     * Get summary
     *
     * @return string 
     */
    public function getSummary()
    {
        return $this->summary;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Issue
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Issue
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime 
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     * @return Issue
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime 
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set priority
     *
     * @param \Oro\Bundle\IssueBundle\Entity\IssuePriority $priority
     * @return Issue
     */
    public function setPriority(\Oro\Bundle\IssueBundle\Entity\IssuePriority $priority = null)
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * Get priority
     *
     * @return \Oro\Bundle\IssueBundle\Entity\IssuePriority 
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * Set resolution
     *
     * @param \Oro\Bundle\IssueBundle\Entity\IssueResolution $resolution
     * @return Issue
     */
    public function setResolution(\Oro\Bundle\IssueBundle\Entity\IssueResolution $resolution = null)
    {
        $this->resolution = $resolution;

        return $this;
    }

    /**
     * Get resolution
     *
     * @return \Oro\Bundle\IssueBundle\Entity\IssueResolution 
     */
    public function getResolution()
    {
        return $this->resolution;
    }

    /**
     * Set workflowItem
     *
     * @param \Oro\Bundle\WorkflowBundle\Entity\WorkflowItem $workflowItem
     * @return Issue
     */
    public function setWorkflowItem(\Oro\Bundle\WorkflowBundle\Entity\WorkflowItem $workflowItem = null)
    {
        $this->workflowItem = $workflowItem;

        return $this;
    }

    /**
     * Get workflowItem
     *
     * @return \Oro\Bundle\WorkflowBundle\Entity\WorkflowItem 
     */
    public function getWorkflowItem()
    {
        return $this->workflowItem;
    }

    /**
     * Set workflowStep
     *
     * @param \Oro\Bundle\WorkflowBundle\Entity\WorkflowStep $workflowStep
     * @return Issue
     */
    public function setWorkflowStep(\Oro\Bundle\WorkflowBundle\Entity\WorkflowStep $workflowStep = null)
    {
        $this->workflowStep = $workflowStep;

        return $this;
    }

    /**
     * Get workflowStep
     *
     * @return \Oro\Bundle\WorkflowBundle\Entity\WorkflowStep 
     */
    public function getWorkflowStep()
    {
        return $this->workflowStep;
    }

    /**
     * Set reporter
     *
     * @param \Oro\Bundle\UserBundle\Entity\User $reporter
     * @return Issue
     */
    public function setReporter(\Oro\Bundle\UserBundle\Entity\User $reporter = null)
    {
        $this->reporter = $reporter;

        return $this;
    }

    /**
     * Get reporter
     *
     * @return \Oro\Bundle\UserBundle\Entity\User 
     */
    public function getReporter()
    {
        return $this->reporter;
    }

    /**
     * Set assignee
     *
     * @param \Oro\Bundle\UserBundle\Entity\User $assignee
     * @return Issue
     */
    public function setAssignee(\Oro\Bundle\UserBundle\Entity\User $assignee = null)
    {
        $this->assignee = $assignee;

        return $this;
    }

    /**
     * Get assignee
     *
     * @return \Oro\Bundle\UserBundle\Entity\User 
     */
    public function getAssignee()
    {
        return $this->assignee;
    }

    /**
     * Add related
     *
     * @param \Oro\Bundle\IssueBundle\Entity\Issue $related
     * @return Issue
     */
    public function addRelated(\Oro\Bundle\IssueBundle\Entity\Issue $related)
    {
        $this->related[] = $related;

        return $this;
    }

    /**
     * Remove related
     *
     * @param \Oro\Bundle\IssueBundle\Entity\Issue $related
     */
    public function removeRelated(\Oro\Bundle\IssueBundle\Entity\Issue $related)
    {
        $this->related->removeElement($related);
    }

    /**
     * Get related
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRelated()
    {
        return $this->related;
    }

    /**
     * Add collaborator
     *
     * @param \Oro\Bundle\UserBundle\Entity\User $collaborator
     * @return Issue
     */
    public function addCollaborator(\Oro\Bundle\UserBundle\Entity\User $collaborator)
    {
        $this->collaborator[] = $collaborator;

        return $this;
    }

    /**
     * Remove collaborator
     *
     * @param \Oro\Bundle\UserBundle\Entity\User $collaborator
     */
    public function removeCollaborator(\Oro\Bundle\UserBundle\Entity\User $collaborator)
    {
        $this->collaborator->removeElement($collaborator);
    }

    /**
     * Get collaborator
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCollaborator()
    {
        return $this->collaborator;
    }

    /**
     * Set parent
     *
     * @param \Oro\Bundle\IssueBundle\Entity\Issue $parent
     * @return Issue
     */
    public function setParent(\Oro\Bundle\IssueBundle\Entity\Issue $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \Oro\Bundle\IssueBundle\Entity\Issue 
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Add children
     *
     * @param \Oro\Bundle\IssueBundle\Entity\Issue $children
     * @return Issue
     */
    public function addChild(\Oro\Bundle\IssueBundle\Entity\Issue $children)
    {
        $this->children[] = $children;

        return $this;
    }

    /**
     * Remove children
     *
     * @param \Oro\Bundle\IssueBundle\Entity\Issue $children
     */
    public function removeChild(\Oro\Bundle\IssueBundle\Entity\Issue $children)
    {
        $this->children->removeElement($children);
    }

    /**
     * Get children
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Set owner
     *
     * @param \Oro\Bundle\UserBundle\Entity\User $owner
     * @return Issue
     */
    public function setOwner(\Oro\Bundle\UserBundle\Entity\User $owner = null)
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Get owner
     *
     * @return \Oro\Bundle\UserBundle\Entity\User 
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * Set organization
     *
     * @param \Oro\Bundle\OrganizationBundle\Entity\Organization $organization
     * @return Issue
     */
    public function setOrganization(\Oro\Bundle\OrganizationBundle\Entity\Organization $organization = null)
    {
        $this->organization = $organization;

        return $this;
    }

    /**
     * Get organization
     *
     * @return \Oro\Bundle\OrganizationBundle\Entity\Organization 
     */
    public function getOrganization()
    {
        return $this->organization;
    }
}
