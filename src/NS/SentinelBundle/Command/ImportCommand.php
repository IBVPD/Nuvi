<?php

namespace NS\SentinelBundle\Command;

use \Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use \Symfony\Component\Console\Input\InputInterface;
use \Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

/**
 * Description of ImportCommand
 *
 * @author gnat
 */
class ImportCommand extends ContainerAwareCommand
{
    private $em;
    
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
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dir = $input->getArgument('directory');
        $files = scandir($dir);
        $this->em = $this->getContainer()->get('doctrine.orm.entity_manager');
        
        foreach($files as $x => $file)
        {
            if($file[0] == '.')
                unset($files[$x]);
            
            switch ($file)
            {
                case 'Countries.csv':
                    $files['country'] = $dir.'/'.$file;
                    unset($files[$x]);
                    break;
                case 'Regions.csv':
                    $files['region'] = $dir.'/'.$file;
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
        $countries = $this->processCountries($files['country'], $regions);
        $output->writeln("Added ".count($countries)." Countries");
        $sites     = $this->processSites($files['site'], $countries);
        $output->writeln("Added ".count($sites)." Sites");
    }
    
    private function processRegions($file,$output)
    {
        $fd = fopen($file,'r');
        $regions = array();
        while(true)
        {
            $row = fgetcsv($fd);

            if($row== null)
                break;

            if(!empty($row[1]))
            {
                $region = new \NS\SentinelBundle\Entity\Region();
                $region->setName($row[1]);
                $region->setCode($row[0]);
                $region->setWebsite($row[2]);
                $this->em->persist($region);
                $this->em->flush();
                $regions[$row[0]] = $region;
            }
            else
                $output->writeln("Row[1] is empty!");
        }
        
        fclose($fd);
        
        return $regions;
    }
    
    private function processCountries($file,$regions)
    {
        $countries = array();
        $fd        = fopen($file,'r');
        
        while(true)
        {
            $row = fgetcsv($fd);

            if($row== null)
                break;

            if(isset($regions[$row[0]]) && !empty($row[2]) && !empty($row[0]) && !empty($row[1]))
            {
                $c = new \NS\SentinelBundle\Entity\Country();
                $c->setName($row[1]);
                $c->setCode($row[2]);
                $c->setPopulation($row[3]);
                $c->setPopulationUnderFive($row[4]);
                $c->setGaviEligible(new \NS\SentinelBundle\Form\Types\GAVIEligible($row[5]));
                $c->setIsActive(true);
                $c->setRegion($regions[$row[0]]);

                $this->em->persist($c);
                $this->em->flush();
                $countries[$row[2]] = $c;
            }
        }
        
        fclose($fd);
        
        return $countries;
    }
    
    private function processSites($file,$countries)
    {
        $sites = array();
        $fd    = fopen($file,'r');
        $x     = 1;
        while(true)
        {
            $row = fgetcsv($fd);

            if($row== null)
                break;

            $c = new \NS\SentinelBundle\Entity\Site();
            $c->setName($row[2]);
            $c->setCode("{$row[1]}$x");
            $c->setRvYearIntro($row[3]);
            $c->setIbdYearIntro($row[4]);
            $c->setStreet($row[5]);
            $c->setCity($row[6]);
            $c->setNumberOfBeds($row[7]);
            $c->setWebsite($row[8]);
            $c->setCountry($countries[$row[1]]);

            $this->em->persist($c);
            $this->em->flush();
            $sites[$row[2]] = $c;
            $x++;
        }
        
        fclose($fd);
        
        return $sites;
    }
    
}
