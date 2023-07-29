<?php

namespace App\DataFixtures;

use DateTime;
use Faker\Factory;
use App\Entity\Album;
use DateTimeImmutable;
use App\Entity\Artiste;
use App\Entity\Morceau;
use Cocur\Slugify\Slugify;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ZikFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        // Tableau d'images unsplash
        $images = [];
        for ($i = 0; $i < 50; $i++) {
            // UNSPLASH API INTEGRATION 
            $apiKey = 'lHI2E7pIyVDoVnz6WkrTBpHBG2bkn9zkTBpE6dc-LdQ';
            $url = 'https://api.unsplash.com/photos/random/?client_id=' . $apiKey.'&query=music';

            $response = file_get_contents($url);
            // Convertir la réponse JSON en tableau associatif
            $imageData = json_decode($response, true);

            // Récupérer l'URL de l'image aléatoire
            $imageUrl = $imageData['urls']['raw'];
            $images[] = $imageUrl;
        }

        // Création des artistes
        $artistes = [];
        $type = ['groupe', 'solo','dj','duo', 'orchestre'];
        $typeMusik = ['rock', 'pop', 'rap', 'jazz', 'blues', 'classique', 'reggae', 'electro', 'metal', 'country', 'variété', 'folk', 'funk', 'soul', 'disco', 'techno', 'hip-hop', 'rnb', 'dance', 'latino', 'punk', 'reggaeton', 'house', 'chanson française', 'hard rock', 'indie', 'alternatif', 'new wave', 'chill', 'lounge', 'ambiance', 'relaxation', 'enfants'];
        $genre = ["men", "women"];
        $gender = ["male", "female"];

        for ($i = 0; $i < 10; $i++) {
            $startDate = strtotime('1950-01-01');
            $endDate = strtotime('2023-06-31');
            $randomTimestamp = random_int($startDate, $endDate);
            $randomDateTime = new DateTimeImmutable("@$randomTimestamp");

            $slugify = new Slugify();

            $artiste = new Artiste();

            $contactGender = mt_rand(0, 1);
            if ($contactGender == 0) {
                $typeGender = "men";
            } else {
                $typeGender = "women";
            }

            $artiste->setNom($faker->name($gender[$contactGender]))
                ->setDescription($faker->text(255))
                ->setSiteWeb('www.' . $slugify->slugify($artiste->getNom()) . '.com')
                ->setSlug($slugify->slugify($artiste->getNom()))
                ->setPhoto("https://randomuser.me/api/portraits/" . $typeGender . "/" . mt_rand(1, 99) . ".jpg")
                ->setType($faker->randomElement($type))
                ->setGenreMusical($faker->randomElement($typeMusik))
                ->setCreatedAt($randomDateTime);
            $manager->persist($artiste);
            $artistes[] = $artiste;
        }

        // Création des albums
        $albums = [];
        for ($i = 0; $i < 50; $i++) {
            $startDate = strtotime('1950-01-01');
            $endDate = strtotime('2023-06-31');
            $randomTimestamp = random_int($startDate, $endDate);
            $randomDateTime = new DateTimeImmutable("@$randomTimestamp");

            $slugify = new Slugify();

            $album = new Album();

            $randomArtiste = $faker->randomElement($artistes);

            $randomWords = $faker->words(rand(1, 5));
            $titre = implode(' ', $randomWords);

            $album->setTitre($titre)
                ->setDateSortie($randomDateTime)
                ->setImage($faker->randomElement($images))
                ->setArtiste($randomArtiste)
                ->setCreatedAt($randomDateTime);
            $manager->persist($album);

            // Génération d'un nombre aléatoire de morceaux par album (entre 5 et 12)
            $numberOfMorceaux = rand(5, 12);
            for ($j = 0; $j < $numberOfMorceaux; $j++) {
                $randomHours = rand(2, 8);
                $randomMinutes = rand(0, 59);

                // Vérifier si l'heure aléatoire est égale à 8 et limiter les minutes à 45 dans ce cas
                if ($randomHours === 8) {
                    $randomMinutes = rand(0, 45);
                }

                $randomTime = sprintf('%02d:%02d', $randomHours, $randomMinutes);

                $morceau = new Morceau();

                $randomWords = $faker->words(rand(1, 5));
                $titreMorceau = implode(' ', $randomWords);

                $morceau->setTitre($titreMorceau)
                    ->setDuree($randomTime)
                    ->setAlbum($album);

                $manager->persist($morceau);
            }

            $albums[] = $album;
        }

        $manager->flush();

        $manager->flush();
    }
}
