<?php

namespace NS\ImportBundle\Tests\Twig;


use NS\ImportBundle\Twig\ImportResultActions;

class ImportResultActionsTest extends \PHPUnit_Framework_TestCase
{
    public function testInititalization()
    {
        list($router,$translator,$import) = $this->getArguments();
        $twigAction = new ImportResultActions($router,$translator);
        $this->assertCount(1,$twigAction->getFunctions());
        $this->assertEquals('ImportResultAction',$twigAction->getName());

    }
    public function testIsCompleteWithoutError()
    {
        list($router,$translator,$import) = $this->getArguments();

        $import->expects($this->once())
            ->method('isComplete')
            ->willReturn(true);

        $import->expects($this->once())
            ->method('hasError')
            ->willReturn(false);

        $router
            ->expects($this->once())
            ->method('generate')
            ->willReturn('/path');

        $twigAction = new ImportResultActions($router,$translator);
        $twigAction->importActions($import);
    }

    public function testIsNotCompleteUnqueuedNoError()
    {
        list($router,$translator,$import) = $this->getArguments();

        $import->expects($this->once())
            ->method('isComplete')
            ->willReturn(false);

        $import->expects($this->once())
            ->method('isQueued')
            ->willReturn(false);

        $import->expects($this->atLeast(2))
            ->method('hasError')
            ->willReturn(false);

        $router
            ->expects($this->once())
            ->method('generate')
            ->willReturn('/path');

        $twigAction = new ImportResultActions($router,$translator);
        $twigAction->importActions($import);
    }

    public function testIsNotCompleteQueuedNoError()
    {
        list($router,$translator,$import) = $this->getArguments();

        $import->expects($this->once())
            ->method('isComplete')
            ->willReturn(false);

        $import->expects($this->once())
            ->method('isQueued')
            ->willReturn(true);

        $import->expects($this->once())
            ->method('hasError')
            ->willReturn(false);

        $router
            ->expects($this->never())
            ->method('generate')
            ->willReturn('/path');

        $twigAction = new ImportResultActions($router,$translator);
        $twigAction->importActions($import);
    }

    public function testIsNotCompleteNotQueuedWithError()
    {
        list($router,$translator,$import) = $this->getArguments();

        $import->expects($this->atLeast(2))
            ->method('isComplete')
            ->willReturn(false);

        $import->expects($this->once())
            ->method('isQueued')
            ->willReturn(false);

        $import->expects($this->atLeast(2))
            ->method('hasError')
            ->willReturn(true);

        $router
            ->expects($this->once())
            ->method('generate')
            ->willReturn('/path');

        $twigAction = new ImportResultActions($router,$translator);
        $twigAction->importActions($import);
    }

    public function getArguments()
    {
        $translator = $this->getMock('Symfony\Component\Translation\TranslatorInterface');
        $router = $this->getMock('Symfony\Component\Routing\RouterInterface',array(), array(), '', false);
        $import = $this->getMock('NS\ImportBundle\Entity\Import',array(), array(), '', false);
        $import->expects($this->any())
            ->method('getId')
            ->willReturn(12);

        return array($router,$translator,$import);
    }
}
