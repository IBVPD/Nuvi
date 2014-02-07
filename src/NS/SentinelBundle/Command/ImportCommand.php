<?php

namespace NS\SentinelBundle\Command;

use \Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use \Symfony\Component\Console\Input\InputInterface;
use \Symfony\Component\Console\Output\OutputInterface;
use \Symfony\Component\Console\Input\InputArgument;

use NS\SentinelBundle\Entity\Region;
use NS\SentinelBundle\Entity\Country;
use NS\SentinelBundle\Entity\Site;
use NS\SentinelBundle\Form\Types\GAVIEligible;
use NS\SentinelBundle\Entity\User;
use NS\SentinelBundle\Entity\ACL;
use NS\SentinelBundle\Form\Types\Role;

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
        $users     = $this->processUsers($regions,$countries,$sites);
        $output->writeln("Added $users Users");
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
                $region = new Region();
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
                $c = new Country();
                $c->setName($row[1]);
                $c->setCode($row[2]);
                $c->setPopulation($row[3]);
                $c->setPopulationUnderFive($row[4]);
                $c->setGaviEligible(new GAVIEligible($row[5]));
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

            $c = new Site();
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

    private function processUsers($regions,$countries, $sites)
    {
        $users     = 1;
        $adminUser = new User();
        $adminUser->setEmail('superadmin@who.int');
        $adminUser->setName('NS Admin User');
        $adminUser->resetSalt();
        $adminUser->setIsAdmin(true);

        $factory = $this->getContainer()->get('security.encoder_factory');
        $encoder = $factory->getEncoder($adminUser);

        $adminUser->setPassword($encoder->encodePassword("GnatAndDaveWho",$adminUser->getSalt()));

        $this->em->persist($adminUser);

        foreach($regions as $obj)
        {
            $user = new User();
            $user->setIsActive(true);
            $user->setEmail($obj->getCode()."@who.int");
            $user->setName($obj->getcode()." User");
            $user->resetSalt();
            $user->setPassword($encoder->encodePassword("1234567-".$obj->getCode(),$user->getSalt()));
            $acl = new ACL();
            $acl->setUser($user);
            $acl->setType(new Role(Role::REGION));
            $acl->setObjectId($obj->getId());
            $this->em->persist($acl);
            $this->em->persist($user);
            ++$users;
        }

        $this->em->flush();
        foreach($countries as $obj)
        {
            $user = new User();
            $user->setIsActive(true);
            $user->setEmail($obj->getCode()."@who.int");
            $user->setName($obj->getcode()." User");
            $user->resetSalt();
            $user->setPassword($encoder->encodePassword("1234567-".$obj->getCode(),$user->getSalt()));
            $acl = new ACL();
            $acl->setUser($user);
            $acl->setType(new Role(Role::COUNTRY));
            $acl->setObjectId($obj->getId());
            $this->em->persist($acl);
            $this->em->persist($user);
            ++$users;
        }

        $this->em->flush();
        foreach($sites as $obj)
        {
            $user = new User();
            $user->setIsActive(true);
            $user->setEmail($obj->getCode()."@who.int");
            $user->setName($obj->getcode()." User");
            $user->resetSalt();
            $user->setPassword($encoder->encodePassword("1234567-".$obj->getCode(),$user->getSalt()));
            $acl = new ACL();
            $acl->setUser($user);
            $acl->setType(new Role(Role::SITE));
            $acl->setObjectId($obj->getId());
            $this->em->persist($acl);
            $this->em->persist($user);

            ++$users;

            $labUser = new User();
            $labUser->setIsActive(true);
            $labUser->setEmail($obj->getCode()."-lab@who.int");
            $labUser->setName($obj->getcode()." Lab User");
            $labUser->resetSalt();
            $labUser->setPassword($encoder->encodePassword("1234567-lab-".$obj->getCode(),$labUser->getSalt()));
            $labacl = new ACL();
            $labacl->setUser($labUser);
            $labacl->setType(new Role(Role::LAB));
            $labacl->setObjectId($obj->getId());
            $this->em->persist($labacl);
            $this->em->persist($labUser);

            ++$users;
            $rrlUser = new User();
            $rrlUser->setIsActive(true);
            $rrlUser->setEmail($obj->getCode()."-rrl@who.int");
            $rrlUser->setName($obj->getcode()." RRL User");
            $rrlUser->resetSalt();
            $rrlUser->setPassword($encoder->encodePassword("1234567-rrl".$obj->getCode(),$rrlUser->getSalt()));
            $acl = new ACL();
            $acl->setUser($rrlUser);
            $acl->setType(new Role(Role::RRL_LAB));
            $acl->setObjectId($obj->getId());
            $this->em->persist($acl);
            $this->em->persist($rrlUser);

            ++$users;
        }

        $this->em->flush();

        return $users;
    }
}
