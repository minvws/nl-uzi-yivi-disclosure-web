<form method="post" action="{{ route('logout') }}" class="inline">
    @csrf
    <button type="submit"><span class="icon	icon-user">Gebruikersicoon</span>Uitloggen</button>
</form>
