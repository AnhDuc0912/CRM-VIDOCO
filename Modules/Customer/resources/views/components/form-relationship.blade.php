<div class="card shadow-none border mb-0 radius-15">
    <div class="card-body">
        <div class="row g-3 mb-4">
            <h4 class="">Sở thích cá nhân</h4>
            <textarea class="form-control" name="relationship[personal_interests]" id="personal_interests"
                placeholder="Nhập nội dung..." rows="3" {{ request()->routeIs('customers.show') ? 'disabled' : '' }}>{{ old('relationship.personal_interests') ?? (!empty($customer) ? $customer->behaviors?->personal_interests : '') }}</textarea>
        </div>
        <div class="row g-3 mb-4">
            <h4 class="">Chủ đề quan tâm</h4>
            <textarea class="form-control" name="relationship[topics_of_interest]" id="topics_of_interest"
                placeholder="Nhập nội dung..." rows="3" {{ request()->routeIs('customers.show') ? 'disabled' : '' }}>{{ old('relationship.topics_of_interest') ?? (!empty($customer) ? $customer->behaviors?->topics_of_interest : '') }}</textarea>
        </div>
        <div class="row g-3 mb-4">
            <h4 class="">Quan điểm tôn giáo, chính trị</h4>
            <textarea class="form-control" name="relationship[religious_political_views]" id="religious_political_views"
                placeholder="Nhập nội dung..." rows="3" {{ request()->routeIs('customers.show') ? 'disabled' : '' }}>{{ old('relationship.religious_political_views') ?? (!empty($customer) ? $customer->behaviors?->religious_political_views : '') }}</textarea>
        </div>
        @if (!request()->routeIs('customers.show'))
            <div class="row g-3 mb-4 text-center">
                <div class="col-12">
                    <button class="btn btn-primary" type="submit">Lưu Dữ
                        Liệu</button>
                </div>
            </div>
        @endif
    </div>
</div>
