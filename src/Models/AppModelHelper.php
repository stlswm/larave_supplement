<?php

namespace stlswm\LaravelSupplement\Models;

use Illuminate\Database\Eloquent\Builder as BuilderAlias;
use Illuminate\Database\Eloquent\Collection as CollectionAlias;
use Illuminate\Database\Eloquent\Model as ModelAlias;
use Illuminate\Support\Facades\DB;

/**
 * Trait AppModel
 *
 * @package   App\Models
 * @property  array $fieldFlag 状态字段[[string]=>[int=>string]]尽量避开使用0=>string类型的值
 * @method static ModelAlias|CollectionAlias|static[]|static|null find(mixed $id, array $columns = ['*'])
 * @method static BuilderAlias select(array | mixed $columns = ["*"])
 * @method static BuilderAlias join(string $table, string $first, string | null $operator = NULL, string | null $second = NULL, string $type = 'inner', bool $where = FALSE)
 * @method static BuilderAlias where(mixed $column, mixed $operator = NULL, mixed $value = NULL, string $boolean = 'and')
 * @method static BuilderAlias whereIn(string $column, mixed $values, string $boolean = 'and', bool $not = FALSE)
 * @method static BuilderAlias whereRaw(string $sql, array $bindings = [], string $boolean = 'and')
 * @method static BuilderAlias findOrFail($id, $columns = ['*'])
 */
trait AppModelHelper
{
    /**
     * @param string $field
     * @param mixed  $value
     *
     * @return string
     * @Author wm
     * @Date   2018/7/13
     * @Time   14:51
     */
    public static function getFieldFlagText(string $field, $value): string
    {
        if (!isset(self::$fieldFlag)) {
            return '';
        }
        if (!isset(self::$fieldFlag[$field])) {
            return '';
        }
        if (!isset(self::$fieldFlag[$field][$value])) {
            return "";
        }
        return self::$fieldFlag[$field][$value];
    }

    /**
     * @param array $data
     *
     * @return bool
     */
    public static function insert(array $data): bool
    {
        return DB::table(self::TABLE_NAME)->insert($data);
    }

    /**
     * @param array $data
     *
     * @return int
     */
    public static function insertGetId(array $data): int
    {
        return DB::table(self::TABLE_NAME)->insertGetId($data);
    }

    /**
     * 验证数据是否存在
     *
     * @param array $option
     *
     * @return bool
     */
    public static function checkExists(array $option): bool
    {
        $query = DB::table(self::TABLE_NAME);
        foreach ($option as $key => $value) {
            $query->where($key, '=', $value);
        }
        return $query->exists();
    }

    /**
     * 获取连接的PDO对象
     *
     * @return \PDO
     * @Author wm
     * @Date   2018/9/5
     * @Time   10:31
     */
    public static function getPDO()
    {
        return DB::connection()->getPDO();
    }
}