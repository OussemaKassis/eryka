@foreach($sections as $section)
    <div class="about-section {{ $section->image_path ? 'about-story-section' : 'about-banner-section' }} {{ $loop->odd ? 'about-section-alt' : '' }}">
        <div class="container">
            @if($section->image_path)
                <div class="row justify-content-between align-items-center {{ $loop->iteration % 2 === 0 ? 'flex-row-reverse' : '' }}">
                    <div class="col-lg-6">
                        <h2 class="section-title">{{ $section->title }}</h2>
                        @foreach(explode("\n\n", $section->body) as $paragraph)
                            <p class="mb-4">{{ $paragraph }}</p>
                        @endforeach
                    </div>

                    <div class="col-lg-5">
                        <div class="img-wrap">
                            <img src="{{ asset('storage/' . $section->image_path) }}" alt="{{ $section->title }}" class="img-fluid" loading="lazy" decoding="async">
                        </div>
                    </div>
                </div>
            @else
                <div class="row justify-content-center text-center">
                    <div class="col-lg-7">
                        <h2 class="section-title">{{ $section->title }}</h2>
                        @foreach(explode("\n\n", $section->body) as $paragraph)
                            <p class="mb-3">{{ $paragraph }}</p>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
@endforeach
