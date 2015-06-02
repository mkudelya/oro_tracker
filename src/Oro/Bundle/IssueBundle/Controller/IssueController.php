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
     *      class="OroIssueBundle:Issue",
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
     *      class="OroIssueBundle:Issue",
     *      permission="CREATE"
     * )
     * @Template("OroIssueBundle:Issue:update.html.twig")
     * @return array
     */
    public function createAction()
    {
        $issue = new Issue();

        $formAction = $this->get('oro_entity.routing_helper')
            ->generateUrlByRequest('oro_issue_create', $this->getRequest());

        return $this->update($issue, $formAction, null);
    }

    /**
     * @Route("/create/subtask/{parentIssueCode}", name="oro_subissue_create")
     * @Acl(
     *      id="oro_issue_create",
     *      type="entity",
     *      class="OroIssueBundle:Issue",
     *      permission="CREATE"
     * )
     * @Template("OroIssueBundle:Issue:update.html.twig")
     * @param string $parentIssueCode
     * @return array
     */
    public function createSubissueAction($parentIssueCode)
    {
        $issue = new Issue();

        $formAction = $this->get('oro_entity.routing_helper')
            ->generateUrlByRequest(
                'oro_subissue_create',
                $this->getRequest(),
                array('parentIssueCode' => $parentIssueCode)
            );

        return $this->update($issue, $formAction, $parentIssueCode);
    }

    /**
     * @Route("/update/{id}", name="oro_issue_update", requirements={"id"="\d+"})
     * @Template()
     * @Acl(
     *      id="oro_issue_update",
     *      type="entity",
     *      class="OroIssueBundle:Issue",
     *      permission="EDIT"
     * )
     * @param Issue $issue
     * @return array
     */
    public function updateAction(Issue $issue)
    {
        $formAction = $this->get('router')->generate('oro_issue_update', ['id' => $issue->getId()]);

        return $this->update($issue, $formAction);
    }

    /**
     * @param Issue $issue
     * @param string $formAction
     * @param string $parentIssueCode
     * @return array
     */
    public function update(Issue $issue, $formAction, $parentIssueCode = null)
    {
        $saved = false;
        if ($this->get('oro_issue.form.handler.issue')->process($issue, $parentIssueCode, $this->getUser())) {
            if (!$this->getRequest()->get('_widgetContainer')) {
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
            $saved = true;
        }

        return array(
            'entity'     => $issue,
            'saved'      => $saved,
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

    /**
     * @Route("/statusbarchart/{widget}", name="oro_issue_statusbarchart", requirements={"widget"="[\w-]+"})
     * @Template("OroIssueBundle:Dashboard:status_bar_chart.html.twig")
     *
     * @param $widget
     * @return array $widgetAttr
     */
    public function statusbarchartAction($widget)
    {
        $data = $this->getDoctrine()->getRepository('OroIssueBundle:Issue')->loadGroupByStatus();

        $widgetAttr = $this->get('oro_dashboard.widget_configs')->getWidgetAttributesForTwig($widget);
        $widgetAttr['chartView'] = $this->get('oro_chart.view_builder')
            ->setArrayData($data)
            ->setOptions(
                array(
                    'name' => 'bar_chart',
                    'data_schema' => array(
                        'label' => array('field_name' => 'name'),
                        'value' => array('field_name' => 'ct')
                    )
                )
            )->getView();

        return $widgetAttr;
    }

    /**
     * @Route("/issueshortgrid/{widget}", name="oro_issue_issueshortgrid", requirements={"widget"="[\w-]+"})
     * @Template("OroIssueBundle:Dashboard:issue_short_grid.html.twig")
     *
     * @param $widget
     * @return array $widgetAttr
     */
    public function issueshortgridAction($widget)
    {
        $widgetAttr = $this->get('oro_dashboard.widget_configs')->getWidgetAttributesForTwig($widget);
        $widgetAttr['user'] = $this->getUser();

        return $widgetAttr;
    }
}
