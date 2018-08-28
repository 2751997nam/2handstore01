<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Rules\CheckPhoneRule;
use App\Order;
use App\Notifications\SellYouProduct;
use App\Repositories\UserRepository;
use App\Repositories\OrderRepository;

class UserController extends Controller
{
    protected $userRepository;
    protected $order;

    public function __construct(UserRepository $userRepository, OrderRepository $orderRepository)
    {
        $this->userRepository = $userRepository;
        $this->order = $orderRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $users = $this->userRepository->showCustomer($request->search);
        $page = $users->currentPage();

        if ($request->ajax()) {
            return view('admin.users.userTable', compact(['users', 'page']));
        }

        return view('admin.users.index', compact(['users', 'page']));
    }

    public function option(Request $request)
    {
        $users = $this->userRepository->showOptionCustomer($request->option, $request->search);
        $page = $users->currentPage();

        return view('admin.users.userTable', compact(['users', 'page']));
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $user = User::findOrFail($request->id);

        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);

        return view('admin.users.edit', compact('user'));
    }

    public function getFilePath(Request $request, $name)
    {
        $file = $request->file($name);
        $fileName = $file->getClientOriginalName();
        $filePath = config('filesystems.img_path');
        if (file_exists($file)) {
            $fileName = md5(str_random(15)) . $fileName;
        }
        $path = $file->storeAs(config('site.avatar') . $filePath, $fileName, 'option');

        return $path;
    }

    public function updateUser($request, $id)
    {
        $this->validate($request, [
            'image' => 'mimes:jpeg,jpg,png,bmp,svg|max:5120',
            'name' => 'string|min:5|max:255',
            'description' => 'string|nullable',
            'address' => 'string|nullable',
            'phone' => [
                'nullable',
                new CheckPhoneRule()
            ]
        ]);
        
        if ($request->has('image')) {
            $request->merge([
                'avatar' => $this->getFilePath($request, 'image')
            ]);
        }

        try {
            $user = User::findOrFail($id);
            $user->saveUser($request->only('name', 'description', 'address', 'phone', 'avatar', 'status'));
        } catch (Exception $e) {
            return $e;
        }
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
        $this->updateUser($request, $id);

        return redirect()->route('admin.users.index');
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

    public function toggleBlock(Request $request)
    {
        $user = User::findOrFail($request->id);
        $user->status >= 0 ? $user->status = -1 : $user->status = 0;
        $user->save();
        
        return redirect()->route('admin.users.option', ['option' => $request->option]);
    }

    public function profile()
    {
        $user = Auth::user();

        return view('client.user.profile', compact('user'));
    }

    public function updateProfile(Request $request, $id)
    {
        $this->updateUser($request, $id);

        return redirect()->route('client.user.profile');
    }

    public function myOrders(Request $request)
    {
        $orders = $request->user()->getProductOrders();

        return view('client.user.myorder', compact('orders'));
    }

    public function sellProduct(Request $request)
    {
        $this->validate(
            $request,
            [
                'order_id' => 'required|exists:orders,id'
            ]
        );
        $order = Order::find($request->order_id);
        $status = $order->status == 0 ? 1 : 0;
        $order->update([
            'status' => $status
        ]);
        if ($status == 0) {
            $order->user->notify(new SellYouProduct($order->products));
        }

        return redirect()->back();
    }

    public function discardOrder(Request $request)
    {
        $this->validate(
            $request,
            [
                'order_id' => 'required|exists:orders,id'
            ]
        );
        $this->order->discard($request->order_id);

        return redirect()->back();
    }
}
