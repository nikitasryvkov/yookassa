<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Проверяем, авторизован ли пользователь и является ли он администратором
        if (auth()->check() && auth()->user()->role_id === 1) {
            return $next($request); // Пользователь администратор, продолжаем
        }

        // Если пользователь не администратор, перенаправляем или возвращаем ошибку
        abort(403, 'Access denied');
    }
}
