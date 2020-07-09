<?php

namespace Dyosis\DatabaseYamlExtractorBundle\DatabaseYamlExtractor;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Yaml\Yaml;

/**
 * Class DatabaseYamlExtractor
 * @package Dyosis\DatabaseYamlExtractorBundle\DatabaseYamlExtractor
 * @author Jean MARIUS <jean.marius@dyosis.com>
 */
class DatabaseYamlExtractor
{
	/**
	 * @var EntityManagerInterface
	 */
	private $registry;

	/**
	 * @var array
	 */
	private $config;

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
	 * @return DatabaseYamlExtractor
	 */
	public function setConfig($config)
	{
		$this->config = $config;

		return $this;
	}

	/**
	 * Get tables.
	 *
	 * @param string|null $managerName
	 * @return array
	 * @throws \ReflectionException
	 */
	public function getTablesContent($managerName = null)
	{
		$manager = $this->registry->getManager($managerName);

		$metadata = $manager->getMetadataFactory()->getAllMetadata();
		$tables = [];
		foreach ($metadata as $metadatum) {
			$entityName = $metadatum->getName();
			$entities = $manager->getRepository($entityName)->findAll();

			$reflection = new \ReflectionClass($entityName);
			$entitiesAssociations = [];
			$entitiesAsArray = [];
			foreach ($entities as $entity) {
				$values = [];
				foreach ($metadatum->getFieldNames() as $key => $name) {
					$property = $reflection->getProperty($name);
					$property->setAccessible(true);
					$value = $property->getValue($entity);
					$values[$name] = $value;
				}
				foreach ($metadatum->getAssociationNames() as $name) {
					if ($metadatum->isAssociationInverseSide($name) && !$metadatum->isCollectionValuedAssociation($name)) {
						continue;
					}
					$property = $reflection->getProperty($name);

					$property->setAccessible(true);
					$value = null;
					if ($metadatum->isSingleValuedAssociation($name)) {
						$value = $property->getValue($entity)->getId();
					} else if ($metadatum->isCollectionValuedAssociation($name)) {
					    $relatedClass = $metadatum->getAssociationTargetClass($name);
					    $relatedReflection = new \ReflectionClass($relatedClass);
						$value = [];

						foreach ($property->getValue($entity) as $subEntity) {
							$identifiers = $manager->getClassMetadata($relatedClass)->getIdentifier();
							$identifierProperty = $relatedReflection->getProperty($identifiers[array_key_first($identifiers)]);
							$identifierProperty->setAccessible(true);
							$value[] = $identifierProperty->getValue($subEntity);
						}
					}

					$entitiesAssociations[$name] = $value;
				}
				$entitiesAsArray[] = ['fields' => $values, 'associations' => $entitiesAssociations];
			}
			$tables[$entityName] = $entitiesAsArray;
		}

		return $tables;
	}

	public function save($manager = null)
	{
		$now = new \DateTime();

		file_put_contents($this->config['output_directory'] . $now->format('Y-m-d H-i-s') . ($manager !== null ? ".$manager" : null) . '.yml', Yaml::dump($this->getTablesContent($manager)));
	}
}
