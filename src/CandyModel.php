<?php

namespace Cyamz\LaravelCandy;

use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Schema;

/**
 * Jump Helper
 * @method static $this|Builder query()
 * @method $this|Builder union($models)
 * @method $this|Builder unionAll($models)
 * @method $this|Builder with(...$models)
 * @method $this|Builder where($field_name, $operation, $value)
 * @method $this|Builder where($field_name, $value)
 * @method $this|Builder where($function)
 * @method $this|Builder orWhere($field_name, $operation, $value)
 * @method $this|Builder orWhere($field_name, $value)
 * @method $this|Builder orWhere($function)
 * @method $this|Builder whereRaw($raw)
 * @method $this|Builder whereHas($field_name, $function = null)
 * @method $this|Builder whereDoesntHave($field_name, $function = null)
 * @method $this|Builder whereIn($field_name, $array)
 * @method $this|Builder whereNotIn($field_name, $array)
 * @method $this|Builder whereNull($field_name)
 * @method $this|Builder whereNotNull($field_name)
 * @method $this|Builder whereBetween($field_name, $array)
 * @method $this|Builder lock()
 * @method $this|Builder lockForUpdate()
 * @method $this|Builder groupBy($field_name)
 * @method $this|Builder orderBy($field_name, $sort_type = 'ASC')
 * @method $this|Builder orderByDesc($field_name)
 * @method $this|Builder inRandomOrder()
 * @method $this|Builder select(...$fields)
 * @method $this|Builder selectRaw($raw)
 * @method $this|Builder distinct($field_name)
 * @method $this|Builder take(int $num)
 * @method $this|Builder latest(string $field = 'created_at')
 * @method $this[]|Collection get()
 * @method $this[]|Collection paginate($num)
 * @method $this|null find($id)
 * @method $this|null first()
 * @method $this firstOrNew($array)
 * @method $this firstOrCreate($array)
 * @method $this firstOrFail($array|null)
 *
 * Candy Scope Methods
 * @method $this|Builder dateBetween($field_name, $start_date = null, $end_date = null) auto add H:i:s
 * @method $this|Builder timeBetween($field_name, $start_date = null, $end_date = null) use first-hand data
 * @method $this|Builder dateGt($field_name, $date) gt date
 * @method $this|Builder dateLt($field_name, $date) lt date
 * @method $this|Builder orDateGt($field_name, $date) or gt date
 * @method $this|Builder orDateLt($field_name, $date) or lt date
 * 
 * @method $this|Builder bothLike($field_name, $value) left & right %
 * @method $this|Builder leftLike($field_name, $value) $value . '%'
 * @method $this|Builder rightLike($field_name, $value) '%' . $value
 * @method $this|Builder orBothLike($field_name, $value) or left & right %
 * @method $this|Builder orLeftLike($field_name, $value) or $value . '%'
 * @method $this|Builder orRightLike($field_name, $value) or '%' . $value
 * 
 * @method $this|Builder equalParams($request_or_array, array $equal_params) add where
 * @method $this|Builder equalExistParams($request_or_array) add where with filter
 * 
 * Candy Methods
 * @method static bool addAll($insert_data, $chunk_num = 2000) insert all(without events & timestamps)
 * @method static $this|Builder createExistAttributes($data, $fill_fields = []) create without casts event & with filter
 * @method $this|Builder fillExistAttributes($data, $fill_fields = []) fill without save event & with filter
 * 
 */
class CandyModel extends Model
{
    public $timestamps = true;
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $guarded = [];

    /**
     * date format
     *
     * @param \DateTimeInterface $date
     * @return string
     */
    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    /** like scopes */
    public function scopeBothLike($query, $field_name, $value)
    {
        return $query->where($field_name, 'like', '%' . $value . '%');
    }
    public function scopeLeftLike($query, $field_name, $value)
    {
        return $query->where($field_name, 'like', $value . '%');
    }
    public function scopeRightLike($query, $field_name, $value)
    {
        return $query->where($field_name, 'like', '%' . $value);
    }
    public function scopeOrBothLike($query, $field_name, $value)
    {
        return $query->orWhere($field_name, 'like', '%' . $value . '%');
    }
    public function scopeOrLeftLike($query, $field_name, $value)
    {
        return $query->orWhere($field_name, 'like', $value . '%');
    }
    public function scopeOrRightLike($query, $field_name, $value)
    {
        return $query->orWhere($field_name, 'like', '%' . $value);
    }

