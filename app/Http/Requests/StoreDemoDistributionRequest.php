<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDemoDistributionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->tokenCan('demo:distribute');
    }

    public function rules(): array
    {
        return [
            'demo_master_id' => 'required|exists:demo_masters,id',
            'start_date' => 'required|date|after_or_equal:today',
            'notes' => 'nullable|string|max:1000',
            'farmer_assignments' => 'required|array|min:1',
            'farmer_assignments.*.farmer_id' => 'required|exists:farmers,id',
            'farmer_assignments.*.land_area_acres' => 'required|numeric|min:0.1',
            'farmer_assignments.*.quantity_given' => 'required|numeric|min:0',
            'farmer_assignments.*.crop_variety' => 'nullable|string|max:100',
            'farmer_assignments.*.sowing_date' => 'nullable|date',
            'farmer_assignments.*.acknowledgment_photo' => 'nullable|string',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $demoMaster = \App\Models\DemoMaster::find($this->demo_master_id);

            if ($demoMaster) {
                // Check max farmers
                $farmerCount = count($this->farmer_assignments ?? []);
                if ($farmerCount > $demoMaster->max_farmers) {
                    $validator->errors()->add(
                        'farmer_assignments',
                        "Maximum {$demoMaster->max_farmers} farmers allowed for this demo."
                    );
                }

                // Check minimum land requirement for each farmer
                foreach ($this->farmer_assignments ?? [] as $index => $assignment) {
                    if (($assignment['land_area_acres'] ?? 0) < $demoMaster->min_land_acres) {
                        $validator->errors()->add(
                            "farmer_assignments.{$index}.land_area_acres",
                            "Minimum {$demoMaster->min_land_acres} acres required."
                        );
                    }
                }
            }
        });
    }

    protected function prepareForValidation(): void
    {
        // Convert start_date string to Carbon if needed
        if ($this->has('start_date') && is_string($this->start_date)) {
            $this->merge([
                'start_date' => \Carbon\Carbon::parse($this->start_date),
            ]);
        }
    }
}
