<?php

namespace NS\SentinelBundle\Services;

use Swift_Mailer;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class DetectGitChanges
{
    /** @var Swift_Mailer */
    private $mailer;

    /** @var string */
    private $projectRoot;

    /**
     * DetectGitChanges constructor.
     *
     * @param Swift_Mailer $mailer
     * @param string       $projectRoot
     */
    public function __construct(Swift_Mailer $mailer, string $projectRoot)
    {
        $this->mailer      = $mailer;
        $this->projectRoot = $projectRoot;
    }

    public function getChanges(): string
    {
        $process = new Process('git diff', $this->projectRoot);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        return $process->getOutput();
    }

    public function sendChanges(string $name, string $email): bool
    {
        $changes = $this->getChanges();
        if (empty($changes)) {
            return true;
        }

        $attachment = new \Swift_Attachment($changes, 'changes.diff', 'text/plain');
        $message    = new \Swift_Message('Changes detected', 'Changes attached as patch');
        $message->setFrom('noreply@vinuvacasos.org', 'No Reply');
        $message->setTo($email, $name);
        $message->attach($attachment);

        return $this->mailer->send($message) === 1;
    }
}
