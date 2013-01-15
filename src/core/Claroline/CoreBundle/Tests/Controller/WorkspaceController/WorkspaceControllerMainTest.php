<?php

namespace Claroline\CoreBundle\Controller;

use Claroline\CoreBundle\Library\Testing\FunctionalTestCase;
use Claroline\CoreBundle\Library\Installation\Plugin\Loader;

class WorkspaceControllerMainTest extends FunctionalTestCase
{
    protected function setUp()
    {
        parent::setUp();
        $this->client->followRedirects();
    }
/*
    public function testWSCreatorcanViewHisWorkspaces()
    {
        $this->loadUserFixture(array('ws_creator'));
        $this->loadWorkspaceFixture(array('ws_a'));
        $crawler = $this->logUser($this->getFixtureReference('user/ws_creator'));
        $link = $crawler->filter('#link-my-workspaces')->link();
        $crawler = $this->client->click($link);
        $this->assertEquals(2, $crawler->filter('.row-workspace')->count());
    }

    public function testAdmincanViewHisWorkspaces()
    {
        $this->loadUserFixture(array('admin'));
        $this->loadWorkspaceFixture(array('ws_e'));
        $crawler = $this->logUser($this->getFixtureReference('user/admin'));
        $link = $crawler->filter('#link-my-workspaces')->link();
        $crawler = $this->client->click($link);
        $this->assertEquals(2, $crawler->filter('.row-workspace')->count());
    }

    public function testWSCreatorCanCreateWS()
    {
        $this->loadUserFixture(array('ws_creator'));
        $crawler = $this->logUser($this->getFixtureReference('user/ws_creator'));
        $link = $crawler->filter('#link-create-ws-form')->link();
        $crawler = $this->client->click($link);
        $form = $crawler->filter('button[type=submit]')->form();
        $form['workspace_form[name]'] = 'new_workspace';
        $form['workspace_form[type]'] = 'simple';
        $form['workspace_form[code]'] = 'code';
        $this->client->submit($form);
        $crawler = $this->client->request('GET', "/workspaces");
        $this->assertEquals(1, $crawler->filter('.row-workspace')->count());
    }

    public function testWSCreatorCanDeleteHisWS()
    {
        $this->loadUserFixture(array('ws_creator'));
        $this->loadWorkspaceFixture(array('ws_a', 'ws_b', 'ws_c', 'ws_d'));
        $this->logUser($this->getFixtureReference('user/ws_creator'));
        $crawler = $this->client->request('DELETE', "/workspaces/{$this->getFixtureReference('workspace/ws_d')->getId()}");
        $crawler = $this->client->request('GET', "/workspaces/user/{$this->getFixtureReference('user/ws_creator')->getId()}");
        $this->assertEquals(4, $crawler->filter('.row-workspace')->count());
    }

    public function testWSManagercanViewHisWS()
    {
        $this->loadUserFixture(array('ws_creator'));
        $this->loadWorkspaceFixture(array('ws_a', 'ws_b', 'ws_c', 'ws_d'));
        $this->logUser($this->getFixtureReference('user/ws_creator'));
        $crawler = $this->client->request('GET', "/workspaces/user/{$this->getFixtureReference('user/ws_creator')->getId()}");
        $link = $crawler->filter("#link-home-{$this->getFixtureReference('workspace/ws_d')->getId()}")->link();
        $crawler = $this->client->click($link);
        $this->assertEquals(1, $crawler->filter(".welcome-home")->count());
    }

    public function testUsercanViewWSList()
    {
        $this->loadUserFixture(array('user', 'admin'));
        $this->loadWorkspaceFixture(array('ws_e', 'ws_f'));
        $this->logUser($this->getFixtureReference('user/user'));
        $crawler = $this->client->request('GET', "/workspaces");
        $this->assertEquals(2, $crawler->filter('.row-workspace')->count());
    }

    //111111111111111111111111111111111
    //++++++++++++++++++++++++++++++/
    // ACCESS WORKSPACE MAIN PAGES +/
    //++++++++++++++++++++++++++++++/

    public function testUserCantAccessUnregisteredResource()
    {
        $this->loadUserFixture(array('user', 'admin'));
        $this->logUser($this->getFixtureReference('user/user'));
        $this->client->request('GET', "/workspaces/resource/{$this->getFixtureReference('user/admin')->getPersonalWorkspace()->getId()}");
        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());
    }

    public function testDisplayHome()
    {
        $this->loadUserFixture(array('user'));
        $this->logUser($this->getFixtureReference('user/user'));
        $this->client->request('GET', "/workspaces/{$this->getFixtureReference('user/user')->getPersonalWorkspace()->getId()}");
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testUserCantAccessUnregisteredHome()
    {
        $this->loadUserFixture(array('user', 'admin'));
        $this->logUser($this->getFixtureReference('user/user'));
        $this->client->request('GET', "/workspaces/{$this->getFixtureReference('user/admin')->getPersonalWorkspace()->getId()}");
        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());
    }

    public function testDisplayUserManagement()
    {
        $this->loadUserFixture(array('user'));
        $this->logUser($this->getFixtureReference('user/user'));
        $this->client->request('GET', "/workspaces/{$this->getFixtureReference('user/user')->getPersonalWorkspace()->getId()}/tools/user_management");
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testUserCantAccessUnregisteredUserManagement()
    {
        $this->loadUserFixture(array('user', 'admin'));
        $this->logUser($this->getFixtureReference('user/user'));
        $this->client->request('GET', "/workspaces/{$this->getFixtureReference('user/admin')->getPersonalWorkspace()->getId()}/tools/user_management");
        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());
    }

    public function testDisplayUnregisteredUserList()
    {
        $this->loadUserFixture(array('user'));
        $this->logUser($this->getFixtureReference('user/user'));
        $this->client->request('GET', "/workspaces/{$this->getFixtureReference('user/user')->getPersonalWorkspace()->getId()}/tools/users/unregistered");
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testUserCantAccessUnregisteredUserList()
    {
        $this->loadUserFixture(array('user', 'admin'));
        $this->logUser($this->getFixtureReference('user/user'));
        $this->client->request('GET', "/workspaces/{$this->getFixtureReference('user/admin')->getPersonalWorkspace()->getId()}/tools/users/unregistered");
        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());
    }

    public function testDisplayUserParameters()
    {
        $this->loadUserFixture(array('user'));
        $this->logUser($this->getFixtureReference('user/user'));
        $this->client->request('GET', "/workspaces/{$this->getFixtureReference('user/user')->getPersonalWorkspace()->getId()}/tools/user/{$this->getFixtureReference('user/user')->getId()}");
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testUserCantAccessUnregisteredUserParameters()
    {
        $this->loadUserFixture(array('user', 'admin'));
        $this->logUser($this->getFixtureReference('user/user'));
        $this->client->request('GET', "/workspaces/{$this->getFixtureReference('user/admin')->getPersonalWorkspace()->getId()}/tools/user/{$this->getFixtureReference('user/user')->getId()}");
        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());
    }

    public function testDisplayGroupManagement()
    {
        $this->loadUserFixture(array('user'));
        $this->logUser($this->getFixtureReference('user/user'));
        $this->client->request('GET', "/workspaces/{$this->getFixtureReference('user/user')->getPersonalWorkspace()->getId()}/tools/group_management");
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testUserCantAccessUnregisteredGroupManagement()
    {
        $this->loadUserFixture(array('user', 'admin'));
        $this->logUser($this->getFixtureReference('user/user'));
        $this->client->request('GET', "/workspaces/{$this->getFixtureReference('user/admin')->getPersonalWorkspace()->getId()}/tools/group_management");
        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());
    }

    public function testDisplayUnregisteredGroupList()
    {
        $this->loadUserFixture(array('user'));
        $this->logUser($this->getFixtureReference('user/user'));
        $this->client->request('GET', "/workspaces/{$this->getFixtureReference('user/user')->getPersonalWorkspace()->getId()}/tools/groups/unregistered");
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testUserCantAccessUnregisteredGroupList()
    {
        $this->loadUserFixture(array('user', 'admin'));
        $this->logUser($this->getFixtureReference('user/user'));
        $this->client->request('GET', "/workspaces/{$this->getFixtureReference('user/admin')->getPersonalWorkspace()->getId()}/tools/groups/unregistered");
        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());
    }

    public function testManagercanViewWidgetProperties()
    {
        $this->registerStubPlugins(array('Valid\WithWidgets\ValidWithWidgets'));
        $this->loadUserFixture(array('user'));
        $this->logUser($this->getFixtureReference('user/user'));
        $crawler = $this->client->request('GET', "/workspaces/{$this->getFixtureReference('user/user')->getPersonalWorkspace()->getId()}/properties/widget");
        $this->assertGreaterThan(3, count($crawler->filter('.row-widget-config')));
    }

    public function testManagerCanInvertWidgetVisible()
    {
        $this->registerStubPlugins(array('Valid\WithWidgets\ValidWithWidgets'));
        $this->loadUserFixture(array('user', 'admin'));
        //admin must unlock first
        $this->logUser($this->getFixtureReference('user/user'));
        $configs = $this->client->getContainer()->get('doctrine.orm.entity_manager')->getRepository('ClarolineCoreBundle:Widget\DisplayConfig')->findAll();
        $countConfigs = count($configs);
        $crawler = $this->client->request('GET', "/workspaces/{$this->getFixtureReference('user/user')->getPersonalWorkspace()->getId()}/widgets");
        $countVisibleWidgets = count($crawler->filter('.widget-content'));
        $this->client->request(
            'POST', "/workspaces/{$this->getFixtureReference('user/user')->getPersonalWorkspace()->getId()}/widget/{$configs[0]->getWidget()->getId()}/baseconfig/{$configs[0]->getId()}/invertvisible"
        );
        $crawler = $this->client->request('GET', "/workspaces/{$this->getFixtureReference('user/user')->getPersonalWorkspace()->getId()}/widgets");
        $this->assertEquals($countVisibleWidgets, count($crawler->filter('.widget-content')));
        $configs = $this->client->getContainer()->get('doctrine.orm.entity_manager')->getRepository('ClarolineCoreBundle:Widget\DisplayConfig')->findAll();
        $this->assertEquals(++$countConfigs, count($configs));
        $this->logUser($this->getFixtureReference('user/admin'));
        $this->client->request('POST', "/admin/plugin/lock/{$configs[0]->getId()}");
        $this->logUser($this->getFixtureReference('user/user'));
        $crawler = $this->client->request('GET', "/workspaces/{$this->getFixtureReference('user/user')->getPersonalWorkspace()->getId()}/widgets");
        $this->assertEquals(--$countVisibleWidgets, count($crawler->filter('.widget-content')));
    }

    public function testDisplayWsRightssProperties()
    {
        $this->loadUserFixture(array('user'));
        $this->logUser($this->getFixtureReference('user/user'));
        $crawler = $this->client->request('GET', "/workspaces/{$this->getFixtureReference('user/user')->getPersonalWorkspace()->getId()}/properties/rights");
        $this->assertEquals(1, count($crawler->filter('#right-table')));
    }

    public function testDisplayWorkspaceRightsForm()
    {
        $this->loadUserFixture(array('user'));
        $this->logUser($this->getFixtureReference('user/user'));
        $workspace = $this->getFixtureReference('user/user')->getPersonalWorkspace();
        $crawler = $this->client->request('GET', "/workspaces/{$workspace->getId()}/properties/workspace/rights/form");
        $this->assertEquals(1, count($crawler->filter('#workspace-rights-form')));
    }*/

