<?php

namespace Modules\Customer\database\seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Customer\Models\CustomerBehaviors;
use Modules\Customer\Enums\PreferredContactTimeEnum;
use Modules\Customer\Enums\ContactMethodEnum;
use Modules\Customer\Enums\ConsumptionHabitEnum;
use Modules\Customer\Enums\PurchaseDecisionTimeEnum;
use Modules\Customer\Enums\PriceSensitivityEnum;
use Modules\Customer\Enums\PurchaseInfluencerEnum;
use Modules\Customer\Enums\PersonalityTypeEnum;
use Modules\Customer\Enums\SensitivePointEnum;
use Modules\Customer\Enums\FavoritePointEnum;
use Modules\Customer\Enums\SalutationEnum;
use Modules\Customer\Enums\SpecialReactionEnum;
use Modules\Customer\Enums\CommonEmotionEnum;

class CustomerBehaviorSeeder extends Seeder
{
    public function run()
    {
        Model::unguard();

        $customers = [
            [
                // Thông tin cơ bản
                'customer_id' => 1,

                // === THỜI ĐIỂM & LIÊN HỆ ===
                // Thời điểm phản hồi tốt (radio button - chọn 1)
                'preferred_contact_time' => PreferredContactTimeEnum::MORNING, // Sáng

                // Cách ưa thích liên hệ (checkbox - chọn nhiều)
                'contact_methods' => [
                    ContactMethodEnum::DIRECT_CALL,    // Gọi trực tiếp
                    ContactMethodEnum::ZALO_ONLY       // Chỉ Zalo
                ],
                'other_contact_method' => 'Email', // Nội dung khác

                // === THÓI QUEN TIÊU DÙNG ===
                // Thói quen tiêu dùng (checkbox - chọn nhiều)
                'consumption_habits' => [
                    ConsumptionHabitEnum::LIKES_PROMOTIONS, // Thích khuyến mãi
                    ConsumptionHabitEnum::LIKES_TO_TRY      // Thích dùng thử trước
                ],

                // Thời gian ra quyết định mua (radio button - chọn 1)
                'purchase_decision_time' => PurchaseDecisionTimeEnum::AVERAGE, // Trung bình (3-5 lần)

                // Độ nhạy cảm với giá (radio button - chọn 1)
                'price_sensitivity' => PriceSensitivityEnum::MODERATE, // Vừa phải

                // Người ảnh hưởng đến quyết định mua (radio button - chọn 1)
                'purchase_influencer' => PurchaseInfluencerEnum::SELF_DECISION, // Tự quyết định

                // === TÂM LÝ & TÍNH CÁCH ===
                // Loại tính cách chính (radio button - chọn 1)
                'personality_traits' => PersonalityTypeEnum::METICULOUS,

                // Điểm nhạy cảm lưu ý (checkbox - chọn nhiều)
                'attention_points' => [
                    SensitivePointEnum::HATES_TOO_MANY_CALLS,  // Ghét gọi điện quá nhiều
                    SensitivePointEnum::HATES_BEING_FORCED     // Không thích bị ép mua
                ],
                'other_attention_point' => 'Không thích bị giục', // Khác

                // Điểm yêu thích (checkbox - chọn nhiều)
                'preferences' => [
                    FavoritePointEnum::LIKES_PRAISE,           // Thích được khen
                    FavoritePointEnum::LIKES_TALKING_FAMILY    // Thích nói về gia đình
                ],

                // Cách xưng hô phù hợp (radio button - chọn 1)
                'salutation' => SalutationEnum::ANH, // Anh

                // Phản ứng đặc biệt đã từng gặp (checkbox - chọn nhiều)
                'special_reactions' => [
                    SpecialReactionEnum::COMPLAINED_QUALITY,   // Từng than phiền chất lượng
                    SpecialReactionEnum::PRAISED_SERVICE       // Từng khen ngợi dịch vụ
                ],
                'other_special_reaction' => 'Từng yêu cầu đổi hàng', // Khác

                // Cảm xúc thường gặp khi trao đổi (radio button - chọn 1)
                'common_emotion' => CommonEmotionEnum::NORMAL, // Bình thường

                // === THÔNG TIN BỔ SUNG ===
                'personal_interests' => 'Thích đọc sách, du lịch',
                'topics_of_interest' => 'Công nghệ, kinh doanh, gia đình',
                'religious_political_views' => 'Không quan tâm',

                'created_at' => '2021-01-01',
                'updated_at' => '2021-01-01',
            ],

            [
                // Thông tin cơ bản
                'customer_id' => 2,

                // === THỜI ĐIỂM & LIÊN HỆ ===
                // Thời điểm phản hồi tốt (radio button - chọn 1)
                'preferred_contact_time' => PreferredContactTimeEnum::AFTERNOON, // Chiều

                // Cách ưa thích liên hệ (checkbox - chọn nhiều)
                'contact_methods' => [
                    ContactMethodEnum::MESSAGE_FIRST,   // Nhắn tin trước
                    ContactMethodEnum::FACEBOOK_ONLY    // Chỉ Facebook
                ],
                'other_contact_method' => null, // Không có nội dung khác

                // === THÓI QUEN TIÊU DÙNG ===
                // Thói quen tiêu dùng (checkbox - chọn nhiều)
                'consumption_habits' => [
                    ConsumptionHabitEnum::ONLY_HIGH_END // Chỉ mua hàng cao cấp
                ],

                // Thời gian ra quyết định mua (radio button - chọn 1)
                'purchase_decision_time' => PurchaseDecisionTimeEnum::FAST, // Nhanh (1-2 lần trao đổi)

                // Độ nhạy cảm với giá (radio button - chọn 1)
                'price_sensitivity' => PriceSensitivityEnum::NOT_IMPORTANT, // Không quan trọng giá

                // Người ảnh hưởng đến quyết định mua (radio button - chọn 1)
                'purchase_influencer' => PurchaseInfluencerEnum::SPOUSE, // Vợ/Chồng

                // === TÂM LÝ & TÍNH CÁCH ===
                // Loại tính cách chính (radio button - chọn 1)
                'personality_traits' => PersonalityTypeEnum::IMPULSIVE,

                // Điểm nhạy cảm lưu ý (checkbox - chọn nhiều)
                'attention_points' => [
                    SensitivePointEnum::HATES_LONG_DELIVERY // Ghét chờ giao hàng lâu
                ],
                'other_attention_point' => null, // Không có điểm khác

                // Điểm yêu thích (checkbox - chọn nhiều)
                'preferences' => [
                    FavoritePointEnum::LIKES_GIFTS,              // Thích được tặng quà
                    FavoritePointEnum::LIKES_PRIORITY_SERVICE    // Thích được ưu tiên dịch vụ riêng
                ],

                // Cách xưng hô phù hợp (radio button - chọn 1)
                'salutation' => SalutationEnum::CHI, // Chị

                // Phản ứng đặc biệt đã từng gặp (checkbox - chọn nhiều)
                'special_reactions' => [
                    SpecialReactionEnum::HAS_BEEN_ANGRY,         // Từng nổi giận
                    SpecialReactionEnum::REQUESTED_STRONG_DISCOUNT // Từng yêu cầu giảm giá mạnh
                ],
                'other_special_reaction' => null, // Không có phản ứng khác

                // Cảm xúc thường gặp khi trao đổi (radio button - chọn 1)
                'common_emotion' => CommonEmotionEnum::HAPPY, // Vui vẻ

                // === THÔNG TIN BỔ SUNG ===
                'personal_interests' => 'Thể thao, âm nhạc',
                'topics_of_interest' => 'Thể thao, giải trí',
                'religious_political_views' => 'Không chia sẻ',

                'created_at' => '2021-01-02',
                'updated_at' => '2021-01-02',
            ],
        ];

        foreach ($customers as $customer) {
            CustomerBehaviors::create($customer);
        }
    }
}
