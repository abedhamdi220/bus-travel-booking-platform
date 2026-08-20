{{-- <x-guest-layout>
    <form method="POST" action="{{ route('resetPassword') }}">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                                type="password"
                                name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Reset Password') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout> --}}


<x-guest-layout>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow p-4">
                    <h3 class="text-center mb-4 fw-bold">إعادة تعيين كلمة المرور</h3>

                    <!-- عرض رسائل الخطأ العامة -->
                    <div id="messageContainer"></div>

                    <form method="POST" action="{{ route('resetPassword') }}" id="resetPasswordForm">
                        @csrf

                        <!-- ✅ Token (مخفي) -->
                        <input type="hidden" name="token" value="{{ $token ?? $request->route('token') }}">

                        <!-- ✅ حقل البريد الإلكتروني (مخفي أو معبأ مسبقاً) -->
                        <input type="hidden" name="email" value="{{ $email ?? $request->email }}">

                        <!-- ✅ حقل الدور (مخفي) -->
                        <input type="hidden" name="role" value="{{ $role ?? 'user' }}">

                        <!-- عرض البريد الإلكتروني للمستخدم (للعلم فقط) -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">البريد الإلكتروني</label>
                            <p class="form-control-plaintext">{{ $email ?? $request->email }}</p>
                        </div>

                        <!-- ✅ كلمة المرور الجديدة -->
                        <div class="mb-3">
                            <label for="password" class="form-label fw-bold">كلمة المرور الجديدة</label>
                            <div class="input-group">
                                <input type="password" id="password" class="form-control"
                                       name="password" placeholder="أدخل كلمة المرور الجديدة" required minlength="8">
                                <button class="btn btn-outline-secondary" type="button"
                                        onclick="togglePassword('password', 'passwordIcon')">
                                    <i class="fas fa-eye" id="passwordIcon"></i>
                                </button>
                            </div>
                            <div class="invalid-feedback" id="password-error"></div>
                            <small class="text-muted">يجب أن تكون كلمة المرور 8 أحرف على الأقل</small>
                        </div>

                        <!-- ✅ تأكيد كلمة المرور -->
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label fw-bold">تأكيد كلمة المرور</label>
                            <div class="input-group">
                                <input type="password" id="password_confirmation" class="form-control"
                                       name="password_confirmation" placeholder="أعد إدخال كلمة المرور" required>
                                <button class="btn btn-outline-secondary" type="button"
                                        onclick="togglePassword('password_confirmation', 'confirmIcon')">
                                    <i class="fas fa-eye" id="confirmIcon"></i>
                                </button>
                            </div>
                            <div class="invalid-feedback" id="password_confirmation-error"></div>
                        </div>

                        <!-- ✅ زر الإرسال -->
                        <button type="submit" class="btn btn-success w-100 rounded-pill main-btn"
                                onclick="handleReset(event)">
                            <i class="fas fa-key me-2"></i> إعادة تعيين كلمة المرور
                        </button>
                    </form>

                    <!-- روابط إضافية -->
                    <div class="text-center mt-3">
                        <a href="{{ route('login') }}">العودة إلى تسجيل الدخول</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // ✅ دالة إظهار/إخفاء كلمة المرور
        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            if (!input) return;

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        // ✅ دالة معالجة الإرسال
        function handleReset(event) {
            event.preventDefault();

            const form = document.getElementById('resetPasswordForm');
            const formData = new FormData(form);
            const token = document.querySelector('input[name="_token"]').value;

            // ✅ تعطيل الزر
            const btn = document.querySelector('button[type="submit"]');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> جاري الإرسال...';

            fetch('{{ route("resetPassword") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(data => {
                        throw { status: response.status, data };
                    });
                }
                return response.json();
            })
            .then(data => {
                // ✅ إعادة الزر
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-key me-2"></i> إعادة تعيين كلمة المرور';

                if (data.errors) {
                    showValidationErrors(data.errors);
                    showMessage('danger', data.message || 'يرجى تصحيح الأخطاء');
                } else if (data.success) {
                    showMessage('success', data.message || 'تم إعادة تعيين كلمة المرور بنجاح');

                    // ✅ التوجيه بعد 2 ثانية
                    setTimeout(() => {
                        window.location.href = data.redirect || '/login';
                    }, 2000);
                } else {
                    showMessage('danger', data.message || 'حدث خطأ غير متوقع');
                }
            })
            .catch(error => {
                console.error('خطأ:', error);
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-key me-2"></i> إعادة تعيين كلمة المرور';

                if (error.data && error.data.errors) {
                    showValidationErrors(error.data.errors);
                    showMessage('danger', error.data.message || 'حدث خطأ في التحقق');
                } else {
                    showMessage('danger', 'حدث خطأ في الاتصال بالخادم');
                }
            });
        }

        // ✅ دالة عرض الأخطاء تحت الحقول
        function showValidationErrors(errors) {
            document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
            document.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');

            for (const [field, messages] of Object.entries(errors)) {
                const input = document.querySelector(`[name="${field}"]`);
                if (input) {
                    input.classList.add('is-invalid');
                    const feedback = document.getElementById(`${field}-error`);
                    if (feedback) {
                        feedback.textContent = messages[0];
                    }
                }
            }
        }

        // ✅ دالة عرض الرسائل العامة
        function showMessage(type, message) {
            const container = document.getElementById('messageContainer');
            if (!container) return;

            let className = 'alert-info';
            let icon = 'fa-info-circle';

            if (type === 'success') {
                className = 'alert-success';
                icon = 'fa-check-circle';
            } else if (type === 'danger') {
                className = 'alert-danger';
                icon = 'fa-exclamation-circle';
            } else if (type === 'warning') {
                className = 'alert-warning';
                icon = 'fa-exclamation-triangle';
            }

            container.innerHTML = `
                <div class="alert ${className} alert-dismissible fade show" role="alert">
                    <i class="fas ${icon} me-2"></i>
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `;

            if (type === 'success') {
                setTimeout(() => {
                    const alert = container.querySelector('.alert');
                    if (alert) {
                        alert.classList.remove('show');
                        setTimeout(() => {
                            container.innerHTML = '';
                        }, 300);
                    }
                }, 5000);
            }
        }
    </script>

    <style>
        .main-btn {
            background-color: #19283f;
            color: white;
        }
        .main-btn:hover {
            background-color: #0f1a2e;
            color: white;
        }
        .main-btn:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }
        .form-control-plaintext {
            font-weight: bold;
            color: #19283f;
        }
    </style>
</x-guest-layout>
