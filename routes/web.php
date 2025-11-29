<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

// Importaciones de tus componentes existentes
use App\Livewire\Products\Create;
use App\Livewire\Products\Index;
use App\Livewire\Products\Update;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\TwoFactor;

// --- NUEVAS IMPORTACIONES (Préstamos e Inventario) ---
use App\Livewire\LoanForm;              // Formulario para estudiantes
use App\Livewire\Admin\EquipmentManager; // CRUD de Equipos (Admin)
use App\Livewire\Admin\LoanManager;      // Gestión de Devoluciones (Admin)

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Grupo de rutas protegidas (Requieren Login)
Route::middleware(['auth'])->group(function () {
    
    // --- RUTAS DE CONFIGURACIÓN DE USUARIO (Existentes) ---
    Route::redirect('settings', 'settings/profile');
    Route::get('settings/profile', Profile::class)->name('profile.edit');
    Route::get('settings/password', Password::class)->name('user-password.edit');
    Route::get('settings/appearance', Appearance::class)->name('appearance.edit');
    
    Route::get('settings/two-factor', TwoFactor::class)
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');

    // --- NUEVAS RUTAS DEL SISTEMA DE PRÉSTAMOS ---

    // 1. Vista Estudiante: Solicitar Préstamo
    Route::get('/solicitar-equipo', LoanForm::class)->name('loans.create');

    // 2. Vistas Administrador (Agrupadas)
    // Las URLs serán: /admin/inventario y /admin/prestamos
    Route::prefix('admin')->name('admin.')->group(function () {
        
        // Gestión de Inventario (Equipos)
        Route::get('/inventario', EquipmentManager::class)->name('equipments');

        // Gestión de Préstamos y Devoluciones
        Route::get('/prestamos', LoanManager::class)->name('loans');
    });

});

// --- RUTAS DE PRODUCTOS (Existentes) ---
// Nota: Si estos productos son parte de la administración, 
// deberías moverlos dentro del middleware ['auth'] también.
Route::get('products', Index::class)->name('products.index');
Route::get('products/create', Create::class)->name('products.create');
Route::get('products/{product}/edit', Update::class)->name('products.edit');