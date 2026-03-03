@extends('layouts.master')

@section('title', __('Edit Product'))

@section('css')
@endsection


@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('dashboard.products.index') }}">{{ __('Products') }}</a></li>
    <li class="breadcrumb-item active">{{ __('Edit') }}</li>
@endsection
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card mb-6">
            <!-- Account -->
            <div class="card-body pt-4">
                <form method="POST" action="{{ route('dashboard.products.update', $product->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row p-5">
                        <h3>{{ __('Edit Product') }}</h3>
                        <div class="mb-4 col-md-6">
                            <label for="name" class="form-label">{{ __('Name') }}</label><span
                                class="text-danger">*</span>
                            <input class="form-control @error('name') is-invalid @enderror" type="text" id="name"
                                name="name" required placeholder="{{ __('Enter name') }}" autofocus
                                value="{{ old('name', $product->name) }}" />
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-4 col-md-6">
                            <label for="slug" class="form-label">{{ __('Slug') }}</label><span
                                class="text-danger">*</span>
                            <input class="form-control @error('slug') is-invalid @enderror" type="text" id="slug"
                                name="slug" required placeholder="{{ __('Enter slug') }}" autofocus
                                value="{{ old('slug', $product->slug) }}" />
                            @error('slug')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-4 col-md-6">
                            <label for="category" class="form-label">{{ __('Category') }}</label><span
                                class="text-danger">*</span>
                            <input class="form-control @error('category') is-invalid @enderror" type="text" id="category"
                                name="category" required placeholder="{{ __('Enter category') }}"
                                value="{{ old('category', $product->category) }}" />
                            @error('category')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-4 col-md-6">
                            <label for="sku" class="form-label">{{ __('SKU') }}</label><span
                                class="text-danger">*</span>
                            <input class="form-control @error('sku') is-invalid @enderror" type="text" id="sku"
                                name="sku" required placeholder="{{ __('Enter SKU') }}"
                                value="{{ old('sku', $product->sku) }}" />
                            @error('sku')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-4 col-md-12">
                            <label for="description" class="form-label">{{ __('Description') }}</label><span
                                class="text-danger">*</span>
                            <textarea class="form-control @error('description') is-invalid @enderror" name="description" id="description" cols="30" rows="10" placeholder="{{ __('Enter description') }}" required>{{ old('description', $product->description) }}</textarea>
                            @error('description')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-4 col-md-6">
                            <label for="main_image" class="form-label">{{ __('Main Image') }}</label>
                            <input class="form-control @error('main_image') is-invalid @enderror" type="file"
                                id="main_image" name="main_image" accept="image/*"/>
                            @error('main_image')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            @if($product->main_image)
                                <img src="{{ asset($product->main_image) }}" alt="main image" class="mt-2" width="120">
                            @endif
                        </div>
                        <div class="mb-4 col-md-6">
                            <label for="price" class="form-label">{{ __('Price') }}</label><span
                                class="text-danger">*</span>
                            <input class="form-control @error('price') is-invalid @enderror" type="number" step="0.01" id="price"
                                name="price" required placeholder="{{ __('Enter price') }}"
                                value="{{ old('price', $product->price) }}" />
                            @error('price')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-4 col-md-6">
                            <label for="reviews_count" class="form-label">{{ __('Reviews Count') }}</label><span
                                class="text-danger">*</span>
                            <input class="form-control @error('reviews_count') is-invalid @enderror" type="number" min="0" id="reviews_count"
                                name="reviews_count" required placeholder="{{ __('i.e. 1435') }}"
                                value="{{ old('reviews_count', $product->reviews_count) }}" />
                            @error('reviews_count')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-4 col-md-6">
                            <label for="rating" class="form-label">{{ __('Rating (between 1 to 5)') }}</label><span
                                class="text-danger">*</span>
                            <input class="form-control @error('rating') is-invalid @enderror" type="number" min="0" id="rating"
                                name="rating" required placeholder="{{ __('i.e. 4') }}"
                                value="{{ old('rating', $product->rating) }}" />
                            @error('rating')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-4 col-md-6">
                            <label for="is_popular" class="form-label">{{ __('Popular Product?') }}</label>
                            <div class="form-check">
                                <input class="form-check-input @error('is_popular') is-invalid @enderror" type="checkbox"
                                    name="is_popular" id="defaultCheck3"  {{old('is_popular',$product->is_popular == '1') ? 'checked' : ''}}>
                                <label class="form-check-label" for="defaultCheck3"> Popular </label>
                            </div>
                            @error('is_popular')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="mt-2">
                        <button type="submit" class="btn btn-primary me-3">{{ __('Edit Product') }}</button>
                    </div>
                </form>
            </div>
            <!-- /Account -->
        </div>
    </div>
@endsection

@section('script')
<script>
        $(document).ready(function() {
            // Generate slug from name
            $('#name').on('keyup change', function() {
                let name = $(this).val();
                let slug = name.toLowerCase()
                    .trim()
                    .replace(/[^a-z0-9\s-]/g, '')
                    .replace(/\s+/g, '-')
                    .replace(/-+/g, '-');
                $('#slug').val(slug);
            });
        });
    </script>
@endsection
