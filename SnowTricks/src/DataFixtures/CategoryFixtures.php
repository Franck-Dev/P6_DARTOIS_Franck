<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $Cate=array('Grabs','Rotations','Flips','Slides');
        $Description=array('Un grab consiste à attraper la planche avec la main pendant le saut. Le verbe anglais to grab signifie « attraper. »
        Il existe plusieurs types de grabs selon la position de la saisie et la main choisie pour l\'effectuer, avec des difficultés variables',
        'On désigne par le mot « rotation » uniquement des rotations horizontales ; les rotations verticales sont des flips. Le principe est d\'effectuer une rotation horizontale pendant le saut, puis d\'attérir en position switch ou normal. La nomenclature se base sur le nombre de degrés de rotation effectués',
        'Un flip est une rotation verticale. On distingue les front flips, rotations en avant, et les back flips, rotations en arrière.
        Il est possible de faire plusieurs flips à la suite, et d\'ajouter un grab à la rotation.','Un slide consiste à glisser sur une barre de slide. Le slide se fait soit avec la planche dans l\'axe de la barre, soit perpendiculaire, soit plus ou moins désaxé.
        On peut slider avec la planche centrée par rapport à la barre (celle-ci se situe approximativement au-dessous des pieds du rideur), mais aussi en n\'est-à-dire l\'avant de la planche sur la barre, ou en tail slide, l\'arrière de la planche sur la barre.');

        for($i=0;$i<count($Cate);$i++){
            $Category= new Category();
                $Category  ->setName($Cate[$i])
                        ->setDescription($Description[$i]);

                $manager->persist($Category);
                $manager->flush();
                $this->addReference($Cate[$i],$Category);         
        }
        
    }
}
