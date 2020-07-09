<?php

namespace Dyosis\DatabaseYamlExtractorBundle\Command;

use Dyosis\DatabaseYamlExtractorBundle\DatabaseYamlExtractor\DatabaseYamlImporter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ImportCommand
 * @package Dyosis\DatabaseYamlExtractorBundle\Command
 * @author Jean Marius <jean.marius@dyosis.com>
 */
class ImportCommand extends Command
{
    /**
     * @var DatabaseYamlImporter
     */
    private $importer;

    /**
     * ImportCommand constructor.
     *
     * @param DatabaseYamlImporter $importer
     */
    public function __construct(DatabaseYamlImporter $importer)
    {
        $this->importer = $importer;
        parent::__construct();
    }

    /**
     * Configure.
     */
    public function configure()
    {
        $this
            ->setName('dyosis:extractor:import')
            ->setDescription('Import database entities')
            ->addArgument('file', InputArgument::REQUIRED, 'The file to import')
            ->addOption('entity-manager', 'em', InputOption::VALUE_OPTIONAL, 'The targeted entity manager you want to import entities to')
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
        $this->importer->load($input->getArgument('file'), $input->getOption('entity-manager'));

        return 0;
    }
}
