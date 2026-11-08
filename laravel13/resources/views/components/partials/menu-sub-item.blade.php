@php
    $hasChild = $item->children && $item->children->isNotEmpty();
    $itemUrl = $item->url ?: '/';
    $isActive = url($itemUrl) === url()->current() || request()->is(ltrim($itemUrl, '/'));
@endphp
<li class="menu-item {{ $hasChild ? 'menu-item-has-children' : '' }} {{ $item->css_class }} {{ $isActive ? 'active' : '' }}">
    <a href="{{ $itemUrl }}" target="{{ $item->target ?: '_self' }}">
        @if ($item->icon_font)
            <i class="{{ trim($item->icon_font) }}"></i> 
        @endif
        {{ $item->title }}
    </a>
    @if ($hasChild)
        <ul class="sub-menu">
            @foreach ($item->children as $child)
                @include('components.partials.menu-sub-item', ['item' => $child])
            @endforeach
        </ul>
    @endif
</li>

