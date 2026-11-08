<!DOCTYPE html>
<html lang="fr"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Connexion · CourseHub</title><link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;750&display=swap" rel="stylesheet">@vite(['resources/css/app.css','resources/js/app.js'])</head>
<body><main class="auth-page"><section class="auth-card" aria-labelledby="login-title">
    <a class="brand" href="{{ route('login') }}"><span class="brand-mark">C</span> coursehub</a>
    <h1 id="login-title">Ravi·e de vous revoir</h1><p>Connectez-vous pour retrouver votre espace d’apprentissage.</p>
    @if(session('success'))<div class="flash" role="status">{{ session('success') }}</div>@endif
    @if($errors->any())<div class="alert" role="alert">@foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach</div>@endif
    <form method="POST" action="{{ route('login') }}">@csrf
        <div class="field"><label for="email">Adresse e-mail</label><input class="input" type="email" name="email" id="email" value="{{ old('email') }}" autocomplete="username" required autofocus>@error('email')<span class="field-error">{{ $message }}</span>@enderror</div>
        <div class="field"><label for="password">Mot de passe</label><input class="input" type="password" name="password" id="password" autocomplete="current-password" required>@error('password')<span class="field-error">{{ $message }}</span>@enderror</div>
        <label style="display:flex;align-items:center;gap:8px;margin:12px 0;color:#71837c;font-size:11px"><input type="checkbox" name="remember" value="1"> Rester connecté·e</label>
        <button class="button auth-submit" type="submit">Se connecter <span aria-hidden="true">→</span></button>
    </form>
    <div class="auth-foot">Pas encore de compte ? <a href="{{ route('register') }}">Créer un compte étudiant</a></div>
</section></main></body></html>
