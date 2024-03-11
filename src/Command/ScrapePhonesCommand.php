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
        $outputFilePath = $input->getArgument('output');

        $page = $this->crawlerService->fetchPage(1);
        $scraperService = new PhoneScraperService($page);
        $pageCount = $scraperService->getPageCount();
        $entities = $scraperService->getEntities();
        for ($pageNumber = 2; $pageNumber <= $pageCount; $pageNumber++) {
            $page = $this->crawlerService->fetchPage($pageNumber);
            $scraperService = new PhoneScraperService($page);
            $entities = array_merge($entities, $scraperService->getEntities());
        }


        $phones = PhoneTransformer::transformMany($entities);
        $phonesJson = json_encode($phones);

        $filesystem = new Filesystem();
        $filesystem->dumpFile($outputFilePath, $phonesJson);

        return 0;
    }
}