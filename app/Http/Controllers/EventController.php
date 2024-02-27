<?php

namespace App\Http\Controllers;

use App\Models\UpcomingEvent;
use Illuminate\Http\Request;
use App\Models\Employee;


class EventController extends Controller
{
    //
    public function index()
    {
        return view('admin.upcoming_events')->with(['all_upcoming_event' => UpcomingEvent::all(), 'employees' => Employee::where('status', '1')->get()]);
    }
    public function store(Request $request)
    {
        $event_obj = new UpcomingEvent;
        $event_obj->title = $request->title;
        if (isset($request->emp_id)) {
            $event_obj->emp_id = $request->emp_id;
        }
        $event_obj->event_type = $request->event_type;
        $event_obj->event_held_on = $request->event_held_on;
        $event_obj->descrption = $request->descrption;

        if ($event_obj->save()) {
            echo json_encode(array('msg' => 'Event has been Added successfully !', 'status' => 'success'));
            die;
        }
        echo json_encode(array('msg' => 'Something went wrong.', 'status' => 'failed'));
        die;
    }

    public function update(Request $request)
    {
        $event_up = UpcomingEvent::find($request->id);
        $event_up->title = $request->title;
        $event_up->event_type = $request->event_type;
        $event_up->event_held_on = $request->event_held_on;
        $event_up->descrption = $request->descrption;

        if ($event_up->save()) {
            echo json_encode(array('msg' => 'Event has been updated successfully !', 'status' => 'success'));
            die;
        }
        echo json_encode(array('msg' => 'Something went wrong.', 'status' => 'failed'));
        die;
    }
    public function update_event(Request $request)
    {
        echo "<pre>";
        print_r($request);
        die;
        // Validate the incoming request data
        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'event_type' => 'required|boolean',
            'event_held_on' => 'required|date',
            'descrption' => 'nullable|string',
        ]);

        // // Find the existing UpcomingEvent by ID
        // $upcomingEvent = UpcomingEvent::find($id);

        // // Check if the event exists
        // if (!$upcomingEvent) {
        //     return redirect()->back()->with('error', 'Event not found.');
        // }

        // // Update the event with the validated data
        // $upcomingEvent->fill($validatedData);

        // // Save the updated event to the database
        // $upcomingEvent->save();

        // // Redirect or perform other actions as needed
        // return redirect()->route('upcoming-events.index')->with('success', 'Event updated successfully!');
    }
    public function delete($id)
    {
        $u_event = UpcomingEvent::find($id);
        if ($u_event) {
            if ($u_event->delete()) {
                echo 'true';
            }
        } else {
            echo 'Event does not exist.';
        }
        die;
    }
    public function editEvent($eventId)
    {
        $event = UpcomingEvent::find($eventId);
        return response()->json($event);
    }
}
