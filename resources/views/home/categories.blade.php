@if (count($links['categories']) > 0)
    <div class="flex flex-wrap">
        @foreach($links['categories'] as $category)
            <div class="w-1/5 px-3 mb-5">
                <a href="{{ $category['link'] }}">
                    <img class="rounded shadow"
                         src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url('/images/category/'.$category['image']) }}"
                         alt="{{ $category['name'] }}"/>
                </a>
            </div>
        @endforeach
    </div>
@else
    <h3 class="text-center">No category images have been added.</h3>
@endif
