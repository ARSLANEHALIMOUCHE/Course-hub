@extends('layouts.app')
@section('title', 'Utilisateurs')
@section('breadcrumb', 'Utilisateurs')
@section('content')
<div class="eyebrow">Administration</div>
<div class="page-heading"><div><h1>Utilisateurs</h1><p>Gérez les comptes et les accès de votre communauté.</p></div><a class="button" href="{{ route('admin.users.create') }}"><span aria-hidden="true">＋</span> Ajouter un utilisateur</a></div>
<section class="panel">
    <div class="panel-header"><div><div class="panel-title">Tous les comptes</div><div class="panel-subtitle" data-result-count>{{ $users->total() }} utilisateurs</div></div></div>
    <div class="panel-body" style="padding-top:15px">
        <div class="toolbar"><label class="search-field"><span class="sr-only"></span><input data-filter-search type="search" placeholder="Rechercher un nom ou un e-mail…" aria-label="Rechercher un nom ou un e-mail"></label><select class="select-field" data-filter-role aria-label="Filtrer par rôle"><option value="">Tous les rôles</option><option value="admin" @selected(request('role') === 'admin')>Administrateur</option><option value="teacher" @selected(request('role') === 'teacher')>Professeur</option><option value="student" @selected(request('role') === 'student')>Étudiant</option></select></div>
    </div>
    <div class="table-wrap"><table class="data-table" data-filter-table><thead><tr><th>Utilisateur</th><th>Rôle</th><th>Statut</th><th>Inscription</th><th>Actions</th></tr></thead><tbody>
        @forelse($users as $member)
            <tr data-role="{{ $member->role }}" data-search="{{ $member->name }} {{ $member->email }}">
                <td><div class="table-person"><span class="avatar {{ $loop->index % 3 === 1 ? 'peach' : ($loop->index % 3 === 2 ? 'blue' : '') }}">{{ collect(explode(' ', $member->name))->map(fn ($part) => mb_substr($part, 0, 1))->take(2)->join('') }}</span><div><div class="person-name">{{ $member->name }}</div><div class="person-email">{{ $member->email }}</div></div></div></td>
                <td>{{ ['admin' => 'Administrateur', 'teacher' => 'Professeur', 'student' => 'Étudiant'][$member->role] }}</td>
                <td><span class="badge {{ $member->is_active ? 'active' : 'inactive' }}"><span class="badge-dot"></span>{{ $member->is_active ? 'Actif' : 'Inactif' }}</span></td>
                <td>{{ $member->created_at->format('d/m/Y') }}</td>
                <td><div class="table-actions">@if($member->id !== $authUser->id)<a class="button secondary small" href="{{ route('admin.users.edit', $member) }}" aria-label="Modifier {{ $member->name }}">Modifier</a><form method="POST" action="{{ route('admin.users.toggle', $member) }}">@csrf @method('PATCH')<button class="button {{ $member->is_active ? 'danger' : 'secondary' }} small" type="submit">{{ $member->is_active ? 'Désactiver' : 'Activer' }}</button></form>@else<span class="course-meta">Votre compte</span>@endif</div></td>
            </tr>
        @empty<tr data-filter-empty><td colspan="5"><div class="empty-state">Aucun utilisateur ne correspond à ces critères.</div></td></tr>@endforelse
    </tbody></table></div>
    <div class="pagination"><span>{{ $users->firstItem() ?? 0 }}–{{ $users->lastItem() ?? 0 }} sur {{ $users->total() }} utilisateurs</span><div class="pagination-controls" data-pagination aria-label="Pagination dynamique"></div>{{ $users->links('pagination::simple-tailwind') }}</div>
</section>
@endsection
