<?php

namespace NS\ImportBundle\Tests\Twig;

use NS\ImportBundle\Twig\ImportResultActions;
use PHPUnit\Framework\TestCase;
use NS\ImportBundle\Entity\Import;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Translation\TranslatorInterface;

class ImportResultActionsTest extends TestCase
{
    public function testInititalization(): void
    {
        [$router, $translator, $import] = $this->getArguments();
        $twigAction = new ImportResultActions($router, $translator);
        $this->assertCount(1, $twigAction->getFunctions());
        $this->assertEquals('ImportResultAction', $twigAction->getName());
    }
    public function testIsCompleteWithoutError(): void
    {
        [$router, $translator, $import] = $this->getArguments();

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

        $twigAction = new ImportResultActions($router, $translator);
        $twigAction->importActions($import);
    }

    public function testIsNotCompleteUnqueuedNoError(): void
    {
        [$router, $translator, $import] = $this->getArguments();

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

        $twigAction = new ImportResultActions($router, $translator);
        $twigAction->importActions($import);
    }

    public function testIsNotCompleteQueuedNoError(): void
    {
        [$router, $translator, $import] = $this->getArguments();

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

        $twigAction = new ImportResultActions($router, $translator);
        $twigAction->importActions($import);
    }

    public function testIsNotCompleteNotQueuedWithError(): void
    {
        [$router, $translator, $import] = $this->getArguments();

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

        $twigAction = new ImportResultActions($router, $translator);
        $twigAction->importActions($import);
    }

    public function getArguments(): array
    {
        $translator = $this->createMock(TranslatorInterface::class);
        $router = $this->createMock(RouterInterface::class);
        $import = $this->createMock(Import::class);
        $import->expects($this->any())
            ->method('getId')
            ->willReturn(12);

        return [$router,$translator,$import];
    }
}
