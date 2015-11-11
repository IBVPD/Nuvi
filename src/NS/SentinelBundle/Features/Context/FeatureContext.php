<?php

namespace NS\SentinelBundle\Features\Context;

use \Behat\Mink\Exception\UnsupportedDriverActionException;
use \Behat\MinkExtension\Context\MinkContext;
use \Behat\Symfony2Extension\Context\KernelAwareContext;
use \Behat\Symfony2Extension\Driver\KernelDriver;

/**
 * Feature context.
 */
class FeatureContext extends MinkContext implements KernelAwareContext
{
    private $kernel;

    /**
     * Sets HttpKernel instance.
     * This method will be automatically called by Symfony2Extension ContextInitializer.
     *
     * @param KernelInterface|\Symfony\Component\HttpKernel\KernelInterface $kernel
     */
    public function setKernel(\Symfony\Component\HttpKernel\KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @Given /^I am not logged in$/
     */
    public function iAmNotLoggedIn()
    {
        $this->visit("/logout");
        $this->visit("/login");
    }

    /**
     * @Given /^I login with "([^"]*)" "([^"]*)"$/
     */
    public function iLoginWith($arg1, $arg2)
    {
        $this->visit("/login");
        $this->fillField("_username", $arg1);
        $this->fillField("_password", $arg2);
        $this->pressButton("login");
    }

    /**
     * @Given /^I visit "([^"]*)" with "([^"]*)"$/
     */
    public function iVisitWith($arg1, $arg2)
    {
        $path = str_replace('//', '/', "$arg1/" . sprintf($arg2, date('y')));

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

        \PHPUnit_Framework_Assert::assertFalse($collector->hasException(), ($collector->hasException() ? $collector->getException()->getMessage() : null));
    }

    /**
     * @return mixed
     * @throws UnsupportedDriverActionException
     */
    public function getSymfonyProfile()
    {
        $driver = $this->getSession()->getDriver();

        if (!$driver instanceof KernelDriver) {
            throw new UnsupportedDriverActionException('You need to tag the scenario with "@mink:symfony2". Using the profiler is not supported by %s', $driver);
        }

        $profile = $driver->getClient()->getProfile();
        if (false === $profile) {
            throw new \RuntimeException('Profiler is disabled.');
        }

        return $profile;
    }

    /**
     * @Then /^The Create Form Has (\d+) Sites$/
     */
    public function theCreateFormHasSites($arg1)
    {
        if (!$this->kernel) {
            \PHPUnit_Framework_Assert::assertFalse(true, "There is no kernel");
        }

        $container   = $this->kernel->getContainer();
        $formFactory = $container->get('form.factory');
        $user        = $container->get('security.context')->getToken()->getUser();
        $ibdForm     = $formFactory->create('create_case');
        $ibdView     = $ibdForm->createView();

        if ($arg1 == 0) {
            \PHPUnit_Framework_Assert::assertFalse($ibdForm->has('site'), "Form has Site field: " . $user->getName());
        }
        else {
            \PHPUnit_Framework_Assert::assertTrue($ibdForm->has('site'), "Form has Site field " . $user->getName());
            \PHPUnit_Framework_Assert::assertCount(intval($arg1), $ibdView['site']->vars['choices'], "$arg1 was passed in " . $user->getName());
        }
    }

    /**
     * @Then /^The Create Form Has (\d+) Types$/
     */
    public function theCreateFormHasTypes($arg1)
    {
        if (!$this->kernel) {
            \PHPUnit_Framework_Assert::assertFalse(true, "There is no kernel");
        }

        $container   = $this->kernel->getContainer();
        $formFactory = $container->get('form.factory');
        $user        = $container->get('security.context')->getToken()->getUser();
        $ibdForm     = $formFactory->create('create_case');
        $ibdView     = $ibdForm->createView();

        if ($arg1 == 0) {
            \PHPUnit_Framework_Assert::assertFalse($ibdForm->has('type'), "IBD Form has types field " . $user->getName());
        }
        else {
            \PHPUnit_Framework_Assert::assertTrue($ibdForm->has('type'), "IBD Form has types " . $user->getName());
            \PHPUnit_Framework_Assert::assertCount(intval($arg1), $ibdView['type']->vars['choices'], "IBD Form $arg1 was passed in " . $user->getName());
        }
    }
}
