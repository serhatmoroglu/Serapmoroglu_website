<!doctype html><html lang="tr"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><meta name="robots" content="noindex,nofollow">
<title>Yönetim girişi</title><link rel="stylesheet" href="/css/admin.css"></head><body>
<div class="login"><div class="panel">
  <h1 style="font-size:22px;margin-bottom:4px">{{ setting('site_name') }}</h1><p class="help" style="margin:0 0 18px">Yönetim paneli girişi</p>
  @if($errors->any())<div class="flash err">{{ $errors->first() }}</div>@endif
  <form method="post" action="{{ route('admin.login') }}">@csrf
    <div class="row"><label for="email">E-posta</label><input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"></div>
    <div class="row"><label for="password">Şifre</label><input id="password" type="password" name="password" required autocomplete="current-password"></div>
    <div class="row"><label style="font-weight:400"><input type="checkbox" name="remember" value="1"> Beni hatırla</label></div>
    <button class="btn" type="submit" style="width:100%">Giriş yap</button>
  </form>
</div></div></body></html>
