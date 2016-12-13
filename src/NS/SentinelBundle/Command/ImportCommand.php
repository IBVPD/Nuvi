<?php

namespace NS\SentinelBundle\Command;

use NS\SentinelBundle\Form\IBD\Types\IntenseSupport;
use NS\SentinelBundle\Form\Types\SurveillanceConducted;
use NS\SentinelBundle\Form\Types\TripleChoice;
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
class ImportCommand extends ContainerAwareCommand
{
    /**
     * @var
     */
    private $entityMgr;

    /**
     *
     */
    protected function configure()
    {
        $this
            ->setName('nssentinel:import')
            ->setDescription('Import Initial Regions and Sites')
            ->addArgument(
                'directory',
                InputArgument::REQUIRED,
                'Directory with CSV Files'
            )
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $files           = $this->processFiles($input->getArgument('directory'));
        $this->entityMgr = $this->getContainer()->get('doctrine.orm.entity_manager');
        $regions         = $this->processRegions($files['region'], $output);

        $output->writeln("Added ".count($regions)." Regions");

        if (isset($files['country'])) {
            $countries = $this->processCountries($files['country'], $regions);
            $output->writeln("Added ".count($countries)." Countries");
        }

        if (isset($files['site'])) {
            $ret = $this->processSites($files['site'], $countries);
            $output->writeln("Added ".count($ret['sites'])." Sites");

            if (isset($ret['errors']) && !empty($ret['errors'])) {
                $output->writeln("");
                $output->writeln("Site import errors");
                foreach ($ret['errors'] as $error) {
                    $output->writeln($error);
                }
            }
        }
    }

    /**
     * @param $dir
     * @return array
     */
    private function processFiles($dir)
    {
        $files = scandir($dir);

        foreach ($files as $index => $file) {
            if ($file[0] == '.') {
                unset($files[$index]);
                continue;
            }

            switch ($file) {
                case 'Regions.csv':
                    $files['region'] = $dir.'/'.$file;
                    unset($files[$index]);
                    break;
                case 'Countries.csv':
                    $files['country'] = $dir.'/'.$file;
                    unset($files[$index]);
                    break;
                case 'Sites.csv':
                    $files['site'] = $dir.'/'.$file;
                    unset($files[$index]);
                    break;
            }
        }

        return $files;
    }

    /**
     * @param $file
     * @param $output
     * @return array
     */
    private function processRegions($file, $output)
    {
        $fileId  = fopen($file, 'r');
        $regions = [];

        while ($row = fgetcsv($fileId)) {
            if (!empty($row[1])) {
                $region = new Region();
                $region->setName($row[1]);
                $region->setCode($row[0]);
                $region->setWebsite($row[2]);
                $this->entityMgr->persist($region);
                $this->entityMgr->flush();
                $regions[$row[0]] = $region;
            } else {
                $output->writeln("Row[1] is empty!");
            }
        }

        fclose($fileId);

        return $regions;
    }

    /**
     * @param $file
     * @param $regions
     * @return array
     */
    private function processCountries($file, $regions)
    {
        $countries = [];
        $fileId        = fopen($file, 'r');

        while ($row = fgetcsv($fileId)) {
            if (isset($regions[$row[0]]) && !empty($row[2]) && !empty($row[0]) && !empty($row[1])) {
                $country = new Country();
                $country->setName($row[1]);
                $country->setCode($row[2]);
                $country->setActive(true);
                $country->setRegion($regions[$row[0]]);
                $country->setHasReferenceLab(true);
                $country->setHasNationalLab(true);

                $this->entityMgr->persist($country);
                $this->entityMgr->flush();
                $countries[$row[2]] = $country;
            }
        }

        fclose($fileId);

        return $countries;
    }

    /**
     * @param $file
     * @param $countries
     * @return array
     * @throws \Exception
     */
    private function processSites($file, $countries)
    {
        $sites      = [];
        $fileId     = fopen($file, 'r');
        $errorSites = [];
        fgetcsv($fileId);

        while ($row = fgetcsv($fileId)) {
            $site = new Site();
            $site->setCode($row[2]);
            $site->setName($row[3]);
            $site->setibdTier($row[10]);

            $this->surveillanceAndSupport($site, $row, $errorSites);
            $this->setSiteIbdLastAssessment($site, $row, $errorSites);
            $this->setSiteRvLastAssessment($site, $row, $errorSites);

            if ($row[13]) {
                $site->setibdSiteAssessmentScore($row[13]);
            }

            $site->setibvpdRl($row[15]);
            $site->setrvRl($row[16]);
            $site->setibdEqaCode($row[17]);
            $site->setrvEqaCode($row[18]);

            $this->modifyCountry($site, $row, $countries[$row[1]]);

            $this->entityMgr->persist($site);
            $this->entityMgr->flush();
            $sites[$row[2]] = $site;
        }

        fclose($fileId);

        return ['sites'=>$sites,'errors'=>$errorSites];
    }

    /**
     * @param $site
     * @param $row
     * @param $country
     */
    private function modifyCountry($site, $row, $country)
    {
        if ($country instanceof Country) {
            $country->setGaviEligible(new TripleChoice($row[5]));
            $country->setHibVaccineIntro($row[19]);
            $country->setPcvVaccineIntro($row[20]);
            $country->setRvVaccineIntro($row[21]);
            $site->setCountry($country);
        }
    }

    /**
     * @param $site
     * @param $row
     * @param $errorSites
     * @throws \Exception
     */
    private function surveillanceAndSupport($site, $row, &$errorSites)
    {
        try {
            $site->setSurveillanceConducted(new SurveillanceConducted($row[9]));
        } catch (\Exception $except) {
            throw new \Exception("Tried to pass '{$row[9]}' to SurveillanceConducted\n " . $except->getMessage());
        }

        try {
            $site->setibdIntenseSupport(new IntenseSupport($row[11]));
        } catch (\Exception $except) {
            $errorSites[] = "{$row[2]}:{$row[3]} - Has Invalid Intense Support Value {$row[11]}";
        }
    }

    /**
     * @param $site
     * @param $row
     * @param $errorSites
     */
    private function setSiteIbdLastAssessment($site, $row, &$errorSites)
    {
        if ($row[12]) {
            try {
                $site->setibdLastSiteAssessmentDate(new \DateTime($row[12]));
            } catch (\Exception $except) {
                $errorSites[] = "{$row[2]}:{$row[3]} - Has Invalid IBD Last Site Assessment Date '{$row[12]}'";
            }
        }
    }

    /**
     * @param $site
     * @param $row
     * @param $errorSites
     */
    private function setSiteRvLastAssessment($site, $row, &$errorSites)
    {
        if ($row[14]) {
            try {
                $site->setRvLastSiteAssessmentDate(new \DateTime($row[14]));
            } catch (\Exception $except) {
                $errorSites[] = "{$row[2]}:{$row[3]} - Has Invalid RV Last Site Assessment Date '{$row[14]}'";
            }
        }
    }
}
