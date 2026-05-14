@foreach($featured as $property)
    <x-property-card :property="$property" />
@endforeach
