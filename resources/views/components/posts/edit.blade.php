@push('style')
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet" />

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
    <!-- Modal header--->
    <div class="flex justify-between items-center pb-4 mb-4 rounded-t border-b sm:mb-5 dark:border-gray-600">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Edit Post</h3>
    </div>

    <!-- Modal body -->
    <form action="/dashboard/{{ $post->slug }}" method="POST" id="post-form">
        @csrf
        @method('PATCH')
        <div class="mb-4">
            <label for="title" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Title</label>
            <input type="text" name="title" id="title"
                class="@error('title') bg-red-100 border-red-500  text-red-900 placeholder-red-700 focus:ring-red-500 focus:border-red-500 @enderror
                border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600
                     focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 
                     dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                placeholder="type post title" autofocus value="{{ old('title') ?? $post->title }}">
            @error('title')
                <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
            @enderror
        </div>
        <div class="mb-4">
            <label for="category" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Category</label>
            <select id="category_id" name="category_id"
                class="@error('category_id') bg-red-100 border-red-500  text-red-900 placeholder-red-700 focus:ring-red-500 focus:border-red-500 @enderror
                border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500
                     focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400
                      dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <option value="" selected="">Select post category</option>
                @foreach (App\Models\Category::get() as $category)
                    <option value="{{ $category->id }}" @selected((old('category_id') ?? $post->category_id) == $category->id)>{{ $category->name }}</option>
                @endforeach
            </select>
            @error('category_id')
                <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
            @enderror
        </div>
        <div class="mb-4">
            <label for="body" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Body</label>
            <textarea id="body" rows="4" name="body"
                class="hidden @error('body') bg-red-100 border-red-500  text-red-900 placeholder-red-700 focus:ring-red-500 focus:border-red-500 @enderror
                border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500
                     focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400
                      dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                placeholder="Write post body here">{{ old('body') ?? $post->body }}</textarea>
            
            <!-- Toolbar Quill -->
            <div id="editor-toolbar">
                <span class="ql-formats">
                    <select class="ql-header">
                        <option selected>Normal</option>
                        <option value="1">Heading 1</option>
                        <option value="2">Heading 2</option>
                        <option value="3">Heading 3</option>
                    </select>
                </span>
                <span class="ql-formats">
                    <button class="ql-bold"></button>
                    <button class="ql-italic"></button>
                    <button class="ql-underline"></button>
                    <button class="ql-link"></button>
                </span>
                <span class="ql-formats">
                    <button class="ql-list" value="ordered"></button>
                    <button class="ql-list" value="bullet"></button>
                </span>
                <span class="ql-formats">
                    <button class="ql-clean"></button>
                </span>
            </div>

            <div id="editor">{!! old('body', $post->body) !!}</div>
            @error('body')
                <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
            @enderror
        </div>
        <div class="flex gap-2">
            <button type="submit"
                class="text-white inline-flex items-center bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none 
                focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700
                dark:focus:ring-blue-800">
                Update Post
            </button>

            <a href="/dashboard"
                class="inline-flex items-center text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-red-500 dark:hover:bg-red-600 dark:focus:ring-red-900">
                <svg aria-hidden="true" class="w-5 h-5 mr-1.5 -ml-1" fill="currentColor" viewbox="0 0 20 20"
                    xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                        d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                        clip-rule="evenodd" />
                </svg>
                Cancel
            </a>
        </div>
    </form>
</div>

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const postForm = document.querySelector('#post-form');
            const postBody = document.querySelector('#body');

            const quill = new Quill('#editor', {
                theme: 'snow',
                placeholder: 'Write post body here',
                modules: {
                    toolbar: '#editor-toolbar'
                }
            });

            // Mengambil isi dari textarea #body untuk validasi atau data lama
            const oldBody = postBody.value.trim();

            if (oldBody !== '') {
                quill.root.innerHTML = oldBody;
            }

            // Sinkronisasi isi Quill sebelum dikirim ke Laravel.
            postForm.addEventListener('submit', function() {
                postBody.value = quill.root.innerHTML;
            });
        });
    </script>
@endpush