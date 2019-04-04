<?php

namespace App\Http\Middleware;

use Symfony\Component\HttpFoundation\ParameterBag;
use Closure;

class TrimString
{
    /**
     * @var array
     */
    protected $except = array();

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $this->clean($request);
        return $next($request);
    }

    /**
     *
     * @return void
     */
    protected function clean($request)
    {
        $this->cleanParameterBag($request->query);

        if ($request->isJson()) {
            $this->cleanParameterBag($request->json());
        } else if ($request->request !== $request->query) {
            $this->cleanParameterBag($request->request);
        }
    }

    /**
     *
     * @return void
     */
    protected function cleanParameterBag(ParameterBag $bag)
    {
        $bag->replace($this->cleanArray($bag->all()));
    }

    /**
     *
     * @return array
     */
    protected function cleanArray(array $data)
    {
        return collect($data)->map(function($value, $key) {
            return $this->cleanValue($key, $value);
        })->all();
    }

    /**
     *
     * @return mixed
     */
    protected function cleanValue($key, $value)
    {
        if (is_array($value)) {
            return $this->cleanArray($value);
        }

        if (in_array($key, $this->except, true)) {
            return $value;
        }

        return is_string($value) ? trim($value) : $value;
    }
}
