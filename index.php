<?php

require_once 'src/autoload.php';

use App\Config\Auth;

class Router
{
    private $routes;

    public function __construct()
    {
        $this->routes = [];
    }

    public function addRoute(string $pattern, string $action, string $controller, string $method, ?string $middleware = null): void
    {
        $this->routes[$pattern] = [
            'action' => $action,
            'controller' => $controller,
            'method' => $method,
            'middleware' => $middleware
        ];
    }

    public function routeRequest(): void
    {
        $uri = $_SERVER['REQUEST_URI'];
        $routeMatched = false;

        try {
            foreach ($this->routes as $pattern => $route) {
                if (preg_match("#^$pattern$#", $uri, $matches)) {
                    array_shift($matches);
                    $controllerName = $route['controller'];
                    $action = $route['action'];

                    require_once __DIR__ . '/src/controllers/' . $controllerName . '.php';

                    $controller = new $controllerName();

                    if (isset($route['middleware'])) {
                        $auth = new Auth();
                        call_user_func([$auth, $route['middleware']]);
                    }

                    $params = array();
                    foreach ($matches as $key => $value) {
                        $params[$key] = $value;
                    }

                    $controller->$action($params);

                    $routeMatched = true;
                    break;
                }
            }

            if (!$routeMatched) {
                throw new Exception('Page non trouvée.');
            }
        } catch (Exception $e) {
            http_response_code(404);
            $message = $e->getMessage();

            require 'templates/404.php';
        }
    }
}

$router = new Router();

$router->addRoute('/', 'getHomeView', 'HomeController', 'GET', 'checkAuth');
$router->addRoute('/search', 'searchUsers', 'HomeController', 'POST', 'checkAuth');

$router->addRoute('/login', 'getLoginView', 'LoginController', 'GET');
$router->addRoute('/login/register', 'loginVerify', 'LoginController', 'POST');
$router->addRoute('/logout', 'setUserLogout', 'LoginController', 'GET', 'checkAuth');

$router->addRoute('/signup', 'getSignupView', 'SignupController', 'GET');
$router->addRoute('/signup/register', 'setRegistration', 'SignupController', 'POST');

$router->addRoute('/timeline', 'refreshTimeline', 'PostController', 'GET', 'checkAuth');
$router->addRoute('/post/(?<post_id>(?!0)[0-9]+)', 'getPostView', 'PostController', 'GET', 'checkAuth');
$router->addRoute('/post/register', 'setPostRegistration', 'PostController', 'POST', 'checkAuth');
$router->addRoute('/comment/register', 'setCommentRegistration', 'PostController', 'POST', 'checkAuth');
$router->addRoute('/post/like/register', 'setLike', 'PostActionController', 'POST', 'checkAuth');
$router->addRoute('/post/retweet/register', 'setRetweet', 'PostActionController', 'POST', 'checkAuth');

$router->addRoute('/tendances/(?<hashtag>[a-zA-Z0-9]+)', 'getPostsFromHashtag', 'TrendController', 'GET', 'checkAuth');
$router->addRoute('/tendances', 'getHashtags', 'TrendController', 'GET', 'checkAuth');
$router->addRoute('/img/(?<img_hash>[A-Za-z0-9]+)', 'getPostPhoto', 'PostController', 'GET', 'checkAuth');

$router->addRoute('/profile/(?<pseudo>[A-Za-z0-9-_]+)', 'getProfileView', 'ProfileController', 'GET', 'checkAuth');
$router->addRoute('/profile/(?<pseudo>[A-Za-z0-9-_]+)/update', 'setProfileUpdate', 'ProfileController', 'POST', 'checkAuth');
$router->addRoute('/profile/(?<pseudo>[A-Za-z0-9-_]+)/photo/update', 'setProfilePhotoUpdate', 'ProfileController', 'POST', 'checkAuth');

$router->addRoute('/profile/(?<pseudo>[A-Za-z0-9-_]+)/followers', 'getFollowersView', 'FollowListController', 'GET', 'checkAuth');
$router->addRoute('/profile/(?<pseudo>[A-Za-z0-9-_]+)/followings', 'getFollowingView', 'FollowListController', 'GET', 'checkAuth');
$router->addRoute('/follow/(?<pseudo>[A-Za-z0-9-_]+)', 'setFollowUser', 'FollowListController', 'POST', 'checkAuth');

$router->addRoute('/chat', 'getChatView', 'ChatController', 'GET', 'checkAuth');
$router->addRoute('/chat/(?<pseudo>[A-Za-z0-9-_]+)', 'getChats', 'ChatController', 'GET', 'checkAuth');
$router->addRoute('/chat/(?<pseudo>[A-Za-z0-9-_]+)/register', 'setChatRegistration', 'ChatController', 'POST', 'checkAuth');

$router->routeRequest();
