<?php

namespace Tests\Feature;

use App\Services\ModuleManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModuleManagerTest extends TestCase
{
    use RefreshDatabase;

    public function test_modules_are_listed_from_config(): void
    {
        $modules = app(ModuleManager::class)->all();

        $this->assertTrue($modules->contains(fn (array $module) => $module['key'] === 'appointments'));
        $this->assertTrue(app(ModuleManager::class)->enabled('reception_ai'));
    }

    public function test_marketing_home_loads(): void
    {
        $this->get('/')->assertOk();
        $this->get('/features')->assertOk();
        $this->get('/modules')->assertOk();
        $this->get('/pricing')->assertOk();
        $this->get('/demo')->assertOk();
    }
}
