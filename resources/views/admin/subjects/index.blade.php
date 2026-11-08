@extends('layouts.app')
@section('title', 'Matières')
@section('breadcrumb', 'Matières')
@section('content')
<div class="eyebrow">Administration</div>
<div class="page-heading"><div><h1>Matières</h1><p>Classez les cours pour aider les étudiants à découvrir ce qui les intéresse.</p></div></div>
<div class="grid-main">
    <section class="panel"><div class="panel-header"><div><div class="panel-title">Votre catalogue</div><div class="panel-subtitle">{{ $subjects->count() }} matières · {{ $subjects->sum('courses_count') }} cours associés</div></div></div>
        <div class="table-wrap"><table class="data-table"><thead><tr><th>Matière</th><th>Cours</th><th>Modifier</th><th></th></tr></thead><tbody>
            @forelse($subjects as $subject)<tr><td><span class="person-name">{{ $subject->name }}</span></td><td>{{ $subject->courses_count }}</td><td><form method="POST" action="{{ route('admin.subjects.update', $subject) }}" style="display:flex;gap:6px">@csrf @method('PUT')<label class="sr-only" for="subject-{{ $subject->id }}">Nom de la matière</label><input class="input" id="subject-{{ $subject->id }}" name="name" value="{{ $subject->name }}" maxlength="100" required style="min-height:32px;max-width:220px"><button class="button secondary small" type="submit">Enregistrer</button></form></td><td>@if($subject->courses_count === 0)<form method="POST" action="{{ route('admin.subjects.destroy', $subject) }}" data-confirm="Supprimer la matière « {{ $subject->name }} » ?">@csrf @method('DELETE')<button class="button danger small" type="submit" aria-label="Supprimer {{ $subject->name }}">Supprimer</button></form>@else<span class="course-meta">Contient des cours</span>@endif</td></tr>@empty<tr><td colspan="4"><div class="empty-state">Aucune matière pour le moment.</div></td></tr>@endforelse
        </tbody></table></div>
    </section>
    <section class="panel"><div class="panel-header"><div><div class="panel-title">Nouvelle matière</div><div class="panel-subtitle">Ajoutez une catégorie au catalogue</div></div></div><div class="panel-body" style="padding-top:16px">
        <form method="POST" action="{{ route('admin.subjects.store') }}">@csrf<div class="field"><label for="name">Nom de la matière</label><input class="input" type="text" name="name" id="name" value="{{ old('name') }}" maxlength="100" placeholder="Ex. Sciences naturelles" required>@error('name')<span class="field-error">{{ $message }}</span>@enderror</div><button class="button" type="submit">＋ Ajouter la matière</button></form>
        <div style="margin-top:22px;padding:12px;color:#71837c;background:#f5f8f6;border-radius:8px;font-size:10px;line-height:1.6">Une matière ne peut pas être supprimée tant qu’un cours y est associé. Modifiez d’abord les cours concernés.</div>
    </div></section>
</div>
@endsection
