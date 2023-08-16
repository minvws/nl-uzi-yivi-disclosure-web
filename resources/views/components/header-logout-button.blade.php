<form method="post" action="{{ route('logout') }}" class="inline">
    @csrf
    <button type="submit"><span class="icon	icon-user">@lang('User icon')</span>@lang('Logout')</button>
</form>
