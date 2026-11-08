<title>{{ $title }}</title>
<meta name="description" content="{{ e($description) }}">
<link rel="canonical" href="{{ $url }}" />

<!-- Open Graph / Facebook -->
<meta property="og:type" content="website" />
<meta property="og:url" content="{{ $url }}" />
<meta property="og:title" content="{{ e($title) }}" />
<meta property="og:description" content="{{ e($description) }}" />
<meta property="og:image" content="{{ $image }}" />

<!-- Twitter -->
<meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:url" content="{{ $url }}" />
<meta name="twitter:title" content="{{ e($title) }}" />
<meta name="twitter:description" content="{{ e($description) }}" />
<meta name="twitter:image" content="{{ $image }}" />

