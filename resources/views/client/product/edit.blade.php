@extends('client.layout.master')


@section('title', __('Edit') . ': ' . $product->name)


@section('content')

<div class="container">
    <div class="bg-white newpost px-4 pb-4">
        <div class="newpost__title">
            <div class="container">
                {{ __('Edit') . ': ' . $product->name }}
                <a href="{{ route('index') }}" class="newpost__back btn btn-danger"> 
                    <i class="fas fa-backspace"></i> 
                    @lang('client.product.back') 
                </a>
                <a href="javascript:void(0)" class="newpost__save btn btn-success">
                    <i class="fas fa-save"></i> 
                    @lang('client.product.save') 
                </a>
            </div>
        </div>
        <div class="newpost__form">

            @forelse($errors->all() as $error)
                <div class="alert alert-danger">
                    {{ $error }}
                </div>
            @empty
            @endforelse
            
            {!! Form::open([
                'class' => 'dropzone',
                'id' => 'my-dropzone',
                'method' => 'POST',
                'route' => 'client.product.upload.image',
                'delete-url' => route('client.products.delete-image'),
                'get-url' => route('client.products.get-image', ['id' => $product->id])
            ]) !!}
            {!! Form::close() !!}

            {!! Form::open([
                'url' => route('client.products.update', $product->id),
                'id' => 'new_product',
                'files' => true,
                'method' => 'PUT'
            ]) !!} 

                {!! Form::hidden('image_data', null, ['id' => 'imageData']) !!}
                <div class="form-group mt-4 row">
                    <div class="input-group mb-2 col-sm-8">
                        <div class="input-group-prepend">
                            <div class="input-group-text">@lang('admin.product.name')</div>
                        </div>
                        {!! Form::text('name', $product->name, [
                            'class' => 'form-control',
                            'placeholder' => __('admin.product.name')
                        ]) !!}
                    </div>
                    <div class="input-group mb-2 col-sm-4">
                        <div class="input-group-prepend">
                            <div class="input-group-text">@lang('admin.product.price')</div>
                        </div>
                        {!! Form::number('price', $product->price, [
                            'class' => 'form-control',
                            'placeholder' => __('admin.product.price')
                        ]) !!}
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-8">
                        {!! Form::label('detail', __('admin.product.description'), []) !!}
                        {!! Form::textarea('detail', $product->detail, [
                            'class' => 'form-control',
                            'rows' => 13,
                            'placeholder' => __('admin.product.description')
                        ]) !!}
                    </div>
                    <div class="col-sm-4">
                        {!! Form::label('description', __('admin.product.thumbnail'), []) !!}
                        <div class="custom-file mb-4">
                            {!! Form::file('thumbnail', ['class' => 'custom-file-input']) !!}
                            {!! Form::label(null, __('admin.product.thumbnail'), ['class' => 'custom-file-label']) !!}
                        </div>
                        {{ Html::image($product->thumbnail(), null, ['class' => 'mb-3']) }}
                        <div class="form-group">
                            {!! Form::label(null, __('admin.product.category'), []) !!}
                            {!! Form::select('category_id', $categories, $product->category->id, ['class' => 'custom-select']) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label(null, __('admin.product.city'), []) !!}
                            {!! Form::select('city', $cities, $product->province->city->id, [
                                    'class' => 'custom-select', 
                                    'id' => 'city',
                                    'data-url' => route('client.products.getSearchProvince')
                                ]) 
                            !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label(null, __('client.category.province'), []) !!}
                            {!! Form::select('province_id', 
                                    $product->province->getProvinces($product->province->city->id), 
                                    $product->province->id, 
                                    ['class' => 'custom-select', 'id' => 'province'
                                ]) 
                            !!}
                        </div>
                    </div>
                </div>

            {!! Form::close() !!}
        </div>
    </div>
</div>

@endsection


@section('js')
    <script src="{{ asset('bower_components/dropzone/dist/min/dropzone.min.js') }}"></script>
    <script src="{{ asset('js/update-product.js') }}"></script>
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/newproduct.css') }}">
    <link rel="stylesheet" href="{{ asset('bower_components/dropzone/dist/min/dropzone.min.css') }}">
@endsection
