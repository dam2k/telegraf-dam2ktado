#!/usr/bin/php
<?php
/**
 * Software Url: https://github.com/dam2k/telegraf-dam2ktado
 * Author: Dino Ciuffetti - <dino@tuxweb.it>
 * Version: 0.9
 * Release date: 2023-08-04
 * License: MIT
 *
 * NOTE: telegraf-dam2ktado is an unofficial TADO (tm) execd input plugin (exporter) for telegraf written in PHP.
 * It's a cool way to export metrics from your thermostats and view them in beautiful grafana dashboards.
 *
 * TADO (tm) does not support its public api in no way. I get the api methods from a tado knowledgebase public post.
 * Also, thank to this post: https://shkspr.mobi/blog/2019/02/tado-api-guide-updated-for-2019/
 * It's working for me, may be this is also ok for you. Like any other open source software, the author cannot assume any warranty.
 */

declare(strict_types=1);

namespace dAm2K\telegrafdam2ktado;

use dAm2K\TadoApi;

require __DIR__.'/vendor/autoload.php';

// this one implements STDIN signaling from telegraf
function stdin_stream()
{
    while($line=fgets(STDIN)) yield $line;
}

// NOTE: you need to setup your environment here!
$tadoconf=[
    // Get your personale Tado (tm) client ID and secret from https://my.tado.com/webapp/env.js
    'tado.clientId' => 'tado-web-app',
    'tado.clientSecret' => 'taG9tXxzGrIFWixUT1nZnzIjlovENGe0KNAB51ADKZQjSlNBvhs0xbT6tC4jIUaC',
    'tado.username' => 'yourtadoemail@email.com',
    'tado.password' => 'yourtadopassporcoziochenotiziamacomelhamessadentroguarda',
    // your home's ID.
    'tado.homeid' => '36389',
    // we put access token here. When the AT expires a new one get collected and saved here
    'statefile' => '/tmp/dam2ktado_aeSh8aem.txt'
];

$tado=new TadoApi($tadoconf);

// we get woken up by telegraf via STDIN (\n)
foreach(stdin_stream() as $line)
{
    try
    {
        $o=$tado->getHomeMetrics();
        //fprintf(STDERR, "%s\n", "New metric...");
        fprintf(STDOUT, "%s\n", json_encode($o));
    } catch (\Exception $e) {
        fprintf(STDERR, "Error with tado api call: %s\n", $e->getMessage());
    }
}

