<?php

namespace Modules\Employee\Enums;

enum JobPositionEnum
{
    // Lãnh đạo
    const CEO = 1;
    const COO = 2;
    const CFO = 3;
    const CMO = 4;
    const CTO = 5;
    // Quản lý
    const HEAD_OF_DESIGN = 6;
    const DIGITAL_MAKETING_MANAGER = 7;
    const HR_MANAGER = 8;
    const ACCOUNTING_MANAGER = 9;
    const TECH_MANAGER = 10;
    const CONTENT_MANAGER  = 11;
    const STRATEGY_MANAGER = 12;
    // Chuyên viên
    const ACCOUNT_EXECUTIVE = 13;
    // Nhân viên
    const COPYWRITER = 14;
    const GRAPHIC_DESIGNER_EMPLOYEE = 15;
    const UI_UX_DESIGNER_EMPLOYEE = 16;
    const VIDEO_EDITOR_EMPLOYEE = 17;
    const PHOTOGRAPHER_EMPLOYEE = 18;
    const DIGITAL_MARKETING_EMPLOYEE = 19;
    const SEO_SPECIALIST_EMPLOYEE = 20;
    const SOCIAL_MEDIA_EXECUTIVE_EMPLOYEE = 21;
    const KOL_INFLUENCER_MANAGER_EMPLOYEE = 22;
    const RECRUITER_EMPLOYEE = 23;
    const FINANCE_ACCOUNTING_EMPLOYEE = 24;
    const IT_SUPPORT_EMPLOYEE = 25;
    const FRONTEND_DEVELOPER_EMPLOYEE = 26;
    const BACKEND_DEVELOPER_EMPLOYEE = 27;
    const FULLSTACK_DEVELOPER_EMPLOYEE = 28;
    const MOBILE_APP_DEVELOPER_EMPLOYEE = 29;
    const WORDPRESS_CMS_DEVELOPER_EMPLOYEE = 30;
    const QA_QC_ENGINEER_EMPLOYEE = 31;
    const SYSTEM_ADMIN_DEVOPS_EMPLOYEE = 32;
    const DATA_ANALYST_ENGINEER_EMPLOYEE = 33;
    const AUTOMATION_ENGINEER_EMPLOYEE = 34;
    const TRAINER_SPECIALIST_EMPLOYEE    = 35;
    const ADMISSION_OFFICER_EMPLOYEE = 36;
    const TEACHING_ASSISTANT_EMPLOYEE = 37;
    // Thực tập sinh
    const COPYWRITER_INTERN = 38;
    const GRAPHIC_DESIGNER_INTERN = 39;
    const UI_UX_DESIGNER_INTERN = 40;
    const VIDEO_EDITOR_INTERN = 41;
    const PHOTOGRAPHER_INTERN = 42;
    const DIGITAL_MARKETING_INTERN = 43;
    const SEO_SPECIALIST_INTERN = 44;
    const SOCIAL_MEDIA_EXECUTIVE_INTERN = 45;
    const KOL_INFLUENCER_MANAGER_INTERN = 46;
    const RECRUITER_INTERN = 47;
    const FINANCE_ACCOUNTING_INTERN = 48;
    const IT_SUPPORT_INTERN = 49;
    const FRONTEND_DEVELOPER_INTERN = 50;
    const BACKEND_DEVELOPER_INTERN = 51;
    const FULLSTACK_DEVELOPER_INTERN = 52;
    const MOBILE_APP_DEVELOPER_INTERN = 53;
    const WORDPRESS_CMS_DEVELOPER_INTERN = 54;
    const QA_QC_ENGINEER_INTERN = 55;
    const SYSTEM_ADMIN_DEVOPS_INTERN = 56;
    const DATA_ANALYST_ENGINEER_INTERN = 57;
    const AUTOMATION_ENGINEER_INTERN = 58;
    const TRAINER_SPECIALIST_INTERN = 59;
    const ADMISSION_OFFICER_INTERN = 60;
    const TEACHING_ASSISTANT_INTERN = 61;

