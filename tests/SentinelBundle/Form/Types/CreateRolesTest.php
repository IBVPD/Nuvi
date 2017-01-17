<?php

namespace NS\SentinelBundle\Tests\Form\Types;

use NS\SentinelBundle\Form\Types\CaseCreationType;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Description of CreateRolesTest
 *
 * @author gnat
 */
class CreateRolesTest extends TypeTestCase
{
    const CREATE = 'ROLE_CAN_CREATE';

    const CREATE_CASE = 'ROLE_CAN_CREATE_CASE';

    const CREATE_LAB = 'ROLE_CAN_CREATE_LAB';

    const CREATE_RRL = 'ROLE_CAN_CREATE_RRL_LAB';

    const CREATE_NL = 'ROLE_CAN_CREATE_NL_LAB';

    /** @var  AuthorizationCheckerInterface */
    private $authChecker;

    /**
     * @dataProvider roleProvider
     * @param $count
     * @param $data
     */
    public function testByRoles($count, $data)
    {
        $this->getAuthorizationChecker($data);
        $form        = $this->factory->create(CaseCreationType::class);
        $this->assertEquals('case_creation', $form->getName());
        $choices     = $form->getConfig()->getOption('choices');
        $this->assertCount($count, $choices);
    }

    public function roleProvider()
    {
        return [
            ['count' => 2, 'data'  => [
                    0 => ['param' => self::CREATE, 'ret' => true],
                    1 => ['param' => self::CREATE_CASE, 'ret' => true],
                    2 => ['param' => self::CREATE_LAB, 'ret' => true],
                    3 => ['param' => self::CREATE_RRL, 'ret' => false],
                    4 => ['param' => self::CREATE_NL, 'ret' => false],
            ]],
            ['count' => 2, 'data'  => [
                    0 => ['param' => self::CREATE, 'ret' => true],
                    1 => ['param' => self::CREATE_CASE, 'ret' => true],
                    2 => ['param' => self::CREATE_LAB, 'ret' => false],
                    3 => ['param' => self::CREATE_RRL, 'ret' => true],
                    4 => ['param' => self::CREATE_NL, 'ret' => false],
            ]],
            ['count' => 2, 'data'  => [
                    0 => ['param' => self::CREATE, 'ret' => true],
                    1 => ['param' => self::CREATE_CASE, 'ret' => true],
                    2 => ['param' => self::CREATE_LAB, 'ret' => false],
                    3 => ['param' => self::CREATE_RRL, 'ret' => false],
                    4 => ['param' => self::CREATE_NL, 'ret' => true],
            ]],
            ['count' => 4, 'data'  => [
                    0 => ['param' => self::CREATE, 'ret' => true],
                    1 => ['param' => self::CREATE_CASE, 'ret' => true],
                    2 => ['param' => self::CREATE_LAB, 'ret' => true],
                    3 => ['param' => self::CREATE_RRL, 'ret' => true],
                    4 => ['param' => self::CREATE_NL, 'ret' => true],
            ]],
            ['count' => 2, 'data'  => [
                    0 => ['param' => self::CREATE, 'ret' => true],
                    1 => ['param' => self::CREATE_CASE, 'ret' => false],
                    2 => ['param' => self::CREATE_LAB, 'ret' => false],
                    3 => ['param' => self::CREATE_RRL, 'ret' => true],
                    4 => ['param' => self::CREATE_NL, 'ret' => true],
            ]],
            ['count' => 2, 'data'  => [
                    0 => ['param' => self::CREATE, 'ret' => true],
                    1 => ['param' => self::CREATE_CASE, 'ret' => false],
                    2 => ['param' => self::CREATE_LAB, 'ret' => true],
                    3 => ['param' => self::CREATE_RRL, 'ret' => true],
                    4 => ['param' => self::CREATE_NL, 'ret' => false],
            ]],
            ['count' => 3, 'data'  => [
                    0 => ['param' => self::CREATE, 'ret' => true],
                    1 => ['param' => self::CREATE_CASE, 'ret' => false],
                    2 => ['param' => self::CREATE_LAB, 'ret' => true],
                    3 => ['param' => self::CREATE_RRL, 'ret' => true],
                    4 => ['param' => self::CREATE_NL, 'ret' => true],
            ]],
            ['count' => 0, 'data'  => [
                    0 => ['param' => self::CREATE, 'ret' => false],
            ]],
        ];
    }

    /**
     * @param $role
     * @param $route
     *
     * @dataProvider getRoutes
     */
    public function testGetRoute($role, $route)
    {
        $form = new CaseCreationType($role);
        $baseRoute = 'base';
        $this->assertEquals(sprintf('%s%s',$baseRoute,$route),$form->getRoute($baseRoute));
    }

    public function getRoutes()
    {
        return [
            [CaseCreationType::BASE,'Edit'],
            [CaseCreationType::SITE,'LabEdit'],
            [CaseCreationType::NL,'NLEdit'],
            [CaseCreationType::RRL,'RRLEdit'],
            [null,'Index'],
        ];
    }

    public function getAuthorizationChecker(array $calls = [])
    {
        foreach ($calls as $index => $call) {
            $this->authChecker->expects($this->at($index))
                ->method('isGranted')
                ->with($call['param'])
                ->willReturn($call['ret']);
        }
    }

    protected function getExtensions()
    {
        $createRole  = new CaseCreationType();
        $createRole->setAuthChecker($this->authChecker);

        return [new PreloadedExtension([$createRole],[])];
    }

    protected function setUp()
    {
        $this->authChecker = $this->createMock(AuthorizationCheckerInterface::class);
        parent::setUp();
    }
}
