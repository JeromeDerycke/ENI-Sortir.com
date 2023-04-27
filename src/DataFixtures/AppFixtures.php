<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Participant;
use App\Entity\Sortie;
use App\Entity\Ville;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        //Campus
        $campus1 = new Campus();
        $campus1->setNom('Lille');
        $manager->persist($campus1);

        $campus2 = new Campus();
        $campus2->setNom('Nimes');
        $manager->persist($campus2);

        $campus3 = new Campus();
        $campus3->setNom('Paris');
        $manager->persist($campus3);


        //Participant
        $participant1 = new Participant();
        $participant1->setNom('Doe');
        $participant1->setPrenom('John');
        $participant1->setPseudo('DoeDoe');
        $participant1->setPhotoFileName('67fa9e2c47df.jpg');
        $participant1->setEmail('johndoe@campus.com');
        $motDePasse = $this->hasher->hashPassword($participant1, 'c@mpusDoe');
        $participant1->setPassword($motDePasse);
        $participant1->setTelephone('0102030405');
        $participant1->setCampus($campus1);
        $participant1->setRoles(['ROLE_ADMIN']);
        $participant1->setActif('true');
        $manager->persist($participant1);

        $participant2 = new Participant();
        $participant2->setNom('Parker');
        $participant2->setPrenom('Peter');
        $participant2->setPseudo('PeterPan');
        $participant2->setPhotoFileName('979a316bd3b7.jpg');
        $participant2->setEmail('peterparker@campus.com');
        $motDePasse = $this->hasher->hashPassword($participant2, 'c@mpusParker');
        $participant2->setPassword($motDePasse);
        $participant2->setTelephone('0102030405');
        $participant2->setCampus($campus2);
        $participant2->setRoles(['ROLE_USER']);
        $participant2->setActif('true');
        $manager->persist($participant2);

        $participant3 = new Participant();
        $participant3->setNom('Stark');
        $participant3->setPrenom('Tony');
        $participant3->setPseudo('Stark');
        $participant3->setPhotoFileName('adb7df72e1f2.webp');
        $participant3->setEmail('tonystark@campus.com');
        $motDePasse = $this->hasher->hashPassword($participant3, 'c@mpusStark');
        $participant3->setPassword($motDePasse);
        $participant3->setTelephone('0102030405');
        $participant3->setCampus($campus2);
        $participant3->setRoles(['ROLE_USER']);
        $participant3->setActif('true');
        $manager->persist($participant3);

        $participant4 = new Participant();
        $participant4->setNom('Lee');
        $participant4->setPrenom('Stan');
        $participant4->setEmail('stanlee@campus.com');
        $participant4->setPseudo('Stanilou');
        $participant4->setPhotoFileName('a28fd0219537.webp');
        $motDePasse = $this->hasher->hashPassword($participant4, 'c@mpusLee');
        $participant4->setPassword($motDePasse);
        $participant4->setTelephone('0102030405');
        $participant4->setCampus($campus3);
        $participant4->setRoles(['ROLE_USER']);
        $participant4->setActif('true');
        $manager->persist($participant4);


        //Ville
        $ville1 = new Ville();
        $ville1->setNom("Lille");
        $ville1->setCodePostal("59000");
        $manager->persist($ville1);

        $ville2 = new Ville();
        $ville2->setNom("Nimes");
        $ville2->setCodePostal("30000");
        $manager->persist($ville2);

        $ville3 = new Ville();
        $ville3->setNom("Paris");
        $ville3->setCodePostal("75000");
        $manager->persist($ville3);

        //Lieu
        $lieu1 = new Lieu();
        $lieu1->setVille($ville1);
        $lieu1->setNom("Cinema CGR");
        $lieu1->setRue("rue du Cinema");
        $lieu1->setLatitude("00");
        $lieu1->setLongitude("00");
        $manager->persist($lieu1);

        $lieu2 = new Lieu();
        $lieu2->setVille($ville2);
        $lieu2->setNom("Closed Escape Game");
        $lieu2->setRue("boulevard Gambetta");
        $lieu2->setLatitude("00");
        $lieu2->setLongitude("00");
        $manager->persist($lieu2);

        $lieu3 = new Lieu();
        $lieu3->setVille($ville3);
        $lieu3->setNom("Champs Elysée");
        $lieu3->setRue("Avenue des Champs Elysée");
        $lieu3->setLatitude("00");
        $lieu3->setLongitude("00");
        $manager->persist($lieu3);

        //Etat
        $etat1 = new Etat();
        $etat1->setLibelle("ouverte");
        $manager->persist($etat1);

        $etat2 = new Etat();
        $etat2->setLibelle("en cours");
        $manager->persist($etat2);

        $etat3 = new Etat();
        $etat3->setLibelle("cloturée");
        $manager->persist($etat3);

        //Sortie
        $sortie1 = new Sortie();
        $sortie1->setNom("cinéma");
        $sortie1->setDateHeureDebut(new \DateTime('12-04-2023 21:30'));
        $sortie1->setDuree("135");
        $sortie1->setDateLimiteInscription(new \DateTime('8-04-2023'));
        $sortie1->setNbInscriptionsMax("15");
        $sortie1->setInfosSortie("Film Donjon & Dragons");

        $sortie1->setLieu($lieu1);
        $sortie1->setCampus($campus1);
        $sortie1->setOrganisateur($participant1);
        $sortie1->setEtat($etat1);
        $manager->persist($sortie1);

        ////*****////

        $sortie2 = new Sortie();
        $sortie2->setNom("escape-game");
        $sortie2->setDateHeureDebut(new \DateTime('21-04-2023 9:00'));
        $sortie2->setDuree("120");
        $sortie2->setDateLimiteInscription(new \DateTime('19-04-2023'));
        $sortie2->setNbInscriptionsMax("10");
        $sortie2->setInfosSortie("amusement et renforcement de l'entraide");

        $sortie2->setLieu($lieu2);
        $sortie2->setCampus($campus2);
        $sortie2->setOrganisateur($participant3);
        $sortie2->setEtat($etat1);
        $manager->persist($sortie2);

        ////*****////

        $sortie3 = new Sortie();
        $sortie3->setNom("Shopping");
        $sortie3->setDateHeureDebut(new \DateTime('1-05-2023 14:00'));
        $sortie3->setDuree("240");
        $sortie3->setDateLimiteInscription(new \DateTime('25-04-2023'));
        $sortie3->setNbInscriptionsMax("15");
        $sortie3->setInfosSortie("Shopping sur les Champs Elysée");

        $sortie3->setLieu($lieu3);
        $sortie3->setCampus($campus3);
        $sortie3->setOrganisateur($participant4);
        $sortie3->setEtat($etat1);
        $manager->persist($sortie3);

        $sortie4 = new Sortie();
        $sortie4->setNom("petit-dejeuner");
        $sortie4->setDateHeureDebut(new \DateTime('21-07-2023 9:00'));
        $sortie4->setDuree("130");
        $sortie4->setDateLimiteInscription(new \DateTime('19-06-2023'));
        $sortie4->setNbInscriptionsMax("35");
        $sortie4->setInfosSortie("amusement et régalade");

        $sortie4->setLieu($lieu3);
        $sortie4->setCampus($campus2);
        $sortie4->setOrganisateur($participant1);
        $sortie4->setEtat($etat1);
        $manager->persist($sortie4);


        //add participants
        $sortie1->addParticipant($participant2);
        $sortie2->addParticipant($participant4);
        $sortie3->addParticipant($participant1);
        $sortie4->addParticipant($participant3);
        $manager->persist($sortie1);
        $manager->persist($sortie2);
        $manager->persist($sortie3);
        $manager->persist($sortie4);

        $manager->flush();
    }
}