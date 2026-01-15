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

    $initials = collect([
        $user->first_name,
        $user->middle_name,
        $user->first_surname,
        $user->second_surname,
    ])->filter()->map(function ($segment) {
        return mb_strtoupper(mb_substr($segment, 0, 1));
    })->implode('');

    $avatarPath = $user->profile_photo_path;
    $avatarUrl = null;
    $avatarWarning = null;
    $storageLinkMissing = !file_exists(public_path('storage'));

    if ($avatarPath) {
        try {
            $publicStorage = \Illuminate\Support\Facades\Storage::disk('public');

            if ($publicStorage->exists($avatarPath)) {
                $version = $user->updated_at ? $user->updated_at->getTimestamp() : now()->getTimestamp();
                $avatarUrl = $publicStorage->url($avatarPath) . '?v=' . $version;
            } elseif (config('app.debug')) {
                $avatarWarning = "Archivo no encontrado en storage. Revisa php artisan storage:link y permisos.";
            }
        } catch (\Throwable $exception) {
            if (config('app.debug')) {
                $avatarWarning = "No se puede acceder al disco público: " . $exception->getMessage();
            }
        }
    }
@endphp

@push('styles')
    {{-- Unificamos versión con el modal --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/cropperjs@1.6.2/dist/cropper.min.css">

    {{-- Estilos del recorte circular (Cropper siempre recorta cuadrado internamente, pero aquí se ve circular) --}}
    <style>
        #cropperStage .cropper-view-box,
        #cropperStage .cropper-face,
        #cropperStage .cropper-crop-box {
            border-radius: 50%;
        }

        #cropperStage .cropper-dashed,
        #cropperStage .cropper-point,
        #cropperStage .cropper-line,
        #cropperStage .cropper-center {
            display: none !important;
        }

        #cropperStage .cropper-view-box {
            outline: 2px solid rgba(255, 255, 255, 0.9);
            outline-offset: -2px;
        }

        #cropperStage .cropper-modal {
            background-color: rgba(0, 0, 0, 0.45) !important;
            opacity: 1 !important;
        }
    </style>
@endpush

