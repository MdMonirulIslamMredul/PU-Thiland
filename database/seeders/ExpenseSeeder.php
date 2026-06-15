<?php

namespace Database\Seeders;

use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class ExpenseSeeder extends Seeder
{
    /**
     * Run the expense seed.
     */
    public function run(): void
    {
        $admin = User::first();

        if (! $admin) {
            $admin = User::factory()->create([
                'name' => 'Demo Admin',
                'email' => 'admin@example.com',
            ]);
        }

        $existingCategories = ExpenseCategory::all()->keyBy('code');

        if ($existingCategories->isEmpty()) {
            return;
        }

        $expenseTemplates = [
            'office_supplies' => [
                'amount' => '142.50',
                'reference' => [
                    'en' => 'OFFICE-001',
                    'bn' => 'অফিস-০০১',
                    'zh' => '办公-001',
                ],
                'description' => [
                    'en' => 'Printer toner and stationery for the sales team.',
                    'bn' => 'বিক্রয় দলের জন্য প্রিন্টার টোনার এবং স্টেশনারি।',
                    'zh' => '销售团队的打印机墨粉和文具。',
                ],
            ],
            'travel' => [
                'amount' => '875.00',
                'reference' => [
                    'en' => 'TRAVEL-001',
                    'bn' => 'ট্রাভেল-০০১',
                    'zh' => '差旅-001',
                ],
                'description' => [
                    'en' => 'Taxi and ride-share fares for client meetings.',
                    'bn' => 'ক্লায়েন্ট মিটিংয়ের জন্য ট্যাক্সি এবং রাইড-শেয়ার ভাড়া।',
                    'zh' => '客户会议的出租车和网约车费用。',
                ],
            ],
            'utilities' => [
                'amount' => '620.78',
                'reference' => [
                    'en' => 'UTIL-001',
                    'bn' => 'ইউটিল-০০১',
                    'zh' => '公用-001',
                ],
                'description' => [
                    'en' => 'Monthly electricity and internet service charges.',
                    'bn' => 'মাসিক বিদ্যুৎ এবং ইন্টারনেট পরিষেবা চার্জ।',
                    'zh' => '每月电费和互联网服务费用。',
                ],
            ],
            'marketing' => [
                'amount' => '1250.00',
                'reference' => [
                    'en' => 'MKT-001',
                    'bn' => 'মার্কেটিং-০০১',
                    'zh' => '营销-001',
                ],
                'description' => [
                    'en' => 'Social media campaign creative and ads spend.',
                    'bn' => 'সোশ্যাল মিডিয়া প্রচারণা ক্রিয়েটিভ এবং বিজ্ঞাপন খরচ।',
                    'zh' => '社交媒体活动创意和广告支出。',
                ],
            ],
            'maintenance' => [
                'amount' => '330.00',
                'reference' => [
                    'en' => 'MAINT-001',
                    'bn' => 'রক্ষণাবেক্ষণ-০০১',
                    'zh' => '维修-001',
                ],
                'description' => [
                    'en' => 'Air conditioning repairs and office cleaning supplies.',
                    'bn' => 'এয়ার কন্ডিশনার মেরামত এবং অফিস পরিস্কারের সরঞ্জাম।',
                    'zh' => '空调维修和办公室清洁用品。',
                ],
            ],
            'factory_expense' => [
                'amount' => '1080.00',
                'reference' => [
                    'en' => 'FACTORY-001',
                    'bn' => 'ফ্যাক্টরি-০০১',
                    'zh' => '工厂-001',
                ],
                'description' => [
                    'en' => 'Raw material and assembly line maintenance costs.',
                    'bn' => 'কাঁচামাল এবং সমাবেশ লাইনের রক্ষণাবেক্ষণ খরচ।',
                    'zh' => '原材料和装配线维护费用。',
                ],
            ],
            'office_expense' => [
                'amount' => '210.00',
                'reference' => [
                    'en' => 'OFFICE-EXP-001',
                    'bn' => 'অফিস-এক্সপি-০০১',
                    'zh' => '办公支出-001',
                ],
                'description' => [
                    'en' => 'Office utilities and stationery charges.',
                    'bn' => 'অফিস ইউটিলিটি এবং স্টেশনারি চার্জ।',
                    'zh' => '办公室水电和文具费用。',
                ],
            ],
            'salary_bonus' => [
                'amount' => '3200.00',
                'reference' => [
                    'en' => 'SALARY-001',
                    'bn' => 'সেলারি-০০১',
                    'zh' => '工资-001',
                ],
                'description' => [
                    'en' => 'Monthly salary and employee bonus payments.',
                    'bn' => 'মাসিক বেতন এবং কর্মচারী বোনাস পেমেন্ট।',
                    'zh' => '每月工资和员工奖金支付。',
                ],
            ],
            'transport_cost' => [
                'amount' => '470.50',
                'reference' => [
                    'en' => 'TRANSPORT-001',
                    'bn' => 'পরিবহন-০০১',
                    'zh' => '运输-001',
                ],
                'description' => [
                    'en' => 'Vehicle fuel and delivery expense for logistic routes.',
                    'bn' => 'লজিস্টিক রুটের জন্য যানবাহন জ্বালানি এবং ডেলিভারি খরচ।',
                    'zh' => '物流路线的车辆燃料和配送费用。',
                ],
            ],
        ];

        $expenses = [];

        foreach ($existingCategories as $code => $category) {
            $template = $expenseTemplates[$code] ?? [
                'amount' => '120.00',
                'reference' => [
                    'en' => strtoupper(str_replace('_', '-', $code)) . '-001',
                    'bn' => 'রেফ-' . strtoupper(str_replace('_', '-', $code)) . '-001',
                    'zh' => '参考-' . strtoupper(str_replace('_', '-', $code)) . '-001',
                ],
                'description' => [
                    'en' => ucfirst(str_replace('_', ' ', strtolower($code))) . ' expense sample.',
                    'bn' => ucfirst(str_replace('_', ' ', strtolower($code))) . ' খরচের নমুনা।',
                    'zh' => ucfirst(str_replace('_', ' ', strtolower($code))) . ' 费用示例。',
                ],
            ];

            $expenses[] = [
                'category' => $code,
                'amount' => $template['amount'],
                'date' => Carbon::now()->subDays(rand(3, 40))->toDateString(),
                'reference' => $template['reference'],
                'description' => $template['description'],
            ];
        }

        foreach ($expenses as $expenseData) {
            $category = $existingCategories[$expenseData['category']];

            if (! Expense::where('expense_category_id', $category->id)
                ->where('amount', $expenseData['amount'])
                ->whereDate('expense_date', $expenseData['date'])
                ->exists()) {
                Expense::create([
                    'expense_category_id' => $category->id,
                    'amount' => $expenseData['amount'],
                    'expense_date' => $expenseData['date'],
                    'reference' => $expenseData['reference'],
                    'description' => $expenseData['description'],
                    'created_by' => $admin->id,
                ]);
            }
        }
    }
}
