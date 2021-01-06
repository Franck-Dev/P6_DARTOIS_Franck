<?php

namespace App\DataFixtures;

use App\Entity\Medias;
use App\Entity\Tricks;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class MediasFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $Tricks=array('Backside Triple Cork 1440','Double Mc Twist 1260','Method Air','Double Backside Rodeo 1080','Misty Flip','Mute','Front Flip','720 Backside');
        $Medias=array('Main_SnowTricks.jpg','MainPicture.jpg','Picture3.jpg','https://youtu.be/KSdx9gNmqlc','https://youtu.be/VVljQ9JccsY','https://youtu.be/q-RAJnV1dfg');
        $Type=array('Picture','Picture','Picture','Video','Video','Video');


        for($i=0;$i<count($Tricks);$i++){
            $Trick= new Tricks;
            $Trick=$this->getReference($Tricks[$i]);
            for($j=0;$j<count($Medias);$j++){
                $Media= new Medias();
                $Media  ->setName($Medias[$j])
                        ->setType($Type[$j])
                        ->setLikes(random_int(1,10))
                        ->setTricks($Trick);

                $manager->persist($Media);
                $manager->flush();
            }
        }
    }

    public function getDependencies():array
    {
        return [
            TricksFixtures :: class,
        ];
   }
}
