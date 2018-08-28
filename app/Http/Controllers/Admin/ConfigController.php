<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Option;
use Storage;
use App\Http\Requests\ConfigRequest;

class ConfigController extends Controller
{
    public function index()
    {
        $options = Option::getOptions();
        $keys = array_keys($options);
        $missedKey = false;

        if (array_diff($keys, config('site.siteinfo'))) {
            $missedKey = true;
        }

        return view('admin.config.index', compact('options', 'missedKey'));
    }

    public function save(ConfigRequest $request)
    {
        $options = $request->except('_token', '_method');
        $assets = config('site.assets');
        foreach ($options as $key => $value) {
            if (in_array($key, $assets)) {
                $option = Option::where('key', $key)->first();
                $name = Storage::disk('public')->delete(config('site.folder') . '/' . $option->value);
                $request->{$key}->store(config('site.folder'), 'option');
                // Storage::disk('public')->put($value, $request->{$key}, 'public');
                $option->update([
                    'value' => $request->{$key}->hashName()
                ]);
            } else {
                $option = Option::where('key', $key)->first();
                $option->update([
                    'value' => $value
                ]);
            }
        }

        return redirect()->route('admin.config.index');
    }
}
