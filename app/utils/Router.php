<?php
class Router {

    public function dispatch($url) {
        $url = urldecode(parse_url($url, PHP_URL_PATH));
        $url = trim($url, '/');

        if (empty($url)) {
            $controllerName = 'PokemonController';
            $methodName = 'index';
            $params = [];
        } else {
            // Split URL into parts
            $parts = explode('/', $url);

            // Get controller name
            $controllerName = ucfirst($parts[0]) . 'Controller';
            // Get method name
            $methodName = isset($parts[1]) ? $parts[1] : 'index';
            
            // Get parameters
            $params = array_slice($parts, 2);
        }

        // Check if controller exists
        $controllerFile = __DIR__ . '/../controllers/' . $controllerName . '.php';
        
        if (file_exists($controllerFile)) {
            $controller = new $controllerName();
            
            if (method_exists($controller, $methodName)) {
                call_user_func_array([$controller, $methodName], $params);
            } else {
                $this->error404();
            }
        } else {
            $this->error404();
        }
    }

    private function error404() {
        header("HTTP/1.0 404 Not Found");
        echo '<!DOCTYPE html>
        <html>
        <head>
            <title>404 - Page Not Found</title>
            <style>
                .error-container {
                    text-align: center;
                    margin-top: 100px;
                    font-family: Arial, sans-serif;
                }
                .error-title {
                    font-size: 72px;
                    color: #333;
                    margin-bottom: 20px;
                }
                .error-message {
                    font-size: 24px;
                    color: #666;
                    margin-bottom: 30px;
                }
                .home-link {
                    text-decoration: none;
                    padding: 10px 20px;
                    background-color: #007bff;
                    color: white;
                    border-radius: 5px;
                    transition: background-color 0.3s;
                }
                .home-link:hover {
                    background-color: #0056b3;
                }
            </style>
        </head>
        <body>
            <div class="error-container">
                <div class="error-title">404</div>
                <div class="error-message">Page Not Found</div>
                <a href="/" class="home-link">Return to Homepage</a>
            </div>
        </body>
        </html>';
        exit();
    }
}