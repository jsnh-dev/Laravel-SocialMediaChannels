@include('navigation.top')

@include('navigation.main', [
    'items' => \App\Models\NavigationItem::all()
])