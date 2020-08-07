<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require_once '../vendor/autoload.php';
require_once '../generated-conf/config.php';

$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = false;

$app = new \Slim\App(['settings' => $config]);

$container = $app->getContainer();

$container['logger'] = function($c) {
    $logger = new \Monolog\Logger('error_logger');
    $file_handler = new \Monolog\Handler\StreamHandler('../logs/error.log');
    $logger->pushHandler($file_handler);
    return $logger;
};

$container['view'] = function ($c) {
    $view = new \Slim\Views\Twig('../templates', [
        'cache' => '../cache'
    ]);
    
    // Instantiate and add Slim specific extension
    $router = $c->get('router');
    $uri = \Slim\Http\Uri::createFromEnvironment(new \Slim\Http\Environment($_SERVER));
    $view->addExtension(new \Slim\Views\TwigExtension($router, $uri));

    return $view;
};

$app->get('/tickets', function (Request $request, Response $response) {
    $tickets = TicketQuery::create()->find();

    return $tickets->toJSON();

    // $response = $this->view->render($response, 'tickets.twig', [
    //     'tickets' => $tickets,
    //     'router' => $this->router
    // ]);

    // return $response;
})->setName('tickets');

$app->get('/ticket/{id}', function (Request $request, Response $response, $args) {
    $ticket_id = (int)$args['id'];

    $ticket = TicketQuery::create()->findPK($ticket_id);

    return $ticket;

    // $response = $this->view->render($response, 'tickets.twig', [
    //     'ticket' => $ticket
    // ]);

    // return $response;
})->setName('ticket-detail');

$app->put('/ticket/{id}', function (Request $request, Response $response, $args) {
    $ticket_id = (int)$args['id'];
    $data = $request->getParsedBody();

    $ticket = TicketQuery::create()->findPK($ticket_id);

    if (!empty($data['title'])) {
        $ticket->setTitle(filter_var($data['title'], FILTER_SANITIZE_STRING));
    }

    if (!empty($data['component'])) {
        $ticket->setComponent(filter_var($data['component'], FILTER_SANITIZE_STRING));
    }

    if (!empty($data['shortDescription'])) {
        $ticket->setShortDescription(filter_var($data['shortDescription'], FILTER_SANITIZE_STRING));
    }

    $ticket->save();

    return $ticket;

    // $response = $this->view->render($response, 'tickets.twig', [
    //     'ticket' => $ticket
    // ]);

    // return $response;
})->setName('ticket-update');

$app->delete('/ticket/{id}', function (Request $request, Response $response, $args) {
    $ticket_id = (int)$args['id'];

    TicketQuery::create()->findPK($ticket_id)->delete();

    $tickets = TicketQuery::create()->find();

    return count($tickets) > 0 ? $tickets : [];

    return $app->response->redirect($app->urlFor('tickets'), 303);
})->setName('ticket-delete');

$app->post('/ticket', function (Request $request, Response $response, $args) {
    $data = $request->getParsedBody();

    $ticket = new Ticket();
    $ticket->setTitle(filter_var($data['title'], FILTER_SANITIZE_STRING));
    $ticket->setComponent(filter_var($data['component'], FILTER_SANITIZE_STRING));
    $ticket->setShortDescription(filter_var($data['shortDescription'], FILTER_SANITIZE_STRING));
    $ticket->save();

    return $ticket->toJSON();

    // $response = $this->view->render($response, 'tickets.twig', [
    //     'ticket' => $ticket
    // ]);

    // return $response;
})->setName('ticket-create');

$app->run();