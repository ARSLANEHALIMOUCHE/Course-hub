@extends('layouts.app')
@section('title', $course->title)
@section('breadcrumb', 'Explorer les cours')
@section('content')
<div class="eyebrow"><a class="text-link" href="{{ route('student.courses.index') }}">Explorer les cours</a>　/　{{ $course->subject->name }}</div>
<div class="page-heading" style="align-items:flex-start"><div><span class="badge active">{{ $course->subject->name }}</span><h1 style="margin-top:12px">{{ $course->title }}</h1><p>{{ $course->description }}</p><div class="course-meta" style="margin-top:12px">Par {{ $course->teacher->name }} · Publié {{ $course->published_at?->translatedFormat('d F Y') }}</div></div><a class="button secondary" href="{{ route('student.courses.index') }}">← Tous les cours</a></div>
<div class="grid-main">
    <article class="panel"><div class="panel-header"><div><div class="panel-title">À propos de ce cours</div><div class="panel-subtitle">Prenez votre temps, apprenez à votre rythme</div></div><span class="subject-tile blue-bg" aria-hidden="true">▤</span></div><div class="course-content">{{ $course->content }}</div></article>
    <aside class="panel"><div class="panel-header"><div><div class="panel-title">Ressources du cours</div><div class="panel-subtitle">{{ $course->files->count() }} fichier(s) à télécharger</div></div></div><div class="panel-body">
        @forelse($course->files as $file)<a class="file-link" href="{{ route('courses.files.download', [$course, $file]) }}"><span aria-hidden="true">▧</span><span style="flex:1;overflow:hidden;text-overflow:ellipsis">{{ $file->original_name }}</span><span class="text-link">↓</span></a>@empty<div class="empty-state" style="padding:24px 4px">Aucun document joint à ce cours.</div>@endforelse
        <div style="margin-top:17px;padding:12px;color:#71837c;background:#f5f8f6;border-radius:8px;font-size:10px;line-height:1.6">Un document vous intéresse ? Sélectionnez son nom pour le télécharger.</div>
    </div></aside>
</div>
@endsection
