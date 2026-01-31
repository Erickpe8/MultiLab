<?php

namespace Database\Factories;

use App\Models\Loan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Loan>
 */
class LoanFactory extends Factory
{
    protected $model = Loan::class;

    public function definition(): array
    {
        $loanAt = $this->faker->dateTimeBetween('-20 days', '+2 days');

        return [
            'user_id' => User::factory()->state(fn () => [
                'is_active' => true,
                'is_blocked' => false,
            ]),
            'issued_by' => User::factory()->state(fn () => [
                'is_active' => true,
                'is_blocked' => false,
            ]),
            'loan_code' => strtoupper($this->faker->unique()->bothify('LN##??')),
            'loan_at' => $loanAt,
            'due_at' => (clone $loanAt)->modify('+5 days'),
            'return_at' => null,
            'status' => 'abierto',
        ];
    }
}
