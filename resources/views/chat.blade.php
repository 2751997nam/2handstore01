<div class="mt-5 ml-5 chat toggle-chat">
    <div class="panel-primary">
        <div class="panel-heading bg-success">
            <span class="ml-2 text-light chat-title">Chat({!! '<span id="chat-num">' . $unseenMessage .'</span>' !!})</span>
            <span><a href="javascript:toggleChat()" class="text-light float-right arrow arrow-up">	﹀ </a></span>
        </div>
        @php
            $user = Auth::user();
        @endphp
        <div class="box-chat w-100" data-url="{{ route('chat.seen') }}">
            @forelse ($conversation as $message)
                <div class="message-div">
                    <div class="col-sm-8 {{ ($message->from === $user->id ) ? 'float-left' : 'float-right' }}">
                        <p class="message {{ ($message->from === $user->id ) ? 'to-message' : 'from-message' }}">{{ $message->message }}</p>
                    </div>
                </div>                
            @empty
                <div class="message-div">
                    <p class="message btn btn-primary">{{ __('Hello, Can I Help You?') }}</p>
                </div>
            @endforelse
        </div>
        <div class="form-group row panel-footer m-0">
            <div class="col-9 row p-0 m-0">
                {!! Form::text('chat', null, [
                    'class' => 'form-control',
                    'data-url' => route('chat.store'),
                    'id' => 'message-content',
                    'placeholder' => __('Type your message')
                ]) !!}
                
                {!! Form::hidden('to_id', $user->id == 1 ? $user->id : 1, [
                    'id' => 'to_id',
                ]) !!}
                
                {!! Form::hidden('from_id', $user->id, [
                    'id' => 'from-id'
                ]) !!}
            </div>
            {!! Form::button(__('Send'), [
                'class' => 'btn btn-success col-3 float-right',
                'onclick' => 'sendMessage()'
                ]
            ) !!}
        </div>
    </div>
</div>
{!! Form::hidden('chanel-id', $user->id, [
    'id' => 'channel-id'
]) !!}
