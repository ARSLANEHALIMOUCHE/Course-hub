<!DOCTYPE html>
<html lang="fr"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Créer un compte · CourseHub</title><link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;750&display=swap" rel="stylesheet">@vite(['resources/css/app.css','resources/js/app.js'])</head>
<body><main class="auth-page"><section class="auth-card" aria-labelledby="register-title">
    <a class="brand" href="{{ route('login') }}"><span class="brand-mark">C</span> coursehub</a>
    <h1 id="register-title">Rejoignez CourseHub</h1><p>Créez un compte étudiant gratuit et explorez les cours partagés par vos professeurs.</p>
    @if($errors->any())<div class="alert" role="alert">@foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach</div>@endif
    <form method="POST" action="{{ route('register') }}">@csrf
        <div class="field"><label for="name">Nom complet</label><input class="input" type="text" name="name" id="name" value="{{ old('name') }}" autocomplete="name" required autofocus>@error('name')<span class="field-error">{{ $message }}</span>@enderror</div>
        <div class="field"><label for="email">Adresse e-mail</label><input class="input" type="email" name="email" id="email" value="{{ old('email') }}" autocomplete="email" required>@error('email')<span class="field-error">{{ $message }}</span>@enderror</div>
        <div class="field"><label for="password">Mot de passe <span style="font-weight:400">(8 caractères minimum)</span></label><input class="input" type="password" name="password" id="password" autocomplete="new-password" required>@error('password')<span class="field-error">{{ $message }}</span>@enderror</div>
        <div class="field"><label for="password_confirmation">Confirmer le mot de passe</label><input class="input" type="password" name="password_confirmation" id="password_confirmation" autocomplete="new-password" required></div>
        <button class="button auth-submit" type="submit">Créer mon compte <span aria-hidden="true">→</span></button>
    </form>
    <div class="auth-foot">Déjà membre ? <a href="{{ route('login') }}">Se connecter</a></div>
</section></main></body></html>