<section>
    <div x-data='{
        loading: false,
        initial: @json($profileState),
        current: @json($profileState),

        avatarPreview: @json($avatarUrl ?? ""),
        initialAvatar: @json($avatarUrl ?? ""),
        avatarChanged: false,
        avatarClientError: "",

        cropOpen: false,
        cropperImageSrc: "",
        cropperObjectUrl: "",
        cropper: null,

        themeSaving: false,
        themeStatus: "",

        get hasChanges() {
            const fieldsChanged = Object.keys(this.initial).some((key) => {
                const initialValue = this.initial[key] ?? "";
                const currentValue = this.current[key] ?? "";
                return initialValue !== currentValue;
            });

            return fieldsChanged || this.avatarChanged;
        },

        handleAvatarChange(event) {
            const file = event.target.files?.[0];
            this.avatarClientError = "";

            if (!file) return;

            const allowedTypes = ["image/jpeg", "image/png", "image/webp"];
            if (!allowedTypes.includes(file.type)) {
                this.avatarClientError = "Solo JPG, PNG o WEBP (máx. 2 MB).";
                this.resetAvatarInput(true);
                return;
            }

            const maxSize = 2 * 1024 * 1024;
            if (file.size > maxSize) {
                this.avatarClientError = "El archivo excede el límite de 2 MB.";
                this.resetAvatarInput(true);
                return;
            }

            this.openCropper(file);
        },

        openCropper(file) {
            if (!file) return;

            this.closeCropper(false);
            this.avatarClientError = "";

            const objectUrl = URL.createObjectURL(file);
            this.cropperImageSrc = objectUrl;
            this.cropperObjectUrl = objectUrl;
            this.cropOpen = true;

            this.$nextTick(() => this.initCropper());
        },

        initCropper() {
            const imageElement = document.getElementById("cropperImage");

            if (!imageElement || !this.cropperImageSrc) {
                this.avatarClientError = "No se pudo preparar el recorte.";
                return;
            }

            this.destroyCropperInstance();

            const initialize = () => {
                this.cropper = new Cropper(imageElement, {
                    aspectRatio: 1,
                    viewMode: 1,

                    // CLAVE: el usuario mueve la caja de recorte (el “círculo”), no la imagen
                    dragMode: "crop",
                    cropBoxMovable: true,
                    cropBoxResizable: false,

                    // Tamaño inicial del recorte dentro del stage (similar a tu 78%)
                    autoCropArea: 0.78,

                    background: false,
                    responsive: true,
                    guides: false,
                    center: false,
                    highlight: false,

                    movable: true,
                    zoomable: true,
                    rotatable: true,
                    scalable: false,

                    toggleDragModeOnDblclick: false,
                });
            };

            if (imageElement.complete) {
                initialize();
            } else {
                imageElement.onload = () => initialize();
            }
        },

        zoomIn() {
            if (!this.cropper) return;
            this.cropper.zoom(0.08);
        },

        zoomOut() {
            if (!this.cropper) return;
            this.cropper.zoom(-0.08);
        },

        rotateLeft() {
            if (!this.cropper) return;
            this.cropper.rotate(-90);
        },

        resetCrop() {
            if (!this.cropper) return;
            this.cropper.reset();
        },

        async applyCrop() {
            if (!this.cropper) {
                this.avatarClientError = "No se pudo recortar la imagen.";
                return;
            }

            try {
                const size = 256;

                // 1) Canvas base (cuadrado) desde Cropper
                const squareCanvas = this.cropper.getCroppedCanvas({
                    width: size,
                    height: size,
                    imageSmoothingEnabled: true,
                    imageSmoothingQuality: "high",
                });

                if (!squareCanvas) throw new Error("canvas");

                // 2) Canvas final circular REAL (con transparencia)
                const out = document.createElement("canvas");
                out.width = size;
                out.height = size;

                const ctx = out.getContext("2d");
                ctx.clearRect(0, 0, size, size);

                ctx.save();
                ctx.beginPath();
                ctx.arc(size / 2, size / 2, size / 2, 0, Math.PI * 2);
                ctx.closePath();
                ctx.clip();

                ctx.drawImage(squareCanvas, 0, 0);
                ctx.restore();

                const blob = await new Promise((resolve, reject) => {
                    out.toBlob((result) => {
                        if (!result) {
                    reject(new Error("El recorte generó un archivo inválido."));
                    return;
                        }
                        resolve(result);
                    }, "image/webp", 0.9);
                });

                const file = new File([blob], "avatar.webp", { type: blob.type });
                const dt = new DataTransfer();
                dt.items.add(file);

                const input = this.$refs.avatarInput;
                if (input) {
                    input.files = dt.files;
                }

                const previewUrl = URL.createObjectURL(blob);
                this.setPreviewUrl(previewUrl);

                this.avatarChanged = true;
                this.avatarClientError = "";
                this.closeCropper(false);
            } catch (error) {
                console.error("No se pudo generar la imagen recortada.", error);
                this.avatarClientError = "No se pudo generar la imagen final.";
            }
        },

        closeCropper(clearInput = true) {
            this.destroyCropperInstance();

            if (this.cropperObjectUrl) {
                URL.revokeObjectURL(this.cropperObjectUrl);
                this.cropperObjectUrl = "";
            }

            this.cropperImageSrc = "";
            this.cropOpen = false;

            if (clearInput) {
                this.resetAvatarInput(true);
            }
        },

        destroyCropperInstance() {
            if (this.cropper) {
                this.cropper.destroy();
                this.cropper = null;
            }
        },

        resetAvatarInput(clearValue = false) {
            const avatarInput = this.$refs.avatarInput;
            if (avatarInput && clearValue) {
                avatarInput.value = "";
            }
        },

        setPreviewUrl(url) {
            if (this.avatarPreview && this.avatarPreview.startsWith("blob:")) {
                URL.revokeObjectURL(this.avatarPreview);
            }
            this.avatarPreview = url;
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
            <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data"
                class="space-y-6 md:space-y-8" @submit="loading = true">
                @csrf
                @method('patch')

                @include('profile.partials.sections.basic-info')

                @include('profile.partials.sections.preferences')

                @include('profile.partials.sections.profile-actions')

            </form>
        </div>

        @include('profile.components.avatar-cropper-modal')

    </div>
</section>

@push('scripts')
    @include('profile.partials.sections.profile-scripts')

    {{-- Unificamos versión con el CSS del header --}}
    <script src="https://cdn.jsdelivr.net/npm/cropperjs@1.6.2/dist/cropper.min.js"></script>
@endpush
