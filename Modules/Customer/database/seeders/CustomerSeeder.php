<?php

namespace Modules\Customer\database\seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Customer\Enums\CustomerTypeEnum;
use Modules\Customer\Enums\SourceCustomerEnum;
use Modules\Customer\Models\Customer;

class CustomerSeeder extends Seeder
{
    public function run()
    {
        Model::unguard();

        $customers = [
            [
                'code' => 'KH/VIDOCO-00001',
                'customer_type' => CustomerTypeEnum::PERSONAL,
                'source_customer' => SourceCustomerEnum::FACEBOOK,
                'person_incharge' => 1,
                'sales_person' => 1,
                'company_name' => 'Công ty TNHH Vido',
                'salutation' => 'Anh',
                'last_name' => 'A',
                'first_name' => 'Nguyễn Văn',
                'birthday' => '1990-01-01',
                'identity_card' => '123456789',
                'gender' => 'Nam',
                'phone' => '0909123456',
                'sub_phone' => '0909123457',
                'email' => 'nguyenvana@gmail.com',
                'sub_email' => 'nguyenvana2@gmail.com',
                'facebook' => 'https://www.facebook.com/nguyenvana',
                'zalo' => '0909123456',
                'address' => '123 Đường ABC, Quận XYZ, TP. HCM',
                'note' => 'Khách cá nhân test',
                'invoice_name' => 'Nguyễn Văn A',
                'invoice_tax_code' => '123456789',
                'invoice_email' => 'invoice.nguyenvana@gmail.com',
            ],
            [
                'code' => 'KH/VIDOCO-00002',
                'customer_type' => CustomerTypeEnum::COMPANY,
                'source_customer' => SourceCustomerEnum::WEBSITE,
                'person_incharge' => 2,
                'sales_person' => 2,
                'company_name' => 'Công ty TNHH Vido',
                'tax_code' => '987654321',
                'founding_date' => '2020-05-20',
                'company_address' => '456 Đường DEF, Quận QWE, TP. HCM',
                'last_name' => 'B', // Người đại diện
                'first_name' => 'Trần Thị',
                'phone' => '0912345678',
                'sub_phone' => '0912345679',
                'email' => 'contact@vido.com',
                'sub_email' => 'info@vido.com',
                'facebook' => 'https://www.facebook.com/vido',
                'zalo' => '0912345678',
                'address' => '456 Đường DEF, Quận QWE, TP. HCM',
                'note' => 'Khách doanh nghiệp test',
                'invoice_name' => 'Công ty TNHH Vido',
                'invoice_tax_code' => '987654321',
                'invoice_email' => 'invoice@vido.com',
            ],
        ];

        foreach ($customers as $customer) {
            Customer::create($customer);
        }
    }
}
