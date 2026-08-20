<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'phone' => 'required|string|max:20',
            'name' => 'required|string|max:100',
            'address' => 'required|string|max:100',
            'email' => 'required|email|unique:companies,email|confirmed',
            'state' => 'in:Pending,Approved,Rejected',
            'password' => 'required|string|min:8|confirmed',

        ];
    }
    public function messages(): array
    {
        return [
            // حقول عامة
            'name.required' => 'اسم الشركة مطلوب',
            'name.max' => 'اسم الشركة لا يتجاوز 255 حرف',

            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'يرجى إدخال بريد إلكتروني صحيح',
            'email.unique' => 'هذا البريد الإلكتروني مستخدم بالفعل',

            'password.required' => 'كلمة المرور مطلوبة',
            'password.min' => 'كلمة المرور يجب أن تكون 8 أحرف على الأقل',
            'password.confirmed' => 'كلمة المرور غير متطابقة',

            // حقول الشركة
            'address.required' => 'عنوان الشركة مطلوب',
            'phone.required' => 'رقم الهاتف مطلوب',

            'logo.required' => 'شعار الشركة مطلوب',
            'logo.image' => 'يجب أن يكون الملف صورة',
            'logo.mimes' => 'نوع الصورة يجب أن يكون JPG أو PNG',
            'logo.max' => 'حجم الصورة لا يتجاوز 2 ميجابايت',

            'infoCompany.required' => 'المستندات القانونية مطلوبة',
            'infoCompany.mimes' => 'يجب أن يكون الملف PDF أو JPG أو PNG',
            'infoCompany.max' => 'حجم الملف لا يتجاوز 5 ميجابايت',
        ];
    }
}
