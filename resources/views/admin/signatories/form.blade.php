@csrf

<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="name" class="form-label">نام</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $signatory->name ?? '') }}" required>
            </div>

            <div class="col-md-6 mb-3">
                <label for="email" class="form-label">ایمیل</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $signatory->email ?? '') }}">
            </div>

            <div class="col-md-6 mb-3">
                <label for="phone" class="form-label">تلفن</label>
                <input type="text" name="phone" class="form-control" value="{{ old('phone', $signatory->phone ?? '') }}">
            </div>

            <div class="col-md-6 mb-3">
                <label for="type" class="form-label">نوع امضا کننده</label>
                <select name="type" class="form-select" required>
                    @foreach(['علمی','دولتی','فنی و حرفه ای','پارک علم','سایر'] as $type)
                        <option value="{{ $type }}" {{ (old('type', $signatory->type ?? '') == $type) ? 'selected' : '' }}>{{ $type }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label for="level" class="form-label">سطح</label>
                <input type="number" name="level" class="form-control" value="{{ old('level', $signatory->level ?? 1) }}" min="1" max="10" required>
            </div>
        </div>
    </div>

    <div class="card-footer">
        <button type="submit" class="btn btn-success">{{ $buttonText ?? 'ثبت' }}</button>
        <a href="{{ route('signatories.index') }}" class="btn btn-secondary">بازگشت</a>
    </div>
</div>
