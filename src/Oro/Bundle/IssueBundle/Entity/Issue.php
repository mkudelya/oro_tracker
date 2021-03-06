<?php

namespace Oro\Bundle\IssueBundle\Entity;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Oro\Bundle\OrganizationBundle\Entity\Organization;
use Oro\Bundle\UserBundle\Entity\User;
use Oro\Bundle\IssueBundle\Model\ExtendIssue;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\Config;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\ConfigField;
use Oro\Bundle\WorkflowBundle\Entity\WorkflowItem;
use Oro\Bundle\WorkflowBundle\Entity\WorkflowStep;
use Oro\Bundle\TagBundle\Entity\Tag;
use Oro\Bundle\TagBundle\Entity\Taggable;
use Oro\Bundle\IssueBundle\Entity\IssuePriority;
use Oro\Bundle\IssueBundle\Entity\IssueResolution;

/**
 * @ORM\Entity(repositoryClass="Oro\Bundle\IssueBundle\Entity\Repository\IssueRepository")
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
 *              "active_workflow"="issue_flow"
 *          },
 *          "grouping"={
 *              "groups"={"activity"}
 *          }
 *      }
 * )
 * @SuppressWarnings(PHPMD.TooManyFields)
 * @SuppressWarnings(PHPMD.TooManyMethods)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Issue extends ExtendIssue implements Taggable
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ConfigField(
     *    defaultValues={
     *        "importexport"={
     *            "header"="Id",
     *            "order"="10"
     *        }
     *    }
     * )
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @ConfigField(
     *    defaultValues={
     *        "importexport"={
     *            "header"="Code",
     *            "order"="20"
     *        }
     *    }
     * )
     */
    protected $code;

    /**
     * @ORM\Column(type="string", length=255)
     * @ConfigField(
     *    defaultValues={
     *        "importexport"={
     *            "header"="Summary",
     *            "order"="30"
     *        }
     *    }
     * )
     */
    protected $summary;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @ConfigField(
     *    defaultValues={
     *        "importexport"={
     *            "header"="Description",
     *            "order"="40"
     *        }
     *    }
     * )
     */
    protected $description;

    /**
     * @ORM\Column(type="string", length=255)
     * @ConfigField(
     *    defaultValues={
     *        "importexport"={
     *            "header"="Type",
     *            "order"="50"
     *        }
     *    }
     * )
     */
    protected $type;

    /**
     * @ORM\ManyToOne(targetEntity="IssuePriority")
     * @ORM\JoinColumn(name="priority_id", referencedColumnName="id")
     * @ConfigField(
     *    defaultValues={
     *        "importexport"={
     *            "header"="Priority",
     *            "order"="60"
     *        }
     *    }
     * )
     **/
    protected $priority;

    /**
     * @ORM\ManyToOne(targetEntity="IssueResolution")
     * @ORM\JoinColumn(name="resolution_id", referencedColumnName="id")
     * @ConfigField(
     *    defaultValues={
     *        "importexport"={
     *            "header"="Resolution",
     *            "order"="70"
     *        }
     *    }
     * )
     **/
    protected $resolution;

    /**
     * @var WorkflowItem
     *
     * @ORM\OneToOne(targetEntity="Oro\Bundle\WorkflowBundle\Entity\WorkflowItem")
     * @ORM\JoinColumn(name="workflow_item_id", referencedColumnName="id", onDelete="SET NULL")
     * @ConfigField(
     *    defaultValues={
     *        "importexport"={
     *            "excluded"=true
     *        }
     *    }
     * )
     */
    protected $workflowItem;

    /**
     * @var WorkflowStep
     *
     * @ORM\ManyToOne(targetEntity="Oro\Bundle\WorkflowBundle\Entity\WorkflowStep")
     * @ORM\JoinColumn(name="workflow_step_id", referencedColumnName="id", onDelete="SET NULL")
     * @ConfigField(
     *    defaultValues={
     *        "importexport"={
     *            "excluded"=true
     *        }
     *    }
     * )
     */
    protected $workflowStep;

    /**
     * @var Tag[]
     * @ConfigField(
     *    defaultValues={
     *        "importexport"={
     *            "excluded"=true
     *        }
     *    }
     * )
     */
    protected $tags;

    /**
     * @ORM\ManyToOne(targetEntity="Oro\Bundle\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="reporter_id", referencedColumnName="id")
     * @ConfigField(
     *    defaultValues={
     *        "importexport"={
     *            "header"="Reporter",
     *            "order"="80",
     *            "short"=true
     *        }
     *    }
     * )
     **/
    protected $reporter;

    /**
     * @ORM\ManyToOne(targetEntity="Oro\Bundle\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="assignee_id", referencedColumnName="id")
     * @ConfigField(
     *    defaultValues={
     *        "importexport"={
     *            "header"="Assignee",
     *            "order"="90",
     *            "short"=true
     *        }
     *    }
     * )
     **/
    protected $assignee;

    /**
     * @ORM\ManyToMany(targetEntity="Issue", inversedBy="related")
     * @ORM\JoinTable(name="oro_issue_issue_relation")
     * @ConfigField(
     *    defaultValues={
     *        "importexport"={
     *            "header"="Related",
     *            "order"="100"
     *        }
     *    }
     * )
     **/
    protected $related;

    /**
     * @ORM\ManyToMany(targetEntity="Oro\Bundle\UserBundle\Entity\User")
     * @ORM\JoinTable(name="oro_issue_issue_collaborators")
     * @ConfigField(
     *    defaultValues={
     *        "importexport"={
     *            "excluded"=true
     *        }
     *    }
     * )
     **/
    protected $collaborators;

    /**
     * @ORM\ManyToOne(targetEntity="Issue", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     * @ConfigField(
     *    defaultValues={
     *        "importexport"={
     *            "excluded"=true
     *        }
     *    }
     * )
     **/
    protected $parent;

    /**
     * @ORM\OneToMany(targetEntity="Issue", mappedBy="parent")
     * @ConfigField(
     *    defaultValues={
     *        "importexport"={
     *            "excluded"=true
     *        }
     *    }
     * )
     */
    protected $children;

    /**
     * @ORM\Column(type="datetime")
     * @ConfigField(
     *    defaultValues={
     *        "importexport"={
     *            "excluded"=true
     *        }
     *    }
     * )
     */
    protected $createdAt;

    /**
     * @ORM\Column(type="datetime")
     * @ConfigField(
     *    defaultValues={
     *        "importexport"={
     *            "excluded"=true
     *        }
     *    }
     * )
     */
    protected $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity="Oro\Bundle\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="owner_id", referencedColumnName="id")
     * @ConfigField(
     *    defaultValues={
     *        "importexport"={
     *            "header"="Owner",
     *            "order"="150"
     *        }
     *    }
     * )
     */
    protected $owner;

    /**
     * @ORM\ManyToOne(targetEntity="Oro\Bundle\OrganizationBundle\Entity\Organization")
     * @ORM\JoinColumn(name="organization_id", referencedColumnName="id")
     * @ConfigField(
     *    defaultValues={
     *        "importexport"={
     *            "header"="Organization",
     *            "order"="160"
     *        }
     *    }
     * )
     */
    protected $organization;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->related = new ArrayCollection();
        $this->collaborators = new ArrayCollection();
        $this->children = new ArrayCollection();
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
     * @param int $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = (int) $id;

        return $this;
    }

    /**
     * Set code
     *
     * @param string $code
     * @return $this
     */
    public function setCode($code)
    {
        if (!empty($code)) {
            $code = strtoupper(preg_replace('/[^a-zA-z_-\d]+/', '', $code));
        }

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
     * @return $this
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
     * @return $this
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
     * @return $this
     */
    public function setCreatedAt($created)
    {
        $this->createdAt = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     * @return $this
     */
    public function setUpdatedAt($updated)
    {
        $this->updatedAt = $updated;

        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set priority
     *
     * @param IssuePriority $priority
     * @return $this
     */
    public function setPriority(IssuePriority $priority = null)
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * Get priority
     *
     * @return IssuePriority
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * Set resolution
     *
     * @param IssueResolution $resolution
     * @return $this
     */
    public function setResolution(IssueResolution $resolution = null)
    {
        $this->resolution = $resolution;

        return $this;
    }

    /**
     * Get resolution
     *
     * @return IssueResolution
     */
    public function getResolution()
    {
        return $this->resolution;
    }

    /**
     * Set workflowItem
     *
     * @param WorkflowItem $workflowItem
     * @return $this
     */
    public function setWorkflowItem(WorkflowItem $workflowItem = null)
    {
        $this->workflowItem = $workflowItem;

        return $this;
    }

    /**
     * Get workflowItem
     *
     * @return WorkflowItem
     */
    public function getWorkflowItem()
    {
        return $this->workflowItem;
    }

    /**
     * Set workflowStep
     *
     * @param WorkflowStep $workflowStep
     * @return $this
     */
    public function setWorkflowStep(WorkflowStep $workflowStep = null)
    {
        $this->workflowStep = $workflowStep;

        return $this;
    }

    /**
     * Get workflowStep
     *
     * @return WorkflowStep
     */
    public function getWorkflowStep()
    {
        return $this->workflowStep;
    }

    /**
     * Set reporter
     *
     * @param User $reporter
     * @return $this
     */
    public function setReporter(User $reporter = null)
    {
        $this->reporter = $reporter;

        return $this;
    }

    /**
     * Get reporter
     *
     * @return User
     */
    public function getReporter()
    {
        return $this->reporter;
    }

    /**
     * Set assignee
     *
     * @param User $assignee
     * @return $this
     */
    public function setAssignee(User $assignee = null)
    {
        $this->assignee = $assignee;

        return $this;
    }

    /**
     * Get assignee
     *
     * @return User
     */
    public function getAssignee()
    {
        return $this->assignee;
    }

    /**
     * Add related
     *
     * @param Issue $related
     * @return $this
     */
    public function setRelated(Issue $related)
    {
        $this->related[] = $related;
        return $this;
    }

    /**
     * Remove related
     *
     * @param Issue $related
     */
    public function removeRelated(Issue $related)
    {
        $this->related->removeElement($related);
    }

    /**
     * Get related
     *
     * @return ArrayCollection
     */
    public function getRelated()
    {
        return $this->related;
    }

    /**
     * Add collaborator
     *
     * @param User $collaborator
     * @return $this
     */
    public function addCollaborator(User $collaborator)
    {
        if (!$this->hasCollaborator($collaborator)) {
            $this->collaborators[] = $collaborator;
        }

        return $this;
    }

    /**
     * Remove collaborator
     *
     * @param User $collaborator
     */
    public function removeCollaborator(User $collaborator)
    {
        $this->collaborators->removeElement($collaborator);
    }

    /**
     * Get collaborator
     *
     * @return ArrayCollection
     */
    public function getCollaborators()
    {
        return $this->collaborators;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function hasCollaborator($user)
    {
        $collaborators = $this->getCollaborators();

        if ($collaborators->count()) {
            foreach ($collaborators as $collaborator) {
                if ($collaborator->getId() === $user->getId()) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Set parent
     *
     * @param Issue $parent
     * @return $this
     */
    public function setParent(Issue $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return Issue
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set children
     *
     * @param Issue $children
     * @return $this
     */
    public function addChild(Issue $children)
    {
        $this->children[] = $children;

        return $this;
    }

    /**
     * Remove children
     *
     * @param Issue $children
     */
    public function removeChild(Issue $children)
    {
        $this->children->removeElement($children);
    }

    /**
     * Get children
     *
     * @return ArrayCollection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Set owner
     *
     * @param User $owner
     * @return $this
     */
    public function setOwner(User $owner = null)
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Get owner
     *
     * @return User
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * Set organization
     *
     * @param Organization $organization
     * @return $this
     */
    public function setOrganization(Organization $organization = null)
    {
        $this->organization = $organization;

        return $this;
    }

    /**
     * Get organization
     *
     * @return Organization
     */
    public function getOrganization()
    {
        return $this->organization;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps()
    {
        $this->setUpdatedAt(new \DateTime('now', new \DateTimeZone('UTC')));

        if ($this->getCreatedAt() == null) {
            $this->setCreatedAt(new \DateTime('now', new \DateTimeZone('UTC')));
        }
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->getSummary();
    }
}
