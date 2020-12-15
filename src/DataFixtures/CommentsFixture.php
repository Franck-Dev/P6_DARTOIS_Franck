<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Tricks;
use App\Entity\Comments;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class CommentsFixture extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $Tricks=array('Backside Triple Cork 1440','Double Mc Twist 1260','Method Air','Double Backside Rodeo 1080','Misty Flip','Mute','Front Flip','720 Backside');
        $Comments=array('Le rider est une machine a envoyer des neuronnes à l\'asile.','AirLines Hospit','La tête en bas','Des tricks de fou dans des décors de fou','WWWOUUUUAAAHAHHH','T\'as de la peuf plein les narines mon gars','Suivez le prof','J\'y vais mais la neige est trop molle pour moi!!!');
        $Users=array('Furibar','Terrible','Fonzy');
        
        for($i=0;$i<count($Tricks);$i++){
            $Trick= new Tricks();
            $Trick=$this->getReference($Tricks[$i]);
            $User= new User();
            $User=$this->getReference($Users[array_rand($Users,1)]);
            for($j=1;$j<random_int(1,6);$j++){
                $Comment= new Comments;
                $Comment  ->setContent($Comments[array_rand($Comments,1)])
                        ->setTrick($Trick)
                        ->setUser($User)
                        ->setCreatedAt(new \datetime());

                $manager->persist($Comment);
                $manager->flush();
            }          
        }
    }

    public function getDependencies()
    {
        return array(
            TricksFixtures :: class,
        );
    }
}
