<?php

namespace Dyosis\DatabaseYamlExtractorBundle\Command;

use Dyosis\DatabaseYamlExtractorBundle\DatabaseYamlExtractor\DatabaseYamlExtractor;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class TestCommand
 * @package Dyosis\DatabaseYamlExtractorBundle\Command
 * @author Jean Marius <jean.marius@dyosis.com>
 */
class ExportCommand extends Command
{
	/**
	 * @var DatabaseYamlExtractor
	 */
	private $extractor;

    /**
     * TestCommand constructor.
     * @param DatabaseYamlExtractor $extractor
     */
	public function __construct(DatabaseYamlExtractor $extractor)
	{
		$this->extractor = $extractor;
		parent::__construct();
	}

    /**
     * Configure.
     */
	public function configure()
	{
		$this
			->setName('dyosis:extractor:export')
			->setDescription('Extract database entities')
            ->addOption('entity-manager', 'em', InputOption::VALUE_OPTIONAL, 'The entity manager which manage entities you want to extract')
		;
	}

    /**
     * Execute.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int|null
     */
	public function execute(InputInterface $input, OutputInterface $output)
	{
		$this->extractor->save($input->getOption('entity-manager'));

		return 0;
	}
}
