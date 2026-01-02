@use('Modules\Customer\Enums\PreferredContactTimeEnum')
@use('Modules\Customer\Enums\ContactMethodEnum')
@use('Modules\Customer\Enums\ConsumptionHabitEnum')
@use('Modules\Customer\Enums\PurchaseDecisionTimeEnum')
@use('Modules\Customer\Enums\PriceSensitivityEnum')
@use('Modules\Customer\Enums\PurchaseInfluencerEnum')
@use('Modules\Customer\Enums\PersonalityTypeEnum')
@use('Modules\Customer\Enums\SensitivePointEnum')
@use('Modules\Customer\Enums\FavoritePointEnum')
@use('Modules\Customer\Enums\SalutationEnum')
@use('Modules\Customer\Enums\SpecialReactionEnum')
@use('Modules\Customer\Enums\CommonEmotionEnum')

<div class="card shadow-none border mb-0 radius-15">
    <div class="card-body">
        <div class="row g-3 mb-4">
            <h4>Thời điểm & Liên hệ</h4>
            <div class="row g-3">
                <div class="col-3">
                    <label class="form-label">Thời điểm phản hồi tốt</label>
                </div>
                <div class="col-9">
                    @foreach (PreferredContactTimeEnum::getValues() as $value)
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="behavior[preferred_contact_time]"
                                id="preferred_contact_time_{{ $value }}" value="{{ $value }}"
                                {{ request()->routeIs('customers.show') ? 'disabled' : '' }}
                                {{ old('behavior.preferred_contact_time') ? (old('behavior.preferred_contact_time') == $value ? 'checked' : '') : (!empty($customer) && $customer->behaviors?->preferred_contact_time == $value ? 'checked' : '') }}>
                            <label class="form-check-label"
                                for="preferred_contact_time_{{ $value }}">{{ PreferredContactTimeEnum::getLabel($value) }}</label>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="row g-3">
                <div class="col-3">
                    <label class="form-label">Cách ưa thích liên hệ</label>
                </div>
                <div class="col-9">
                    @foreach (ContactMethodEnum::getValues() as $value)
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="contact_method_{{ $value }}"
                                name="behavior[contact_methods][]" value="{{ $value }}"
                                {{ request()->routeIs('customers.show') ? 'disabled' : '' }}
                                {{ old('behavior.contact_methods')
                                    ? (in_array($value, (array) old('behavior.contact_methods', []))
                                        ? 'checked'
                                        : '')
                                    : (in_array($value, (array) data_get($customer ?? [], 'behaviors.contact_methods', []))
                                        ? 'checked'
                                        : '') }}>
                            <label class="form-check-label"
                                for="contact_method_{{ $value }}">{{ ContactMethodEnum::getLabel($value) }}</label>
                        </div>
                    @endforeach
                    <div class="form-check form-check-inline">
                        <input class="form-control" type="text" name="behavior[other_contact_method]"
                            placeholder="Nội dung khác" {{ request()->routeIs('customers.show') ? 'disabled' : '' }}
                            value="{{ old('behavior.other_contact_method') ?? (!empty($customer) && $customer->behaviors?->other_contact_method ? $customer->behaviors?->other_contact_method : '') }}">
                    </div>
                </div>
            </div>

            <h4>Thói quen tiêu dùng</h4>

            <div class="row g-3">
                <div class="col-3">
                    <label class="form-label">Thói quen tiêu dùng</label>
                </div>
                <div class="col-9">
                    @foreach (ConsumptionHabitEnum::getValues() as $value)
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="consumption_habit_{{ $value }}"
                                name="behavior[consumption_habits][]" value="{{ $value }}"
                                {{ request()->routeIs('customers.show') ? 'disabled' : '' }}
                                {{ old('behavior.consumption_habits')
                                    ? (in_array($value, (array) old('behavior.consumption_habits', []))
                                        ? 'checked'
                                        : '')
                                    : (in_array($value, (array) data_get($customer ?? [], 'behaviors.consumption_habits', []))
                                        ? 'checked'
                                        : '') }}>
                            <label class="form-check-label"
                                for="consumption_habit_{{ $value }}">{{ ConsumptionHabitEnum::getLabel($value) }}</label>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="row g-3">
                <div class="col-3">
                    <label class="form-label">Thời gian ra quyết định
                        mua</label>
                </div>
                <div class="col-9">
                    @foreach (PurchaseDecisionTimeEnum::getValues() as $value)
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="behavior[purchase_decision_time]"
                                id="purchase_decision_time_{{ $value }}" value="{{ $value }}"
                                {{ request()->routeIs('customers.show') ? 'disabled' : '' }}
                                {{ old('behavior.purchase_decision_time') ? (old('behavior.purchase_decision_time') == $value ? 'checked' : '') : (!empty($customer) && $customer->behaviors?->purchase_decision_time == $value ? 'checked' : '') }}>
                            <label class="form-check-label"
                                for="purchase_decision_time_{{ $value }}">{{ PurchaseDecisionTimeEnum::getLabel($value) }}</label>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="row g-3">
                <div class="col-3">
                    <label class="form-label">Độ nhạy cảm với giá</label>
                </div>
                <div class="col-9">
                    @foreach (PriceSensitivityEnum::getValues() as $value)
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="behavior[price_sensitivity]"
                                id="price_sensitivity_{{ $value }}" value="{{ $value }}"
                                {{ request()->routeIs('customers.show') ? 'disabled' : '' }}
                                {{ old('behavior.price_sensitivity') ? (old('behavior.price_sensitivity') == $value ? 'checked' : '') : (!empty($customer) && $customer->behaviors?->price_sensitivity == $value ? 'checked' : '') }}>
                            <label class="form-check-label"
                                for="price_sensitivity_{{ $value }}">{{ PriceSensitivityEnum::getLabel($value) }}</label>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="row g-3">
                <div class="col-3">
                    <label class="form-label">Người ảnh hưởng đến quyết
                        định mua</label>
                </div>
                <div class="col-9">
                    @foreach (PurchaseInfluencerEnum::getValues() as $value)
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="behavior[purchase_influencer]"
                                id="purchase_influencer_{{ $value }}" value="{{ $value }}"
                                {{ request()->routeIs('customers.show') ? 'disabled' : '' }}
                                {{ old('behavior.purchase_influencer') ? (old('behavior.purchase_influencer') == $value ? 'checked' : '') : (!empty($customer) && $customer->behaviors?->purchase_influencer == $value ? 'checked' : '') }}>
                            <label class="form-check-label"
                                for="purchase_influencer_{{ $value }}">{{ PurchaseInfluencerEnum::getLabel($value) }}</label>
                        </div>
                    @endforeach
                </div>
            </div>

            <h4>Tâm lý & Tính cách</h4>

            <div class="row g-3">
                <div class="col-3">
                    <label class="form-label">Loại tính cách chính</label>
                </div>
                <div class="col-9">
                    @foreach (PersonalityTypeEnum::getValues() as $value)
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="behavior[personality_traits]"
                                id="personality_traits_{{ $value }}" value="{{ $value }}"
                                {{ request()->routeIs('customers.show') ? 'disabled' : '' }}
                                {{ old('behavior.personality_traits') ? (old('behavior.personality_traits') == $value ? 'checked' : '') : (!empty($customer) && $customer->behaviors?->personality_traits == $value ? 'checked' : '') }}>
                            <label class="form-check-label"
                                for="personality_traits_{{ $value }}">{{ PersonalityTypeEnum::getLabel($value) }}</label>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="row g-3">
                <div class="col-3">
                    <label class="form-label">Điểm nhạy cảm lưu ý</label>
                </div>
                <div class="col-9">
                    @foreach (SensitivePointEnum::getValues() as $value)
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="behavior[attention_points][]"
                                id="sensitive_point_{{ $value }}" value="{{ $value }}"
                                {{ request()->routeIs('customers.show') ? 'disabled' : '' }}
                                {{ old('behavior.attention_points')
                                    ? (in_array($value, (array) old('behavior.attention_points', []))
                                        ? 'checked'
                                        : '')
                                    : (in_array($value, (array) data_get($customer ?? [], 'behaviors.attention_points', []))
                                        ? 'checked'
                                        : '') }}>
                            <label class="form-check-label"
                                for="sensitive_point_{{ $value }}">{{ SensitivePointEnum::getLabel($value) }}</label>
                        </div>
                    @endforeach

                    <div class="form-check form-check-inline">
                        <input class="form-control" type="text" name="behavior[other_sensitive_point]"
                            placeholder="Nội dung khác" {{ request()->routeIs('customers.show') ? 'disabled' : '' }}
                            value="{{ old('behavior.other_sensitive_point') ?? (!empty($customer) && $customer->behaviors?->other_sensitive_point ? $customer->behaviors?->other_sensitive_point : '') }}">
                    </div>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-3">
                    <label class="form-label">Điểm yêu thích</label>
                </div>
                <div class="col-9">
                    @foreach (FavoritePointEnum::getValues() as $value)
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="behavior[preferences][]"
                                id="favorite_point_{{ $value }}" value="{{ $value }}"
                                {{ request()->routeIs('customers.show') ? 'disabled' : '' }}
                                {{ request()->routeIs('customers.show') ? 'disabled' : '' }}
                                {{ old('behavior.preferences')
                                    ? (in_array($value, (array) old('behavior.preferences', []))
                                        ? 'checked'
                                        : '')
                                    : (in_array($value, (array) data_get($customer ?? [], 'behaviors.preferences', []))
                                        ? 'checked'
                                        : '') }}>
                            <label class="form-check-label"
                                for="favorite_point_{{ $value }}">{{ FavoritePointEnum::getLabel($value) }}</label>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="row g-3">
                <div class="col-3">
                    <label class="form-label">Cách xưng hô phù hợp</label>
                </div>
                <div class="col-9">
                    @foreach (SalutationEnum::getValues() as $value)
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="behavior[salutation]"
                                id="salutation_{{ $value }}" value="{{ $value }}"
                                {{ request()->routeIs('customers.show') ? 'disabled' : '' }}
                                {{ old('behavior.salutation') ? (old('behavior.salutation') == $value ? 'checked' : '') : (!empty($customer) && $customer->behaviors?->salutation == $value ? 'checked' : '') }}>
                            <label class="form-check-label"
                                for="salutation_{{ $value }}">{{ SalutationEnum::getLabel($value) }}</label>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="row g-3">
                <div class="col-3">
                    <label class="form-label">Phản ứng đặc biệt đã từng
                        gặp</label>
                </div>
                <div class="col-9">
                    @foreach (SpecialReactionEnum::getValues() as $value)
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="behavior[special_reactions][]"
                                id="special_reaction_{{ $value }}" value="{{ $value }}"
                                {{ request()->routeIs('customers.show') ? 'disabled' : '' }}
                                {{ old('behavior.special_reactions')
                                    ? (in_array($value, (array) old('behavior.special_reactions', []))
                                        ? 'checked'
                                        : '')
                                    : (in_array($value, (array) data_get($customer ?? [], 'behaviors.special_reactions', []))
                                        ? 'checked'
                                        : '') }}>
                            <label class="form-check-label"
                                for="special_reaction_{{ $value }}">{{ SpecialReactionEnum::getLabel($value) }}</label>
                        </div>
                    @endforeach
                    <div class="form-check form-check-inline">
                        <input class="form-control" type="text" name="behavior[other_special_reaction]"
                            placeholder="Nội dung khác" {{ request()->routeIs('customers.show') ? 'disabled' : '' }}
                            value="{{ old('behavior.other_special_reaction') ?? (!empty($customer) && $customer->behaviors?->other_special_reaction ? $customer->behaviors?->other_special_reaction : '') }}">
                    </div>
                </div>
            </div>
            <div class="row g-3">
                <div class="col-3">
                    <label class="form-label">Cảm xúc thường gặp khi trao
                        đổi</label>
                </div>
                <div class="col-9">
                    @foreach (CommonEmotionEnum::getValues() as $value)
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="behavior[common_emotion]"
                                id="common_emotion_{{ $value }}" value="{{ $value }}"
                                {{ request()->routeIs('customers.show') ? 'disabled' : '' }}
                                {{ old('behavior.common_emotion') ? (old('behavior.common_emotion') == $value ? 'checked' : '') : (!empty($customer) && $customer->behaviors?->common_emotion == $value ? 'checked' : '') }}>
                            <label class="form-check-label"
                                for="common_emotion_{{ $value }}">{{ CommonEmotionEnum::getLabel($value) }}</label>
                        </div>
                    @endforeach
                </div>
            </div>

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
