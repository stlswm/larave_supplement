<?php

namespace stlswm\LaravelSupplement\Router;

use Exception;

/**
 * laravel v8后路由生成
 * Class GeneratorV8
 * @package stlswm\LaravelSupplement\Router
 */
class GeneratorV8
{
    public static $cache = [
        'api' => [
            '<?php',
            '',
            'use Illuminate\Support\Facades\Route;',
            '',
            ''
        ],
        'web' => [
            '<?php',
            '',
            'use Illuminate\Support\Facades\Route;',
            '',
            ''
        ],
    ];

    /**
     * 开始生成
     * @param  string  $readDir
     * @param  string  $routerDir
     * @return bool
     * @throws Exception
     */
    public static function start(string $readDir, string $routerDir): bool
    {
        if (!is_dir($readDir)) {
            throw new Exception($readDir.'is not a dir');
        }
        $dh = opendir($readDir);
        while ($file = readdir($dh)) {
            if ($file != '.' && $file != '..') {
                $newPath = $readDir.'/'.$file;
                if (is_dir($newPath)) {
                    self::start($newPath, $routerDir);
                } else {
                    self::parseFile($newPath);
                }
            }
        }
        closedir($dh);
        file_put_contents($routerDir.'/api.php', join("\n", self::$cache['api']));
        file_put_contents($routerDir.'/web.php', join("\n", self::$cache['web']));
        return true;
    }

    /**
     * @param  string  $file
     * @param  string  $routerDir
     */
    /**
     * @param  string  $file
     * @throws Exception
     */
    public static function parseFile(string $file)
    {
        $namespace = '';
        $controller = '';
        $router = [];
        $fh = fopen($file, 'r');
        if (end(self::$cache['api']) != '') {
            self::$cache['api'][] = '';
        }
        if (end(self::$cache['web']) != '') {
            self::$cache['web'][] = '';
        }
        while ($line = fgets($fh)) {
            if (!$namespace) {
                preg_match('/^(?:\s+)?namespace\s(.*?);/', $line, $matched);
                if ($matched) {
                    $namespace = $matched[1];
                    continue;
                }
            }
            if (!$controller) {
                preg_match('/^(?:\s+)?class (\w+){?/', $line, $matched);
                if ($matched) {
                    $controller = trim($matched[1]);
                    continue;
                }
            }
            preg_match('/^(?:\s+)?\*(?:\s+)?@router\s+(\w+)\s+(.*?)\n$/', $line, $matched);
            if ($matched) {
                $router = [
                    trim($matched[1]),
                    trim($matched[2]),
                ];
                continue;
            }
            preg_match('/^(?:\s+)?public function (\w+)(?:\()?/', $line, $matched);
            if ($matched) {
                $function = trim($matched[1]);
                if ($router && $function) {
                    if (strpos($router[1], '/api') === 0) {
                        $router[1] = substr($router[1], 4);
                        self::$cache['api'][] = "Route::{$router[0]}(\"{$router[1]}\", [{$namespace}\\{$controller}::class,\"{$function}\"]);";
                    } else {
                        self::$cache['web'][] = "Route::{$router[0]}(\"{$router[1]}\", [{$namespace}\\{$controller}::class,\"{$function}\"]);";
                    }
                }
                $router = '';
                continue;
            }
        }
        fclose($fh);
    }
}