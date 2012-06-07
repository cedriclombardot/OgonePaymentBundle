<?php

namespace Cedriclombardot\OgonePaymentBundle\Tests\Manager;

use Cedriclombardot\OgonePaymentBundle\Tests\TestCase;
use Cedriclombardot\OgonePaymentBundle\Config\ConfigurationContainer;
use Cedriclombardot\OgonePaymentBundle\Config\SecureConfigurationContainer;
use Cedriclombardot\OgonePaymentBundle\Propel\OgoneAlias;
use Cedriclombardot\OgonePaymentBundle\Manager\AliasManager;
use Cedriclombardot\OgonePaymentBundle\Manager\Api;

class AliasManagerTest extends TestCase
{
    protected $aliasManager;

    public function setUp()
    {
        $configurationContainer = new ConfigurationContainer(array('PSPID' => 'TEST'));
        $secureConfigurationContainer = new SecureConfigurationContainer(array());

        $api = new Api($configurationContainer, $secureConfigurationContainer);

        $this->aliasManager = new AliasManagerMock($api);
    }

    public function testBuildOgoneCsv()
    {
        $datas = array(
            'card_name'   => 'Foo bar',
            'card_number' => '4111113333333333',
            'expiration_date_month' => 9,
            'expiration_date_year'  => 2012,
            'brand' => 'VISA',
        );

        $alias = new OgoneAlias();
        $alias->setName('NAME');
        $this->aliasManager->withAlias($alias);

        $this->assertEquals('ADDALIAS;NAME;Foo bar;4111113333333333;0912;VISA;TEST;', $this->aliasManager->buildOgoneCsv(AliasManager::METHOD_ADDALIAS, $datas));
        $this->assertEquals('DELALIAS;NAME;Foo bar;4111113333333333;0912;VISA;TEST;', $this->aliasManager->buildOgoneCsv(AliasManager::METHOD_DELALIAS, $datas));
    }

}

class AliasManagerMock extends AliasManager
{
    public function __call($method, $args)
    {
        return call_user_func_array(array($this,$method), $args);
    }
}
