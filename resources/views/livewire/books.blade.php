<div class="p-6">
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <h1 class="text-3xl font-bold text-zinc-500 dark:text-white/80 mb-2">Gerenciador de Livros</h1>
            
            <button 
                wire:click="create"
                class="ml-4 bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold transition duration-200 flex items-center"
            >
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Adicionar Livro
            </button>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
            {{ session('error') }}
        </div>
    @endif

    <!-- List Table -->
    <div class="bg-zinc-50 rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-white/80 uppercase tracking-wider">Título</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-zinc-500 dark:text-white/80 uppercase tracking-wider">Autor</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-zinc-500 dark:text-white/80 uppercase tracking-wider">Publicação</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-zinc-500 dark:text-white/80 uppercase tracking-wider">Ações</th>
                </tr>
            </thead>
            <tbody class="border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900 divide-y divide-gray-200">
                @forelse($books as $book)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-left font-medium text-zinc-500 dark:text-white/80">{{ $book->name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-zinc-500 dark:text-white/80">{{ $book->author->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-zinc-500 dark:text-white/80">{{ $book->published_at->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium">
                            <button 
                                wire:click="edit({{ $book->id }})"
                                class="text-blue-600 hover:text-blue-900 mr-3 transition duration-200"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </button>
                            <button 
                                wire:click="delete({{ $book->id }})"
                                wire:confirm="Tem certeza que deseja excluir este livro?"
                                class="text-red-600 hover:text-red-900 transition duration-200"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            Nenhum livro encontrado.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginação -->
    <div class="mt-6">
        {{ $books->links() }}
    </div>

    <!-- Modal -->
    @if($showModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-zinc-50 dark:bg-zinc-900">
                <!-- Cabeçalho do modal -->
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-zinc-500 dark:text-white/80">
                        {{ $editMode ? 'Editar Livro' : 'Adicionar Livro' }}
                    </h3>
                    <button 
                        wire:click="closeModal"
                        class="text-gray-400 hover:text-gray-600 transition duration-200"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <form wire:submit.prevent="save" enctype="multipart/form-data">
                    <input type="hidden" wire:model="book_id" />
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-zinc-500 dark:text-white/80 mb-2">Título *</label>
                            <input 
                                type="text" 
                                wire:model="name"
                                class="w-full px-3 py-2 border border-zinc-200 dark:border-zinc-700 rounded-md"
                            />
                            @error('name') <span class="error text-red-600">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-zinc-500 dark:text-white/80 mb-2">Autor *</label>
                            <select 
                                wire:model="author_id"
                                class="w-full px-3 py-2 border border-zinc-200 dark:border-zinc-700 rounded-md"
                            >
                                <option value="" class="bg-zinc-50 dark:bg-zinc-900">Selecione um autor</option>
                                @foreach($authors as $author)
                                    <option value="{{ $author->id }}" class="bg-zinc-50 dark:bg-zinc-900">{{ $author->name }}</option>
                                @endforeach
                            </select>
                            @error('author_id') <span class="error text-red-600">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-zinc-500 dark:text-white/80 mb-2">Data de Publicação</label>
                            <input 
                                type="date" 
                                wire:model="published_at"
                                class="w-full px-3 py-2 border border-zinc-200 dark:border-zinc-700 rounded-md"
                            />
                            @error('published_at') <span class="error text-red-600">{{ $message }}</span> @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-zinc-500 dark:text-white/80 mb-2">Capa do livro</label>
                            <input 
                                type="file" 
                                wire:model="fileUpload"
                                class="w-full px-3 py-2 border border-zinc-200 dark:border-zinc-700 rounded-md"
                            />
                            @error('fileUpload') <span class="error text-red-600">{{ $message }}</span> @enderror
                            @if ($image)
                                <div class="mt-2">
                                    <img src="{{ asset('storage/' . $image) }}" alt="Capa do livro" class="w-32 h-32 object-cover rounded-md">
                                </div>
                            @endif
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-zinc-500 dark:text-white/80 mb-2">Descrição</label>
                            <textarea 
                                wire:model="description"
                                rows="3"
                                class="w-full px-3 py-2 border border-zinc-200 dark:border-zinc-700 rounded-md"
                            ></textarea>
                            @error('description') <span class="error text-red-600">{{ $message }}</span> @enderror

                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 mt-6 pt-4 border-t border-zinc-200 dark:border-zinc-700">
                        <button 
                            type="button"
                            wire:click="closeModal"
                            class="px-4 py-2 border border-zinc-400 rounded-md text-zinc-500 dark:text-white/80 dark:hover:text-zinc-900 hover:bg-zinc-50 transition duration-200"
                        >
                            Cancelar
                        </button>
                        <button 
                            type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-200"
                        >
                            {{ $editMode ? 'Atualizar' : 'Salvar' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>