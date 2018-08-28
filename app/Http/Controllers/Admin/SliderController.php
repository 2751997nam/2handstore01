<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Slider;
use Storage;

class SliderController extends Controller
{
    public function index()
    {
        $slides = Slider::paginate(config('database.paginate'));

        return view('admin.slider.index', compact('slides'));
    }

    public function add()
    {
        return view('admin.slider.add');
    }

    public function store(Request $request)
    {
        $this->validate(
            $request,
            [
                'image' => 'image',
                'link' => 'min:1|max:255'
            ],
            [
                'image.image' => __('admin.slide.alert.image'),
                'link.min' => __('admin.slide.alert.linkmin')
            ]
        );

        $image = $request->image->hashName();
        $request->image->store(config('site.slider'), 'option');
        Slider::create([
            'image' => $image,
            'link' => $request->link
        ]);

        return redirect()->route('admin.slide.index')->with('msg', __('admin.slide.alert.success'));
    }

    public function destroy(Slider $slider)
    {
        Storage::delete(config('site.slider') . '/' . $slider->image);
        $slider->delete();
        
        return response()->json([
            'status' => 'success'
        ]);
    }

    public function edit(Slider $slider)
    {
        return view('admin.slider.edit', compact('slider'));
    }

    public function update(Request $request, Slider $slider)
    {
        $this->validate(
            $request,
            [
                'link' => 'min:1|max:255'
            ],
            [
                'link.min' => __('admin.slide.alert.linkmin'),
            ]
        );

        $data = [
            'link' => $request->link
        ];

        if ($request->has('image')) {
            Storage::delete(config('site.slider') . '/' . $slider->image);
            $image = $request->image->hashName();
            $request->image->store(config('site.slider'), 'option');
            $data['image'] = $image;
        }
        $slider->update($data);

        return redirect()->route('admin.slide.index')->with('msg', __('admin.slide.alert.success'));
    }
}
