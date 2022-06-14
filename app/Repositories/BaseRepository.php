<?php

// phpcs:disable Generic.Files.LineLength.MaxExceeded,Generic.Files.LineLength.TooLong

namespace App\Repositories;

use App\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Abstract BaseRepository.
 *
 * @method \Illuminate\Database\Eloquent\Builder                                             where($column, $operator = null, $value = null, $boolean = 'and')
 * @method \Illuminate\Database\Eloquent\Builder                                             orWhere($column, $operator = null, $value = null)
 * @method \Illuminate\Database\Eloquent\Builder                                             whereRaw($sql, $bindings = [], $boolean = 'and')
 * @method \Illuminate\Database\Eloquent\Builder                                             orWhereRaw($sql, $bindings = [])
 * @method \Illuminate\Database\Eloquent\Builder                                             whereIn($column, $values, $boolean = 'and', $not = false)
 * @method \Illuminate\Database\Eloquent\Builder                                             orWhereIn($column, $values)
 * @method \Illuminate\Database\Eloquent\Builder                                             whereNotIn($column, $values, $boolean = 'and')
 * @method \Illuminate\Database\Eloquent\Builder                                             orWhereNotIn($column, $values)
 * @method \Illuminate\Database\Eloquent\Builder                                             whereNull($columns, $boolean = 'and', $not = false)
 * @method \Illuminate\Database\Eloquent\Builder                                             orWhereNull($column)
 * @method \Illuminate\Database\Eloquent\Builder                                             whereNotNull($columns, $boolean = 'and')
 * @method \Illuminate\Database\Eloquent\Builder                                             orWhereNotNull($column)
 * @method \Illuminate\Database\Eloquent\Builder                                             whereBetween($column, array $values, $boolean = 'and', $not = false)
 * @method \Illuminate\Database\Eloquent\Builder                                             orWhereBetween($column, array $values)
 * @method \Illuminate\Database\Eloquent\Builder                                             orWhereNotBetween($column, array $values)
 * @method \Illuminate\Database\Eloquent\Builder                                             whereNotBetween($column, array $values, $boolean = 'and')
 * @method \Illuminate\Database\Eloquent\Builder                                             whereDate($column, $operator, $value = null, $boolean = 'and')
 * @method \Illuminate\Database\Eloquent\Builder                                             orWhereDate($column, $operator, $value = null)
 * @method \Illuminate\Database\Eloquent\Builder                                             groupBy(...$groups)
 * @method \Illuminate\Database\Eloquent\Builder                                             groupByRaw($sql, array $bindings = [])
 * @method \Illuminate\Database\Eloquent\Builder                                             having($column, $operator = null, $value = null, $boolean = 'and')
 * @method \Illuminate\Database\Eloquent\Builder                                             orderBy($column, $direction = 'asc')
 * @method \Illuminate\Database\Eloquent\Builder                                             orderByDesc($column)
 * @method \Illuminate\Database\Eloquent\Builder                                             union($query, $all = false)
 * @method \Illuminate\Database\Eloquent\Builder                                             unionAll($query)
 * @method bool                                                                              exists()
 * @method int                                                                               count($columns = '*')
 * @method \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|null find($id, $columns = ['*'])
 * @method \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection      findOrFail($id, $columns = ['*'])
 * @method \Illuminate\Database\Eloquent\Model|object                                        first($columns = ['*'])
 * @method \Illuminate\Database\Eloquent\Model                                               firstOrFail($columns = ['*'])
 * @method \Illuminate\Database\Eloquent\Collection                                          get($columns = ['*'])
 * @method \Illuminate\Support\Collection                                                    pluck($column, $key = null)
 * @method \Illuminate\Database\Eloquent\Builder                                             with($relations)
 * @method \Illuminate\Database\Eloquent\Collection                                          all($columns = ['*'])
 * @method \Illuminate\Database\Eloquent\Model                                               fresh($with = [])
 * @method \Illuminate\Database\Eloquent\Builder                                             when($value, $callback, $default = null)
 * @method \Illuminate\Database\Eloquent\Builder                                             select($columns = ['*'])
 * @method \Illuminate\Database\Eloquent\Builder                                             selectRaw($expression, array $bindings = [])
 * @method \Illuminate\Database\Eloquent\Builder                                             join($table, $first, $operator = null, $second = null, $type = 'inner', $where = false)
 * @method \Illuminate\Database\Eloquent\Builder                                             join($table, $first, $operator = null, $second = null)
 *
 * @see \Illuminate\Database\Eloquent\Builder
 * @see \Illuminate\Database\Eloquent\Model
 * @see \Illuminate\Database\Query\Builder
 */
