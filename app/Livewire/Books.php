<?php

namespace App\Livewire;

use App\Http\Requests\BookRequest;
use App\Models\Author;
use App\Models\Book;
use App\Models\FileUpload;
use Illuminate\Support\Facades\Validator;
use Livewire\{Component, WithFileUploads, WithPagination};
use Livewire\Attributes\Validate;

class Books extends Component {
    use WithPagination, WithFileUploads;

    public $showModal = false;
    public $editMode = false;

    // Form fields
    public $book_id = null;
    #[Validate('required', message: 'O nome é obrigatório.')]
    #[Validate('string', message: 'O nome deve ser uma string.')]
    #[Validate('max:255', message: 'O nome não pode ter mais que 255 caracteres.')]
    public $name;
    #[Validate('string', message: 'A descrição deve ser uma string.')]
    #[Validate('required', message: 'A descrição é obrigatória.')]
    public $description;
    #[Validate('required', message: 'A data de publicação é obrigatória.')]
    #[Validate('date', message: 'A data de publicação deve ser uma data válida.')]
    public $published_at;
    #[Validate('required|exists:authors,id', message: 'O autor é obrigatório.')]
    public $author_id;
    public $image;
    #[Validate('nullable|image|mimes:png,jpg|max:2048', message: 'O arquivo deve ser uma imagem JPG ou PNG de no .')]
    public $fileUpload;

    public function render() {
        return view('livewire.books')->with([
            'books' => Book::with('author')->paginate(10),
            'authors' => Author::where('active', true)->get(),
            'showModal' => $this->showModal,
            'editMode' => $this->editMode,
        ]);
    }

    public function create() {
        $this->clearFormFields();
        $this->showModal = true;
        $this->editMode = false;
    }

    public function edit($bookId) {
        $book = Book::findOrFail($bookId);

        $this->book_id = $book->id;
        $this->name = $book->name;
        $this->description = $book->description;
        $this->published_at = $book->published_at;
        $this->author_id = $book->author_id;
        $this->image = $book->image; // Armazena o caminho da imagem atual

        $this->editMode = true;
        $this->showModal = true;
    }

    public function save() {
        $this->validate();

        // Processar upload da imagem
        if ($this->fileUpload) {
            // Excluir imagem antiga se existir
            if ($this->editMode && $this->image) {
                FileUpload::deleteImage($this->image);
            }
            
            $imagePath = FileUpload::uploadImage($this->fileUpload);
            $this->image = $imagePath;
        }

        Book::updateOrCreate(
            ['id' => $this->book_id],
            $this->getFormFields()
        );

        session()->flash('message', $this->editMode ? 'Livro atualizado com sucesso.' : 'Livro criado com sucesso.');
        $this->closeModal();
    }

    public function delete($bookId) {
        $book = Book::findOrFail($bookId);
        
        if ($book->image) {
            FileUpload::deleteImage($book->image);
        }
        
        $book->delete();
        session()->flash('message', 'Livro excluído com sucesso.');
    }

    public function closeModal(): void {
        $this->showModal = false;
        $this->editMode = false;
        $this->clearFormFields();
    }

    private function getFormFields(): array {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'published_at' => $this->published_at,
            'author_id' => $this->author_id,
            'image' => $this->image
        ];
    }

    private function clearFormFields(): void {
        $this->reset([
            'book_id', 
            'name', 
            'description', 
            'published_at', 
            'author_id',
            'image',
            'fileUpload'
        ]);
    }
}
