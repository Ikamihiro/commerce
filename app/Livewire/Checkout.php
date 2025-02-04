<?php

namespace App\Livewire;

use App\Enums\CheckoutStepEnum;
use App\Models\Course;
use Livewire\Component;

class Checkout extends Component
{
    public Course $course;
    public int $currentStep = CheckoutStepEnum::FirstStep->value;

    public array $payer = [
        'first_name' => '',
        'last_name' => '',
        'email' => '',
        'identification' => [
            'type' => '',
            'number' => '',
        ],
    ];

    public function mount(Course $course)
    {
        $this->course = $course;
    }

    public function render()
    {
        return view('livewire.checkout');
    }

    public function next()
    {
        $this->validateCurrentStep();

        $this->currentStep++;
    }

    public function back()
    {
        $this->currentStep--;
    }

    private function validateCurrentStep()
    {
        if ($this->currentStep === CheckoutStepEnum::FirstStep->value) {
            $this->validate([
                'payer.first_name' => 'required|string',
                'payer.last_name' => 'required|string',
                'payer.email' => 'required|email',
                'payer.identification.type' => 'required|string',
                'payer.identification.number' => 'required|string',
            ], [
                'payer.first_name.required' => 'O campo de nome é obrigatório.',
                'payer.last_name.required' => 'O campo de sobrenome é obrigatório.',
                'payer.email.required' => 'O campo de email é obrigatório.',
                'payer.email.email' => 'O campo de email deve ser um endereço de email válido.',
                'payer.identification.type.required' => 'O campo de tipo de identificação é obrigatório.',
                'payer.identification.number.required' => 'O campo de número de identificação é obrigatório.',
            ]);
        }

        if ($this->currentStep === CheckoutStepEnum::SecondStep->value) {
            // Validate second step
        }

        if ($this->currentStep === CheckoutStepEnum::ThirdStep->value) {
            // Validate third step
        }
    }
}
