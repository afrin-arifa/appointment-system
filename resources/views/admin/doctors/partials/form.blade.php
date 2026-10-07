<div class="row g-3">
    <div class="col-md-6"><label class="form-label" for="name">Doctor name</label><input class="form-control" id="name" name="name" value="{{ old('name', $doctor->name ?? '') }}" required></div>
    <div class="col-md-6"><label class="form-label" for="email">Email address</label><input class="form-control" id="email" type="email" name="email" value="{{ old('email', $doctor->email ?? '') }}" required></div>
    <div class="col-md-6"><label class="form-label" for="specialization">Specialization</label><input class="form-control" id="specialization" name="specialization" value="{{ old('specialization', $doctor->doctorProfile?->specialization ?? '') }}" required></div>
    <div class="col-md-6"><label class="form-label" for="phone">Phone</label><input class="form-control" id="phone" name="phone" value="{{ old('phone', $doctor->doctorProfile?->phone ?? '') }}" required></div>
    <div class="col-md-6"><label class="form-label" for="experience">Experience (years)</label><input class="form-control" id="experience" type="number" min="0" name="experience" value="{{ old('experience', $doctor->doctorProfile?->experience ?? 0) }}" required></div>
    <div class="col-md-6"><label class="form-label" for="consultation_fee">Consultation fee</label><input class="form-control" id="consultation_fee" type="number" step="0.01" min="0" name="consultation_fee" value="{{ old('consultation_fee', $doctor->doctorProfile?->consultation_fee ?? '') }}" required></div>
    <div class="col-12"><label class="form-label" for="chamber_address">Chamber address</label>
        <textarea class="form-control" id="chamber_address" name="chamber_address" rows="3" required>{{ old('chamber_address', $doctor->doctorProfile?->chamber_address ?? '') }}</textarea>
    </div>
    <div class="col-md-6"><label class="form-label" for="password">{{ isset($doctor) ? 'New password (optional)' : 'Password' }}</label><input class="form-control" id="password" type="password" name="password" @unless (isset($doctor)) required @endunless></div>
    <div class="col-md-6"><label class="form-label" for="password_confirmation">Confirm password</label><input class="form-control" id="password_confirmation" type="password" name="password_confirmation"></div>
</div>
