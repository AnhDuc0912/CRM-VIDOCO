<?php

namespace Modules\Customer\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Customer\Enums\PreferredContactTimeEnum;
use Modules\Customer\Enums\ContactMethodEnum;
use Modules\Customer\Enums\ConsumptionHabitEnum;
use Modules\Customer\Enums\PurchaseDecisionTimeEnum;
use Modules\Customer\Enums\PriceSensitivityEnum;
use Modules\Customer\Enums\PurchaseInfluencerEnum;
use Modules\Customer\Enums\PersonalityTypeEnum;
use Modules\Customer\Enums\SensitivePointEnum;
use Modules\Customer\Enums\FavoritePointEnum;
use Modules\Customer\Enums\SpecialReactionEnum;
use Modules\Customer\Enums\CommonEmotionEnum;
// use Modules\Customer\Database\Factories\CustomerBehaviorsFactory;

class CustomerBehaviors extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'preferred_contact_time',
        'contact_methods',
        'other_contact_method',
        'consumption_habits',
        'purchase_decision_time',
        'price_sensitivity',
        'purchase_influencer',
        'personality_traits',
        'attention_points',
        'other_attention_point',
        'preferences',
        'salutation',
        'special_reactions',
        'other_special_reaction',
        'common_emotion',
        'personal_interests',
        'topics_of_interest',
        'religious_political_views',
        'customer_id',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'contact_methods' => 'array',
        'consumption_habits' => 'array',
        'attention_points' => 'array',
        'preferences' => 'array',
        'special_reactions' => 'array',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    /**
     * Get preferred contact time label
     */
    public function getPreferredContactTimeLabelAttribute(): ?string
    {
        return $this->preferred_contact_time ? PreferredContactTimeEnum::getLabel($this->preferred_contact_time) : null;
    }

    /**
     * Get purchase decision time label
     */
    public function getPurchaseDecisionTimeLabelAttribute(): ?string
    {
        return $this->purchase_decision_time ? PurchaseDecisionTimeEnum::getLabel($this->purchase_decision_time) : null;
    }

    /**
     * Get price sensitivity label
     */
    public function getPriceSensitivityLabelAttribute(): ?string
    {
        return $this->price_sensitivity ? PriceSensitivityEnum::getLabel($this->price_sensitivity) : null;
    }

    /**
     * Get purchase influencer label
     */
    public function getPurchaseInfluencerLabelAttribute(): ?string
    {
        return $this->purchase_influencer ? PurchaseInfluencerEnum::getLabel($this->purchase_influencer) : null;
    }

    /**
     * Get common emotion label
     */
    public function getCommonEmotionLabelAttribute(): ?string
    {
        return $this->common_emotion ? CommonEmotionEnum::getLabel($this->common_emotion) : null;
    }

    /**
     * Get personality traits label
     */
    public function getPersonalityTraitsLabelAttribute(): ?string
    {
        return $this->personality_traits ? PersonalityTypeEnum::getLabel($this->personality_traits) : null;
    }

    /**
     * Get contact methods labels
     */
    public function getContactMethodsLabelsAttribute(): array
    {
        if (!$this->contact_methods) {
            return [];
        }

        return array_map(function ($method) {
            return ContactMethodEnum::getLabel($method);
        }, $this->contact_methods);
    }

    /**
     * Get consumption habits labels
     */
    public function getConsumptionHabitsLabelsAttribute(): array
    {
        if (!$this->consumption_habits) {
            return [];
        }

        return array_map(function ($habit) {
            return ConsumptionHabitEnum::getLabel($habit);
        }, $this->consumption_habits);
    }

    /**
     * Get attention points labels
     */
    public function getAttentionPointsLabelsAttribute(): array
    {
        if (!$this->attention_points) {
            return [];
        }

        return array_map(function ($point) {
            return SensitivePointEnum::getLabel($point);
        }, $this->attention_points);
    }

    /**
     * Get preferences labels
     */
    public function getPreferencesLabelsAttribute(): array
    {
        if (!$this->preferences) {
            return [];
        }

        return array_map(function ($preference) {
            return FavoritePointEnum::getLabel($preference);
        }, $this->preferences);
    }

    /**
     * Get special reactions labels
     */
    public function getSpecialReactionsLabelsAttribute(): array
    {
        if (!$this->special_reactions) {
            return [];
        }

        return array_map(function ($reaction) {
            return SpecialReactionEnum::getLabel($reaction);
        }, $this->special_reactions);
    }

    public function getContactMethodsAttribute($value)
    {
        $decoded = json_decode($value, true);

        if (is_array($decoded)) {
            return array_map(function ($item) {
                return is_numeric($item) ? (int) $item : $item;
            }, $decoded);
        }

        return $decoded;
    }

    public function setContactMethodsAttribute($value)
    {
        if (is_array($value)) {
            $value = array_map(function ($item) {
                return is_numeric($item) ? (int) $item : $item;
            }, $value);

            // Loại bỏ null values
            $value = array_filter($value, function ($item) {
                return !is_null($item);
            });

            $this->attributes['contact_methods'] = json_encode(array_values($value));
        } else {
            $this->attributes['contact_methods'] = $value;
        }
    }

    public function getConsumptionHabitsAttribute($value)
    {
        $decoded = json_decode($value, true);

        if (is_array($decoded)) {
            return array_map(function ($item) {
                return is_numeric($item) ? (int) $item : $item;
            }, $decoded);
        }

        return $decoded;
    }

    public function setConsumptionHabitsAttribute($value)
    {
        if (is_array($value)) {
            $value = array_map(function ($item) {
                return is_numeric($item) ? (int) $item : $item;
            }, $value);

            $this->attributes['consumption_habits'] = json_encode(array_values($value));
        } else {
            $this->attributes['consumption_habits'] = $value;
        }
    }

    public function getAttentionPointsAttribute($value)
    {
        $decoded = json_decode($value, true);

        if (is_array($decoded)) {
            return array_map(function ($item) {
                return is_numeric($item) ? (int) $item : $item;
            }, $decoded);
        }

        return $decoded;
    }

    public function setAttentionPointsAttribute($value)
    {
        if (is_array($value)) {
            $value = array_map(function ($item) {
                return is_numeric($item) ? (int) $item : $item;
            }, $value);

            $this->attributes['attention_points'] = json_encode(array_values($value));
        } else {
            $this->attributes['attention_points'] = $value;
        }
    }

    public function getPreferencesAttribute($value)
    {
        $decoded = json_decode($value, true);

        if (is_array($decoded)) {
            return array_map(function ($item) {
                return is_numeric($item) ? (int) $item : $item;
            }, $decoded);
        }

        return $decoded;
    }

    public function setPreferencesAttribute($value)
    {
        if (is_array($value)) {
            $value = array_map(function ($item) {
                return is_numeric($item) ? (int) $item : $item;
            }, $value);

            $this->attributes['preferences'] = json_encode(array_values($value));
        } else {
            $this->attributes['preferences'] = $value;
        }
    }

    public function getSpecialReactionsAttribute($value)
    {
        $decoded = json_decode($value, true);

        if (is_array($decoded)) {
            return array_map(function ($item) {
                return is_numeric($item) ? (int) $item : $item;
            }, $decoded);
        }

        return $decoded;
    }

    public function setSpecialReactionsAttribute($value)
    {
        if (is_array($value)) {
            $value = array_map(function ($item) {
                return is_numeric($item) ? (int) $item : $item;
            }, $value);

            $this->attributes['special_reactions'] = json_encode(array_values($value));
        } else {
            $this->attributes['special_reactions'] = $value;
        }
    }
}
