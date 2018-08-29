<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class MessageRepository extends EloquentRepository
{
    public function model()
    {
        return  \App\Message::class;
    }

    public function lastestMessages($id)
    {
        $this->makeModel();
        
        return $this->model->where('to', $id)
        ->whereIn('created_at', function ($query) {
            $query->select(DB::raw('max(created_at)'))
                    ->from('messages')
                    ->groupBy('from');
        })
        ->orderBy('created_at', 'desc')->with('user')->get();
    }

    public function conversation($id, $from = null)
    {
        $this->makeModel();

        return $this->model->where([
            'to' =>  $id,
            'from' => $from
            ])
            ->orWhere(function ($query) use ($id, $from) {
                $query->where([
                    'to' => $from,
                    'from' => $id
                ]);
            })
            ->with('user')->get();
    }

    public function clientConversation($id)
    {
        $this->makeModel();

        return $this->where('to', $id)
                    ->orWhere('from', $id)
                    ->get();
    }

    public function seen()
    {
        $this->makeModel();
        $args = func_get_args();
        if (func_num_args() == 1) {
            return $this->where('to', $args[0])
                    ->where('status', config('database.unseen'))
                    ->update(['status' => config('database.seen')]);
        }
        
        return $this->where('from', $args[1])
                    ->where('to', $args[0])
                    ->where('status', config('database.unseen'))
                    ->update(['status' => config('database.seen')]);
    }

    public function unseenMessage($id)
    {
        $this->makeModel();
        
        return $this->model->where('status', config('database.unseen'))
                            ->where('to', $id)
                            ->count();
    }
}