    /** time scopes */
    public function scopeDateBetween($query, $field_name, $start_date = null, $end_date = null)
    {
        if (!empty($start_date)) {
            $query->where($field_name, '>=', $start_date . ' 00:00:00');
        }

        if (!empty($end_date)) {
            $query->where($field_name, '<=', $end_date . ' 23:59:59');
        }

        return $query;
    }
    public function scopeTimeBetween($query, $field_name, $start_time = null, $end_time = null)
    {
        if (!empty($start_time)) {
            $query->where($field_name, '>=', $start_time);
        }

        if (!empty($end_time)) {
            $query->where($field_name, '<=', $end_time);
        }

        return $query;
    }
    public function scopeDateGt($query, $field_name, $date)
    {
        return $query->where($field_name, '>=', $date . ' 00:00:00');
    }
    public function scopeDateLt($query, $field_name, $date)
    {
        return $query->where($field_name, '<=', $date . ' 23:59:59');
    }
    public function scopeOrDateGt($query, $field_name, $date)
    {
        return $query->orWhere($field_name, '>=', $date . ' 00:00:00');
    }
    public function scopeOrDateLt($query, $field_name, $date)
    {
        return $query->orWhere($field_name, '<=', $date . ' 23:59:59');
    }

    /** where scopes */
    public function scopeEqualParams($query, $data, $equal_params)
    {
        if (!is_array($data)) {
            $data = $data->toArray();
        }

        foreach ($equal_params as $param_name) {
            if (isset($data[$param_name])) {
                $query->where($param_name, $data[$param_name]);
            }
        }

        return $query;
    }
    public function scopeEqualExistParams($query, $data)
    {
        if (!is_array($data)) {
            $data = $data->toArray();
        }

        $params = Schema::getColumnListing($this->getTable());
        foreach ($params as $param_name) {
            if (isset($data[$param_name])) {
                $query->where($param_name, $data[$param_name]);
            }
        }

        return $query;
    }

    /**
     * insert all(without events & timestamps)
     *
     * @param array $insert_data
     * @return bool
     */
    public static function addAll($insert_data, $chunk_num = 2000)
    {
        if ($insert_data) {
            $arr = array_chunk($insert_data, $chunk_num);

            DB::beginTransaction();
            foreach ($arr as $insert_group) {
                $flag = DB::table(self::getModel()->getTable())->insert($insert_group);
                if (!$flag) {
                    DB::rollBack();
                    return false;
                }
            }
            DB::commit();
        }

        return true;
    }

    /**
     * fill without save event & with filter
     *
     * @param array|Model $data
     * @return $this
     */
    public function fillExistAttributes($data, $fill_fields = [])
    {
        if (!is_array($data)) {
            $data = $data->toArray();
        }

        $columns = Schema::getColumnListing($this->getTable());
        unset($columns[0]); //id

        if ($fill_fields) {
            $columns = array_intersect($columns, $fill_fields);
            $columns = array_unique($columns);
        }

        foreach ($columns as $column) {
            if (in_array($column, ['created_at', 'updated_at'])) {
                continue;
            }
            if (isset($data[$column])) {
                $this->{$column} = $data[$column];
            }
        }
        return $this;
    }

    /**
     * create without casts event & with filter
     *
     * @param array|model $data
     * @return \Illuminate\Database\Eloquent\Model|$this
     */
    public static function createExistAttributes($data, $fill_fields = [])
    {
        if (!is_array($data)) {
            $data = $data->toArray();
        }

        $columns = Schema::getColumnListing(self::getModel()->getTable());
        unset($columns[0]); //id

        if ($fill_fields) {
            $columns = array_intersect($columns, $fill_fields);
            $columns = array_unique($columns);
        }

        $create_data = [];
        foreach ($columns as $column) {
            if (in_array($column, ['created_at', 'updated_at'])) {
                continue;
            }
            if (isset($data[$column])) {
                $create_data[$column] = $data[$column];
            }
        }

        return self::query()->create($create_data);
    }

}
