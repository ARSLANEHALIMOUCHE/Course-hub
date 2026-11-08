<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'CourseHub') · CourseHub</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;750&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
<div class="app-shell">
    <aside class="sidebar" data-sidebar aria-label="Navigation principale">
        <a class="brand" href="{{ route('dashboard') }}"><span class="brand-mark">C</span> coursehub</a>
        <div class="nav-label">Espace {{ ['admin' => 'admin', 'teacher' => 'professeur', 'student' => 'étudiant'][$authUser->role] }}</div>
        @if($authUser->role === 'admin')
            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}"><span class="nav-icon">⌂</span>Vue d’ensemble</a>
            <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}"><span class="nav-icon">♙</span>Utilisateurs</a>
            <a class="nav-link {{ request()->routeIs('admin.subjects.*') ? 'active' : '' }}" href="{{ route('admin.subjects.index') }}"><span class="nav-icon">▦</span>Matières</a>
        @elseif($authUser->role === 'teacher')
            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}"><span class="nav-icon">⌂</span>Vue d’ensemble</a>
            <a class="nav-link {{ request()->routeIs('teacher.courses.*') ? 'active' : '' }}" href="{{ route('teacher.courses.index') }}"><span class="nav-icon">▤</span>Mes cours</a>
            <a class="nav-link" href="{{ route('teacher.courses.create') }}"><span class="nav-icon">＋</span>Créer un cours</a>
        @else
            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}"><span class="nav-icon">⌂</span>Vue d’ensemble</a>
            <a class="nav-link {{ request()->routeIs('student.courses.*') ? 'active' : '' }}" href="{{ route('student.courses.index') }}"><span class="nav-icon">▤</span>Explorer les cours</a>
        @endif
        <div class="sidebar-bottom">
            <div class="nav-label">Votre espace</div>
            <form method="POST" action="{{ route('logout') }}">@csrf<button class="nav-link" type="submit" style="width:100%;border:0;cursor:pointer;text-align:left;background:transparent"><span class="nav-icon">↪</span>Se déconnecter</button></form>
            <div class="user-mini">
                <span class="avatar">{{ collect(explode(' ', $authUser->name))->map(fn ($part) => mb_substr($part, 0, 1))->take(2)->join('') }}</span>
                <div><div class="mini-name">{{ $authUser->name }}</div><div class="mini-role">{{ ['admin' => 'Administrateur', 'teacher' => 'Professeur', 'student' => 'Étudiant'][$authUser->role] }}</div></div>
            </div>
        </div>
    </aside>
    <main class="workspace">
        <header class="topbar">
            <button type="button" class="icon-button mobile-menu" data-menu-toggle aria-label="Ouvrir le menu">☰</button>
            <div class="breadcrumb"><strong>CourseHub</strong><span aria-hidden="true">　/　</span>@yield('breadcrumb', 'Accueil')</div>
            <div class="top-actions"><span class="avatar" aria-hidden="true">{{ collect(explode(' ', $authUser->name))->map(fn ($part) => mb_substr($part, 0, 1))->take(2)->join('') }}</span></div>
        </header>
        <div class="page">
            @if(session('success'))<div class="flash" role="status">{{ session('success') }}</div>@endif
            @if($errors->any())<div class="alert" role="alert">@foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach</div>@endif
            @yield('content')
        </div>
    </main>
</div>
@stack('scripts')
</body>
</html>
