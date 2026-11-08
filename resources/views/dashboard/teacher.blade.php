@extends('layouts.app')
@section('title', 'Mon espace professeur')
@section('breadcrumb', 'Vue d’ensemble')
@section('content')
<div class="eyebrow">Votre espace professeur</div>
<div class="page-heading"><div><h1>Bonjour, {{ explode(' ', $authUser->name)[0] }} <span aria-hidden="true">✦</span></h1><p>Créez des cours qui donnent envie d’apprendre.</p></div><a class="button" href="{{ route('teacher.courses.create') }}"><span aria-hidden="true">＋</span> Créer un cours</a></div>
<section class="stats-grid" aria-label="Statistiques de vos cours">
    <article class="stat-card"><div class="stat-top"><span class="stat-label">Mes cours</span><span class="stat-icon">▤</span></div><div class="stat-value">{{ $publishedCount + $draftCount }}</div><div class="stat-note">Dans votre bibliothèque</div></article>
    <article class="stat-card"><div class="stat-top"><span class="stat-label">Publiés</span><span class="stat-icon">✓</span></div><div class="stat-value">{{ $publishedCount }}</div><div class="stat-note">Visibles par les étudiants</div></article>
    <article class="stat-card"><div class="stat-top"><span class="stat-label">Brouillons</span><span class="stat-icon">✎</span></div><div class="stat-value">{{ $draftCount }}</div><div class="stat-note">À terminer quand vous voulez</div></article>
    <article class="stat-card"><div class="stat-top"><span class="stat-label">Matières</span><span class="stat-icon">▦</span></div><div class="stat-value">{{ $subjectsCount }}</div><div class="stat-note">Pour classer vos cours</div></article>
</section>
<section class="panel"><div class="panel-header"><div><div class="panel-title">Vos cours récents</div><div class="panel-subtitle">Reprenez là où vous en étiez</div></div><a class="text-link" href="{{ route('teacher.courses.index') }}">Tous mes cours →</a></div><div class="panel-body">
    @forelse($courses as $course)<a class="course-row" href="{{ route('teacher.courses.edit', $course) }}"><span class="subject-tile {{ $loop->index % 2 ? 'blue-bg' : '' }}">▤</span><div class="course-info"><div class="course-title">{{ $course->title }}</div><div class="course-meta">{{ $course->subject->name }} · Mis à jour {{ $course->updated_at->diffForHumans() }}</div></div><span class="badge {{ $course->status }}"><span class="badge-dot"></span>{{ $course->status === 'published' ? 'Publié' : 'Brouillon' }}</span><span class="course-chevron">›</span></a>@empty<div class="empty-state">Vous n’avez pas encore créé de cours. <a class="text-link" href="{{ route('teacher.courses.create') }}">Créer votre premier cours →</a></div>@endforelse
</div></section>
@endsection
