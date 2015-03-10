<?php
namespace Oro\Bundle\IssueBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Oro\Bundle\IssueBundle\Entity\Issue;
use Oro\Bundle\SecurityBundle\Annotation\Acl;
use Oro\Bundle\SecurityBundle\Annotation\AclAncestor;

class IssueController extends Controller
{

    /**
     * @Route(name="oro_issue_index")
     * @Template()
     * @Acl(
     *      id="oro_issue_view",
     *      type="entity",
     *      class="IssueBundle:Issue",
     *      permission="VIEW"
     * )
     * @return array
     */
    public function indexAction()
    {
        return [
            'entity_class' => $this->container->getParameter('oro_issue.entity.class')
        ];
    }

    /**
     * @Route("/create", name="oro_issue_create")
     * @Acl(
     *      id="oro_issue_create",
     *      type="entity",
     *      class="IssueBundle:Issue",
     *      permission="CREATE"
     * )
     * @Template("IssueBundle:Issue:update.html.twig")
     */
    public function createAction()
    {
        $issue = new Issue();
        $issue->setReporter($this->getUser());

        $formAction = $this->get('oro_entity.routing_helper')
            ->generateUrlByRequest('oro_issue_create', $this->getRequest());

        return $this->update($issue, $formAction);
    }

    /**
     * @Route("/update/{id}", name="oro_issue_update", requirements={"id"="\d+"})
     * @Template()
     * @Acl(
     *      id="oro_issue_update",
     *      type="entity",
     *      class="IssueBundle:Issue",
     *      permission="EDIT"
     * )
     */
    public function updateAction(Issue $issue)
    {
        $formAction = $this->get('router')->generate('oro_issue_update', ['id' => $issue->getId()]);

        return $this->update($issue, $formAction);
    }

    /**
     * @param Issue $issue
     * @param string $formAction
     * @return array
     */
    public function update(Issue $issue, $formAction)
    {
        if ($this->get('oro_issue.form.handler.issue')->process($issue)) {
            return $this->get('oro_ui.router')->redirectAfterSave(
                array(
                    'route' => 'oro_issue_update',
                    'parameters' => array('id' => $issue->getId()),
                ),
                array(
                    'route' => 'oro_issue_view',
                    'parameters' => array('id' => $issue->getId()),
                )
            );
        }

        return array(
            'entity'     => $issue,
            'form'       => $this->get('oro_issue.form.handler.issue')->getForm()->createView(),
            'formAction' => $formAction,
        );
    }

    /**
     * @Route("/view/{id}", name="oro_issue_view", requirements={"id"="\d+"})
     * @AclAncestor("oro_issue_view")
     * @Template()
     * @param Issue $issue
     * @return array
     */
    public function viewAction(Issue $issue)
    {
        return array('entity' => $issue);
    }
}