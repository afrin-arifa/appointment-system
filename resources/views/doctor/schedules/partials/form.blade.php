<div class="row g-3">
    <div class="col-md-6"><label class="form-label" for="available_date">Available date</label><input class="form-control" id="available_date" type="date" name="available_date" min="{{ today()->format('Y-m-d') }}" value="{{ old('available_date', isset($schedule) ? $schedule->available_date->format('Y-m-d') : '') }}" required></div>
    <div class="col-md-6"><label class="form-label" for="appointment_duration">Appointment duration</label><select class="form-select" id="appointment_duration" name="appointment_duration" required>
            @foreach ([15, 20, 30, 45, 60] as $duration)
                <option value="{{ $duration }}" @selected(old('appointment_duration', $schedule->appointment_duration ?? 30) == $duration)>{{ $duration }} minutes</option>
            @endforeach
        </select></div>
    <div class="col-md-6"><label class="form-label" for="start_time">Start time</label><input class="form-control" id="start_time" type="time" name="start_time" value="{{ old('start_time', isset($schedule) ? \Carbon\Carbon::parse($schedule->start_time)->format('H:i') : '') }}" required></div>
    <div class="col-md-6"><label class="form-label" for="end_time">End time</label><input class="form-control" id="end_time" type="time" name="end_time" value="{{ old('end_time', isset($schedule) ? \Carbon\Carbon::parse($schedule->end_time)->format('H:i') : '') }}" required></div>
    <div class="col-12">
        <div class="form-check"><input class="form-check-input" id="is_available" type="checkbox" name="is_available" value="1" @checked(old('is_available', $schedule->is_available ?? true))><label class="form-check-label" for="is_available">This schedule is available for booking</label></div>
    </div>
</div>
