<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use App\Services\CrawlerService;
use GuzzleHttp\Client;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Dotenv\Dotenv;

return static function (ContainerConfigurator $configurator, ContainerBuilder $container) {
    $dotenv = new Dotenv();
    $dotenv->load(__DIR__.'/../.env');

    $container->setParameter('SCRAPE_TARGET_URL', '%env(SCRAPE_TARGET_URL)%');

    $services = $configurator
        ->services()
        ->defaults()
        ->autowire(true);

    $services
        ->instanceof(\Symfony\Component\Console\Command\Command::class)
        ->tag('app.command');

    $services->load('App\\', '../src/*');

    $services
        ->set(\App\App::class)
        ->public()
        ->args([tagged_iterator('app.command')]);


    $guzzleConfig = [
        'base_uri' => '%env(SCRAPE_TARGET_URL)%',
    ];
    $services
        ->set(Client::class)
        ->public()
        ->tag('app.services.http')
        ->arg('$config', $guzzleConfig);

    $services
        ->set(CrawlerService::class)
        ->public()
        ->tag('app.services.crawler_service')
        ->arg('$url', '%env(SCRAPE_TARGET_URL)%');
};