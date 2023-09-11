<?php

namespace stlswm\LaravelSupplement\Models;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use PDO;

/**
 * Trait AppModel
 * @package   App\Models
 * @property array $fieldFlag       状态字段[[string]=>[int=>string]]尽量避开使用0=>string类型的值
 * @property array $attributeLabels 栏位label map[string]string
 * @method static mixed|static find($id, $columns = ['*'])
 * @method static Builder select(array | mixed $columns = ["*"])
 * @method static Builder join(string $table, string $first, string | null $operator = null, string | null $second = null, string $type = 'inner', bool $where = false)
 * @method static Builder leftJoin($table, $first, $operator = null, $second = null)
 * @method static Builder where(mixed $column, mixed $operator = null, mixed $value = null, string $boolean = 'and')
 * @method static Builder whereIn(string $column, mixed $values, string $boolean = 'and', bool $not = false)
 * @method static Builder whereRaw(string $sql, array $bindings = [], string $boolean = 'and')
 * @method static Builder findOrFail($id, $columns = ['*'])
 * @method static self create(array $data)
 */
trait AppModelHelper
{
    /**
     * @param  string  $field
     * @param  mixed   $value
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
     * @param  string  $attribute
     * @return string
     */
    public static function attributeLabel(string $attribute): string
    {
        if (isset(self::$attributeLabels) && isset(self::$attributeLabels[$attribute])) {
            return self::$attributeLabels[$attribute];
        }
        return $attribute;
    }

    /**
     * @param  array  $data
     * @return bool
     */
    public static function insert(array $data): bool
    {
        return DB::table(self::TABLE_NAME)->insert($data);
    }

    /**
     * @param  array  $data
     * @return int
     */
    public static function insertGetId(array $data): int
    {
        return DB::table(self::TABLE_NAME)->insertGetId($data);
    }

    /**
     * 验证数据是否存在
     * @param  array  $option
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
     * @return PDO
     * @Author wm
     * @Date   2018/9/5
     * @Time   10:31
     */
    public static function getPDO()
    {
        return DB::connection()->getPDO();
    }
}