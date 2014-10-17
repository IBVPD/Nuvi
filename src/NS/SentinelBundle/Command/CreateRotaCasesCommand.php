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
            ->addOption('codes',null, InputOption::VALUE_OPTIONAL, null,'NIC-53,NIC-56,NIC-57,NIC-61,NIC-65,SLV-114,SLV-116,SLV-26,SLV-27,SLV-28,SLV-33,SLV-34,SLV-36,SLV-5,BGD-1,BGD-2,BGD-3')
        ; 
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        ini_set('memory_limit','768M');
        $codes = explode(",", str_replace(' ','',$input->getOption('codes')));
        $output->writeln(print_r($codes,true));
        if(empty($codes))
        {
            $output->writeln("No codes to add cases to");
            return;
        }

        $this->entityMgr = $this->getContainer()->get('ns.model_manager');
        $sites    = $this->entityMgr->getRepository('NSSentinelBundle:Site')->getChainByCode($codes);
        $output->writeln("Received ".count($sites)." sites");
        if(count($sites)== 0)
            return;

        $male   = new Gender(Gender::MALE);
        $fmale  = new Gender(Gender::FEMALE);
        $dx[]   = new Diagnosis(Diagnosis::SUSPECTED_MENINGITIS);
        $dx[]   = new Diagnosis(Diagnosis::SUSPECTED_PNEUMONIA);
        $dx[]   = new Diagnosis(Diagnosis::SUSPECTED_SEPSIS);
        $dx[]   = new Diagnosis(Diagnosis::OTHER);

        for($x = 0; $x < 2700; $x++)
        {
            $dob     = $this->getRandomDate();
            $siteKey = array_rand($sites);

            $m = new RotaVirus();
            $m->setDob($dob);
            $m->setAdmDate($this->getRandomDate(null,$dob));
            $m->setSite($sites[$siteKey]);
            $m->setCaseId($this->getCaseId($sites[$siteKey]));
            $m->setGender(($x%7)?$fmale:$male);

            $this->entityMgr->persist($m);

            if($x % 100 == 0)
                $this->entityMgr->flush();
        }

        $this->entityMgr->flush();
        $output->writeln("Create 2700 rota cases");
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
