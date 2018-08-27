@extends('admin.layout.master')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/adminChat.css') }}">
@endsection

@section('content')
    <div class="container">
        
        <h3 class=" text-center bg-light p-1">{{ __('Messaging') }}</h3>
        <div class="messaging">
            <div class="inbox_msg">
                <div class="inbox_people">
                    <div class="headind_srch">
                    <div class="recent_heading">
                        <h4>{{ __('Recent') }}</h4>
                    </div>
                    <div class="srch_bar">
                        <div class="stylish-input-group">
                            {!! Form::text('search-bar', null, [
                                'class' => 'search-bar',
                                'placeholder' => __('Search')
                                ]) 
                            !!}
                            <span class="input-group-addon">
                                {!! Form::button('<i class="fa fa-search" aria-hidden="true"></i>') !!}
                            </span> 
                        </div>
                    </div>
                </div>
                <div class="inbox_chat" data-url="{{ route('admin.chat.show') }}">
                    @forelse($messages as $message)
                        <div class="chat_list {{ $message === $messages[0] ? 'active_chat' : '' }}" data-from="{{ $message->from }}">
                            <div class="chat_people">
                                <div class="chat_img"> {!! Html::image($message->user->getAvatar(), 'avatar') !!} </div>
                                <div class="chat_ib">
                                    <h5>{{ $message->user->name }} <span class="chat_date">{{ $message->created_at }}</span></h5>
                                    <p class="{{ $message->status == 0 ? 'font-weight-bold text-danger' : ''}}">{{ $message->getShortMessage() }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                    @endforelse
                </div>
                </div>
                <div class="mesgs">
                    <div class="msg_history">
                        @include('admin.chat.chat')
                    </div>
                    <div class="type_msg">
                        <div class="input_msg_write">
                            
                            {!! Form::text('write_msg', null, [
                                'class' => 'write_msg',
                                'data-url' => route('admin.chat.store'),
                                'data-id' => $user->id,
                                'placeholder' => __('Type a message')
                            ]) !!}
                            
                            {!! Form::button('<i class="fa fa-paper-plane-o" aria-hidden="true"></i>', [
                                'class' => 'msg_send_btn',
                                'data-id' => $messages[0]->from,
                                'onclick' => 'sendMessage()',
                            ]) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('js/admin/chat.js') }}"></script>
@endsection
