@extends('layouts.app')
@section('title', 'Mes cours')
@section('breadcrumb', 'Mes cours')
@section('content')
<div class="eyebrow">Espace professeur</div>
<div class="page-heading"><div><h1>Mes cours</h1><p>Créez, organisez et partagez vos cours avec vos étudiants.</p></div><a class="button" href="{{ route('teacher.courses.create') }}"><span aria-hidden="true">＋</span> Créer un cours</a></div>
<section class="panel">
    <div class="panel-header"><div><div class="panel-title">Votre bibliothèque</div><div class="panel-subtitle">{{ $courses->total() }} cours</div></div></div>
    <div class="panel-body" style="padding-top:15px"><form method="GET" class="toolbar"><label class="search-field"><input type="search" name="search" value="{{ request('search') }}" placeholder="Rechercher un cours…" aria-label="Rechercher un cours"></label><select class="select-field" name="status" aria-label="Filtrer par statut"><option value="">Tous les statuts</option><option value="published" @selected(request('status') === 'published')>Publiés</option><option value="draft" @selected(request('status') === 'draft')>Brouillons</option></select><button class="button secondary small" type="submit">Filtrer</button></form></div>
    <div class="table-wrap"><table class="data-table"><thead><tr><th>Cours</th><th>Matière</th><th>Statut</th><th>Modifié</th><th>Actions</th></tr></thead><tbody>
        @forelse($courses as $course)<tr><td><div class="person-name">{{ $course->title }}</div><div class="person-email">{{ \Illuminate\Support\Str::limit($course->description, 58) }}</div></td><td>{{ $course->subject->name }}</td><td><span class="badge {{ $course->status }}"><span class="badge-dot"></span>{{ $course->status === 'published' ? 'Publié' : 'Brouillon' }}</span></td><td>{{ $course->updated_at->diffForHumans() }}</td><td><div class="table-actions"><a class="button secondary small" href="{{ route('teacher.courses.edit', $course) }}">Modifier</a><form method="POST" action="{{ route('teacher.courses.status', $course) }}">@csrf @method('PATCH')<button class="button secondary small" type="submit">{{ $course->status === 'published' ? 'Dépublier' : 'Publier' }}</button></form><form method="POST" action="{{ route('teacher.courses.destroy', $course) }}" data-confirm="Supprimer « {{ $course->title }} » et tous ses fichiers joints ?">@csrf @method('DELETE')<button class="button danger small" type="submit" aria-label="Supprimer {{ $course->title }}">Supprimer</button></form></div></td></tr>@empty<tr><td colspan="5"><div class="empty-state">Aucun cours trouvé. <a class="text-link" href="{{ route('teacher.courses.create') }}">Créer un cours →</a></div></td></tr>@endforelse
    </tbody></table></div>
    <div class="pagination"><span>{{ $courses->firstItem() ?? 0 }}–{{ $courses->lastItem() ?? 0 }} sur {{ $courses->total() }} cours</span>{{ $courses->links('pagination::simple-tailwind') }}</div>
</section>
@endsection
