<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixture extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $Users=array('Furibar','Terrible','Fonzy');
        $MdP=array('Furibar2008','Terrible2008','Fonzy2008');
        $Mail=array('Furibar@2008.com','Terrible@2008.com','Fonzy@2008.com');
        $Role=array(['ROLE_ADMIN'],['ROLE_USER'],['ROLE_USER']);
        
        for($i=0;$i<count($Users);$i++){
            $user= new User;
            $user  ->setPseudo($Users[$i])
                   ->setPassword($this->passwordEncoder->encodePassword(
                             $user,
                             $MdP[$i]
                         ))
                    ->setEmail($Mail[$i])
                    ->setRoles($Role[$i])
                    ->setIsVerified(1);

            $manager->persist($user);
            $manager->flush();
            $this->addReference($Users[$i],$user);          
        }
    }
}
