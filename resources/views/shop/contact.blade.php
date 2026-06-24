@extends('layouts.app')

@section('hero-title', $pageHero->title ?? __('site.contact_hero_title'))
@section('hero-subtitle', $pageHero->subtitle ?? __('site.contact_hero_subtitle'))

@if($heroSlides->isNotEmpty())
    @section('hero-bg-slides')
        @foreach($heroSlides as $slide)
            <div class="hero-slide" style="background-image: url('{{ asset('storage/' . $slide->image_path) }}')"></div>
        @endforeach
    @endsection

    @if($heroSlides->count() > 1)
        @section('hero-slider-dots')
            <div class="hero-slider-dots">
                @foreach($heroSlides as $key => $slide)
                    <span class="hero-dot {{ $loop->first ? 'active' : '' }}" onclick="goToHeroSlide({{ $key }})"></span>
                @endforeach
            </div>
        @endsection
    @endif
@endif

@section('content')
<div class="untree_co-section">
    <div class="container">
        @if($errors->any())
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <div class="row g-5">
            <div class="col-lg-5">
                <h2 class="section-title mb-4">{{ __('site.our_information') }}</h2>

                @if($contactInfos->isEmpty())
                    <p>{{ __('site.contact_details_soon') }}</p>
                @else
                    <ul class="list-unstyled contact-info-list">
                        @foreach($contactInfos->where('type', 'email') as $info)
                            <li>
                                <span class="contact-info-icon"><i class="{{ $info->icon_class }}"></i></span>
                                <a href="mailto:{{ $info->value }}">{{ $info->value }}</a>
                            </li>
                        @endforeach

                        @foreach($contactInfos->where('type', 'phone') as $info)
                            <li>
                                <span class="contact-info-icon"><i class="{{ $info->icon_class }}"></i></span>
                                @if($info->label)
                                    <sup class="contact-info-label">{{ $info->label }}</sup>
                                @endif
                                <a href="tel:{{ $info->value }}">{{ $info->value }}</a>
                            </li>
                        @endforeach

                        @foreach($contactInfos->where('type', 'address') as $info)
                            <li>
                                <span class="contact-info-icon"><i class="{{ $info->icon_class }}"></i></span>
                                @if($info->label)
                                    <strong>{{ $info->label }} :</strong>
                                @endif
                                {{ $info->value }}
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

            <div class="col-lg-7">
                <h2 class="section-title mb-4">{{ __('site.contact_form_title') }}</h2>

                <form action="{{ route('shop.contact.submit') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="text-black" for="name">{{ __('site.your_name') }} <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="text-black" for="email">{{ __('site.email_address') }} <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="text-black" for="phone">{{ __('site.phone_number') }} <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control" id="phone" name="phone" value="{{ old('phone') }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-5">
                        <label class="text-black" for="message">{{ __('site.message') }} <span class="text-danger">*</span></label>
                        <textarea name="message" id="message" cols="30" rows="6" class="form-control" required>{{ old('message') }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">{{ __('site.send_message') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
