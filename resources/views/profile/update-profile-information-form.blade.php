@php
    $profileState = [
        'first_name' => old('first_name', $user->first_name),
        'middle_name' => old('middle_name', $user->middle_name),
        'first_surname' => old('first_surname', $user->first_surname),
        'second_surname' => old('second_surname', $user->second_surname),
        'gender' => old('gender', $user->gender),
        'email' => old('email', $user->email),
        'phone' => old('phone', $user->phone),
        'mobile' => old('mobile', $user->mobile),
        'phone_extension' => old('phone_extension', $user->phone_extension),
        'notify_email' => (bool) old('notify_email', $user->notify_email ?? true),
        'notify_in_app' => (bool) old('notify_in_app', $user->notify_in_app ?? true),
        'digest_frequency' => old('digest_frequency', $user->digest_frequency ?? 'weekly'),
        'theme' => old('theme', $user->theme ?? 'system'),
        'compact_mode' => (bool) old('compact_mode', $user->compact_mode ?? false),
    ];
@endphp

<section>
    <div x-data='{
        loading: false,
        initial: @json($profileState),
        current: @json($profileState),

        themeSaving: false,
        themeStatus: "",

        get hasChanges() {
            const fieldsChanged = Object.keys(this.initial).some((key) => {
                const initialValue = this.initial[key] ?? "";
                const currentValue = this.current[key] ?? "";
                return initialValue !== currentValue;
            });

            return fieldsChanged;
        },

        applyTheme(theme) {
            if (typeof window.applyProfileTheme === "function") {
                window.applyProfileTheme(theme);
            }
        },

        async persistTheme(event) {
            const theme = event.target.value ?? this.current.theme;
            this.current.theme = theme;
            this.themeSaving = true;
            this.themeStatus = "";

            try {
                const token = document.querySelector("meta[name=\"csrf-token\"]")?.getAttribute("content") ?? "";
                const response = await fetch("{{ route('profile.theme.update') }}", {
                    method: "PATCH",
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json",
                        "X-CSRF-TOKEN": token,
                    },
                    body: JSON.stringify({ theme }),
                });

                if (!response.ok) {
                    throw new Error("network");
                }

                const payload = await response.json();

                if (payload.ok) {
                    localStorage.setItem("theme", payload.theme);
                    if (window.theme?.apply) {
                        window.theme.apply(payload.theme);
                    } else if (typeof window.applyProfileTheme === "function") {
                        window.applyProfileTheme(payload.theme);
                    }
                    this.applyTheme(payload.theme);
                    this.themeStatus = "Guardado";
                } else {
                    this.themeStatus = "Error";
                }
            } catch (error) {
                this.themeStatus = "Error";
            } finally {
                this.themeSaving = false;
                setTimeout(() => {
                    this.themeStatus = "";
                }, 2500);
            }
        },
    }'>

        <form id="send-verification" method="post" action="{{ route('verification.send') }}">@csrf</form>

        <div class="max-w-5xl mx-auto space-y-6 md:space-y-8">
            <form method="post" action="{{ route('profile.update') }}"
                class="space-y-6 md:space-y-8" @submit="loading = true">
                @csrf
                @method('patch')

                @include('profile.partials.sections.basic-info')

                @include('profile.partials.sections.preferences')

                @include('profile.partials.sections.profile-actions')

            </form>
        </div>

    </div>
</section>

@push('scripts')
    @include('profile.partials.sections.profile-scripts')
@endpush
