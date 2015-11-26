<?php

class RobotScheduleController extends \BaseController
{

    /**
     * Display a listing of the resource.
     * GET /robot
     *
     * @return Response
     */
    public function index($robotSn)
    {
        $schedules = Robot::findBySn($robotSn)->schedules()->get();
        return JsonView::make('success', ['schedules'=>$schedules]);
    }

    /**
     * Show the form for creating a new resource.
     * GET /robot/create
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     * POST /robot
     *
     * @return Response
     */
    public function store($robotSn)
    {
        $robot = Robot::findBySn($robotSn);

        $data = Input::all();
        if (isset($data['repeat']) && ! is_array($data['repeat'])) {
            $data['repeat'] = json_decode($data['repeat'], true);
        }

        App::make('Robot\Validators\ScheduleValidator')->validate($data);

        $schedule = new Schedule($data);
        $schedule = $robot->schedules()->save($schedule);

        return JsonView::make('success');
    }

    /**
     * Display the specified resource.
     * GET /robot/{id}
     *
     * @param  int  $id
     * @return Response
     */
    public function show($robotSn, $scheduleId)
    {
        $robot = Robot::findBySn($robotSn);
        $schedule = $robot->schedules()->findOrFail($scheduleId);

        return JsonView::make('success', ['schedule'=>$schedule]);
    }

    /**
     * Show the form for editing the specified resource.
     * GET /robot/{id}/edit
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     * PUT /robot/{id}
     *
     * @param  int  $id
     * @return Response
     */
    public function update($robotSn, $scheduleId)
    {
        $robot = Robot::findBySn($robotSn);
        $schedule = $robot->schedules()->findOrFail($scheduleId);

        $data = Input::all();
        if (isset($data['repeat']) && ! is_array($data['repeat'])) {
            $data['repeat'] = json_decode($data['repeat'], true);
        }

        App::make('Robot\Validators\ScheduleValidator')->validate($data);

        $schedule->update($data);

        return JsonView::make('success');
    }

    /**
     * Remove the specified resource from storage.
     * DELETE /robot/{id}
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($robotSn, $scheduleId)
    {
        $robot = Robot::findBySn($robotSn);
        $schedule = $robot->schedules()->findOrFail($scheduleId);

        $schedule->delete();

        return JsonView::make('success');
    }

}
