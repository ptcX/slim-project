<?php

namespace App\Http\Controllers;

use Interop\Container\ContainerInterface;
use App\Job\TestJob;
use Monolog\Logger;

class HelloController extends Controller
{

    /**
     * @var \App\Http\Service\HelloService
     */
    private $helloService;

    public function __construct(ContainerInterface $ci)
    {
        parent::__construct($ci);
        $this->helloService = $ci->get('service.hello');
    }

    /**
     * hello page
     *
     * @param  \Slim\Http\Request    $request  PSR7 request
     * @param  \Slim\Http\Response   $response PSR7 response
     * @param  array                 $args
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function helloPage($request, $response, $args)
    {
        $view = $this->ci->get('view');

        return $view->render($response, '/hello/page.html.php', [
            'title' => 'hello page',
            'body' => $this->helloService->getHello(),
        ]);
    }

    /**
     * hello api
     *
     * @param  \Slim\Http\Request    $request  PSR7 request
     * @param  \Slim\Http\Response   $response PSR7 response
     * @param  array                 $args
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function helloApi($request, $response, $args)
    {
        $logger = $this->ci->get('appLog');
        $logger->info('new request');

        return $response->withJson([
            'status' => 0,
            'message' => 'ok',
            'body' => $this->helloService->getHello(),
        ], 200);
    }

    /**
     * hello job test
     *
     * @param  \Slim\Http\Request    $request  PSR7 request
     * @param  \Slim\Http\Response   $response PSR7 response
     * @param  array                 $args
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function helloJob($request, $response, $args)
    {
        $resqueService = $this->ci->get('resque');
        $result = $resqueService->push('test', TestJob::class, ['foo' => 'bar']);
        return $response->withJson([
            'status' => 0,
            'message' => 'ok',
            'body' => $result,
        ], 200);
    }
}