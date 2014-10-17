<?php

namespace NS\SentinelBundle\Command;

use \Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use \Symfony\Component\Console\Input\InputInterface;
use \Symfony\Component\Console\Output\OutputInterface;
use \Symfony\Component\Console\Input\InputArgument;
use \Symfony\Component\Console\Input\InputOption;

use NS\SentinelBundle\Entity\Region;
use NS\SentinelBundle\Entity\Country;
use NS\SentinelBundle\Entity\Site;
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
    private $entityMgr;
    
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
            ->addOption('with-users', null, InputOption::VALUE_OPTIONAL, true)
        ; 
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dir = $input->getArgument('directory');
        $files = scandir($dir);
        $this->entityMgr = $this->getContainer()->get('doctrine.orm.entity_manager');
        
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

        if(isset($files['country']))
        {
            $countries = $this->processCountries($files['country'], $regions);
            $output->writeln("Added ".count($countries)." Countries");
        }

        if(isset($files['site']))
        {
            $sites     = $this->processSites($files['site'], $countries);
            $output->writeln("Added ".count($sites)." Sites");
        }

        if($input->hasOption('with-users'))
        {
            $users     = $this->processUsers($regions,$countries,$sites);
            $output->writeln("Added $users Users");
        }
    }
    
    private function processRegions($file,$output)
    {
        $fileId = fopen($file,'r');
        $regions = array();
        while(true)
        {
            $row = fgetcsv($fileId);

            if($row== null)
                break;

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
        $fileId    = fopen($file,'r');
        
        while(true)
        {
            $row = fgetcsv($fileId);

            if($row== null)
                break;

            if(isset($regions[$row[0]]) && !empty($row[2]) && !empty($row[0]) && !empty($row[1]))
            {
                $cnt = new Country();
                $cnt->setName($row[1]);
                $cnt->setCode($row[2]);
                $cnt->setPopulation($row[3]);
                $cnt->setPopulationUnderFive($row[4]);
                $cnt->setIsActive(true);
                $cnt->setRegion($regions[$row[0]]);
                $cnt->setHasReferenceLab(true);
                $cnt->setHasNationalLab(true);

                $this->entityMgr->persist($cnt);
                $this->entityMgr->flush();
                $countries[$row[2]] = $cnt;
            }
        }
        
        fclose($fileId);
        
        return $countries;
    }
    
    private function processSites($file,$countries)
    {
        $sites  = array();
        $fileId = fopen($file,'r');
        $x      = 1;
        while(true)
        {
            $row = fgetcsv($fileId);

            if($row== null)
                break;

            $site = new Site();
            $site->setName($row[2]);
            $site->setCode("{$row[1]}$x");
            $site->setRvYearIntro($row[3]);
            $site->setIbdYearIntro($row[4]);
            $site->setStreet($row[5]);
            $site->setCity($row[6]);
            $site->setNumberOfBeds($row[7]);
            $site->setWebsite($row[8]);
            $site->setCountry($countries[$row[1]]);

            $this->entityMgr->persist($site);
            $this->entityMgr->flush();
            $sites[$row[2]] = $site;
            $x++;
        }
        
        fclose($fileId);
        
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
        $adminUser->setIsActive(true);

        $factory = $this->getContainer()->get('security.encoder_factory');
        $encoder = $factory->getEncoder($adminUser);

        $adminUser->setPassword($encoder->encodePassword("GnatAndDaveWho",$adminUser->getSalt()));

        $this->entityMgr->persist($adminUser);

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
            $this->entityMgr->persist($acl);
            $this->entityMgr->persist($user);
            ++$users;
        }

        $this->entityMgr->flush();
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
            $this->entityMgr->persist($acl);
            $this->entityMgr->persist($user);
            ++$users;
        }

        $this->entityMgr->flush();
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
            $this->entityMgr->persist($acl);
            $this->entityMgr->persist($user);

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
            $this->entityMgr->persist($labacl);
            $this->entityMgr->persist($labUser);

            ++$users;
            $rrlUser = new User();
            $rrlUser->setIsActive(true);
            $rrlUser->setEmail($obj->getCode()."-rrl@who.int");
            $rrlUser->setName($obj->getcode()." RRL User");
            $rrlUser->resetSalt();
            $rrlUser->setPassword($encoder->encodePassword("1234567-rrl-".$obj->getCode(),$rrlUser->getSalt()));
            $acl = new ACL();
            $acl->setUser($rrlUser);
            $acl->setType(new Role(Role::LAB));
            $acl->setObjectId($obj->getId());
            $this->entityMgr->persist($acl);
            $this->entityMgr->persist($rrlUser);

            ++$users;
            $nlUser = new User();
            $nlUser->setIsActive(true);
            $nlUser->setEmail($obj->getCode()."-nl@who.int");
            $nlUser->setName($obj->getcode()." NL User");
            $nlUser->resetSalt();
            $nlUser->setPassword($encoder->encodePassword("1234567-nl-".$obj->getCode(),$nlUser->getSalt()));
            $acl = new ACL();
            $acl->setUser($nlUser);
            $acl->setType(new Role(Role::LAB));
            $acl->setObjectId($obj->getId());
            $this->entityMgr->persist($acl);
            $this->entityMgr->persist($nlUser);

            ++$users;
        }

        $this->entityMgr->flush();

        return $users;
    }
}
