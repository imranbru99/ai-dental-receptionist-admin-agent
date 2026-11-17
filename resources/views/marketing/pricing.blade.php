@extends('layouts.marketing')

@section('title', 'Pricing — AI Dental Receptionist')
@section('meta_description', 'Clinic pricing for AI dental receptionist and admin automation — starter, growth, and multi-location plans.')

@section('content')
<section class="hero">
    <span class="pill">Pricing</span>
    <h1>Clear plans for single offices and DSOs</h1>
    <p>ROI starts when the phone stops stealing hygienist time. Most clinics recover the subscription in under two weeks of front-desk hours saved.</p>
</section>

<section class="section">
    <div class="grid">
        <article class="panel">
            <h3>Starter</h3>
            <p style="font-size:2rem;font-weight:700;margin:0.4rem 0;">$199<span style="font-size:1rem;color:var(--muted);">/mo</span></p>
            <p class="muted">1 location · AI receptionist · appointments · reminders</p>
        </article>
        <article class="panel" style="border-color:var(--accent);">
            <span class="pill">Most clinics</span>
            <h3>Growth</h3>
            <p style="font-size:2rem;font-weight:700;margin:0.4rem 0;">$449<span style="font-size:1rem;color:var(--muted);">/mo</span></p>
            <p class="muted">Insurance checks · billing estimates · intake · priority support</p>
        </article>
        <article class="panel">
            <h3>Enterprise / DSO</h3>
            <p style="font-size:2rem;font-weight:700;margin:0.4rem 0;">Custom</p>
            <p class="muted">Multi-location modules, SSO, SLA, and custom payer workflows</p>
        </article>
    </div>
</section>
@endsection
