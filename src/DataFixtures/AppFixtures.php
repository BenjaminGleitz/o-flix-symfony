<?php

namespace App\DataFixtures;

use App\Entity\Character;
use App\Entity\Season;
use App\Entity\TvShow;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create();
        $faker->addProvider(new \Xylis\FakerCinema\Provider\Character($faker));
        $faker->addProvider(new \Xylis\FakerCinema\Provider\TvShow($faker));

        // Création de personnages
        for ($index = 0; $index < 20; $index++) {
            $gender = mt_rand(0, 1) ? 'male' : 'female';
            $fullNameArray = explode(" ", $faker->character($gender));

            // On créé un personnage vide
            $character = new Character();
            $character->setFirstname($fullNameArray[0]);
            $character->setLastname($fullNameArray[1] ?? ' Doe' . $index);
            $character->setGender($gender == 'male' ? 'Homme' : 'Femme');

            // On met le personnage en liste d'attente
            // pour une sauvegarde au moment du "flush"
            $manager->persist($character);
        }


        for ($i = 0; $i < 20; $i++) {
            // On créé une série vide
            $tvShow = new TvShow();

            // On ajoute des informations
            $tvShow->setTitle($faker->tvShow);
            $tvShow->setSynopsis($faker->overview);
            $tvShow->setNbLikes(150000);

            // On créé de nouvelles saisons que l'on associe à tvShow
            $seasonOne = new Season();
            $seasonOne->setSeasonNumber(1);
            $tvShow->addSeason($seasonOne);

            $seasonTwo = new Season();
            $seasonTwo->setSeasonNumber(2);
            $tvShow->addSeason($seasonTwo);

            $seasonThree = new Season();
            $seasonThree->setSeasonNumber(3);
            $tvShow->addSeason($seasonThree);

            $manager->persist($tvShow);
            $manager->persist($seasonOne);
            $manager->persist($seasonTwo);
            $manager->persist($seasonThree);
        }

        // On sauvegarde les séries/saisons en BDD
        $manager->flush();
    }
}
