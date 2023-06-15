<form method="post" action="{{ route('logout') }}" class="inline">
    @csrf
    <button type="submit">Logout</button>
</form>
