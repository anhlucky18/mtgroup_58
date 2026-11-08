<div class="dropdown">
    <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
        {!! language_flag($currentLocaleFlag, $currentLocaleName) !!}
        <span>{{ $currentLocaleName }}</span>
        <span class="caret"></span>
    </button>
    <ul class="dropdown-menu language_bar_chooser">
        @foreach ($supportedLocales as $code => $prop)
            <li class="{{ $code === $currentLocale ? 'active' : '' }}">
                <a rel="alternate" hreflang="{{ $code }}" href="{{ $prop['url'] }}">
                    {!! language_flag($prop['flag'], $prop['name']) !!}
                    <span>{{ $prop['name'] }}</span>
                </a>
            </li>
        @endforeach
    </ul>
</div>

