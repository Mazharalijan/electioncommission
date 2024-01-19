<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pollingstation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PollingStationController extends Controller
{
    public function index()
    {
        $pollingStation = PollingStation::orderBy('pollingStationName', 'ASC')->get();
        $data = compact('pollingStation');

        return view()->with($data);
    }

    public function show()
    {

    }

    public function create()
    {

    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pollingstationName' => 'required',
        ]);
        if ($validator->passes()) {
            // validation clear
            try {
                $data = [
                    'pollingStationName' => $request->pollingstationName,
                ];
                PollingStation::create($data);

                return redirect()->route()->with('success', 'Polling Station created successfully!');
            } catch (\Exception $error) {
                return redirect()->route()->with('danger', 'Polling Station not created!');
            }

        } else {
            // validator error
            return redirect()->route()->withErrors($validator)->withInput();
        }

    }

    public function edit($id)
    {
        $pollingStation = PollingStation::find($id);
        if (! is_null($pollingStation)) {
            $data = compact('pollingStation');

            return view()->with($data);
        } else {
            return redirect()->back()->with('danger', 'Polling Station not found!');
        }

    }

    public function update(string $id, Request $request)
    {
        $pollingStation = PollingStation::find($id);
        if (! is_null($pollingStation)) {
            $validator = Validator::make($request->all(), [
                'pollingstationName' => 'required',
            ]);
            if ($validator->passes()) {
                // validation clear
                try {
                    $data = [
                        'pollingStationName' => $request->pollingstationName,
                    ];
                    $pollingStation->update($data);

                    return redirect()->route()->with('success', 'Polling Station updated successfully!');
                } catch (\Exception $error) {
                    return redirect()->route()->with('danger', 'Polling Station not updated!');
                }

            } else {
                // validator error
                return redirect()->route()->withErrors($validator)->withInput();
            }

        } else {
            return redirect()->route()->with('danger', 'Polling Station not found!');
        }

    }

    public function destroy(string $id)
    {
        $pollingStation = PollingStation::find($id);
        if (! is_null($pollingStation)) {
            try {
                $pollingStation->delete();

                return redirect()->route()->with('success', 'Polling Station deleted successfully!');
            } catch (\Exception $error) {
                return redirect()->route()->with('danger', 'Polling Station not deleted!');
            }

        } else {
            return redirect()->route()->with('danger', 'Polling Station not found!');
        }

    }
}
