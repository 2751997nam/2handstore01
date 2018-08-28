<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Product;
use App\Image;
use App\Category;
use App\City;
use App\Http\Requests\ProductRequest;
use Storage;
use App\Repositories\ProductRepository;
use App\Repositories\ImageRepository;

class ProductController extends Controller
{
    private $product;
    private $image;

    public function __construct(
        ProductRepository $productRepository, 
        ImageRepository $imageRepository
    )
    {
        $this->product = $productRepository;
        $this->image = $imageRepository;
    }

    public function postProduct()
    {
        $categories = Category::convertToArray();
        $cities = City::convertToArray();
        
        return view('client.product.new', compact('categories', 'cities'));
    }

    public function store(ProductRequest $request)
    {
        $data = $request->all();
        $data['thumbnail'] = 'default.jpg';
        if ($request->has('thumbnail')) {
            $data['thumbnail'] = $request->thumbnail->hashName();
            $request->thumbnail->store(config('site.thumbpath'), 'option');
        }

        $product = $request->user()->products()->create($data);

        if ($request->has('image_data')) {
            $imageIds = explode(',', $request->image_data);
            if (count($imageIds) > 0) {
                Image::whereIn('id', $imageIds)->update([
                    'product_id' => $product->id
                ]);
            }
        }

        return redirect()->route('index');
    }

    public function uploadImage(Request $request)
    {
        if (!$request->has('file') || count($request->file) < 1) {
            return response()->json([
                'error' => true
            ]);
        }

        $data = [];

        foreach ($request->file as $image) {
            $imageModel = Image::create([
                'file_name' => $image->hashName(),
            ]);
            $image->store(config('site.product'), 'option');
            $data[] = $imageModel->id;
        }

        return response()->json($data);
    }

    public function getMyProducts(Request $request)
    {
        $products = $request->user()->getMyProducts();

        return view('client.user.myproduct', compact('products'));
    }

    public function changeStatus(Product $product)
    {
        $this->authorize('status', $product);
        $status = $product->status == 1 ? 0 : 1;
        $product->update([
            'status' => $status
        ]);

        return redirect()->back();
    }

    public function myPurchases(Request $request)
    {
        $orders = $request->user()->getMyPurchases();

        return view('client.user.mypurchases', compact('orders'));
    }

    public function edit(Product $product)
    {
        $this->authorize('update', $product);
        $categories = Category::convertToArray();
        $cities = City::convertToArray();
        
        return view('client.product.edit', compact('categories', 'cities', 'product'));
    }

    public function update(ProductRequest $request, Product $product)
    {
        $this->authorize('update', $product);

        $data = $request->except('_token', '_method', 'city', 'image_data');
        if ($request->has('thumbnail')) {
            if ($product->thumbnail != 'default.jpg') {
                Storage::delete(config('deletethumb') . $product->thumbnail);
            }
            $request->thumbnail->store(config('site.thumbpath'));
            $data['thumbnail'] = $request->thumbnail->hashName();
        }

        $this->product->update($data, $product->id);

        if ($request->has('image_data') && $request->image_data != null) {
            $imageIds = explode(',', $request->image_data);
            if (count($imageIds) > 0) {
                $this->image->setProductId($imageIds, $product->id);
            }
        }

        return redirect()->route('client.myproduct.index');
    }

    public function getImages(Product $product)
    {
        $data = [];
        foreach ($product->images as $image) {
            $data[] = [
                'id' => $image->id,
                'name' => $image->name,
                'url' => asset('storage/' . $image->file_name),
            ];
        }

        return response()->json($data);
    }

    public function deleteImage(Request $request)
    {
        $image = $this->image->findOrFail($request->id);
        $image->delete();

        return response()->json([
            'status' => 'success'
        ]);
    }

    public function changeQuantity(Request $request)
    {
        $this->validate(
            $request,
            [
                'id' => 'required|exists:products,id',
                'quantity' => 'required|numeric|min:0'
            ]
        );
        $product = $this->product->findOrFail($request->id);
        $this->authorize('update', $product);
        $this->product->update([
            'quantity' => $request->quantity
        ], $request->id);

        return redirect()->back();
    }
}
