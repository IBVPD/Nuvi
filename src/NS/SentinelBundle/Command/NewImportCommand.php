<?php

namespace NS\SentinelBundle\Command;

use \Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use \Symfony\Component\Console\Input\InputInterface;
use \Symfony\Component\Console\Output\OutputInterface;
use \Symfony\Component\Console\Input\InputArgument;

use NS\SentinelBundle\Entity\Region;
use NS\SentinelBundle\Entity\Country;
use NS\SentinelBundle\Entity\Site;

/**
 * Description of ImportCommand
 *
 * @author gnat
 */
class NewImportCommand extends ContainerAwareCommand
{
    private $entityMgr;

    protected function configure()
    {
        $this
            ->setName('nssentinel:new-import')
            ->setDescription('Import Initial Regions and Sites')
            ->addArgument(
                'directory',
                InputArgument::REQUIRED,
                'Directory with CSV Files'
            )
        ; 
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dir      = $input->getArgument('directory');
        $files    = scandir($dir);
        $this->em = $this->getContainer()->get('doctrine.orm.entity_manager');

        foreach($files as $x => $file)
        {
            if($file[0] == '.')
            {
                unset($files[$x]);
                continue;
            }

            switch ($file)
            {
                case 'Regions.csv':
                    $files['region'] = $dir.'/'.$file;
                    unset($files[$x]);
                    break;
                case 'Countries.csv':
                    $files['country'] = $dir.'/'.$file;
                    unset($files[$x]);
                    break;
                case 'Sites.csv':
                    $files['site'] = $dir.'/'.$file;
                    unset($files[$x]);
                    break;
            }
        }

        $regions   = $this->processRegions($files['region'],$output);
        $output->writeln("Added ".count($regions)." Regions");

        if(isset($files['country']))
        {
            $countries = $this->processCountries($files['country'], $regions);
            $output->writeln("Added ".count($countries)." Countries");
        }

        if(isset($files['site']))
        {
            $ret = $this->processSites($files['site'], $countries);
            $output->writeln("Added ".count($ret['sites'])." Sites");

            if(isset($ret['errors']) && !empty($ret['errors']))
            {
                $output->writeln("");
                $output->writeln("Site import errors");
                foreach($ret['errors'] as $error)
                    $output->writeln($error);
            }
        }
    }

    private function processRegions($file,$output)
    {
        $fileId      = fopen($file,'r');
        $regions = array();

        while($row = fgetcsv($fileId))
        {
            if(!empty($row[1]))
            {
                $region = new Region();
                $region->setName($row[1]);
                $region->setCode($row[0]);
                $region->setWebsite($row[2]);
                $this->entityMgr->persist($region);
                $this->entityMgr->flush();
                $regions[$row[0]] = $region;
            }
            else
                $output->writeln("Row[1] is empty!");
        }

        fclose($fileId);

        return $regions;
    }

    private function processCountries($file,$regions)
    {
        $countries = array();
        $fileId        = fopen($file,'r');

        while($row = fgetcsv($fileId))
        {
            if(isset($regions[$row[0]]) && !empty($row[2]) && !empty($row[0]) && !empty($row[1]))
            {
                $c = new Country();
                $c->setName($row[1]);
                $c->setCode($row[2]);
                $c->setIsActive(true);
                $c->setRegion($regions[$row[0]]);
                $c->setHasReferenceLab(true);
                $c->setHasNationalLab(true);

                $this->entityMgr->persist($c);
                $this->entityMgr->flush();
                $countries[$row[2]] = $c;
            }
        }

        fclose($fileId);

        return $countries;
    }

    private function processSites($file,$countries)
    {
        $sites      = array();
        $fileId     = fopen($file,'r');
        $x          = 1;
        $errorSites = array();
        $row        = fgetcsv($fileId);
        while($row = fgetcsv($fileId))
        {
            $site = new Site();
            $site->setCode($row[2]);
            $site->setName($row[3]);
            $site->setibdTier($row[10]);

            $this->surveillanceAndSupport($site, $row, $errorSites);
            $this->setSiteIbdLastAssessment($site, $row, $errorSites);
            $this->setSiteRvLastAssessment($site, $row, $errorSites);

            if($row[13])
                $site->setibdSiteAssessmentScore($row[13]);

            $site->setibvpdRl($row[15]);
            $site->setrvRl($row[16]);
            $site->setibdEqaCode($row[17]);
            $site->setrvEqaCode($row[18]);

            $this->modifyCountry($site,$row, $countries[$row[1]]);

            $this->entityMgr->persist($site);
            $this->entityMgr->flush();
            $sites[$row[2]] = $site;
            $x++;
        }

        fclose($fileId);

        return array('sites'=>$sites,'errors'=>$errorSites);
    }

    private function modifyCountry($c, $row, $country)
    {
        if($country instanceof Country)
        {
            $country->setGaviEligible(new \NS\SentinelBundle\Form\Types\TripleChoice($row[5]));
            $country->setHibVaccineIntro($row[19]);
            $country->setPcvVaccineIntro($row[20]);
            $country->setRvVaccineIntro($row[21]);
            $country->setPopulationUnderFive2014($row[22]);
            $country->setPopulationUnderFive2014($row[23]);
            $c->setCountry($country);
        }
    }

    private function surveillanceAndSupport($c,$row, &$errorSites)
    {
        try
        {
            $c->setSurveillanceConducted(new \NS\SentinelBundle\Form\Types\SurveillanceConducted($row[9]));
        }
        catch (\Exception $e)
        {
            throw new \Exception("Tried to pass '{$row[9]}' to SurveillanceConducted\n ".$e->getMessage());
        }

        try
        {
            $c->setibdIntenseSupport(new \NS\SentinelBundle\Form\Types\IBDIntenseSupport($row[11]));
        }
        catch(\Exception $e)
        {
            $errorSites[] = "{$row[2]}:{$row[3]} - Has Invalid Intense Support Value {$row[11]}";
        }
    }

    private function setSiteIbdLastAssessment($c, $row, &$errorSites)
    {
        if($row[12])
        {
            try
            {
                $c->setibdLastSiteAssessmentDate(new \DateTime($row[12]));
            }
            catch(\Exception $e)
            {
                $errorSites[] = "{$row[2]}:{$row[3]} - Has Invalid IBD Last Site Assessment Date '{$row[12]}'";
            }
        }
    }

    private function setSiteRvLastAssessment($c, $row, &$errorSites)
    {
        if($row[14])
        {
            try
            {
                $c->setRvLastSiteAssessmentDate(new \DateTime($row[14]));
            }
            catch(\Exception $e)
            {
                $errorSites[] = "{$row[2]}:{$row[3]} - Has Invalid RV Last Site Assessment Date '{$row[14]}'";
            }
        }
    }
}