    public function testEditResourceRights()
    {
        $this->loadUserFixture(array('user'));
        $this->logUser($this->getFixtureReference('user/user'));
        $workspace = $this->getFixtureReference('user/user')->getPersonalWorkspace();
        $em = $this->client->getContainer()->get('doctrine.orm.entity_manager');

        $workspaceRights = $em->getRepository('ClarolineCoreBundle:Rights\WorkspaceRights')->findBy(array('workspace' => $workspace));

        $this->client->request(
            'POST',
            "/workspaces/{$workspace->getId()}/properties/workspace/rights/edit",
            array(
               "canView-{$workspaceRights[0]->getId()}" => true,
               "canView-{$workspaceRights[1]->getId()}" => true,
               "canDelete-{$workspaceRights[1]->getId()}" => true,
           )
        );

        $em = $this->client->getContainer()->get('doctrine.orm.entity_manager');
        $seeToTrue = $em->getRepository('ClarolineCoreBundle:Rights\WorkspaceRights')->find($workspaceRights[0]->getId());
        $seeAndDeleteToTrue = $em->getRepository('ClarolineCoreBundle:Rights\WorkspaceRights')->find($workspaceRights[1]->getId());

        $this->assertTrue($seeToTrue->isEquals(array(
            'canView' => true,
            'canDelete' => false,
            'canEdit' => false,
            'canManage' => false,
        )));

        $this->assertTrue($seeAndDeleteToTrue->isEquals(array(
            'canView' => true,
            'canDelete' => true,
            'canEdit' => false,
            'canManage' => false
        )));
    }

    private function registerStubPlugins(array $pluginFqcns)
    {
        $container = $this->client->getContainer();
        $dbWriter = $container->get('claroline.plugin.recorder_database_writer');
        $pluginDirectory = $container->getParameter('claroline.stub_plugin_directory');
        $loader = new Loader($pluginDirectory);
        $validator = $container->get('claroline.plugin.validator');

        foreach ($pluginFqcns as $pluginFqcn) {
            $plugin = $loader->load($pluginFqcn);
            $validator->validate($plugin);
            $dbWriter->insert($plugin, $validator->getPluginConfiguration());
        }
    }
}