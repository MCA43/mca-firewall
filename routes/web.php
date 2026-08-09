<?php

use Illuminate\Support\Facades\Route;

$web = config('firewall.routes.web', []);
$prefix = $web['prefix'] ?? 'mca/firewall';
$middleware = $web['middleware'] ?? ['web', 'auth', 'mca.firewall.root', 'mca.firewall.locale'];
$namePrefix = config('firewall.routes.web.name_prefix', 'mca.firewall.');
$controllers = config('firewall.controllers.web', []);
$rules = $controllers['rules'] ?? \Mca\Firewall\Http\Controllers\Web\RuleController::class;

Route::prefix($prefix)
    ->middleware($middleware)
    ->name($namePrefix)
    ->group(function () use ($rules) {
        Route::get('/', [$rules, 'index'])->name('index');
        Route::get('/create', [$rules, 'create'])->name('create');
        Route::post('/', [$rules, 'store'])->name('store');
        Route::get('/trash', [$rules, 'trash'])->name('trash');
        Route::get('/{rule}/edit', [$rules, 'edit'])->name('edit');
        Route::put('/{rule}', [$rules, 'update'])->name('update');
        Route::delete('/{rule}', [$rules, 'destroy'])->name('destroy');
        Route::post('/{rule}/toggle', [$rules, 'toggle'])->name('toggle');
        Route::post('/trash/{rule}/restore', [$rules, 'restore'])->name('restore');
        Route::delete('/trash/{rule}', [$rules, 'forceDestroy'])->name('force-destroy');
    });