abstract class BaseRepository
{
    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;

    /**
     * @var \App\Filters\Filter[]
     */
    protected $filters = [];

    /**
     * Constructor.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return void
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Redireciona a chamada de métodos inexistentes para o model.
     *
     * @param string $name
     * @param array  $arguments
     *
     * @return mixed
     */
    public function __call(string $name, array $arguments)
    {
        return \call_user_func_array([$this->model, $name], $arguments);
    }

    /**
     * Retorna uma instance do query builder do model.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(): Builder
    {
        return $this->model->newQuery();
    }

    /**
     * Create/Store.
     *
     * @param array $data
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function store(array $data): Model
    {
        $model = $this->model->newInstance();
        $model->fill($data);
        $model->save();

        return $model;
    }

    /**
     * Update.
     *
     * @param int   $id
     * @param array $data
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function update(int $id, array $data): Model
    {
        $model = $this->query()
                    ->findOrFail($id);
        $model->fill($data);
        $model->save();

        return $model;
    }

    /**
     * Delete/Destroy.
     *
     * @param int $id
     *
     * @return bool
     */
    public function destroy(int $id): bool
    {
        return $this->query()
                    ->findOrFail($id)
                    ->delete();
    }

    /**
     * Filtra a query com base nos filtros pré-definidos.
     *
     * @param array                          $request
     * @param \App\Filters\Filter|array|null $filters
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function filter(array $request, $filters = null)
    {
        $currentFilters = $this->filters;
        $query = $this->query();

        // Caso seja uma instancia da interface Filter, passa como array.
        if ($filters instanceof Filter) {
            return $this->filter([$filters]);
        } elseif (\is_array($filters)) {
            // Caso seja um array de filtros
            // Utiliza esses no lugar dos filtros da classe
            $currentFilters = $filters;
        }

        foreach ($currentFilters as $filter) {
            $filter->apply($request, $query);
        }

        return $query;
    }

    /**
     * Pagina os resultados de acordo com os dados fornecidos na request.
     * Opcionalmente recebe um objeto Builder para paginar os resultados.
     *
     * @param array                                                                                                        $request
     * @param \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder|\Illuminate\Support\Collection|null $items
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginate(array $request, $items = null)
    {
        $perPage = config('staffpro.api_default_pagination');

        if (!$items) {
            $items = $this->query();
        }

        if (\array_key_exists('per_page', $request)) {
            if (0 == $request['per_page']) {
                return $items;
            }

            $perPage = $request['per_page'];
        }

        if ($items instanceof \Illuminate\Database\Query\Builder ||
            $items instanceof \Illuminate\Database\Eloquent\Builder ||
            $items instanceof \Illuminate\Database\Eloquent\Relations\Relation
        ) {
            $items = $items->paginate($perPage);
        } elseif ($items instanceof \Illuminate\Support\Collection) {
            $total = $items->count();
            $page = $request['page'] ?? 1;
            $paginated = $items->forPage($page, $perPage);

            $items = new \Illuminate\Pagination\LengthAwarePaginator(
                $paginated,
                $total,
                $perPage,
                $page,
                ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()]
            );
        }

        return $items;
    }
}
