@extends('layouts.app')
@section('title', 'Vue d’ensemble')
@section('breadcrumb', 'Vue d’ensemble')
@section('content')
<div class="eyebrow">{{ now()->translatedFormat('l, d F Y') }}</div>
<div class="page-heading"><div><h1>Bonjour, {{ explode(' ', $authUser->name)[0] }} <span aria-hidden="true">✦</span></h1><p>Voici ce qui se passe sur votre plateforme aujourd’hui.</p></div><a class="button" href="{{ route('admin.users.create') }}"><span aria-hidden="true">＋</span> Ajouter un utilisateur</a></div>
<section class="stats-grid" aria-label="Statistiques de la plateforme">
    <article class="stat-card"><div class="stat-top"><span class="stat-label">Utilisateurs</span><span class="stat-icon">♙</span></div><div class="stat-value">{{ $usersCount }}</div><div class="stat-note"><span class="positive">{{ $teacherCount }} professeurs</span> · {{ $studentCount }} étudiants</div></article>
    <article class="stat-card"><div class="stat-top"><span class="stat-label">Cours publiés</span><span class="stat-icon">▤</span></div><div class="stat-value">{{ $publishedCount }}</div><div class="stat-note">Disponibles aux étudiants</div></article>
    <article class="stat-card"><div class="stat-top"><span class="stat-label">Brouillons</span><span class="stat-icon">✎</span></div><div class="stat-value">{{ $draftCount }}</div><div class="stat-note">En préparation</div></article>
    <article class="stat-card"><div class="stat-top"><span class="stat-label">Matières</span><span class="stat-icon">▦</span></div><div class="stat-value">{{ \App\Models\Subject::count() }}</div><div class="stat-note"><a class="text-link" href="{{ route('admin.subjects.index') }}">Gérer les matières →</a></div></article>
</section>
<div class="grid-main">
    <section class="panel"><div class="panel-header"><div><div class="panel-title">Comptes récemment ajoutés</div><div class="panel-subtitle">Les derniers membres de votre communauté</div></div><a class="text-link" href="{{ route('admin.users.index') }}">Tous les utilisateurs →</a></div><div class="panel-body">
        @forelse($latestUsers as $member)<div class="course-row"><span class="avatar {{ $loop->index % 2 ? 'peach' : '' }}">{{ collect(explode(' ', $member->name))->map(fn ($part) => mb_substr($part, 0, 1))->take(2)->join('') }}</span><div class="course-info"><div class="course-title">{{ $member->name }}</div><div class="course-meta">{{ $member->email }} · {{ ['admin' => 'Administrateur', 'teacher' => 'Professeur', 'student' => 'Étudiant'][$member->role] }}</div></div><span class="badge {{ $member->is_active ? 'active' : 'inactive' }}"><span class="badge-dot"></span>{{ $member->is_active ? 'Actif' : 'Inactif' }}</span></div>@empty<div class="empty-state">Aucun utilisateur pour le moment.</div>@endforelse
    </div></section>
    <section class="panel"><div class="panel-header"><div><div class="panel-title">Vue par rôle</div><div class="panel-subtitle">La communauté CourseHub</div></div></div><div class="panel-body">
        <div class="course-row"><span class="subject-tile">✦</span><div class="course-info"><div class="course-title">Administrateurs</div><div class="course-meta">Gestion de la plateforme</div></div><strong>{{ \App\Models\User::where('role', 'admin')->count() }}</strong></div>
        <div class="course-row"><span class="subject-tile blue-bg">◈</span><div class="course-info"><div class="course-title">Professeurs</div><div class="course-meta">Créent et partagent des cours</div></div><strong>{{ $teacherCount }}</strong></div>
        <div class="course-row"><span class="subject-tile peach-bg">♙</span><div class="course-info"><div class="course-title">Étudiants</div><div class="course-meta">Apprennent à leur rythme</div></div><strong>{{ $studentCount }}</strong></div>
        <a class="button secondary" href="{{ route('admin.subjects.index') }}" style="width:100%;margin-top:12px">Gérer les matières <span aria-hidden="true">→</span></a>
    </div></section>
</div>
@endsection