    public static function getLabel($value)
    {
        return match ($value) {
            // Lãnh đạo
            self::CEO => 'CEO - Giám dốc điều hành',
            self::COO => 'COO - Giám đốc vận hành',
            self::CFO => 'CFO - Giám đốc tài chính',
            self::CMO => 'CMO - Giám đốc marketing',
            self::CTO => 'CTO - Giám đốc công nghệ',
            // Quản lý
            self::HEAD_OF_DESIGN => 'Head of Design',
            self::DIGITAL_MAKETING_MANAGER => 'Digital Marketing Manager',
            self::HR_MANAGER => 'HR Manager',
            self::ACCOUNTING_MANAGER => 'Finance & Accounting Manager',
            self::TECH_MANAGER => 'Tech Manager/Head of Development',
            self::CONTENT_MANAGER  => 'Content/Production Manager',
            self::STRATEGY_MANAGER => 'Strategy Manager/Head of Strategy',
            // Chuyên viên
            self::ACCOUNT_EXECUTIVE => 'Account Executive',
            // Nhân viên
            self::COPYWRITER => 'Copywriter / Content Creator',
            self::GRAPHIC_DESIGNER_EMPLOYEE => 'Graphic Designer',
            self::UI_UX_DESIGNER_EMPLOYEE => 'UI/UX Designer',
            self::VIDEO_EDITOR_EMPLOYEE => 'Video Editor',
            self::PHOTOGRAPHER_EMPLOYEE => 'Photographer',
            self::DIGITAL_MARKETING_EMPLOYEE => 'Digital Marketing',
            self::SEO_SPECIALIST_EMPLOYEE => 'SEO Specialist',
            self::SOCIAL_MEDIA_EXECUTIVE_EMPLOYEE => 'Social Media Executive',
            self::KOL_INFLUENCER_MANAGER_EMPLOYEE => 'KOL/Influencer Manager',
            self::RECRUITER_EMPLOYEE => 'Recruiter / Chuyên viên tuyển dụng',
            self::FINANCE_ACCOUNTING_EMPLOYEE => 'Finance & Accounting / Kế toán',
            self::IT_SUPPORT_EMPLOYEE => 'IT Support / Hỗ trợ kỹ thuật',
            self::FRONTEND_DEVELOPER_EMPLOYEE => 'Front-end Developer',
            self::BACKEND_DEVELOPER_EMPLOYEE => 'Back-end Developer',
            self::FULLSTACK_DEVELOPER_EMPLOYEE => 'Full-stack Developer',
            self::MOBILE_APP_DEVELOPER_EMPLOYEE => 'Mobile App Developer',
            self::WORDPRESS_CMS_DEVELOPER_EMPLOYEE => 'WordPress / CMS Developer',
            self::QA_QC_ENGINEER_EMPLOYEE => 'QA/QC Engineer (Tester)',
            self::SYSTEM_ADMIN_DEVOPS_EMPLOYEE => 'System Admin / DevOps',
            self::DATA_ANALYST_ENGINEER_EMPLOYEE => 'Data Analyst / Data Engineer',
            self::AUTOMATION_ENGINEER_EMPLOYEE => 'Automation Engineer',
            self::TRAINER_SPECIALIST_EMPLOYEE => 'Trainer / Training Specialist',
            self::ADMISSION_OFFICER_EMPLOYEE => 'Admission Officer',
            self::TEACHING_ASSISTANT_EMPLOYEE => 'Teaching Assistant',
            // Thực tập sinh
            self::COPYWRITER_INTERN => 'Copywriter / Content Creator',
            self::GRAPHIC_DESIGNER_INTERN => 'Graphic Designer',
            self::UI_UX_DESIGNER_INTERN => 'UI/UX Designer',
            self::VIDEO_EDITOR_INTERN => 'Video Editor',
            self::PHOTOGRAPHER_INTERN => 'Photographer',
            self::DIGITAL_MARKETING_INTERN => 'Digital Marketing',
            self::SEO_SPECIALIST_INTERN => 'SEO Specialist',
            self::SOCIAL_MEDIA_EXECUTIVE_INTERN => 'Social Media Executive',
            self::KOL_INFLUENCER_MANAGER_INTERN => 'KOL/Influencer Manager',
            self::RECRUITER_INTERN => 'Recruiter / Chuyên viên tuyển dụng',
            self::FINANCE_ACCOUNTING_INTERN => 'Finance & Accounting / Kế toán',
            self::IT_SUPPORT_INTERN => 'IT Support / Hỗ trợ kỹ thuật',
            self::FRONTEND_DEVELOPER_INTERN => 'Front-end Developer',
            self::BACKEND_DEVELOPER_INTERN => 'Back-end Developer',
            self::FULLSTACK_DEVELOPER_INTERN => 'Full-stack Developer',
            self::MOBILE_APP_DEVELOPER_INTERN => 'Mobile App Developer',
            self::WORDPRESS_CMS_DEVELOPER_INTERN => 'WordPress / CMS Developer',
            self::QA_QC_ENGINEER_INTERN => 'QA/QC Engineer (Tester)',
            self::SYSTEM_ADMIN_DEVOPS_INTERN => 'System Admin / DevOps',
            self::DATA_ANALYST_ENGINEER_INTERN => 'Data Analyst / Data Engineer',
            self::AUTOMATION_ENGINEER_INTERN => 'Automation Engineer',
            self::TRAINER_SPECIALIST_INTERN => 'Trainer / Training Specialist',
            self::ADMISSION_OFFICER_INTERN => 'Admission Officer',
            self::TEACHING_ASSISTANT_INTERN => 'Teaching Assistant',
        };
    }

