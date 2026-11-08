@extends('layouts.app')
@section('title', 'Mon espace étudiant')
@section('breadcrumb', 'Vue d’ensemble')
@section('content')
<div class="eyebrow">Votre espace étudiant</div>
<div class="page-heading"><div><h1>Bonjour, {{ explode(' ', $authUser->name)[0] }} <span aria-hidden="true">✦</span></h1><p>Une petite découverte aujourd’hui ? Votre prochain cours vous attend.</p></div><a class="button" href="{{ route('student.courses.index') }}">Explorer les cours <span aria-hidden="true">→</span></a></div>
<section class="stats-grid" aria-label="Bibliothèque de cours">
    <article class="stat-card"><div class="stat-top"><span class="stat-label">Cours à découvrir</span><span class="stat-icon">▤</span></div><div class="stat-value">{{ $publishedCount }}</div><div class="stat-note">Cours publiés par vos professeurs</div></article>
    <article class="stat-card"><div class="stat-top"><span class="stat-label">Matières</span><span class="stat-icon">▦</span></div><div class="stat-value">{{ $subjectsCount }}</div><div class="stat-note">Autant de chemins à explorer</div></article>
    <article class="stat-card"><div class="stat-top"><span class="stat-label">Votre prochaine étape</span><span class="stat-icon">✦</span></div><div class="stat-value" style="font-size:19px">À vous de choisir</div><div class="stat-note">Apprenez à votre rythme</div></article>
    <article class="stat-card" style="background:#edf5ee"><div class="stat-top"><span class="stat-label">Le saviez-vous ?</span><span class="stat-icon">☀</span></div><div class="stat-value" style="font-size:15px;line-height:1.35">La curiosité est un super-pouvoir.</div><div class="stat-note">Explorez une nouvelle matière aujourd’hui.</div></article>
</section>
<section class="panel"><div class="panel-header"><div><div class="panel-title">Fraîchement publiés</div><div class="panel-subtitle">Les dernières leçons ajoutées à votre bibliothèque</div></div><a class="text-link" href="{{ route('student.courses.index') }}">Voir tous les cours →</a></div><div class="panel-body">
    @forelse($courses as $course)<a class="course-row" href="{{ route('student.courses.show', $course) }}"><span class="subject-tile {{ ['blue-bg', 'peach-bg', 'lilac-bg'][$loop->index % 3] }}">▤</span><div class="course-info"><div class="course-title">{{ $course->title }}</div><div class="course-meta">{{ $course->subject->name }} · Par {{ $course->teacher->name }}</div></div><span class="course-meta">{{ $course->published_at?->diffForHumans() }}</span><span class="course-chevron">›</span></a>@empty<div class="empty-state">Aucun cours n’a encore été publié.</div>@endforelse
</div></section>
@endsection
