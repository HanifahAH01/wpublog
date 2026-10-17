@push('style')
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css"
        rel="stylesheet" />

    <style>
        #editor {
            min-height: 120px;
            border: 1px solid #d1d5db;
            border-top: 0;
            border-radius: 0 0 1rem 1rem;
            background-color: white;
        }

        #editor .ql-editor {
            min-height: 120px;
            font-size: 14px;
        }

        #editor-toolbar {
            border: 1px solid #d1d5db;
            border-radius: 0;
        }

        #editor-toolbar.ql-toolbar.ql-snow {
            padding: 10px 12px;
        }

        #editor.ql-container.ql-snow {
            border-top: 0;
        }
    </style>
@endpush

<div class="max-w-4xl relative p-4 bg-white rounded-lg border dark:bg-gray-800 sm:p-5">

    {{-- Modal header --}}
    <div class="flex justify-between items-center pb-4 mb-4 rounded-t border-b sm:mb-5 dark:border-gray-600">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
            Add Post
        </h3>
    </div>

    {{-- Modal body --}}
    <form action="/dashboard" method="POST" id="post-form">
        @csrf

        {{-- Title --}}
        <div class="mb-4">
            <label for="title"
                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                Title
            </label>

            <input
                type="text"
                name="title"
                id="title"
                value="{{ old('title') }}"
                placeholder="type post title"
                autofocus
                class="@error('title') bg-red-100 border-red-500 text-red-900 placeholder-red-700 @enderror
                    border border-gray-300 text-gray-900 text-sm rounded-lg
                    focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5
                    dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400
                    dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            >

            @error('title')
                <p class="mt-2 text-sm text-red-600 dark:text-red-500">
                    {{ $message }}
                </p>
            @enderror
        </div>

        {{-- Category --}}
        <div class="mb-4">
            <label for="category_id"
                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                Category
            </label>

            <select
                id="category_id"
                name="category_id"
                class="@error('category_id') bg-red-100 border-red-500 @enderror
                    border border-gray-300 text-gray-900 text-sm rounded-lg
                    focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5
                    dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400
                    dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            >
                <option value="">Select post category</option>

                @foreach (App\Models\Category::get() as $category)
                    <option
                        value="{{ $category->id }}"
                        @selected(old('category_id') == $category->id)
                    >
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>

            @error('category_id')
                <p class="mt-2 text-sm text-red-600 dark:text-red-500">
                    {{ $message }}
                </p>
            @enderror
        </div>

        {{-- Body --}}
        <div class="mb-4">
            <label for="editor"
                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                Body
            </label>

            {{-- Menyimpan body lama untuk mengembalikan konten setelah validasi gagal.
                 Tidak menggunakan name="body" agar tidak duplikat. --}}
            <textarea
                id="body-source"
                rows="4"
                class="hidden"
            >{{ old('body') }}</textarea>

            {{-- Quill toolbar --}}
            <div id="editor-toolbar" class="bg-white">
                <span class="ql-formats">
                    <select class="ql-header">
                        <option selected></option>
                        <option value="1"></option>
                        <option value="2"></option>
                        <option value="3"></option>
                    </select>
                </span>

                <span class="ql-formats">
                    <button type="button" class="ql-bold"></button>
                    <button type="button" class="ql-italic"></button>
                    <button type="button" class="ql-underline"></button>
                    <button type="button" class="ql-link"></button>
                </span>

                <span class="ql-formats">
                    <button type="button" class="ql-list" value="ordered"></button>
                    <button type="button" class="ql-list" value="bullet"></button>
                </span>

                <span class="ql-formats">
                    <button type="button" class="ql-clean"></button>
                </span>
            </div>

            {{-- Area editor Quill --}}
            <div id="editor" class="bg-white"></div>

            {{-- Satu-satunya field body yang dikirim ke Laravel --}}
            <input
                type="hidden"
                name="body"
                id="body"
                value=""
            >

            @error('body')
                <p class="mt-2 text-sm text-red-600 dark:text-red-500">
                    {{ $message }}
                </p>
            @enderror
        </div>

        {{-- Buttons --}}
        <div class="flex gap-2">
            <button
                type="submit"
                class="text-white inline-flex items-center bg-blue-600 hover:bg-blue-700
                    focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium
                    rounded-lg text-sm px-5 py-2.5 text-center
                    dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
            >
                <svg
                    class="mr-1 -ml-1 w-6 h-6"
                    fill="currentColor"
                    viewBox="0 0 20 20"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <path
                        fill-rule="evenodd"
                        d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                        clip-rule="evenodd"
                    />
                </svg>

                Add new post
            </button>

            <a
                href="/dashboard"
                class="inline-flex items-center text-white bg-red-600 hover:bg-red-700
                    focus:ring-4 focus:outline-none focus:ring-red-300 font-medium
                    rounded-lg text-sm px-5 py-2.5 text-center
                    dark:bg-red-500 dark:hover:bg-red-600 dark:focus:ring-red-900"
            >
                Cancel
            </a>
        </div>
    </form>
</div>

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const postForm = document.querySelector('#post-form');
            const postBody = document.querySelector('#body');
            const bodySource = document.querySelector('#body-source');

            const quill = new Quill('#editor', {
                theme: 'snow',
                placeholder: 'Write post body here',
                modules: {
                    toolbar: '#editor-toolbar'
                }
            });

            // Mengembalikan isi sebelumnya jika validasi gagal.
            const oldBody = bodySource.value.trim();

            if (oldBody !== '') {
                quill.root.innerHTML = oldBody;
            }

            // Sinkronisasi isi Quill sebelum dikirim ke Laravel.
            postForm.addEventListener('submit', function () {
                postBody.value = quill.root.innerHTML;
            });
        });
    </script>
@endpush