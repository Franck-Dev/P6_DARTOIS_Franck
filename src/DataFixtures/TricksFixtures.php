<?php

namespace App\DataFixtures;

use App\Entity\Tricks;
use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class TricksFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $Tricks=array('Backside Triple Cork 1440','Double Mc Twist 1260','Method Air','Double Backside Rodeo 1080','Misty Flip','Mute','Front Flip','720 Backside');
        $Description=array('Le rider commence la rotation arrière puis effectue quatre rotations complètes. Ensuite, pour en faire un triple, vous devez ajouter trois rotations désaxées.','Lorem ipsum','Lorem ipsum','Lorem ipsum','Lorem ipsum','Lorem ipsum','Lorem ipsum','Lorem ipsum');
        $CateTricks=array('Rotations','Flips','Grabs','Rotations','Flips','Grabs','Flips','Rotations');
        $Cate=array('Grabs','Rotations','Flips','Slides');

        
        for($i=0;$i<count($Tricks);$i++){
            $Cate= new Category;
            $Cate=$this->getReference($CateTricks[$i]);
            $Trick= new Tricks();
            $Trick  ->setName($Tricks[$i])
                    ->setCategory($Cate)
                    ->setDescription($Description[$i])
                    ->setCreatedAt(new \DateTime());

            $manager->persist($Trick);
            $manager->flush();
            $this->addReference($Tricks[$i],$Trick);          
        }
    }

    public function getDependencies()
    {
        return array(
            CategoryFixtures :: class,
        );
    }
}
