@extends('layouts.marketing')

@section('title', 'Modules — AI Dental Receptionist')
@section('meta_description', 'Toggleable dental clinic modules for appointments, patients, insurance, AI receptionist, billing, reminders, and staff attendance.')

@section('content')
<section class="hero">
    <span class="pill">Modular architecture</span>
    <h1>Run your clinic stack your way</h1>
    <p>Enable the modules that match your business. Disable the rest without ripping out code paths.</p>
</section>

<section class="section">
    <div class="grid">
        @foreach($modules as $module)
            <article class="panel">
                <div style="display:flex;justify-content:space-between;gap:0.75rem;align-items:center;">
                    <h3 style="margin:0;font-family:Fraunces,Georgia,serif;">{{ $module['name'] }}</h3>
                    <span class="pill">{{ $module['enabled'] ? 'ON' : 'OFF' }}</span>
                </div>
                <p class="muted">Key: <code>{{ $module['key'] }}</code></p>
                <p class="muted" style="margin-bottom:0;">{{ $module['description'] }}</p>
            </article>
        @endforeach
    </div>
</section>
@endsection