    public static function getValues(): array
    {
        return [
            // Lãnh đạo
            self::CEO,
            self::COO,
            self::CFO,
            self::CMO,
            self::CTO,
            // Quản lý
            self::HEAD_OF_DESIGN,
            self::DIGITAL_MAKETING_MANAGER,
            self::HR_MANAGER,
            self::ACCOUNTING_MANAGER,
            self::TECH_MANAGER,
            self::CONTENT_MANAGER,
            self::STRATEGY_MANAGER,
            // Chuyên viên
            self::ACCOUNT_EXECUTIVE,
            // Nhân viên
            self::COPYWRITER,
            self::GRAPHIC_DESIGNER_EMPLOYEE,
            self::UI_UX_DESIGNER_EMPLOYEE,
            self::VIDEO_EDITOR_EMPLOYEE,
            self::PHOTOGRAPHER_EMPLOYEE,
            self::DIGITAL_MARKETING_EMPLOYEE,
            self::SEO_SPECIALIST_EMPLOYEE,
            self::SOCIAL_MEDIA_EXECUTIVE_EMPLOYEE,
            self::KOL_INFLUENCER_MANAGER_EMPLOYEE,
            self::RECRUITER_EMPLOYEE,
            self::FINANCE_ACCOUNTING_EMPLOYEE,
            self::IT_SUPPORT_EMPLOYEE,
            self::FRONTEND_DEVELOPER_EMPLOYEE,
            self::BACKEND_DEVELOPER_EMPLOYEE,
            self::FULLSTACK_DEVELOPER_EMPLOYEE,
            self::MOBILE_APP_DEVELOPER_EMPLOYEE,
            self::WORDPRESS_CMS_DEVELOPER_EMPLOYEE,
            self::QA_QC_ENGINEER_EMPLOYEE,
            self::SYSTEM_ADMIN_DEVOPS_EMPLOYEE,
            self::DATA_ANALYST_ENGINEER_EMPLOYEE,
            self::AUTOMATION_ENGINEER_EMPLOYEE,
            self::TRAINER_SPECIALIST_EMPLOYEE,
            self::ADMISSION_OFFICER_EMPLOYEE,
            self::TEACHING_ASSISTANT_EMPLOYEE,
            // Thực tập sinh
            self::COPYWRITER_INTERN,
            self::GRAPHIC_DESIGNER_INTERN,
            self::UI_UX_DESIGNER_INTERN,
            self::VIDEO_EDITOR_INTERN,
            self::PHOTOGRAPHER_INTERN,
            self::DIGITAL_MARKETING_INTERN,
            self::SEO_SPECIALIST_INTERN,
            self::SOCIAL_MEDIA_EXECUTIVE_INTERN,
            self::KOL_INFLUENCER_MANAGER_INTERN,
            self::RECRUITER_INTERN,
            self::FINANCE_ACCOUNTING_INTERN,
            self::IT_SUPPORT_INTERN,
            self::FRONTEND_DEVELOPER_INTERN,
            self::BACKEND_DEVELOPER_INTERN,
            self::FULLSTACK_DEVELOPER_INTERN,
            self::MOBILE_APP_DEVELOPER_INTERN,
            self::WORDPRESS_CMS_DEVELOPER_INTERN,
            self::QA_QC_ENGINEER_INTERN,
            self::SYSTEM_ADMIN_DEVOPS_INTERN,
            self::DATA_ANALYST_ENGINEER_INTERN,
            self::AUTOMATION_ENGINEER_INTERN,
            self::TRAINER_SPECIALIST_INTERN,
            self::ADMISSION_OFFICER_INTERN,
            self::TEACHING_ASSISTANT_INTERN,
        ];
    }

    public static function getLevel($value): int
    {
        return match ($value) {
            1, 2, 3, 4, 5 => 1, // Lãnh đạo
            6, 7, 8, 9, 10, 11, 12 => 2, // Quản lý
            13 => 3, // Chuyên viên
            14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37 => 4, // Nhân viên
            38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57, 58, 59, 60, 61 => 5, // Thực tập sinh
        };
    }

    public static function getByLevel($level): array
    {
        return array_filter(self::getValues(), function ($value) use ($level) {
            return self::getLevel($value) === $level;
        });
    }
}
