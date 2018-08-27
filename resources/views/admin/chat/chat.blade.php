@forelse($conversation as $message)
    @if($message->from !== $user->id)
    <div class="incoming_msg">
        <div class="incoming_msg_img"> {!! Html::image($message->user->getAvatar(), 'avatar') !!} </div>
        <div class="received_msg">
        <div class="received_withd_msg">
            <p class="msg_content">{{ $message->message }}</p>
            <span class="time_date">{{ $message->created_at }}</span></div>
        </div>
    </div>
    @else
    <div class="outgoing_msg">
        <div class="sent_msg">
        <p class="msg_content">{{ $message->message }}</p>
        </div>
    </div>
    @endif
@empty

@endforelse
