<?php

namespace App\Http\Controllers;

use App\DataTables\EventDataTable;
use App\Http\Requests\CreateEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Models\Category;
use App\Models\EventImage;
use App\Repositories\EventRepository;
use App\Rules\UploadCount;
use Flash;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Prettus\Validator\Exceptions\ValidatorException;
use Response;

class EventController extends AppBaseController
{
    /** @var  EventRepository */
    private $eventRepository;

    public function __construct(EventRepository $eventRepo)
    {
        $this->eventRepository = $eventRepo;
    }

    /**
     * Display a listing of the Event.
     *
     * @param EventDataTable $eventDataTable
     * @return Response
     */
    public function index(EventDataTable $eventDataTable)
    {
        return $eventDataTable->render('events.index');
    }

    /**
     * Show the form for creating a new Event.
     *
     */
    public function create()
    {
        $categories = Category::query()->pluck('name', 'id');
        return view('events.create', compact('categories'));
    }

    /**
     * Store a newly created Event in storage.
     *
     * @param CreateEventRequest $request
     *
     */
    public function store(CreateEventRequest $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'images.*' => 'required|mimes:jpeg,jpg,png',
            'images' => [new UploadCount()],
        ]);

        if ($validator->fails()) {
            Flash::error($validator->errors()->all());
            return redirect()->back()
                ->withErrors($validator->errors()->all())
                ->withInput($input);
        }

        DB::beginTransaction();
        try {
            if ($request->hasFile('banner')) {
                $file = $request->file('banner');
                $banner = 'Banner_' . time() . '_' . $file->getClientOriginalName();
                $file->storePubliclyAs('public/banners', $banner);
                $input['banner'] = "/storage/banners/" . $banner;
            }
            $event = $this->eventRepository->create($input);

            if ($request->hasFile('images')) {
                $files = $request->file('images');
                foreach ($files as $image) {
                    $img = 'Image_' . time() . '_' . $image->getClientOriginalName();
                    $image->storePubliclyAs('public/images', $img);
                    $eventImage = EventImage::query()->create([
                        'event_id' => $event->id,
                        'path' => "/storage/images/" . $img
                    ]);
                }
            }

            DB::commit();
            Flash::success('Event saved successfully.');

            return redirect(route('events.index'));
        } catch (ValidatorException $e) {
            Flash::error([$e->getMessage()]);

            return redirect()->back()
                ->withErrors([$e->getMessage()])
                ->withInput($input);
        }


    }

    /**
     * Display the specified Event.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $event = $this->eventRepository->find($id);

        if (empty($event)) {
            Flash::error('Event not found');

            return redirect(route('events.index'));
        }

        return view('events.show')->with('event', $event);
    }

    /**
     * Show the form for editing the specified Event.
     *
     * @param int $id
     *
     */
    public function edit($id)
    {
        $event = $this->eventRepository->find($id);

        if (empty($event)) {
            Flash::error('Event not found');

            return redirect(route('events.index'));
        }


        $categories = Category::query()->pluck('name', 'id');
        return view('events.edit', compact('categories'))->with('event', $event);
    }

    /**
     * Update the specified Event in storage.
     *
     * @param int $id
     * @param UpdateEventRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateEventRequest $request)
    {
        $event = $this->eventRepository->find($id);

        if (empty($event)) {
            Flash::error('Event not found');

            return redirect(route('events.index'));
        }

        $event = $this->eventRepository->update($request->all(), $id);

        Flash::success('Event updated successfully.');

        return redirect(route('events.index'));
    }

    /**
     * Remove the specified Event from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $event = $this->eventRepository->find($id);

        if (empty($event)) {
            Flash::error('Event not found');

            return redirect(route('events.index'));
        }

        $this->eventRepository->delete($id);

        Flash::success('Event deleted successfully.');

        return redirect(route('events.index'));
    }

    public function deleteImage(Request $request){
        $input = $request->all();

        $validator = Validator::make($input,[
            'id' => 'required|numeric'
        ]);

        if ($validator->fails()){
            $this->sendError($validator->errors()->all()[0], 422);
        }

        DB::beginTransaction();

        try {
            $image = EventImage::query()->findOrFail($request->post('id'))->delete();
            if ($image) {
                DB::commit();
                return $this->sendSuccess("Image Deleted Successfully");
            }

            return $this->sendError("Some Error Occurred!", 400);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->sendError($e->getMessage(), 500);
        }
    }
}
