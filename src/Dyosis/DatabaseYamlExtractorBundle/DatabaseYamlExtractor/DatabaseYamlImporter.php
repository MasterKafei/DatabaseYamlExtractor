<?php

namespace Dyosis\DatabaseYamlExtractorBundle\DatabaseYamlExtractor;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Yaml\Yaml;

class DatabaseYamlImporter
{
    const FILE_EXTENSION = '.yml';

    /**
     * @var EntityManagerInterface
     */
    private $registry;

    /**
     * @var array
     */
    private $config;

    /**
     * @var array
     */
    private $instances = [];

    /**
     * Database Yaml Extractor Constructor.
     *
     * @param Registry $registry
     */
    public function __construct(Registry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * Set config
     *
     * @param array $config
     * @return DatabaseYamlImporter
     */
    public function setConfig($config)
    {
        $this->config = $config;

        return $this;
    }

    /**
     * Load.
     *
     * @param string $file
     * @param string|null $manager
     * @throws
     */
    public function load($file, $manager = null)
    {
        $position = strpos($file, self::FILE_EXTENSION);
        if (!$position || (strlen($file) !== $position + (strlen(self::FILE_EXTENSION)))) {
            $file .= self::FILE_EXTENSION;
        }

        $content = Yaml::parse(file_get_contents($this->config['output_directory'] . $file), Yaml::PARSE_DATETIME);

        $manager = $this->registry->getEntityManager($manager);
        $purger = new ORMPurger($manager);
        $purger->setPurgeMode(ORMPurger::PURGE_MODE_DELETE);
        $purger->purge();
        $this->setTableContents($content, $manager);
    }

    /**
     * Set table contents.
     *
     * @param array $content
     * @param ObjectManager $manager
     * @throws \ReflectionException
     */
    public function setTableContents($content, $manager)
    {
        foreach ($content as $fqcn => $entitiesConfig) {
            $reflection = new \ReflectionClass($fqcn);
            $metadata = $manager->getClassMetadata($fqcn);
            foreach ($entitiesConfig as $entityConfig) {
                $fields = $entityConfig['fields'];
                $associations = $entityConfig['associations'];
                $instance = $reflection->newInstanceWithoutConstructor();
                foreach ($fields as $field => $value) {
                    $property = $reflection->getProperty($field);
                    $property->setAccessible(true);
                    $property->setValue($instance, $value);
                }

                foreach ($associations as $field => $id) {
                    if (!$metadata->isAssociationInverseSide($field)) {
                        $property = $reflection->getProperty($field);
                        $property->setAccessible(true);
                        $property->setValue($instance, $this->createAssociation($fqcn, $field, $id, $content, $manager));
                    }
                }
                $values = $metadata->getIdentifierValues($instance);
                $this->instances[$fqcn][$values[array_key_first($values)]] = $instance;
                $manager->persist($instance);
            }
        }

        $manager->flush();
    }

    /**
     * create association.
     *
     * @param $fqcn
     * @param $associationField
     * @param $id
     * @param $content
     * @param ObjectManager $manager
     * @return array|object|void|null
     * @throws \ReflectionException
     */
    private function createAssociation($fqcn, $associationField, $id, $content, $manager)
    {
        if (is_iterable($id)) {
            $entities = [];
            foreach ($id as $value) {
                $entities[] = $this->createAssociation($fqcn, $associationField, $value, $content, $manager);
            }
            return $entities;
        }

        $metadata = $manager->getClassMetadata($fqcn);

        $targetClass = $metadata->getAssociationTargetClass($associationField);

        if (isset($this->instances[$targetClass][$id])) {
            return $this->instances[$targetClass][$id];
        }

        $entities = $content[$targetClass];
        $reflection = new \ReflectionClass($targetClass);

        $metadata = $manager->getClassMetadata($targetClass);
        foreach ($entities as $entity) {
            foreach ($entity['fields'] as $field => $valueField) {
                if ($metadata->isIdentifier($field)) {
                    if ($entity['fields'][$field] === $id) {
                        $instance = $reflection->newInstanceWithoutConstructor();
                        foreach ($entity['fields'] as $creationField => $value) {
                            $property = $reflection->getProperty($creationField);
                            $property->setAccessible(true);
                            $property->setValue($instance, $value);
                        }

                        foreach ($entity['associations'] as $association => $associationId) {
                            if (!$metadata->isAssociationInverseSide($association)) {
                                if($metadata->isCollectionValuedAssociation($association)) {
                                    dump($association, "Collection");
                                    if (is_iterable($associationId)) {
                                        $entities = [];
                                        foreach ($associationId as $value) {
                                            $entities[] = $this->createAssociation($fqcn, $associationField, $value, $content, $manager);
                                        }
                                    } else {
                                        $entities = [$this->createAssociation($fqcn, $associationField, $value, $content, $manager)];
                                    }
                                } else {
                                    dump($association, "Single");
                                    $entities = $this->createAssociation($targetClass, $association, $associationId, $content, $manager);
                                }
                                $property = $reflection->getProperty($association);
                                $property->setAccessible(true);
                                $property->setValue($instance, $entities);
                            }
                        }
                        $this->instances[$targetClass][$id] = $instance;
                        return $instance;
                    }
                }
            }
        }

        return null;
    }
}