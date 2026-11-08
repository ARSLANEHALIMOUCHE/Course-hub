@extends('layouts.app')
@section('title', 'Explorer les cours')
@section('breadcrumb', 'Explorer les cours')
@section('content')
<div class="eyebrow">Votre bibliothèque</div>
<div class="page-heading"><div><h1>Explorer les cours</h1><p>Choisissez une matière, suivez votre curiosité et apprenez à votre rythme.</p></div><span class="badge active">{{ $courses->total() }} cours disponibles</span></div>
<section class="panel">
    <div class="panel-body" style="padding-top:16px">
        <form method="GET" class="toolbar" role="search">
            <label class="search-field"><input data-course-search type="search" name="search" value="{{ request('search') }}" placeholder="Un sujet, un mot-clé…" aria-label="Rechercher un cours par mot-clé"></label>
            <select class="select-field" name="subject" aria-label="Filtrer par matière"><option value="">Toutes les matières</option>@foreach($subjects as $subject)<option value="{{ $subject->id }}" @selected((string)$selectedSubject === (string)$subject->id)>{{ $subject->name }}</option>@endforeach</select>
            <button class="button secondary small" type="submit">Rechercher</button>
        </form>
        @if($subjects->isNotEmpty())<div class="subject-grid" style="padding:3px 0 16px">@foreach($subjects as $subject)<a class="subject-chip" href="{{ route('student.courses.index', array_filter(['subject' => $subject->id, 'search' => request('search')])) }}"><span aria-hidden="true">{{ ['🌱','📐','🏛️','🌍'][$loop->index % 4] }}</span><span>{{ $subject->name }}</span></a>@endforeach</div>@endif
    </div>
</section>
<div class="eyebrow" style="margin:23px 0 12px">Cours à découvrir</div>
<section class="stats-grid" style="grid-template-columns:repeat(auto-fit,minmax(245px,1fr));align-items:stretch">
    @forelse($courses as $course)
        <article class="course-card" data-course-card style="padding:17px">
            <div style="display:flex;align-items:center;justify-content:space-between"><span class="badge active">{{ $course->subject->name }}</span><span class="course-meta">{{ $course->published_at?->diffForHumans() }}</span></div>
            <a href="{{ route('student.courses.show', $course) }}"><h2 class="course-title" style="margin:17px 0 7px;font-size:15px;white-space:normal;line-height:1.45">{{ $course->title }}</h2></a>
            <p style="min-height:45px;margin:0;color:#7a8c85;font-size:11px;line-height:1.65">{{ \Illuminate\Support\Str::limit($course->description, 105) }}</p>
            <div style="display:flex;align-items:center;justify-content:space-between;margin-top:15px;padding-top:12px;border-top:1px solid #eff2f0"><span class="course-meta">Par {{ $course->teacher->name }}</span><a class="text-link" href="{{ route('student.courses.show', $course) }}">Découvrir →</a></div>
        </article>
    @empty<div class="panel" style="grid-column:1/-1"><div class="empty-state">Aucun cours ne correspond à votre recherche. Essayez un autre mot-clé ou une autre matière.</div></div>@endforelse
</section>
@if($courses->hasPages())<div class="pagination"><span>{{ $courses->firstItem() }}–{{ $courses->lastItem() }} sur {{ $courses->total() }} cours</span>{{ $courses->links('pagination::simple-tailwind') }}</div>@endif
@endsection
