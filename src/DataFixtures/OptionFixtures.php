<?php

namespace App\DataFixtures ;

use App\Entity\Option;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class OptionFixtures extends Fixture
{
      public function load(ObjectManager $manager){

         $options = new Option('Title of blog', 'blog_about', 'My blog', TextType::class);
         $options = new Option('Text of copyright', 'blog_copyright', 'All rights reserved', TextType::class);
         $options = new Option('Nbr of articles per page', 'blog_articles_limit', 5 , NumberType::class);
         $options = new Option('Everybody can register ', 'users_can_register', true , CheckboxType::class);

      }
}