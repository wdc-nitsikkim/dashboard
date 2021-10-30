<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

use App\CustomHelper;

class StoreStudentInfo extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        /* as authorization is done in the controller itself */
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $nameRule           = 'required | string | min:3';
        $mobileRule         = 'nullable | digits:10';
        $addressRule        = 'nullable | string | min:10';
        $scoreRule          = 'required | numeric | between:0,100';
        $passingYearRule    = 'required | integer | digits:4 | min:2010 | max:' . date('Y');
        $markingSchemeRule  = ['required', Rule::in(CustomHelper::FORM_SELECTMENU['marking_schemes'])];
        $schoolBoardRUle    = ['required', Rule::in(CustomHelper::FORM_SELECTMENU['school_boards'])];

        return [
            'date_of_birth' => 'required | date',
            'personal_email' => ['nullable', 'email',
                Rule::unique('students_information', 'personal_email')
            ],
            'secondary_mobile' => ['nullable', 'digits:10',
                Rule::unique('students_information', 'secondary_mobile')
            ],
            'gender' => ['required', Rule::in(CustomHelper::FORM_SELECTMENU['genders'])],
            'blood_group' => ['required', Rule::in(CustomHelper::FORM_SELECTMENU['blood_groups'])],
            'category' => ['required', Rule::in(CustomHelper::FORM_SELECTMENU['categories'])],
            'religion' => ['required', Rule::in(CustomHelper::FORM_SELECTMENU['religions'])],
            'fathers_name' => $nameRule,
            'fathers_mobile' => $mobileRule,
            'mothers_name' => $nameRule,
            'mothers_mobile' => $mobileRule,
            'current_address' => $addressRule,
            'permanent_address' => $addressRule,
            '10th_score' => $scoreRule,
            '10th_marking_scheme' => $markingSchemeRule,
            '10th_passing_year' => $passingYearRule,
            '10th_board' => $schoolBoardRUle,
            '10th_board_other' => 'required_if:10th_board,other',
            '12th_score' => $scoreRule,
            '12th_marking_scheme' => $markingSchemeRule,
            '12th_passing_year' => $passingYearRule,
            '12th_board' => $schoolBoardRUle,
            '12th_board_other' => 'required_if:12th_board,other',
            'cgpa' => 'nullable | numeric | between:0,10',
            'till_sem' => ['nullable', 'required_with:cgpa', Rule::exists('semesters', 'id')]
        ];
    }

    /**
     * Customize validation rules for updating, following rules should be merged
     * in the actual rule list
     *
     * @param \App\Models\Student $student  Student model
     * @return array
     */
    public function updateRules($student)
    {
        $switchRule = 'filled | in:on';
        $fileMimes = 'mimes:pdf,doc,docx';

        return [
            'personal_email' => ['nullable', 'email',
                Rule::unique('students_information', 'personal_email')->ignore($student->id, 'student_id')
            ],
            'secondary_mobile' => ['nullable', 'digits:10',
                Rule::unique('students_information', 'secondary_mobile')->ignore($student->id, 'student_id')
            ],
            'remove_image' => $switchRule,
            'image' => 'nullable | image | max:800',
            'remove_signature' => $switchRule,
            'signature' => 'nullable | image | max:500',
            'remove_resume' => $switchRule,
            'resume' => ['nullable', $fileMimes, 'max:5120']
        ];
    }
}
