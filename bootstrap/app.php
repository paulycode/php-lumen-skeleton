<?php require __dir__ . '/../vendor/autoload.php';

(new Laravel\Lumen\Bootstrap\LoadEnvironmentVariables(
    dirname(__dir__)
))->bootstrap();

$app = new Laravel\Lumen\Application(dirname(__dir__));
$app->withFacades();
$app->withEloquent();

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

$app->middleware([
    App\Http\Middleware\TrimString::class,
]);

$app->register(App\Providers\FormRequestServiceProvider::class);

$app->router->group([
    'namespace' => 'App\Http\Controllers',
], function ($router) {
    require __dir__ . '/../routes/api.php';
});

return $app;
