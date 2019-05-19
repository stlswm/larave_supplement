<?php

namespace stlswm\LaravelSupplement\Router;

use Exception;

/**
 * Class Generator
 * 路由生成器
 *
 * @package stlswm\LaravelSupplement\Router
 */
class Generator
{
    public static $cache = [
        'api' => [
            '<?php',
            'use Illuminate\Support\Facades\Route;',
            '',
            ''
        ],
        'web' => [
            '<?php',
            'use Illuminate\Support\Facades\Route;',
            '',
            ''
        ],
    ];

    /**
     * 开始生成
     *
     * @param string $readDir
     * @param string $routerDir
     *
     * @return bool
     * @throws Exception
     */
    public static function start(string $readDir, string $routerDir): bool
    {
        if (!is_dir($readDir)) {
            throw new Exception($readDir . 'is not a dir');
        }
        $dh = opendir($readDir);
        while ($file = readdir($dh)) {
            if ($file != '.' && $file != '..') {
                $newPath = $readDir . '/' . $file;
                if (is_dir($newPath)) {
                    self::start($newPath, $routerDir);
                } else {
                    self::parseFile($newPath);
                }
            }
        }
        closedir($dh);
        file_put_contents($routerDir . '/api.php', join("\n", self::$cache['api']));
        file_put_contents($routerDir . '/web.php', join("\n", self::$cache['web']));
        return TRUE;
    }

    /**
     * @param string $file
     * @param string $routerDir
     */
    /**
     * @param string $file
     *
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
                    $namespace = str_replace("\\", "\\\\", $matched[1]);
                    $namespace = str_replace('App\\\\Http\\\\Controllers\\\\', '', $namespace);
                    continue;
                }
            }
            if (!$controller) {
                preg_match('/^(?:\s+)?class (\w+){?/', $line, $matched);
                if ($matched) {
                    $controller = $matched[1];
                    continue;
                }
            }
            preg_match('/^(?:\s+)?\*(?:\s+)?@router\s+(\w+)\s+(.*?)\n$/', $line, $matched);
            if ($matched) {
                $router = [
                    $matched[1],
                    $matched[2],
                ];
                continue;
            }
            preg_match('/^(?:\s+)?public function (\w+)(?:\()?/', $line, $matched);
            if ($matched) {
                $function = $matched[1];
                if ($router && $function) {
                    if (strpos($router[1], '/api') === 0) {
                        $router[1] = substr($router[1], 4);
                        self::$cache['api'][] = "Route::{$router[0]}(\"{$router[1]}\",\"{$namespace}\\\\{$controller}@{$function}\");";
                    } else {
                        self::$cache['web'][] = "Route::{$router[0]}(\"{$router[1]}\",\"{$namespace}\\\\{$controller}@{$function}\");";
                    }
                }
                $router = '';
                continue;
            }
        }
        fclose($fh);
    }
}