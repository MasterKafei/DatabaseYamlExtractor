<?php

namespace AppBundle\DataFixtures;

use AppBundle\Entity\Author;
use AppBundle\Entity\Many;
use AppBundle\Entity\Mark;
use AppBundle\Entity\Post;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;

class DataPostFixtures extends AbstractFixture
{
	public function load(ObjectManager $manager)
	{
	    $many1 = new Many();
	    $many2 = new Many();
	    $many3 = new Many();
	    $many4 = new Many();
	    $many5 = new Many();
	    $manager->persist($many1);
	    $manager->persist($many2);
	    $manager->persist($many3);
	    $manager->persist($many4);
	    $manager->persist($many5);

		$author = new Author();
        $author
			->setFirstName("Jean")
			->setLastName("Marius")
			->setAge(23)
            ->setManies([$many1, $many2, $many3])
		;

        $many1->addAuthor($author);
        $many2->addAuthor($author);
        $many3->addAuthor($author);

        $post = new Post();
        $post
            ->setAuthor($author)
            ->setName('Oui')
            ->setCreationDate(new \DateTime())
            ->setLastUpdateDate((new \DateTime())->add(new \DateInterval('PT1H')))
            ->setDescription('Super description')
        ;

        $post = new Post();
        $post
            ->setAuthor($author)
            ->setName('Alors')
            ->setCreationDate(new \DateTime())
            ->setLastUpdateDate((new \DateTime())->add(new \DateInterval('PT2H')))
            ->setDescription('Supeeeer description')
        ;

        $manager->persist($author);

        $author = new Author();
        $author
            ->setFirstName('Paul')
            ->setLastName('Marius')
            ->setAge(21)
            ->setManies([$many1, $many3, $many4, $many5])
        ;

        $many1->addAuthor($author);
        $many3->addAuthor($author);
        $many4->addAuthor($author);
        $many5->addAuthor($author);

        $manager->persist($author);

		$post = new Post();
		$post
			->setName("Super")
			->setDescription("Woaw")
			->setCreationDate((new \DateTime())->sub(new \DateInterval('P1D')))
			->setLastUpdateDate(new \DateTime())
			->setAuthor($author)
		;

		$manager->persist($post);

		$post = new Post();
		$post
			->setName("Génial")
			->setDescription("Yes")
			->setCreationDate((new \DateTime())->sub(new \DateInterval('P2D')))
			->setLastUpdateDate(new \DateTime())
			->setAuthor($author)
		;

		$manager->persist($post);

		$mark = new Mark();
		$mark
            ->setValue(20)
            ->setAuthor($author)
        ;

		$manager->persist($mark);
		$manager->flush();
	}
}
