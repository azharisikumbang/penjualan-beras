<?php ob_start();

require_once __DIR__ . '/../src/App.php';
require_once __DIR__ . '/../src/Manager.php';
require_once __DIR__ . '/../src/Libraries/Database.php';
require_once __DIR__ . '/../src/Libraries/Router.php';
require_once __DIR__ . '/../src/Libraries/Session.php';
require_once __DIR__ . '/../src/Libraries/Response.php';
require_once __DIR__ . '/../src/Libraries/Request.php';

$appConfiguration = require_once __DIR__ . '/../src/resources/config.php';

$manager = new Manager();
$manager->setDatabaseManager(new Database($appConfiguration['database']));
$manager->setSessionManager(new Session());
$manager->setRouterManager(new Router());
$manager->setRequestManager(new Request($_REQUEST));
$manager->setResponseManager(new Response());

$app = new App($manager);
$app->addConfigFor('app', $appConfiguration['app']);
$app->addConfigFor('disk', $appConfiguration['disk']);
$app->addConfigFor('whatsapp', $appConfiguration['whatsapp']);
$app->setEnvironment('development');
$app->setTimeZone('Asia/Jakarta');
$app->loadFunction('functions', fn ($file) => require_once $file);
$app->buildRoute($_GET['path'] ?? 'homepage');
$app->run();