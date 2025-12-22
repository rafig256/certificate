<form action="{{ $organization->id ?? null ? route('organizations.update', $organization) : route('organizations.store') }}" method="POST">
    @csrf
    @if(isset($organization))
        @method('PUT')
    @endif
    <div class="form-group">
        <label>نام <span class="text-warning">*</span> </label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $organization->name ?? '') }}" required>
    </div>
    <div class="form-group">
        <label>نام نمایشی<span class="text-warning">*</span></label>
        <input type="text" name="slug" class="form-control" value="{{ old('slug', $organization->slug ?? '') }}" required>
    </div>
    <div class="form-group">
        <label>ایمیل</label>
        <input type="email" name="email" class="form-control" value="{{ old('email', $organization->email ?? '') }}">
    </div>
    <div class="form-group">
        <label>شماره موبایل</label>
        <input type="text" name="mobile" class="form-control" value="{{ old('mobile', $organization->mobile ?? '') }}">
    </div>
    <div class="form-group">
        <label>تلفن ثابت</label>
        <input type="text" name="phone" class="form-control" value="{{ old('phone', $organization->phone ?? '') }}">
    </div>
    <div class="form-group">
        <label>وبسایت</label>
        <input type="text" name="website" class="form-control" value="{{ old('website', $organization->website ?? '') }}">
    </div>
    <div class="form-group">
        <label>آدرس</label>
        <textarea name="address" class="form-control">{{ old('address', $organization->address ?? '') }}</textarea>
    </div>
    <button type="submit" class="btn btn-success mt-2">ذخیره</button>
</form>
