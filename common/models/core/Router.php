<?php

namespace calamity\common\models\core;

use calamity\common\models\core\exception\ForbiddenException;
use calamity\common\models\core\exception\NotFoundException;
use calamity\common\models\core\exception\ServiceUnavailableException;
use calamity\common\models\core\exception\UnauthorizedException;
use calamity\common\models\Users;

class Router {
    private Request $request;
    private Response $response;
    private array $routeMap = [];

    public function __construct(Request $request, Response $response) {
        $this->request = $request;
        $this->response = $response;
    }

    public static function get(string $url, $callback): void {
        Calamity::$app->router->routeMap['get'][$url] = $callback;
    }

    public static function post(string $url, $callback): void {
        Calamity::$app->router->routeMap['post'][$url] = $callback;
    }

    public static function getNpost(string $url, $callback): void {
        Calamity::$app->router->routeMap['get'][$url] = $callback;
        Calamity::$app->router->routeMap['post'][$url] = $callback;
    }

    /**
     * @throws \calamity\common\models\core\exception\ForbiddenException
     * @throws \calamity\common\models\core\exception\NotFoundException
     * @throws \calamity\common\models\core\exception\ServiceUnavailableException
     * @throws \calamity\common\models\core\exception\UnauthorizedException
     */
    public function resolve(): mixed {
        if (Calamity::$app->maintenance) {
            throw new ServiceUnavailableException(Calamity::t('general', 'Maintenance in progress'));
        }

        $method = $this->request->getMethod();
        $url = $this->request->getUrl();
        $callback = $this->routeMap[$method][$url] ?? false;
        if (!$callback) {
            $callback = $this->getCallback();

            if ($callback === false) {
                throw new NotFoundException();
            }
        }
        if (is_string($callback)) {
            return $this->renderView($callback);
        }
        if (is_array($callback)) {
            /**
             * @var $controller Controller
             */
            $controller = new $callback[0]();
            $controller->action = $callback[1];
            Calamity::$app->controller = $controller;

            if (isset($_COOKIE['rememberMe'])) {
                /** @var Users $user */
                $user = Users::findOne(['id' => $_COOKIE['rememberMe']]);

                if ($user) {
                    Calamity::$app->login($user);
                }
            }

            $modified = $this->getHost($url);
            if (!Users::canRoute(Calamity::$app->user, $modified)) {
                if (Calamity::$app->user) {
                    throw new UnauthorizedException();
                }
                throw new ForbiddenException();
            }

            $callback[0] = $controller;
        }
        return $callback($this->request, $this->response);
    }

    public function getCallback(): mixed {
        $method = $this->request->getMethod();
        $url = $this->request->getUrl();
        // Trim slashes
        $url = trim($url, '/');

        // Get all routes for current request method
        $routes = $this->getRouteMap($method);

        // Start iterating registered routes
        foreach ($routes as $route => $callback) {
            // Trim slashes
            $route = trim($route, '/');
            $routeNames = [];

            if (!$route) {
                continue;
            }

            // Find all route names from route and save in $routeNames
            if (preg_match_all('/\{(\w+)(:[^}]+)?}/', $route, $matches)) {
                $routeNames = $matches[1];
            }

            // Convert route name into regex pattern
            $routeRegex = "@^" . preg_replace_callback('/\{\w+(:([^}]+))?}/', static fn($m) => isset($m[2]) ? "({$m[2]})" : '(\w+)', $route) . "$@";

            // Test and match current route against $routeRegex
            if (preg_match_all($routeRegex, $url, $valueMatches)) {
                $values = [];
                for ($i = 1; $i < count($valueMatches); $i++) {
                    $values[] = $valueMatches[$i][0];
                }
                $routeParams = array_combine($routeNames, $values);

                $this->request->setRouteParams($routeParams);
                return $callback;
            }
        }

        return false;
    }

    public function getRouteMap($method): array {
        return $this->routeMap[$method] ?? [];
    }

    public function renderView($view, $params = []): string {
        return Calamity::$app->view->renderView($view, $params);
    }

    private function getHost(mixed $url): string {
        $host = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https://" : "http://") . $_SERVER['HTTP_HOST'];
        if ($host == Calamity::$URL['@admin']) {
            $modified = '@admin' . $url;
        } elseif ($host == Calamity::$URL['@public']) {
            $modified = '@public' . $url;
        } else {
            $modified = '';
        }
        return $modified;
    }

    public function renderViewOnly($view, $params = []): string {
        return Calamity::$app->view->renderViewOnly($view, $params);
    }
}