<?php

namespace NS\SentinelBundle\Features\Context;

use Symfony\Component\HttpKernel\KernelInterface;
use Behat\Symfony2Extension\Context\KernelAwareInterface;
use Behat\MinkExtension\Context\MinkContext;
use Behat\Behat\Context\Step\When;
use Behat\Mink\Exception\UnsupportedDriverActionException;
use \Behat\Symfony2Extension\Driver\KernelDriver;

//
// Require 3rd-party libraries here:
//
//   require_once 'PHPUnit/Autoload.php';
//   require_once 'PHPUnit/Framework/Assert/Functions.php';
//
// Use PHP Assertions:
//   \PHPUnit_Framework_Assert::assertTrue(true,"Have an event log of $arg1 type");
//

/**
 * Feature context.
 */
class FeatureContext extends MinkContext //MinkContext if you want to test web
                  implements KernelAwareInterface
{
    private $kernel;
    private $parameters;

    /**
     * Initializes context with parameters from behat.yml.
     *
     * @param array $parameters
     */
    public function __construct(array $parameters)
    {
        $this->parameters = $parameters;
    }

    /**
     * Sets HttpKernel instance.
     * This method will be automatically called by Symfony2Extension ContextInitializer.
     *
     * @param KernelInterface $kernel
     */
    public function setKernel(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @Given /^I am not logged in$/
     */
    public function iAmNotLoggedIn()
    {
        return array(
            new When('I go to "/logout"'),
            new When('I should be on "/login"'),
        );
    }

    /**
     * @Given /^I login with "([^"]*)" "([^"]*)"$/
     */
    public function iLoginWith($arg1, $arg2)
    {
        return array(
            new When('I am on "/login"'),
            new When('I fill in "_username" with "'.$arg1.'"'),
            new When('I fill in "_password" with "'.$arg2.'"'),
            new When('I press "login"'),
            );
    }

    /**
     * @Given /^I visit "([^"]*)" with "([^"]*)"$/
     */
    public function iVisitWith($arg1, $arg2)
    {
        $path = str_replace('//','/',"$arg1/".sprintf($arg2,date('y')));

        $this->visit($path);
    }

    /**
     * @Then /^I should not be on "([^"]*)"$/
     */
    public function iShouldNotBeOn($arg1)
    {
        $this->assertSession()->addressNotEquals($this->locatePath($arg1));
    }

    /**
     * @Then /^There should be no exception$/
     */
    public function thereShouldBeNoException()
    {
        $profile   = $this->getSymfonyProfile();
        $collector = $profile->getCollector('exception');
        \PHPUnit_Framework_Assert::assertFalse($collector->hasException(),($collector->hasException()?$collector->getException()->getMessage():null));
    }

    public function getSymfonyProfile()
    {
        $driver = $this->getSession()->getDriver();

        if (!$driver instanceof KernelDriver)
            throw new UnsupportedDriverActionException('You need to tag the scenario with "@mink:symfony2". Using the profiler is not supported by %s', $driver);

        $profile = $driver->getClient()->getProfile();
        if (false === $profile)
            throw new \RuntimeException('Profiler is disabled.');

        return $profile;
    }

    /**
     * @Then /^The Create Form Has (\d+) Sites$/
     */
    public function theCreateFormHasSites($arg1)
    {
        $container   = $this->kernel->getContainer();
        $formFactory = $container->get('form.factory');

        $user    = $container->get('security.context')->getToken()->getUser();
        $ibdForm = $formFactory->create('create_ibd');
        $ibdView = $ibdForm->createView();

        if($arg1 == 0)
            \PHPUnit_Framework_Assert::assertFalse($ibdForm->has('site'), "Form has Site field: " . $user->getName());
        else
        {
            \PHPUnit_Framework_Assert::assertTrue($ibdForm->has('site'), "Form has Site field " . $user->getName());
            \PHPUnit_Framework_Assert::assertCount(intval($arg1), $ibdView['site']->vars['choices'], "$arg1 was passed in " . $user->getName());
        }

        $rotaForm = $formFactory->create('create_rotavirus');
        $rotaView = $rotaForm->createView();

        if($arg1 == 0)
            \PHPUnit_Framework_Assert::assertFalse($rotaForm->has('site'), "Form has Site field" . $user->getName());
        else
        {
            \PHPUnit_Framework_Assert::assertTrue($rotaForm->has('site'), "Form has Site field " . $user->getName());
            \PHPUnit_Framework_Assert::assertCount(intval($arg1), $rotaView['site']->vars['choices'], "$arg1 was passed in " . $user->getName());
        }
    }

    /**
     * @Then /^The Create Form Has (\d+) Types$/
     */
    public function theCreateFormHasTypes($arg1)
    {
        $container   = $this->kernel->getContainer();
        $formFactory = $container->get('form.factory');

        $user    = $container->get('security.context')->getToken()->getUser();
        $ibdForm = $formFactory->create('create_ibd');
        $ibdView = $ibdForm->createView();

        if($arg1 == 0)
            \PHPUnit_Framework_Assert::assertFalse($ibdForm->has('type'), "IBD Form has types field " . $user->getName());
        else
        {
            \PHPUnit_Framework_Assert::assertTrue($ibdForm->has('type'), "IBD Form has types " . $user->getName());
            \PHPUnit_Framework_Assert::assertCount(intval($arg1), $ibdView['type']->vars['choices'], "IBD Form $arg1 was passed in " . $user->getName());
        }

        $rotaForm = $formFactory->create('create_rotavirus');
        $rotaView = $rotaForm->createView();

        if($arg1 == 0)
            \PHPUnit_Framework_Assert::assertFalse($rotaForm->has('type'), "Rota Form does not have type field " . $user->getName());
        else
        {
            \PHPUnit_Framework_Assert::assertTrue($rotaForm->has('type'), "Form has types " . $user->getName());
            \PHPUnit_Framework_Assert::assertCount(intval($arg1), $rotaView['type']->vars['choices'], "$arg1 was passed in " . $user->getName());
        }
    }
}
