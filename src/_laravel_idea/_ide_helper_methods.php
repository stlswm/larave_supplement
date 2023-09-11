<?php

namespace Illuminate\Database\Eloquent {

    use Illuminate\Database\Query\Builder;

    /**
     * Class Model
     * @package Illuminate\Database\Eloquent
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
    class Model
    {

    }
}

namespace Illuminate\Database\Query {

    /**
     * Class Builder
     * @package Illuminate\Database\Query
     * @method \Illuminate\Database\Eloquent\Builder withTrashed(bool $withTrashed = true)
     */
    class Builder
    {
    }
}