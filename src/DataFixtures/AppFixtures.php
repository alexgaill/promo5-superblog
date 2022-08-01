<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Category;
use App\Entity\Post;
use DateTime;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // On charge le Générateur de fake data
        $faker = Factory::create('fr_FR');

        // On génère 10 catégories
        for ($i=0; $i < 10; $i++) { 
            $category = (new Category)->setTitle($faker->words(3, true));

            $manager->persist($category);

            // On génère 10 articles rattachés à la catégorie générée au-dessus
            for ($j=0; $j < 10; $j++) { 
                $post = (new Post)
                    ->setTitle($faker->words(3, true))
                    ->setContent($faker->paragraphs(3, true))
                    ->setCreatedAt($faker->dateTime())
                    ->setCategory($category)
                    ->setPicture($faker->imageUrl(300, 200, 'animals', true))
                    ;
                $manager->persist($post);
            }
        }
        $manager->flush();
    }
}
