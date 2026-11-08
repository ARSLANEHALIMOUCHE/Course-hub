@extends('layouts.app')
@section('title', $course->exists ? 'Modifier un cours' : 'Créer un cours')
@section('breadcrumb', 'Mes cours')
@section('content')
<div class="eyebrow"><a class="text-link" href="{{ route('teacher.courses.index') }}">Mes cours</a>　/　{{ $course->exists ? 'Modifier le cours' : 'Nouveau cours' }}</div>
<div class="page-heading"><div><h1>{{ $course->exists ? 'Modifier votre cours' : 'Créer un cours' }}</h1><p>Préparez une leçon claire, puis publiez-la lorsque vous êtes prêt·e.</p></div></div>
<section class="panel" style="max-width:790px"><div class="panel-body" style="padding:22px">
    <form method="POST" action="{{ $course->exists ? route('teacher.courses.update', $course) : route('teacher.courses.store') }}" enctype="multipart/form-data">
        @csrf @if($course->exists) @method('PUT') @endif
        <div class="field"><label for="title">Titre du cours</label><input class="input" type="text" id="title" name="title" value="{{ old('title', $course->title) }}" maxlength="180" required placeholder="Ex. Les secrets du système solaire">@error('title')<span class="field-error">{{ $message }}</span>@enderror</div>
        <div class="field"><label for="subject_id">Matière</label><select class="input" id="subject_id" name="subject_id" required><option value="">Choisir une matière</option>@foreach($subjects as $subject)<option value="{{ $subject->id }}" @selected((string)old('subject_id', $course->subject_id) === (string)$subject->id)>{{ $subject->name }}</option>@endforeach</select>@error('subject_id')<span class="field-error">{{ $message }}</span>@enderror</div>
        <div class="field"><label for="description">Description courte</label><textarea class="textarea" id="description" name="description" maxlength="500" required placeholder="Présentez en quelques mots ce que les étudiants vont découvrir.">{{ old('description', $course->description) }}</textarea><span class="course-meta">500 caractères maximum.</span>@error('description')<span class="field-error">{{ $message }}</span>@enderror</div>
        <div class="field"><label for="content">Contenu du cours</label><textarea class="textarea" id="content" name="content" maxlength="50000" required placeholder="Rédigez le contenu de votre leçon…">{{ old('content', $course->content) }}</textarea>@error('content')<span class="field-error">{{ $message }}</span>@enderror</div>
        @if($course->exists && $course->files->isNotEmpty())
            <div class="field"><label>Fichiers joints</label>@foreach($course->files as $file)<div class="file-link"><span aria-hidden="true">▧</span><span style="flex:1">{{ $file->original_name }}</span><a class="text-link" href="{{ route('courses.files.download', [$course, $file]) }}">Télécharger</a><form method="POST" action="{{ route('teacher.courses.files.destroy', [$course, $file]) }}" data-confirm="Supprimer ce fichier joint ?">@csrf @method('DELETE')<button class="button danger small" type="submit">Supprimer</button></form></div>@endforeach</div>
        @endif
        <div class="field"><label for="files">Ajouter des fichiers PDF</label><input class="input" type="file" id="files" name="files[]" accept="application/pdf,.pdf" multiple style="height:auto;padding:10px"><span class="course-meta">PDF uniquement · 10 Mo maximum par fichier · 10 fichiers maximum par ajout.</span>@error('files')<span class="field-error">{{ $message }}</span>@enderror @error('files.*')<span class="field-error">{{ $message }}</span>@enderror</div>
        <div class="field"><label for="status">Publication</label><select class="input" id="status" name="status" required><option value="draft" @selected(old('status', $course->status ?: 'draft') === 'draft')>Enregistrer comme brouillon</option><option value="published" @selected(old('status', $course->status) === 'published')>Publier le cours</option></select><span class="course-meta">Les étudiants ne voient que les cours publiés.</span>@error('status')<span class="field-error">{{ $message }}</span>@enderror</div>
        <div style="display:flex;flex-wrap:wrap;gap:9px;margin-top:22px"><button class="button" type="submit">{{ $course->exists ? 'Enregistrer les modifications' : 'Créer le cours' }}</button><a class="button secondary" href="{{ route('teacher.courses.index') }}">Annuler</a></div>
    </form>
</div></section>
@endsection
