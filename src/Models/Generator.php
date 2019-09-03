<?php

namespace stlswm\LaravelSupplement\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Generator
 * 模型生成器
 *
 * @package stlswm\LaravelSupplement\Models
 */
class Generator
{
    /**
     * @param string $modelDir
     * @param string $namespace
     *
     * @return array
     */
    public static function start(string $modelDir, string $namespace = "App\\Models\\"): array
    {
        if (!is_dir($modelDir)) {
            return [FALSE, 'error dir:' . $modelDir];
        }
        $files = scandir($modelDir);
        if (!$files) {
            return [FALSE, 'empty dir'];
        }
        $namespace = rtrim($namespace, "\\") . "\\";
        if (!is_dir($modelDir . '/Generator')) {
            mkdir($modelDir . '/Generator');
        }
        foreach ($files as $file) {
            if ($file != '.' && $file != '..') {
                $fileName = pathinfo($file, PATHINFO_FILENAME);
                $className = $namespace . $fileName;
                if (!class_exists($className)) {
                    continue;
                }
                /**
                 * @var Model $model
                 */
                $model = new $className;
                if ($model instanceof Model) {
                    $tmpFile = "<?php\n\n";
                    $tmpFile .= "namespace {$namespace}Generator;\n";
                    $tmpFile .= "/**\n";
                    $tmpFile .= " * trait {$fileName}\n";
                    $tmpFile .= " *\n";
                    $tmpFile .= " * @package {$namespace}Generator\n";
                    $tmpFile .= " *\n";
                    $schemaBuilder = $model->getConnection()->getSchemaBuilder();
                    $columnList = $schemaBuilder->getColumnListing($model->getTable());
                    foreach ($columnList as $column) {
                        switch (strtoupper($schemaBuilder->getColumnType($model->getTable(), $column))) {
                            case 'TINYINT':
                            case 'TINYINTEGER':
                            case 'SMALLINT':
                            case 'MEDIUMINT':
                            case 'INT':
                            case 'INTEGER':
                            case 'BIGINT':
                            case "TIMESTAMP":
                                $tmpFile .= " * @property int \${$column}\n";
                                break;
                            case 'FLOAT':
                            case 'DOUBLE':
                            case 'DECIMAL':
                                $tmpFile .= " * @property float \${$column}\n";
                                break;
                            case 'DATE':
                            case 'TIME':
                            case 'YEAR':
                            case 'DATETIME':
                            case 'CHAR':
                            case 'VARCHAR':
                            case 'TINYBLOB':
                            case 'TINYTEXT':
                            case 'BLOB':
                            case 'TEXT':
                            case 'MEDIUMBLOB':
                            case 'MEDIUMTEXT':
                            case 'LONGBLOB':
                            case 'LONGTEXT':
                            case 'STRING':
                                $tmpFile .= " * @property string \${$column}\n";
                                break;
                        }
                    }
                    $tmpFile .= " */\n";
                    $tmpFile .= "trait {$fileName}\n";
                    $tmpFile .= "{\n";
                    $tmpFile .= "}";
                    file_put_contents($modelDir . '/Generator/' . $fileName . '.php', $tmpFile);
                }
            }
        }
        return [TRUE, ''];
    }
}