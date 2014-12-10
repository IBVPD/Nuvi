<?php

namespace NS\SentinelBundle\Command;

use \NS\SentinelBundle\Entity\RotaVirus;
use \NS\SentinelBundle\Entity\Site;
use \NS\SentinelBundle\Form\Types\Diagnosis;
use \NS\SentinelBundle\Form\Types\Gender;
use \Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use \Symfony\Component\Console\Input\InputInterface;
use \Symfony\Component\Console\Input\InputOption;
use \Symfony\Component\Console\Output\OutputInterface;

/**
 * Description of ImportCommand
 *
 * @author gnat
 */
class CreateRotaCasesCommand extends ContainerAwareCommand
{
    private $entityMgr;
    
    protected function configure()
    {
        $this
            ->setName('nssentinel:rota:create:cases')
            ->setDescription('Create RotaVirus cases')
            ->addArgument('codes', null, InputOption::VALUE_REQUIRED)
            ->addOption('casecount', null, InputOption::VALUE_OPTIONAL, null, 2700)
        ;
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        ini_set('memory_limit','768M');
        $codes = explode(",", str_replace(' ', '', $input->getArgument('codes')));
        $output->writeln(print_r($codes,true));
        if(empty($codes))
        {
            $output->writeln("No codes to add cases to");
            return;
        }

        $caseCount       = $input->getOption('casecount');
        $this->entityMgr = $this->getContainer()->get('ns.model_manager');
        $sites    = $this->entityMgr->getRepository('NSSentinelBundle:Site')->getChainByCode($codes);
        $output->writeln("Received ".count($sites)." sites");
        if(count($sites)== 0)
            return;

        $male        = new Gender(Gender::MALE);
        $fmale       = new Gender(Gender::FEMALE);
        $diagnosis[] = new Diagnosis(Diagnosis::SUSPECTED_MENINGITIS);
        $diagnosis[] = new Diagnosis(Diagnosis::SUSPECTED_PNEUMONIA);
        $diagnosis[] = new Diagnosis(Diagnosis::SUSPECTED_SEPSIS);
        $diagnosis[] = new Diagnosis(Diagnosis::OTHER);

        for ($x = 0; $x < $caseCount; $x++)
        {
            $dob     = $this->getRandomDate();
            $siteKey = array_rand($sites);

            $case = new RotaVirus();
            $case->setDob($dob);
            $case->setAdmDate($this->getRandomDate(null, $dob));
            $case->setSite($sites[$siteKey]);
            $case->setCaseId($this->getCaseId($sites[$siteKey]));
            $case->setGender(($x % 7) ? $fmale : $male);

            $this->entityMgr->persist($case);

            if($x % 100 == 0)
                $this->entityMgr->flush();
        }

        $this->entityMgr->flush();
        $output->writeln(sprintf("Create %d rota cases", $caseCount));
    }

    public function getCaseId(Site $site)
    {
        return md5(uniqid().spl_object_hash($site).time());
    }

    public function getRandomDate(\DateTime $before = null, \DateTime $after = null)
    {
        $years  = range(1995,date('Y'));
        $months = range(1,12);
        $days   = range(1,28);

        $yKey   = array_rand($years);
        $mKey   = array_rand($months);
        $dKey   = array_rand($days);

        if($before != null)
        {
            $byear = $before->format('Y');
            while($years[$yKey] > $byear)
                $yKey = array_rand($years);
        }

        if($after != null)
        {
            $ayear = $after->format('Y');
            while($years[$yKey] < $ayear)
            {
                $yKey = array_rand($years);
            }
        }

        return new \DateTime("{$years[$yKey]}-{$months[$mKey]}-{$days[$dKey]}");
    }
}
