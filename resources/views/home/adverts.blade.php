<div class="hidden md:block w-1/4 mr-5">
    @foreach($adverts as $advert)
        <div class="mb-5">
            <a href="{{ $advert->link }}" target="_blank">
                <img class="shadow w-64"
                     src="{{ \Illuminate\Support\Facades\Storage::url(config('app.name').'/advert/'.$advert->image) }}">
            </a>
        </div>
    @endforeach
</div>
