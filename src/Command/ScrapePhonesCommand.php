<?php

declare(strict_types=1);

namespace App\Command;

use App\Services\CrawlerService;
use App\Services\PhoneScraperService;
use App\Transformer\PhoneTransformer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

class ScrapePhonesCommand extends Command
{
    /**
     * The name of the command.
     *
     * @var string
     */
    public const NAME = 'scrape:phones:json';

    public function __construct(
        private CrawlerService $crawlerService
    ) {
        parent::__construct();
        $this->crawlerService = $crawlerService;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName(self::NAME)
            ->setDescription('Scrape phones and save as a JSON file.')
            ->addArgument('output', InputArgument::REQUIRED, 'The file path to store the resulting JSON file.');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        return 0;
    }
}