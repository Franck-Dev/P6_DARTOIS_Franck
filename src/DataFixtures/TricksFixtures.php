<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Tricks;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class TricksFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $Tricks=array('Backside Triple Cork 1440','Double Mc Twist 1260','Method Air','Double Backside Rodeo 1080','Misty Flip','Mute','Front Flip','720 Backside');
        $Description=array('Le rider commence la rotation arrière puis effectue quatre rotations complètes. Ensuite, pour en faire un triple, vous devez ajouter trois rotations désaxées.','Lorem ipsum','Lorem ipsum','Lorem ipsum','Lorem ipsum','Lorem ipsum','Lorem ipsum','Lorem ipsum');
        $CateTricks=array('Rotations','Flips','Grabs','Rotations','Flips','Grabs','Flips','Rotations');
        $Images=array('','','','','','','','');
        $Videos=array('','','','','','','','');
        $Cate=array('Grabs','Rotations','Flips','Slides');
        
        for($i=0;$i<count($Tricks);$i++){
            $Cate= new Category;
            $Cate=$this->getReference($CateTricks[$i]);
            $Trick= new Tricks();
            $Trick  ->setName($Tricks[$i])
                    ->setCategory($Cate)
                    ->setDescription($Description[$i])
                    ->setPictures($Images[$i])
                    ->setVideo($Videos[$i]);

            $manager->persist($Trick);
            $manager->flush();          
        }
    }

    public function getDependencies()
    {
        return array(
            Category::class,
        );
    }
}
