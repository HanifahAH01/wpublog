@push('style')
    <!-- FilePond CSS & Preview Plugin CSS -->
    <link href="https://unpkg.com/filepond@^4/dist/filepond.css" rel="stylesheet" />
    <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet" />
    <style>
        /* Menyesuaikan sedikit agar kotak FilePond mirip dengan video */
        .filepond--panel-root {
            background-color: #f3f4f6;
            /* gray-100 */
            border-radius: 0.5rem;
        }
    </style>
@endpush

<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <!-- Tambahkan id="profile-form" agar mudah dijangkau JavaScript FilePond -->
    <form id="profile-form" method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6"
        enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)"
                required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="username" :value="__('Username')" />
            <x-text-input id="username" name="username" type="text" class="mt-1 block w-full" :value="old('username', $user->username)"
                required autofocus autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('username')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)"
                required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />
        </div>

        {{-- FilePond Upload Avatar --}}
        <div>
            <label class="block mb-2 text-sm font-medium text-gray-800 dark:text-white" for="avatar">Upload
                avatar</label>
            <input id="avatar" name="avatar" type="file" accept="image/png, image/jpeg, image/jpg" />
            @error('avatar')
                <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
            @enderror
            <p class="text-xs text-gray-500 mt-1">.PNG or .JPG max. 1mb</p>
        </div>

        {{-- Gambar Profil Bundar di Bawah --}}
        <div class="flex items-center gap-4">
            <img class="w-20 h-20 rounded-full object-cover border border-gray-200 shadow-sm"
                src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('/img/avatar.png') }}"
                alt="{{ $user->name }}">

            <div>
                <p class="text-sm font-medium text-gray-900">Current Avatar</p>
                <p class="text-xs text-gray-500">Will update after you click Save.</p>
            </div>
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600">{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>

@push('script')
    <!-- FilePond Plugin & Core JS -->
    <script src="https://unpkg.com/filepond-plugin-image-transform/dist/filepond-plugin-image-transform.js"></script>
    <script src="https://unpkg.com/filepond-plugin-image-resize/dist/filepond-plugin-image-resize.js"></script>
    <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
    <script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>
    <script src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.js"></script>
    <script src="https://unpkg.com/filepond@^4/dist/filepond.js"></script>

    <script>
        // Daftarkan plugin FilePond
        FilePond.registerPlugin(
            FilePondPluginImagePreview,
            FilePondPluginFileValidateType,
            FilePondPluginFileValidateSize,
            FilePondPluginImageTransform,
            FilePondPluginImageResize
        );

        // Inisialisasi FilePond
        const inputElement = document.querySelector('#avatar');
        const pond = FilePond.create(inputElement, {
            allowImagePreview: true,
            imagePreviewHeight: 180,
            allowFileTypeValidation: true,
            acceptedFileTypes: ['image/png', 'image/jpeg', 'image/jpg'],
            maxFileSize: '5MB',

            // --- Paksa jadi Kotak 600x600 otomatis di browser ---
            allowImageResize: true,
            imageResizeTargetWidth: 600,
            imageResizeTargetHeight: 600,
            imageResizeMode: 'cover',
            imageResizeUpscale: true,
            imageTransformOutputQuality: 1,

            labelIdle: 'Drag & Drop your files or <span class="filepond--label-action">Browse</span>'
        });

        // Live Preview: Mengubah gambar profil bulat di bawah secara real-time saat foto dipilih
        pond.on('addfile', (error, file) => {
            if (!error) {
                const imgElement = document.querySelector('img[alt="{{ $user->name }}"]');
                if (imgElement) {
                    imgElement.src = URL.createObjectURL(file.file);
                }
            }
        });

        // Skrip untuk menyuntikkan file dari FilePond ke form Laravel saat tombol SAVE ditekan
        const form = document.querySelector('#profile-form');
        form.addEventListener('submit', function(e) {
            const fileItem = pond.getFile();
            if (fileItem) {
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(fileItem.file);

                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'file';
                hiddenInput.name = 'avatar';
                hiddenInput.files = dataTransfer.files;
                hiddenInput.style.display = 'none';

                form.appendChild(hiddenInput);
            }
        });
    </script>
@endpush
