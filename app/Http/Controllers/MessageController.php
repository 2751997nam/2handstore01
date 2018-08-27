<?php

namespace App\Http\Controllers;

use App\User;
use App\Message;
use Illuminate\Http\Request;
use App\Events\MessagePosted;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Repositories\MessageRepository;

class MessageController extends Controller
{
    public $messageRepository;

    public function __construct(MessageRepository $messageRepository)
    {
        $this->messageRepository = $messageRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();

        $messages = $this->messageRepository->lastestMessages($user->id);

        $conversation = $this->messageRepository->conversation($user->id, $messages[0]->from);
        
        return view('admin.chat.index', compact('messages', 'conversation', 'user'));
    }

    public function seenMessage(Request $request)
    {
        $user = Auth::user();
        try {
            $this->messageRepository->seen($user->id);
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function showMessages(Request $request)
    {
        $user = Auth::user();
        try {
            $this->messageRepository->seen($user->id, $request->from);
        } catch (\Exception $e) {
            return $e;
        }
        $conversation = $this->messageRepository->conversation($user->id, $request->from);

        return view('admin.chat.chat', compact('conversation', 'user'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $message = $this->messageRepository->create($request->all());
            $avatar = $message->user->getAvatar();
            event(new MessagePosted($message, $avatar));
        } catch (\Exception $e) {
            return $e;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
