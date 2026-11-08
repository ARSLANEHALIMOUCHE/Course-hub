@extends('layouts.app')
@section('title', $user->exists ? 'Modifier un utilisateur' : 'Ajouter un utilisateur')
@section('breadcrumb', 'Utilisateurs')
@section('content')
<div class="eyebrow"><a class="text-link" href="{{ route('admin.users.index') }}">Utilisateurs</a>　/　{{ $user->exists ? 'Modifier un compte' : 'Nouveau compte' }}</div>
<div class="page-heading"><div><h1>{{ $user->exists ? 'Modifier le compte' : 'Ajouter un utilisateur' }}</h1><p>Les rôles professeur et administrateur sont attribués par un administrateur.</p></div></div>
<section class="panel" style="max-width:680px"><div class="panel-body" style="padding:22px">
    <form method="POST" action="{{ $user->exists ? route('admin.users.update', $user) : route('admin.users.store') }}">
        @csrf @if($user->exists) @method('PUT') @endif
        <div class="field"><label for="name">Nom complet</label><input class="input" type="text" name="name" id="name" value="{{ old('name', $user->name) }}" maxlength="120" required>@error('name')<span class="field-error">{{ $message }}</span>@enderror</div>
        <div class="field"><label for="email">Adresse e-mail</label><input class="input" type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required>@error('email')<span class="field-error">{{ $message }}</span>@enderror</div>
        <div class="field"><label for="role">Rôle</label><select class="input" name="role" id="role" required><option value="student" @selected(old('role', $user->role ?: 'student') === 'student')>Étudiant</option><option value="teacher" @selected(old('role', $user->role) === 'teacher')>Professeur</option><option value="admin" @selected(old('role', $user->role) === 'admin')>Administrateur</option></select>@error('role')<span class="field-error">{{ $message }}</span>@enderror</div>
        <div class="field"><label for="password">Mot de passe {{ $user->exists ? '(laisser vide pour le conserver)' : '' }}</label><input class="input" type="password" name="password" id="password" {{ $user->exists ? '' : 'required' }} minlength="8" autocomplete="new-password">@error('password')<span class="field-error">{{ $message }}</span>@enderror</div>
        @if($user->exists)<div class="field"><label style="display:flex;align-items:center;gap:8px"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $user->is_active))> Compte actif — autoriser la connexion</label></div>@else<input type="hidden" name="is_active" value="1">@endif
        <div style="display:flex;gap:9px;margin-top:22px"><button class="button" type="submit">{{ $user->exists ? 'Enregistrer les modifications' : 'Créer le compte' }}</button><a class="button secondary" href="{{ route('admin.users.index') }}">Annuler</a></div>
    </form>
</div></section>
@endsection
