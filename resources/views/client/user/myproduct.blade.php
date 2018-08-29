@extends('client.layout.master')

@section('title', __('client.products.posted'))

@section('content')

<div class="container bg-white mt-5 pb-4">
    <h2 class="text-center my-4 pt-4">{{ __('client.products.posted') }}</h2>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">@lang('admin.product.thumbnail')</th>
                <th scope="col">@lang('admin.product.name')</th>
                <th scope="col">@lang('admin.product.price')</th>
                <th scope="col">@lang('admin.product.category')</th>
                <th scope="col">@lang('admin.product.province')</th>
                <th scope="col">@lang('admin.product.status.status')</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($products as $product)
                <tr>
                    <td><img src="{{ $product->thumbnail() }}" alt=""></td>
                    <td>
                        <a href="{{ route('client.products.show', ['id' => $product->id]) }}">{{ $product->name }}</a>
                    </td>
                    <td>{{ $product->money }}</td>
                    <td>{{ $product->category->name }}</td>
                    <td>{{ $product->province->city->name }}</td>
                    <td>
                        {!! Form::open([
                            'url' => route('client.myproduct.status', ['product' => $product->id]),
                            'method' => 'PUT'
                        ]) !!}
                        @if($product->status == config('site.active') && $product->quantity > 0)
                            {!! Form::submit(__('admin.product.status.available'), ['class' => 'btn btn-success']) !!}
                        @else
                            {!! Form::submit(__('admin.product.status.sold'), ['class' => 'btn btn-secondary']) !!}
                        @endif
                            {!! Form::close() !!}
                    </td>
                    <td>
                        <a href="{{ route('client.myproduct.edit', $product->id) }}" class="btn btn-warning mb-2">
                            @lang('Edit')
                        </a>
                        <a data-id="{{ $product->id }}" href="javascript:void(0)" data-quantity="{{ $product->quantity }}" class="btn btn-primary mb-2 quantity">
                            @lang('Quantity') ({{ $product->quantity }})
                        </a>
                    </td>
                </tr>
            @empty
                <div class="alert alert-warning text-center">
                    @lang('client.empty')
                </div>
            @endforelse
        </tbody>
    </table>
    <div class="text-center">
        {{ $products->links() }}
    </div>
</div>
<div class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Change Quantity')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            {!! Form::open([
                'url' => route('client.products.quantity'),
                'method' => 'PUT'
            ]) !!}
            <div class="modal-body">
                <p>
                    {!! Form::number('quantity', null, [
                        'class' => 'form-control',
                        'min' => 0
                    ]) !!}
                    {!! Form::hidden('id', null) !!}
                </p>
            </div>
            <div class="modal-footer">
                <a href="javascript:void(0)" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times-circle"></i>
                </a>
                {!! Form::button('<i class="fas fa-save"></i>', [
                    'type' => 'submit',
                    'class' => 'btn btn-primary'
                ]) !!}
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection

@section('js')
    <script src="{{ asset('js/client/myproduct.js') }}"></script>
@endsection
